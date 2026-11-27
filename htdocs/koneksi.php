<?php
/**
 * SiApgus - Sistem Aplikasi Guru Sekolah
 * File: koneksi.php
 * Konfigurasi database MySQL untuk XAMPP / Laragon / cPanel Hosting
 */

header('Content-Type: text/html; charset=utf-8');

// Muat file konfigurasi config.php jika tersedia
if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}

// Konfigurasi Database (Mendukung konstanta dari config.php atau default XAMPP)
$db_host = defined('DB_HOST') ? DB_HOST : "localhost";
$db_user = defined('DB_USER') ? DB_USER : "root";
$db_pass = defined('DB_PASS') ? DB_PASS : "";
$db_name = defined('DB_NAME') ? DB_NAME : "db_siapgus";
$db_port = defined('DB_PORT') ? DB_PORT : "3306";

$pdo = null;
$db_connected = false;
$error_message = "";

try {
    // 1. Coba hubungkan ke MySQL server
    $dsn_server = "mysql:host={$db_host};port={$db_port};charset=utf8mb4";
    $pdo_temp = new PDO($dsn_server, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_TIMEOUT => 2
    ]);

    // 2. Buat database db_siapgus secara otomatis jika belum ada
    $pdo_temp->exec("CREATE DATABASE IF NOT EXISTS `{$db_name}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

    // 3. Hubungkan ke database db_siapgus
    $dsn_db = "mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8mb4";
    $pdo = new PDO($dsn_db, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $db_connected = true;

    // 4. Inisialisasi tabel otomatis (Auto Schema Generator)
    inisialisasiTabel($pdo);

} catch (PDOException $e) {
    $db_connected = false;
    $error_message = $e->getMessage();
}

/**
 * Membuat tabel otomatis jika belum tersedia
 */
function inisialisasiTabel($pdo) {
    if (!$pdo) return;

    $sql = "
    CREATE TABLE IF NOT EXISTS `tbl_pengaturan` (
        `kunci` VARCHAR(64) PRIMARY KEY,
        `nilai` LONGTEXT NOT NULL,
        `diperbarui_pada` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `tbl_users` (
        `id` VARCHAR(50) PRIMARY KEY,
        `username` VARCHAR(50) UNIQUE NOT NULL,
        `password` VARCHAR(100) NOT NULL,
        `full_name` VARCHAR(150) NOT NULL,
        `role` ENUM('admin', 'guru') NOT NULL,
        `nik_nuptk` VARCHAR(50) NULL,
        `email` VARCHAR(100) NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `tbl_guru` (
        `id` VARCHAR(50) PRIMARY KEY,
        `nik_nuptk` VARCHAR(50) NULL,
        `nama` VARCHAR(150) NOT NULL,
        `jenis_kelamin` ENUM('L', 'P') NOT NULL,
        `email` VARCHAR(100) NULL,
        `no_hp` VARCHAR(30) NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `tbl_mata_pelajaran` (
        `id` VARCHAR(50) PRIMARY KEY,
        `kode` VARCHAR(30) NOT NULL,
        `nama` VARCHAR(150) NOT NULL,
        `kktp` INT DEFAULT 75,
        `kelompok` VARCHAR(50) NULL,
        `fase` VARCHAR(10) NULL,
        `jam_pekan` INT DEFAULT 2,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `tbl_wali_kelas` (
        `id` VARCHAR(50) PRIMARY KEY,
        `guru_id` VARCHAR(50) NOT NULL,
        `nama_guru` VARCHAR(150) NOT NULL,
        `kode_kelas` VARCHAR(20) NOT NULL,
        `rombel` VARCHAR(20) NOT NULL,
        `tahun_ajaran` VARCHAR(30) NOT NULL,
        `semester` VARCHAR(20) NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    $pdo->exec($sql);
}
?>
