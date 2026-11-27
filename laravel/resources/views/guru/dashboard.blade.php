@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Selamat Datang, {{ $user->full_name }}</h2>
            <p class="text-xs text-slate-500">Dashboard Guru &bull; Tahun Ajaran {{ $profil->tahun_ajaran }} ({{ $profil->semester }})</p>
        </div>
        @if($waliKelas)
            <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold border border-emerald-300">
                ⭐ Terdaftar Sebagai Wali Kelas {{ $waliKelas->kode_kelas }}-{{ $waliKelas->rombel }}
            </span>
        @endif
    </div>

    <!-- Info Card Tugas Mengajar & Wali Kelas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3">
            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Aktivitas Penilaian &amp; Rapor</h3>
            <p class="text-xs text-slate-600">
                Kelola penilaian Kurikulum Merdeka (TP1..TP4, STS, SAS, dan Nilai Ujian Praktek 0-100), cetak rapor sisipan dan akhir siswa secara mandiri.
            </p>
            <a href="{{ route('guru.penilaian') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition">
                <span>📝 Buka Input Nilai &amp; Rapor</span>
            </a>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3">
            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Tugas Wali Kelas</h3>
            @if($waliKelas)
                <p class="text-xs text-slate-600">
                    Anda ditugaskan sebagai <strong>Wali Kelas {{ $waliKelas->kode_kelas }}-{{ $waliKelas->rombel }}</strong>. Anda memiliki hak akses penuh ke menu Presensi Harian, Rekap Bulanan, dan Cetak Rekap Resmi A4.
                </p>
                <a href="{{ route('guru.absensi') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition">
                    <span>📅 Kelola Absensi &amp; Rekap Bulanan</span>
                </a>
            @else
                <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 text-xs">
                    ℹ️ Anda saat ini berstatus <strong>Guru Mata Pelajaran</strong>. Menu Absensi Siswa hanya dibuka untuk guru yang telah ditetapkan sebagai Wali Kelas oleh Administrator.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
