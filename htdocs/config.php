<?php
/**
 * ==============================================================
 * SiApgus - Sistem Aplikasi Guru Sekolah
 * File: config.php
 * Konfigurasi Utama Database MySQL & Pengaturan Server Web
 * Cocok untuk XAMPP (Windows/Mac/Linux), Laragon, VPS & cPanel Hosting
 * ==============================================================
 */

// Zona Waktu Indonesia
date_default_timezone_set('Asia/Jakarta');

// Konfigurasi Database MySQL
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_siapgus');
define('DB_CHARSET', 'utf8mb4');

// URL Dasar Aplikasi (Opsional: Kosongkan untuk deteksi otomatis)
// Contoh untuk XAMPP: http://localhost/siapgus
// Contoh untuk Online: https://rapor.sekolah.sch.id
define('BASE_URL', '');

// Mode Aplikasi: 'development' (tampilkan error) atau 'production' (sembunyikan error)
define('APP_ENV', 'development');

if (APP_ENV === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
?>
