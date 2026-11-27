-- ==============================================================
-- DATABASE SIAPGUS (SISTEM APLIKASI GURU SEKOLAH)
-- Siap Import Langsung di phpMyAdmin (XAMPP / Laragon / cPanel)
-- ==============================================================

CREATE DATABASE IF NOT EXISTS `db_siapgus` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_siapgus`;

-- 1. Tabel Pengaturan & Payload Database JSON
CREATE TABLE IF NOT EXISTS `tbl_pengaturan` (
    `kunci` VARCHAR(64) PRIMARY KEY,
    `nilai` LONGTEXT NOT NULL,
    `diperbarui_pada` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabel Pengguna (Admin & Guru)
CREATE TABLE IF NOT EXISTS `tbl_users` (
    `id` VARCHAR(50) PRIMARY KEY,
    `username` VARCHAR(50) UNIQUE NOT NULL,
    `password` VARCHAR(100) NOT NULL,
    `full_name` VARCHAR(150) NOT NULL,
    `role` ENUM('admin', 'guru') NOT NULL,
    `nik_nuptk` VARCHAR(50) NULL,
    `email` VARCHAR(100) NULL,
    `avatar_url` LONGTEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabel Siswa
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

-- 4. Tabel Guru
CREATE TABLE IF NOT EXISTS `tbl_guru` (
    `id` VARCHAR(50) PRIMARY KEY,
    `nik_nuptk` VARCHAR(50) NULL,
    `nama` VARCHAR(150) NOT NULL,
    `jenis_kelamin` ENUM('L', 'P') NOT NULL,
    `mapel` VARCHAR(150) NULL,
    `foto_url` LONGTEXT NULL,
    `email` VARCHAR(100) NULL,
    `no_hp` VARCHAR(30) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Tabel Mata Pelajaran
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

-- 6. Tabel Wali Kelas
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

-- 7. Tabel Absensi Harian Siswa
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

-- 8. Tabel Penilaian Rapor (STS, SAS, Ujian Praktek, Capaian)
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

-- Insert Akun Default
INSERT INTO `tbl_users` (`id`, `username`, `password`, `full_name`, `role`, `nik_nuptk`, `email`)
VALUES 
('usr_admin', 'admin', 'admin123', 'Administrator SiApgus', 'admin', '198501012010011001', 'admin@sekolah.sch.id'),
('usr_guru', 'guru', 'guru123', 'Budi Santoso, S.Pd', 'guru', '198804122015021002', 'budi.santoso@sekolah.sch.id')
ON DUPLICATE KEY UPDATE `full_name` = VALUES(`full_name`);
