<?php
/**
 * ==============================================================
 * SiApgus - Sistem Aplikasi Guru Sekolah
 * File: export_database.php
 * Utilitas Backup / Ekspor Database MySQL SiApgus Langsung ke File .SQL
 * ==============================================================
 */

require_once __DIR__ . '/koneksi.php';

$format = $_GET['format'] ?? 'sql';
$timestamp = date('Y-m-d_His');

if ($format === 'json') {
    $json_file = __DIR__ . '/data/database.json';
    if (file_exists($json_file)) {
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="siapgus_backup_' . $timestamp . '.json"');
        readfile($json_file);
        exit;
    }
}

// Ekspor ke .SQL
header('Content-Type: text/plain; charset=utf-8');
header('Content-Disposition: attachment; filename="db_siapgus_backup_' . $timestamp . '.sql"');

echo "-- ==============================================================\n";
echo "-- SIAPGUS DATABASE BACKUP EXPORTER\n";
echo "-- Tanggal & Waktu: " . date('Y-m-d H:i:s') . "\n";
echo "-- Host: " . ($db_host ?? 'localhost') . "\n";
echo "-- Database: " . ($db_name ?? 'db_siapgus') . "\n";
echo "-- ==============================================================\n\n";

echo "SET NAMES utf8mb4;\n";
echo "SET FOREIGN_KEY_CHECKS = 0;\n\n";

if (!$db_connected || !$pdo) {
    echo "-- PERINGATAN: Database MySQL tidak terkoneksi.\n";
    echo "-- Menampilkan struktur SQL standar bawaan SiApgus.\n\n";
    $sql_file = __DIR__ . '/database.sql';
    if (file_exists($sql_file)) {
        readfile($sql_file);
    }
    exit;
}

$tables = [
    'tbl_pengaturan',
    'tbl_users',
    'tbl_siswa',
    'tbl_guru',
    'tbl_mata_pelajaran',
    'tbl_wali_kelas',
    'tbl_absensi',
    'tbl_nilai_rapor'
];

foreach ($tables as $table) {
    try {
        // 1. Create table structure
        $stmt = $pdo->query("SHOW CREATE TABLE `{$table}`");
        $row = $stmt->fetch(PDO::FETCH_NUM);
        if ($row) {
            echo "-- --------------------------------------------------------\n";
            echo "-- Struktur Tabel `{$table}`\n";
            echo "-- --------------------------------------------------------\n";
            echo "DROP TABLE IF EXISTS `{$table}`;\n";
            echo $row[1] . ";\n\n";
        }

        // 2. Dump table data
        $stmt = $pdo->query("SELECT * FROM `{$table}`");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($rows)) {
            echo "-- Data untuk Tabel `{$table}`\n";
            foreach ($rows as $dataRow) {
                $cols = array_map(function($c) { return "`{$c}`"; }, array_keys($dataRow));
                $vals = array_map(function($v) use ($pdo) {
                    if ($v === null) return "NULL";
                    return $pdo->quote($v);
                }, array_values($dataRow));

                echo "INSERT INTO `{$table}` (" . implode(", ", $cols) . ") VALUES (" . implode(", ", $vals) . ");\n";
            }
            echo "\n";
        }
    } catch (Exception $e) {
        echo "-- Error saat mengekspor {$table}: " . $e->getMessage() . "\n\n";
    }
}

echo "SET FOREIGN_KEY_CHECKS = 1;\n";
echo "-- Ekspor Database Selesai.\n";
exit;
?>
