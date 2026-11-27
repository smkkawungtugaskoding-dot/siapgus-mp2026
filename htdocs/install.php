<?php
/**
 * ==============================================================
 * SiApgus - Sistem Aplikasi Guru Sekolah
 * File: install.php
 * Web Setup Wizard: Pemasangan Database MySQL Otomatis
 * Kompatibel dengan XAMPP, Laragon, VPS, dan cPanel Hosting
 * ==============================================================
 */

header('Content-Type: text/html; charset=utf-8');

$config_file = __DIR__ . '/config.php';
$default_host = 'localhost';
$default_port = '3306';
$default_user = 'root';
$default_pass = '';
$default_name = 'db_siapgus';

// Baca config jika sudah ada
if (file_exists($config_file)) {
    @include_once $config_file;
    if (defined('DB_HOST')) $default_host = DB_HOST;
    if (defined('DB_PORT')) $default_port = DB_PORT;
    if (defined('DB_USER')) $default_user = DB_USER;
    if (defined('DB_PASS')) $default_pass = DB_PASS;
    if (defined('DB_NAME')) $default_name = DB_NAME;
}

$message = '';
$message_type = '';
$step = 1;

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $host = trim($_POST['db_host'] ?? 'localhost');
    $port = trim($_POST['db_port'] ?? '3306');
    $user = trim($_POST['db_user'] ?? 'root');
    $pass = $_POST['db_pass'] ?? '';
    $name = trim($_POST['db_name'] ?? 'db_siapgus');
    $admin_user = trim($_POST['admin_user'] ?? 'admin');
    $admin_pass = trim($_POST['admin_pass'] ?? 'admin123');
    $admin_name = trim($_POST['admin_name'] ?? 'Administrator SiApgus');
    $admin_email = trim($_POST['admin_email'] ?? 'admin@sekolah.sch.id');

    if ($action === 'test_connection') {
        try {
            $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
            $test_pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 3
            ]);
            $message = "Koneksi ke MySQL Server ({$host}:{$port}) BERHASIL! Server siap untuk pembuatan database.";
            $message_type = "success";
        } catch (PDOException $e) {
            $message = "Gagal terhubung ke MySQL Server: " . $e->getMessage();
            $message_type = "error";
        }
    } elseif ($action === 'install_now') {
        try {
            // 1. Hubungkan ke MySQL
            $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            // 2. Buat Database jika belum ada
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$name}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            $pdo->exec("USE `{$name}`;");

            // 3. Buat Seluruh Tabel
            $schema = "
            CREATE TABLE IF NOT EXISTS `tbl_pengaturan` (
                `kunci` VARCHAR(64) PRIMARY KEY,
                `nilai` LONGTEXT NOT NULL,
                `diperbarui_pada` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `tbl_users` (
                `id` VARCHAR(50) PRIMARY KEY,
                `username` VARCHAR(50) UNIQUE NOT NULL,
                `password` VARCHAR(100) NOT NULL,
                `full_name` VARCHAR(150) NOT NULL,
                `role` ENUM('admin', 'guru') NOT NULL,
                `nik_nuptk` VARCHAR(50) NULL,
                `email` VARCHAR(100) NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `tbl_siswa` (
                `id` VARCHAR(50) PRIMARY KEY,
                `nisn` VARCHAR(30) NULL,
                `nama` VARCHAR(150) NOT NULL,
                `kelas` VARCHAR(20) NOT NULL,
                `rombel` VARCHAR(20) NOT NULL,
                `jenis_kelamin` ENUM('L', 'P') NOT NULL,
                `no_hp` VARCHAR(30) NULL,
                `nama_wali` VARCHAR(150) NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `tbl_guru` (
                `id` VARCHAR(50) PRIMARY KEY,
                `nik_nuptk` VARCHAR(50) NULL,
                `nama` VARCHAR(150) NOT NULL,
                `jenis_kelamin` ENUM('L', 'P') NOT NULL,
                `email` VARCHAR(100) NULL,
                `no_hp` VARCHAR(30) NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `tbl_mata_pelajaran` (
                `id` VARCHAR(50) PRIMARY KEY,
                `kode` VARCHAR(30) NOT NULL,
                `nama` VARCHAR(150) NOT NULL,
                `kktp` INT DEFAULT 75,
                `kelompok` VARCHAR(50) NULL,
                `fase` VARCHAR(10) NULL,
                `jam_pekan` INT DEFAULT 2,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `tbl_wali_kelas` (
                `id` VARCHAR(50) PRIMARY KEY,
                `guru_id` VARCHAR(50) NOT NULL,
                `nama_guru` VARCHAR(150) NOT NULL,
                `kode_kelas` VARCHAR(20) NOT NULL,
                `rombel` VARCHAR(20) NOT NULL,
                `tahun_ajaran` VARCHAR(30) NOT NULL,
                `semester` VARCHAR(20) NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `tbl_absensi` (
                `id` VARCHAR(100) PRIMARY KEY,
                `tanggal` DATE NOT NULL,
                `siswa_id` VARCHAR(50) NOT NULL,
                `kelas` VARCHAR(20) NOT NULL,
                `rombel` VARCHAR(20) NOT NULL,
                `status` ENUM('H', 'S', 'I', 'A') NOT NULL DEFAULT 'H',
                `keterangan` VARCHAR(255) NULL,
                `dicatat_oleh` VARCHAR(150) NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `tbl_nilai_rapor` (
                `id` VARCHAR(120) PRIMARY KEY,
                `siswa_id` VARCHAR(50) NOT NULL,
                `mapel_id` VARCHAR(50) NOT NULL,
                `kelas` VARCHAR(20) NOT NULL,
                `rombel` VARCHAR(20) NOT NULL,
                `tp1` DECIMAL(5,2) DEFAULT 0,
                `tp2` DECIMAL(5,2) DEFAULT 0,
                `tp3` DECIMAL(5,2) DEFAULT 0,
                `tp4` DECIMAL(5,2) DEFAULT 0,
                `sts` DECIMAL(5,2) DEFAULT 0,
                `sas` DECIMAL(5,2) DEFAULT 0,
                `ujian_praktek` DECIMAL(5,2) DEFAULT 0,
                `nilai_akhir` DECIMAL(5,2) DEFAULT 0,
                `capaian` TEXT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ";
            $pdo->exec($schema);

            // 4. Seed Akun Admin & Guru
            $stmt = $pdo->prepare("INSERT INTO `tbl_users` (`id`, `username`, `password`, `full_name`, `role`, `nik_nuptk`, `email`)
                VALUES (:id, :u, :p, :name, 'admin', '198501012010011001', :email)
                ON DUPLICATE KEY UPDATE `password` = VALUES(`password`), `full_name` = VALUES(`full_name`), `email` = VALUES(`email`)");
            $stmt->execute([
                'id' => 'usr_admin',
                'u' => $admin_user,
                'p' => $admin_pass,
                'name' => $admin_name,
                'email' => $admin_email
            ]);

            // Akun Guru Default (Budi)
            $stmt2 = $pdo->prepare("INSERT INTO `tbl_users` (`id`, `username`, `password`, `full_name`, `role`, `nik_nuptk`, `email`)
                VALUES ('usr_guru', 'guru', 'guru123', 'Budi Santoso, S.Pd', 'guru', '198804122015021002', 'budi.santoso@sekolah.sch.id')
                ON DUPLICATE KEY UPDATE `full_name` = VALUES(`full_name`)");
            $stmt2->execute();

            // 5. Tulis / Update config.php
            $new_config = "<?php\n"
                . "/**\n * SiApgus - Konfigurasi Otomatis dari Web Installer\n */\n"
                . "date_default_timezone_set('Asia/Jakarta');\n\n"
                . "define('DB_HOST', " . var_export($host, true) . ");\n"
                . "define('DB_PORT', " . var_export($port, true) . ");\n"
                . "define('DB_USER', " . var_export($user, true) . ");\n"
                . "define('DB_PASS', " . var_export($pass, true) . ");\n"
                . "define('DB_NAME', " . var_export($name, true) . ");\n"
                . "define('DB_CHARSET', 'utf8mb4');\n"
                . "define('BASE_URL', '');\n"
                . "define('APP_ENV', 'development');\n\n"
                . "if (APP_ENV === 'development') {\n"
                . "    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);\n"
                . "} else {\n"
                . "    ini_set('display_errors', 0);\n"
                . "    error_reporting(0);\n"
                . "}\n"
                . "?>";
            @file_put_contents($config_file, $new_config);

            $message = "SELAMAT! Database `{$name}` dan 8 tabel SiApgus berhasil dibuat, akun Administrator telah terdaftar, dan file config.php telah diperbarui otomatis!";
            $message_type = "success";
            $step = 2; // Selesai
        } catch (Exception $e) {
            $message = "Terjadi kesalahan saat instalasi: " . $e->getMessage();
            $message_type = "error";
        }
    }
}

// Cek kelayakan server
$php_ok = version_compare(PHP_VERSION, '7.4.0', '>=');
$pdo_ok = extension_loaded('pdo');
$mysql_ok = extension_loaded('pdo_mysql');
$json_ok = extension_loaded('json');
$writable_ok = is_writable(__DIR__);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SiApgus - Web Setup & Database Installer</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-blue-950 min-h-screen text-slate-100 p-4 sm:p-6 lg:p-8 flex items-center justify-center">

  <div class="max-w-2xl w-full bg-slate-900/90 backdrop-blur-md rounded-2xl border border-slate-700/80 shadow-2xl p-6 sm:p-8 space-y-6">
    
    <!-- Header -->
    <div class="flex items-center gap-4 border-b border-slate-800 pb-5">
      <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-lg shadow-blue-500/30">
        SG
      </div>
      <div>
        <div class="flex items-center gap-2">
          <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">SiApgus Web Installer</h1>
          <span class="px-2 py-0.5 bg-blue-500/20 text-blue-300 text-[10px] font-semibold rounded-full border border-blue-500/30">XAMPP & Hosting</span>
        </div>
        <p class="text-xs text-slate-400 mt-0.5">Wizard Pemasangan Database MySQL Otomatis & Konfigurasi Server</p>
      </div>
    </div>

    <!-- Alert Notifikasi -->
    <?php if (!empty($message)): ?>
      <div class="p-4 rounded-xl text-xs font-medium flex items-start gap-3 <?= $message_type === 'success' ? 'bg-emerald-950/80 border border-emerald-500/50 text-emerald-200' : 'bg-rose-950/80 border border-rose-500/50 text-rose-200' ?>">
        <span class="text-base"><?= $message_type === 'success' ? '✓' : '⚠️' ?></span>
        <div class="flex-1"><?= htmlspecialchars($message) ?></div>
      </div>
    <?php endif; ?>

    <!-- Status Kelayakan Server -->
    <div class="bg-slate-800/60 rounded-xl p-4 border border-slate-700/60 space-y-2.5">
      <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pemeriksaan Kelayakan Sistem:</div>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs">
        <div class="flex items-center gap-2 bg-slate-900/50 p-2 rounded-lg border border-slate-800">
          <span><?= $php_ok ? '✅' : '❌' ?></span>
          <div>
            <div class="text-slate-400 text-[10px]">PHP Version</div>
            <div class="font-bold text-white"><?= PHP_VERSION ?></div>
          </div>
        </div>
        <div class="flex items-center gap-2 bg-slate-900/50 p-2 rounded-lg border border-slate-800">
          <span><?= $pdo_ok ? '✅' : '❌' ?></span>
          <div>
            <div class="text-slate-400 text-[10px]">PDO Core</div>
            <div class="font-bold text-white"><?= $pdo_ok ? 'Tersedia' : 'Tidak Ada' ?></div>
          </div>
        </div>
        <div class="flex items-center gap-2 bg-slate-900/50 p-2 rounded-lg border border-slate-800">
          <span><?= $mysql_ok ? '✅' : '❌' ?></span>
          <div>
            <div class="text-slate-400 text-[10px]">PDO MySQL</div>
            <div class="font-bold text-white"><?= $mysql_ok ? 'Tersedia' : 'Tidak Ada' ?></div>
          </div>
        </div>
        <div class="flex items-center gap-2 bg-slate-900/50 p-2 rounded-lg border border-slate-800">
          <span><?= $writable_ok ? '✅' : '⚠️' ?></span>
          <div>
            <div class="text-slate-400 text-[10px]">Write Access</div>
            <div class="font-bold text-white"><?= $writable_ok ? 'Writable' : 'Read-only' ?></div>
          </div>
        </div>
      </div>
    </div>

    <?php if ($step === 2): ?>
      <!-- Tampilan Berhasil / Sukses -->
      <div class="bg-emerald-950/40 border border-emerald-500/40 rounded-xl p-6 text-center space-y-4">
        <div class="w-16 h-16 bg-emerald-500/20 text-emerald-400 rounded-full flex items-center justify-center mx-auto text-3xl font-black border border-emerald-500/30">
          ✓
        </div>
        <div>
          <h2 class="text-lg font-bold text-white">Instalasi Selesai!</h2>
          <p class="text-xs text-slate-300 mt-1 max-w-md mx-auto">
            Aplikasi SiApgus kini terhubung sempurna ke database MySQL. Anda dapat langsung masuk dengan akun Administrator.
          </p>
        </div>

        <div class="bg-slate-900/80 p-3 rounded-lg border border-slate-800 text-left text-xs space-y-1 max-w-sm mx-auto font-mono">
          <div class="text-slate-400 text-[10px] uppercase font-sans font-bold">Kredensial Login Administrator:</div>
          <div><span class="text-slate-400">Username:</span> <span class="text-blue-400 font-bold"><?= htmlspecialchars($admin_user) ?></span></div>
          <div><span class="text-slate-400">Password:</span> <span class="text-emerald-400 font-bold"><?= htmlspecialchars($admin_pass) ?></span></div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center pt-2">
          <a href="index.php" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl shadow-lg transition">
            🚀 Buka Aplikasi SiApgus
          </a>
          <a href="export_database.php" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-xs rounded-xl border border-slate-700 transition">
            📥 Backup Database (.sql)
          </a>
        </div>
      </div>

    <?php else: ?>

      <!-- Form Konfigurasi Database -->
      <form method="POST" action="install.php" class="space-y-4">
        
        <div class="text-xs font-bold text-blue-400 flex items-center gap-1.5 uppercase tracking-wider">
          <span>⚙️</span> Parameter Database MySQL
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-slate-300 mb-1">Database Host</label>
            <input type="text" name="db_host" required value="<?= htmlspecialchars($default_host) ?>" class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <span class="text-[10px] text-slate-400">XAMPP/Hosting: biasanya <code>localhost</code></span>
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-300 mb-1">Port</label>
            <input type="text" name="db_port" required value="<?= htmlspecialchars($default_port) ?>" class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-white focus:ring-2 focus:ring-blue-500 focus:outline-none font-mono">
            <span class="text-[10px] text-slate-400">Standar: <code>3306</code></span>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-semibold text-slate-300 mb-1">Database User</label>
            <input type="text" name="db_user" required value="<?= htmlspecialchars($default_user) ?>" class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <span class="text-[10px] text-slate-400">XAMPP default: <code>root</code></span>
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-300 mb-1">Database Password</label>
            <input type="password" name="db_pass" value="<?= htmlspecialchars($default_pass) ?>" class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-white focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Kosong untuk XAMPP default">
            <span class="text-[10px] text-slate-400">XAMPP default kosong</span>
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Database MySQL</label>
          <input type="text" name="db_name" required value="<?= htmlspecialchars($default_name) ?>" class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-white focus:ring-2 focus:ring-blue-500 focus:outline-none font-mono">
          <span class="text-[10px] text-slate-400">Database akan otomatis dibuat bila belum tersedia di MySQL.</span>
        </div>

        <div class="pt-3 border-t border-slate-800">
          <div class="text-xs font-bold text-amber-400 flex items-center gap-1.5 uppercase tracking-wider mb-3">
            <span>👤</span> Akun Administrator SiApgus Baru
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-slate-300 mb-1">Username Admin</label>
              <input type="text" name="admin_user" required value="admin" class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-300 mb-1">Password Admin</label>
              <input type="text" name="admin_pass" required value="admin123" class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-white focus:ring-2 focus:ring-blue-500 focus:outline-none font-mono">
            </div>
          </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="pt-4 flex flex-col sm:flex-row gap-3">
          <button type="submit" name="action" value="test_connection" class="flex-1 py-2.5 px-4 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl text-xs font-bold transition">
            🔌 Uji Koneksi MySQL
          </button>
          <button type="submit" name="action" value="install_now" class="flex-1 py-2.5 px-4 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold shadow-lg shadow-blue-600/30 transition">
            ⚡ Pasang Database & Tabel Sekarang
          </button>
        </div>
      </form>

    <?php endif; ?>

    <!-- Footer Panduan -->
    <div class="border-t border-slate-800 pt-4 text-center text-[11px] text-slate-500 space-y-1">
      <div>Folder instalasi: <code class="text-slate-400 font-mono"><?= __DIR__ ?></code></div>
      <div>Untuk XAMPP: Taruh folder ini di <code class="text-slate-400 font-mono">C:\xampp\htdocs\siapgus</code> lalu buka <code class="text-blue-400">http://localhost/siapgus/install.php</code></div>
    </div>

  </div>

</body>
</html>
