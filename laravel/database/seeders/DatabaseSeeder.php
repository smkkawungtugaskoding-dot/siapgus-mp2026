<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Default pada tabel 'users' (Admin & Guru/Teacher)
        DB::table('users')->updateOrInsert(
            ['id' => 'usr_admin'],
            [
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'full_name' => 'Administrator SiApgus',
                'role' => 'admin',
                'nik_nuptk' => '198501012010011001',
                'email' => 'admin@sekolah.sch.id',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        DB::table('users')->updateOrInsert(
            ['id' => 'usr_guru'],
            [
                'username' => 'guru',
                'password' => Hash::make('guru123'),
                'full_name' => 'Budi Santoso, S.Pd',
                'role' => 'guru',
                'nik_nuptk' => '198804122015021002',
                'email' => 'budi.santoso@sekolah.sch.id',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // 2. Profil Sekolah pada tabel 'profiles'
        DB::table('profiles')->updateOrInsert(
            ['id' => 1],
            [
                'school_name' => 'SMK KAWUNG SURABAYA',
                'npsn' => '20532189',
                'address' => 'Jl. Kawung No. 12, Surabaya, Jawa Timur',
                'phone' => '031-8412345',
                'email' => 'smkkawung@sch.id',
                'website' => 'https://smkkawung.sch.id',
                'principal_name' => 'Drs. H. Ahmad Sudirman, M.M.',
                'principal_nip' => '197203151998021003',
                'academic_year' => '2026/2027',
                'semester' => 'Ganjil',
                'curriculum' => 'Kurikulum Merdeka',
                'logo_url' => '',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // 3. Data Guru pada tabel 'teachers'
        DB::table('teachers')->updateOrInsert(
            ['id' => 'TCH-001'],
            [
                'user_id' => 'usr_guru',
                'nik_nuptk' => '198804122015021002',
                'name' => 'Budi Santoso, S.Pd',
                'gender' => 'L',
                'email' => 'budi.santoso@sekolah.sch.id',
                'phone' => '081234567890',
                'position' => 'Guru Kejuruan Informatika & Wali Kelas',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // 4. Data Kelas pada tabel 'classrooms'
        DB::table('classrooms')->updateOrInsert(
            ['id' => '10-A'],
            [
                'grade_level' => '10',
                'rombel' => 'A',
                'name' => 'Kelas 10-A',
                'phase' => 'E',
                'academic_year' => '2026/2027',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        DB::table('classrooms')->updateOrInsert(
            ['id' => '10-B'],
            [
                'grade_level' => '10',
                'rombel' => 'B',
                'name' => 'Kelas 10-B',
                'phase' => 'E',
                'academic_year' => '2026/2027',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // 5. Data Siswa pada tabel 'students'
        DB::table('students')->updateOrInsert(
            ['id' => 'SIS-001'],
            [
                'classroom_id' => '10-A',
                'nis' => '2026001',
                'nisn' => '0071234561',
                'name' => 'Ahmad Fauzi',
                'gender' => 'L',
                'phone' => '082100010001',
                'parent_name' => 'Bambang Supriyanto',
                'address' => 'Jl. Ketintang Baru No. 5, Surabaya',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        DB::table('students')->updateOrInsert(
            ['id' => 'SIS-002'],
            [
                'classroom_id' => '10-A',
                'nis' => '2026002',
                'nisn' => '0071234562',
                'name' => 'Siti Nurhaliza',
                'gender' => 'P',
                'phone' => '082100010002',
                'parent_name' => 'Abdul Halim',
                'address' => 'Jl. Wonokromo Indah No. 12, Surabaya',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // 6. Data Mata Pelajaran pada tabel 'subjects'
        DB::table('subjects')->updateOrInsert(
            ['id' => 'SUB-INF'],
            [
                'code' => 'INF',
                'name' => 'Informatika & Pemrograman',
                'kktp' => 75,
                'category' => 'Kejuruan',
                'phase' => 'E',
                'hours_per_week' => 4,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        DB::table('subjects')->updateOrInsert(
            ['id' => 'SUB-MAT'],
            [
                'code' => 'MAT',
                'name' => 'Matematika Terapan',
                'kktp' => 75,
                'category' => 'Umum',
                'phase' => 'E',
                'hours_per_week' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // 7. Plotting Guru Mapel pada tabel 'teacher_subject_assignments'
        DB::table('teacher_subject_assignments')->updateOrInsert(
            ['id' => 'TSA-001'],
            [
                'teacher_id' => 'TCH-001',
                'subject_id' => 'SUB-INF',
                'classroom_id' => '10-A',
                'academic_year' => '2026/2027',
                'semester' => 'Ganjil',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // 8. Penetapan Wali Kelas pada tabel 'homeroom_assignments'
        DB::table('homeroom_assignments')->updateOrInsert(
            ['id' => 'HRA-10A'],
            [
                'teacher_id' => 'TCH-001',
                'classroom_id' => '10-A',
                'academic_year' => '2026/2027',
                'semester' => 'Ganjil',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // 9. Contoh Nilai Rapor & Ujian Praktek pada tabel 'grades'
        DB::table('grades')->updateOrInsert(
            ['id' => 'GRD-10A-INF-SIS001'],
            [
                'student_id' => 'SIS-001',
                'subject_id' => 'SUB-INF',
                'classroom_id' => '10-A',
                'tp1' => 85.00,
                'tp2' => 88.00,
                'tp3' => 82.00,
                'tp4' => 86.00,
                'sts' => 85.00,
                'sas' => 88.00,
                'practical_exam' => 90.00, // Ujian Praktek 0 - 100
                'final_score' => 86.9,
                'competency_achievement' => 'Menunjukkan penguasaan sangat baik dalam logika algoritma dan pemrograman web terapan.',
                'semester' => 'Ganjil',
                'academic_year' => '2026/2027',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }
}
