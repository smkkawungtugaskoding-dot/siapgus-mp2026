# Panduan Lengkap Pengoperasian SiApgus (XAMPP, LAN Wi-Fi & Web Hosting Online)

**SiApgus (Sistem Aplikasi Guru Sekolah)** adalah sistem manajemen akademik sekolah modern yang mendukung Kurikulum Merdeka, penilaian formatif/sumatif, ujian praktek (0-100), cetak rapor sisipan dan akhir berstandar A4 dengan Tanda Tangan Elektronik (TTE QR Code), absensi wali kelas dengan rekap bulanan A4, serta sinkronisasi otomatis database MySQL dan Google Spreadsheet.

---

## 📁 1. Daftar Perangkat & Komponen Paket `htdocs`

Folder ini berisi seluruh komponen perangkat lunak siap jalan:

| Nama File / Folder | Fungsi & Deskripsi |
| :--- | :--- |
| `index.php` | Entry point server Apache/PHP yang menyajikan aplikasi dengan integrasi database |
| `index.html` | Aplikasi Web Single Page Application (SPA) mandiri berbasis Tailwind & JS modern |
| `config.php` | Konfigurasi database MySQL (`DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`, `DB_PORT`) |
| `install.php` | **Web Setup Wizard** interaktif untuk tes koneksi dan pembuatan tabel otomatis |
| `koneksi.php` | Script PDO penghubung MySQL dengan mekanisme auto-create database & auto-schema |
| `api.php` | Endpoint REST API sinkronisasi data frontend dengan MySQL & file cadangan JSON |
| `database.sql` | Struktur tabel dan data awal MySQL siap import langsung di phpMyAdmin |
| `export_database.php` | Utilitas pencadangan (backup) database langsung menjadi file `.sql` via browser |
| `.htaccess` | Pengaturan server Apache: mod_rewrite, UTF-8 charset, Gzip kompresi, header keamanan |
| `favicon.ico` & `.svg` | Ikon resmi SiApgus untuk tab browser |
| `data/` | Direktori penyimpanan database offline lokal (`database.json`) |

---

## 💻 2. Cara Menjalankan di XAMPP Windows (Localhost)

1. **Persiapan:** Pastikan aplikasi **XAMPP** (versi PHP 7.4 ke atas) telah terpasang di komputer Anda.
2. **Ekstrak File:** Buat folder `siapgus` di dalam direktori htdocs XAMPP:
   ```text
   C:\xampp\htdocs\siapgus\
   ```
   Salin seluruh file dari paket ini ke dalam folder tersebut.
3. **Nyalakan Layanan XAMPP:**
   - Buka **XAMPP Control Panel**.
   - Klik tombol **Start** pada baris **Apache** (hingga berwarna hijau).
   - Klik tombol **Start** pada baris **MySQL** (hingga berwarna hijau).
4. **Buka Aplikasi:**
   Buka Google Chrome atau Microsoft Edge, lalu ketik:
   ```text
   http://localhost/siapgus/
   ```
5. **(Opsional) Setup Wizard:** Jika ingin memverifikasi koneksi database atau mengganti password admin:
   ```text
   http://localhost/siapgus/install.php
   ```

---

## 📶 3. Cara Mengakses Online di Jaringan Wi-Fi Sekolah (Multi-User Guru)

Anda dapat membuat komputer admin di ruang guru berfungsi sebagai **Server Sekolah**, sehingga semua guru dan wali kelas dapat mengakses SiApgus dari laptop atau HP masing-masing tanpa memerlukan koneksi internet:

1. Pastikan laptop admin dan semua perangkat guru terhubung ke jaringan **Wi-Fi yang sama**.
2. Di laptop admin XAMPP, buka **Command Prompt (CMD)** lalu ketik:
   ```cmd
   ipconfig
   ```
3. Lihat bagian **IPv4 Address**, misalnya tercatat: `192.168.1.50`
4. Di laptop atau HP guru lain, cukup buka browser dan ketik:
   ```text
   http://192.168.1.50/siapgus/
   ```
5. Semua guru kini bisa input nilai, absensi, dan cetak rapor secara bersamaan dari tempat duduk masing-masing!

---

## 🌐 4. Cara Upload ke Web Hosting / cPanel (Online di Seluruh Dunia)

Agar SiApgus bisa diakses dari mana saja melalui internet dengan domain resmi sekolah (contoh: `https://rapor.smkkawung.sch.id`):

### Langkah 1: Buat Arsip ZIP
Kompres seluruh isi folder `htdocs` ini menjadi file ZIP (misal: `siapgus.zip`).

### Langkah 2: Upload ke cPanel
1. Login ke panel hosting sekolah Anda (misal: `https://domainsekolah.sch.id:2083`).
2. Masuk ke menu **File Manager**.
3. Buka direktori target:
   - Jika ingin menjadi website utama: masuk ke folder `public_html`.
   - Jika ingin menjadi sub-domain / folder: buat folder baru, misalnya `public_html/rapor`.
4. Klik tombol **Upload**, unggah file `siapgus.zip`, kemudian klik kanan file tersebut dan pilih **Extract**.

### Langkah 3: Buat Database MySQL di Hosting
1. Di halaman cPanel, buka menu **MySQL Databases** atau **MySQL Database Wizard**.
2. Buat nama database baru, misalnya: `sekolah_siapgus`.
3. Buat nama user baru, misalnya: `sekolah_user`, dan masukkan password yang kuat.
4. Hubungkan user ke database dengan mencentang pilihan **ALL PRIVILEGES**.

### Langkah 4: Jalankan Setup Wizard Otomatis
1. Buka browser dan akses alamat instalasi:
   ```text
   https://domainsekolah.sch.id/rapor/install.php
   ```
2. Isi formulir dengan data yang baru dibuat di cPanel:
   - **Database Host:** `localhost`
   - **Database User:** `sekolah_user`
   - **Database Password:** *(password database hosting Anda)*
   - **Database Name:** `sekolah_siapgus`
3. Klik tombol **⚡ Pasang Database & Tabel Sekarang**.
4. Sistem akan otomatis menguji koneksi, membuat 8 tabel MySQL, mendaftarkan akun admin, dan memperbarui file `config.php`.
5. Klik **Buka Aplikasi SiApgus** — website sekolah Anda telah aktif dan online!

---

## 🔐 5. Kredensial Login Bawaan

| Akun | Username | Password Standar | Peran & Hak Akses |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin` | `123` / `admin123` | Akses Penuh: Profil Sekolah, Guru, Siswa, Mapel, Wali Kelas, Backup Data |
| **Guru / Wali Kelas** | `budi` / `guru` | `123` / `guru123` | Jurnal KBM, Presensi Siswa & Rekap Bulanan A4, Nilai SAS & Ujian Praktek, Rapor TTE QR Code |

### 🔑 Pemulihan Password (Lupa Password):
Jika administrator atau guru lupa password:
1. Di halaman login, klik tulisan **Lupa Password?**.
2. Pilih peran akun (**Administrator** atau **Guru**).
3. Masukkan username atau alamat email yang terdaftar.
4. Masukkan password baru dan konfirmasi.
5. Password akan langsung diperbarui di database lokal dan MySQL.

---

## 💾 6. Cara Mencadangkan (Backup) Database

Untuk menjaga keamanan data sekolah secara berkala:
- **Download File SQL:** Cukup buka URL `http://domain-anda/export_database.php?format=sql` di browser. File `.sql` lengkap akan terunduh dalam 1 detik.
- **Download File JSON:** Buka URL `http://domain-anda/export_database.php?format=json` untuk backup format JSON portabel.
