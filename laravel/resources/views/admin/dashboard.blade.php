@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Dashboard Administrator</h2>
            <p class="text-xs text-slate-500">Ringkasan Sistem &amp; Manajemen Data Sekolah SiApgus (Laravel 11)</p>
        </div>
        <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold border border-blue-200">
            TA {{ $profil->tahun_ajaran }} ({{ $profil->semester }})
        </span>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-xs font-semibold text-slate-400">Total Siswa</span>
            <p class="text-2xl font-black text-slate-800">{{ $totalSiswa }}</p>
            <span class="text-[10px] text-blue-600 font-bold">Terdaftar Aktif</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-xs font-semibold text-slate-400">Total Guru</span>
            <p class="text-2xl font-black text-slate-800">{{ $totalGuru }}</p>
            <span class="text-[10px] text-emerald-600 font-bold">Dewan Pengajar</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-xs font-semibold text-slate-400">Mata Pelajaran</span>
            <p class="text-2xl font-black text-slate-800">{{ $totalMapel }}</p>
            <span class="text-[10px] text-indigo-600 font-bold">Kurikulum Merdeka</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-xs font-semibold text-slate-400">Wali Kelas</span>
            <p class="text-2xl font-black text-slate-800">{{ $totalWaliKelas }}</p>
            <span class="text-[10px] text-amber-600 font-bold">Ditetapkan Resmi</span>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3">
        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Akses Cepat Pengelolaan</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <a href="{{ route('admin.profil') }}" class="p-3 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 font-bold text-slate-700 flex items-center gap-2 transition">
                <span>🏛️ Profil Sekolah</span>
            </a>
            <a href="{{ route('admin.siswa') }}" class="p-3 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 font-bold text-slate-700 flex items-center gap-2 transition">
                <span>👥 Data Siswa</span>
            </a>
            <a href="{{ route('admin.guru') }}" class="p-3 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 font-bold text-slate-700 flex items-center gap-2 transition">
                <span>👨‍🏫 Data Guru</span>
            </a>
            <a href="{{ route('admin.wali_kelas') }}" class="p-3 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 font-bold text-slate-700 flex items-center gap-2 transition">
                <span>📋 Penetapan Wali Kelas</span>
            </a>
        </div>
    </div>
</div>
@endsection
