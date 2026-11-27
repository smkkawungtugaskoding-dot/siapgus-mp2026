<?php
/**
 * SiApgus - Sistem Aplikasi Guru Sekolah
 * File: index.php
 * Entry point utama aplikasi SiApgus untuk XAMPP / Apache / cPanel Hosting
 */

// Memuat koneksi database & inisialisasi tabel otomatis
require_once __DIR__ . '/koneksi.php';

// Membaca file index.html utama
$html_file = __DIR__ . '/index.html';
if (file_exists($html_file)) {
    // Sajikan index.html dengan header UTF-8
    header('Content-Type: text/html; charset=utf-8');
    require_once $html_file;
} else {
    echo "<h1>Error: file index.html tidak ditemukan di dalam folder htdocs!</h1>";
}
?>
