<?php
/**
 * SiApgus - Sistem Aplikasi Guru Sekolah
 * File: api.php
 * REST API untuk sinkronisasi data frontend dengan MySQL / File Storage pada XAMPP/Apache
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/koneksi.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$data_dir = __DIR__ . '/data';
$json_file = $data_dir . '/database.json';

// Pastikan folder data tersedia
if (!is_dir($data_dir)) {
    @mkdir($data_dir, 0777, true);
}

// Data default template
function getDefaultDatabase() {
    return [
        "users" => [
            [
                "id" => "usr_admin",
                "username" => "admin",
                "password" => "admin123",
                "fullName" => "Administrator SiApgus",
                "role" => "admin",
                "nikNuptk" => "198501012010011001",
                "email" => "admin@sekolah.sch.id"
            ],
            [
                "id" => "usr_guru",
                "username" => "guru",
                "password" => "guru123",
                "fullName" => "Budi Santoso, S.Pd",
                "role" => "guru",
                "nikNuptk" => "198804122015021002",
                "email" => "budi.santoso@sekolah.sch.id"
            ]
        ],
        "profile" => [
            "namaSekolah" => "SMK KAWUNG SURABAYA",
            "npsn" => "20532189",
            "alamatSekolah" => "Jl. Kawung No. 12, Surabaya",
            "telp" => "031-8412345",
            "kepalaNama" => "Drs. H. Ahmad Sudirman, M.M.",
            "kepalaNip" => "197203151998021003",
            "tahunAjaran" => "2026/2027",
            "semester" => "Ganjil",
            "kurikulum" => "Kurikulum Merdeka",
            "logoUrl" => ""
        ],
        "siswa" => [],
        "guru" => [],
        "mataPelajaran" => [],
        "guruMapel" => [],
        "waliKelas" => [],
        "absensi" => [],
        "penilaian" => [],
        "jurnal" => []
    ];
}

// 1. ACTION: STATUS & DIAGNOSTIK
if ($action === 'status') {
    $tables = [];
    if ($db_connected && $pdo) {
        try {
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {}
    }

    echo json_encode([
        'status' => 'online',
        'app_name' => 'SiApgus',
        'php_version' => PHP_VERSION,
        'mysql_connected' => $db_connected,
        'mysql_host' => $db_host ?? 'localhost',
        'mysql_database' => $db_name ?? 'db_siapgus',
        'mysql_error' => $error_message,
        'mysql_tables' => $tables,
        'table_count' => count($tables),
        'json_file_exists' => file_exists($json_file),
        'config_file_exists' => file_exists(__DIR__ . '/config.php'),
        'server_time' => date('Y-m-d H:i:s')
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// 2. ACTION: GET DATA
if ($_SERVER['REQUEST_METHOD'] === 'GET' || $action === 'get_data') {
    $db_data = null;

    // Coba ambil dari MySQL tbl_pengaturan jika terkoneksi
    if ($db_connected && $pdo) {
        try {
            $stmt = $pdo->prepare("SELECT `nilai` FROM `tbl_pengaturan` WHERE `kunci` = 'database_payload' LIMIT 1");
            $stmt->execute();
            $row = $stmt->fetch();
            if ($row && !empty($row['nilai'])) {
                $db_data = json_decode($row['nilai'], true);
            }
        } catch (Exception $e) {
            // fallback
        }
    }

    // Fallback: baca dari data/database.json
    if (!$db_data && file_exists($json_file)) {
        $raw = @file_get_contents($json_file);
        if ($raw) {
            $db_data = json_decode($raw, true);
        }
    }

    // Jika belum ada, gunakan data default
    if (!$db_data) {
        $db_data = getDefaultDatabase();
        @file_put_contents($json_file, json_encode($db_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    echo json_encode([
        'status' => 'success',
        'source' => $db_connected ? 'mysql' : 'json_file',
        'data' => $db_data,
        'server_time' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 3. ACTION: SAVE DATA
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw_input = file_get_contents('php://input');
    $payload = json_decode($raw_input, true);

    if (!$payload) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Format payload JSON tidak valid.'
        ]);
        exit;
    }

    // 1. Simpan ke file data/database.json (Offline Backup)
    @file_put_contents($json_file, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    // 2. Simpan ke MySQL jika database aktif
    $mysql_saved = false;
    if ($db_connected && $pdo) {
        try {
            $json_str = json_encode($payload, JSON_UNESCAPED_UNICODE);
            $stmt = $pdo->prepare("INSERT INTO `tbl_pengaturan` (`kunci`, `nilai`) VALUES ('database_payload', :val) ON DUPLICATE KEY UPDATE `nilai` = :val");
            $stmt->execute(['val' => $json_str]);
            $mysql_saved = true;
        } catch (Exception $e) {
            $mysql_saved = false;
        }
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Database berhasil disimpan secara permanen.',
        'mysql_saved' => $mysql_saved,
        'file_saved' => true,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit;
}

// Default 404
http_response_code(404);
echo json_encode(['status' => 'error', 'message' => 'Action tidak ditemukan.']);
?>
