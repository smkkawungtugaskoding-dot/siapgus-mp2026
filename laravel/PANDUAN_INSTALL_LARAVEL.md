# PANDUAN LENGKAP SIAPGUS (LARAVEL 11 + LARAVEL BREEZE)
## Sistem Aplikasi Guru Sekolah Terpadu dengan Otentikasi Role Admin & Guru

Proyek ini adalah implementasi lengkap aplikasi **SiApgus** berbasis **Framework Laravel 11** yang telah diintegrasikan dengan **Laravel Breeze** untuk sistem autentikasi, manajemen sesi aman, rate limiting (anti brute-force), dan otorisasi ketat berbasis peran (*role-based authorization*).

---

### 1. Kebutuhan Sistem (*System Requirements*)
* PHP versi **8.2** atau lebih tinggi
* Composer versi **2.x**
* Database Server **MySQL / MariaDB** (XAMPP, Laragon, Docker, atau MySQL mandiri)
* Ekstensi PHP: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`

---

### 2. Arsitektur Otentikasi Laravel Breeze & Role Otorisasi

#### A. Alur Login & Rate Limiting (`LoginRequest.php`)
Otentikasi ditangani oleh `App\Http\Requests\Auth\LoginRequest`.
* **Mendukung Login via Username & Email:** Pengguna dapat masuk menggunakan kolom `username` (seperti `admin` atau `guru`) maupun alamat `email` yang tersimpan pada tabel `users`.
* **Rate Limiter Bawaan Breeze:** Membatasi percobaan login gagal maksimal 5 kali per menit per IP untuk mencegah serangan *brute force*.

#### B. Pengalihan Otomatis Berbasis Peran (`AuthenticatedSessionController.php`)
Setelah kredensial divalidasi, controller secara otomatis mendeteksi peran pengguna:
* Pengguna dengan `role = 'admin'` dialihkan ke rute **`admin.dashboard`** (`/admin/dashboard`).
* Pengguna dengan `role = 'guru'` dialihkan ke rute **`guru.dashboard`** (`/guru/dashboard`).

#### C. Proteksi Middleware Role & Tamu
* **`CheckRole` Middleware (`app/Http/Middleware/CheckRole.php`):**
  * Memastikan rute `/admin/*` hanya dapat dibuka oleh akun bertipe `admin`.
  * Memastikan rute `/guru/*` hanya dapat dibuka oleh akun bertipe `guru`.
  * Jika user mencoba membuka URL yang tidak sesuai hak aksesnya, sistem otomatis mengarahkan ke dashboard yang benar dengan pesan peringatan.
* **`RedirectIfAuthenticated` Middleware (`app/Http/Middleware/RedirectIfAuthenticated.php`):**
  * Mencegah user yang sudah login membuka halaman `/login`. User langsung diarahkan ke dashboard masing-masing.
* **Proteksi Khusus Wali Kelas (`AbsensiController.php`):**
  * Menu Presensi Siswa dan Cetak Rekap Bulanan hanya dapat diakses oleh guru yang tercatat secara resmi pada tabel `wali_kelas` di database.

---

### 3. Langkah-Langkah Instalasi (5 Menit)

#### Langkah 1: Ekstrak Proyek
Ekstrak arsip `siapgus-laravel.zip` ke folder web server Anda:
* **Laragon:** `C:\laragon\www\siapgus-laravel`
* **XAMPP:** `C:\xampp\htdocs\siapgus-laravel`
* **Linux/Mac:** `~/projects/siapgus-laravel`

Buka terminal di dalam folder tersebut.

#### Langkah 2: Instalasi Dependensi Composer
```bash
composer install
```

#### Langkah 3: Konfigurasi Berkas Lingkungan (.env)
Salin berkas `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
*(Di Windows Command Prompt: `copy .env.example .env`)*

Buka berkas `.env` dan sesuaikan koneksi database MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_siapgus
DB_USERNAME=root
DB_PASSWORD=
```
*(Buat database kosong bernama `db_siapgus` di phpMyAdmin / MySQL terlebih dahulu).*

#### Langkah 4: Generate Application Key
```bash
php artisan key:generate
```

#### Langkah 5: Migrasi Database & Seeder Data Awal
Jalankan migrasi untuk membuat seluruh tabel dan menyuntikkan akun pengguna default:
```bash
php artisan migrate --seed
```

#### Langkah 6: Jalankan Server Lokal
```bash
php artisan serve
```
Buka browser Anda dan akses:  
👉 **http://127.0.0.1:8000** atau **http://localhost:8000**

---

### 4. Akun Pengujian Role Bawaan

| Role | Username | Password | Dashboard Tujuan | Hak Akses Utama |
| :--- | :--- | :--- | :--- | :--- |
| **Admin Sekolah** | `admin` | `admin123` | `/admin/dashboard` | Profil Sekolah & Favicon Dinamis, Master Siswa, Master Guru, Master Mapel, Penetapan Wali Kelas |
| **Guru / Wali Kelas** | `guru` | `guru123` | `/guru/dashboard` | Penilaian Rapor (TP, STS, SAS, Ujian Praktek 0-100), Cetak Rapor Sisipan & Akhir A4 TTE, Presensi & Rekap Bulanan Wali Kelas |

> *Tips:* Pada halaman login telah disediakan tombol cepat **Demo Admin** dan **Demo Guru** untuk memudahkan pengujian multi-role secara instan.

---

### 5. Struktur Berkas Laravel Breeze & Modul SiApgus
```
siapgus-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── AuthenticatedSessionController.php  # Breeze Session Login & Role Redirect
│   │   │   ├── AdminDashboardController.php           # Dashboard Admin
│   │   │   ├── GuruDashboardController.php            # Dashboard Guru
│   │   │   ├── ProfilSekolahController.php            # Profil Sekolah & Dynamic Favicon
│   │   │   ├── SiswaController.php                    # CRUD Siswa
│   │   │   ├── GuruController.php                     # CRUD Guru & Pembuatan Akun Otomatis
│   │   │   ├── MataPelajaranController.php            # CRUD Mapel & KKTP
│   │   │   ├── WaliKelasController.php                # Penetapan Wali Kelas
│   │   │   ├── AbsensiController.php                  # Presensi & Rekap Bulanan (Khusus Wali)
│   │   │   ├── PenilaianController.php                # Nilai SAS & Ujian Praktek 0-100
│   │   │   └── RaporController.php                    # Cetak Rapor A4 Resmi dengan TTE QR Code
│   │   ├── Middleware/
│   │   │   ├── CheckRole.php                          # Otorisasi Role Admin & Guru
│   │   │   └── RedirectIfAuthenticated.php            # Pengalihan Tamu Berdasarkan Role
│   │   └── Requests/
│   │       └── Auth/
│   │           └── LoginRequest.php                   # Breeze Login Request & Rate Limiter
│   └── Models/
│       ├── User.php                                   # Model User dengan isAdmin() & isGuru()
│       ├── ProfilSekolah.php
│       ├── Siswa.php
│       ├── Guru.php
│       ├── MataPelajaran.php
│       ├── WaliKelas.php
│       ├── Absensi.php
│       └── PenilaianRapor.php
├── database/
│   ├── migrations/
│   │   ├── 2026_01_01_000001_create_users_table.php
│   │   ├── 2026_01_01_000002_create_profiles_table.php
│   │   ├── 2026_01_01_000003_create_teachers_table.php
│   │   ├── 2026_01_01_000004_create_classrooms_table.php
│   │   ├── 2026_01_01_000005_create_students_table.php
│   │   ├── 2026_01_01_000006_create_subjects_table.php
│   │   ├── 2026_01_01_000007_create_teacher_subject_assignments_table.php
│   │   ├── 2026_01_01_000008_create_homeroom_assignments_table.php
│   │   ├── 2026_01_01_000009_create_attendance_table.php
│   │   ├── 2026_01_01_000010_create_daily_journals_table.php
│   │   └── 2026_01_01_000011_create_grades_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   └── views/
│       ├── components/                                # Komponen Breeze Blade
│       │   ├── auth-session-status.blade.php
│       │   ├── input-error.blade.php
│       │   ├── input-label.blade.php
│       │   ├── primary-button.blade.php
│       │   └── text-input.blade.php
│       ├── layouts/
│       │   ├── app.blade.php                          # Layout Aplikasi dengan Otorisasi Menu
│       │   └── guest.blade.php                        # Layout Breeze Tamu / Login
│       ├── auth/
│       │   └── login.blade.php                        # Halaman Login Breeze Multi-Role
│       ├── admin/
│       └── guru/
├── routes/
│   ├── auth.php                                       # Rute Otentikasi Breeze
│   ├── web.php                                        # Rute Aplikasi Berbasis Middleware Role
│   └── api.php
├── composer.json
└── .env.example
```
