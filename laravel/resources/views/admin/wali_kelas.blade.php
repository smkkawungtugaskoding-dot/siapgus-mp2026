@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Penetapan Wali Kelas</h2>
            <p class="text-xs text-slate-500">Guru yang ditetapkan otomatis mendapatkan hak akses Menu Absensi Siswa &amp; Cetak Rekap Bulanan</p>
        </div>
    </div>

    <!-- Form Penetapan Wali Kelas -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Tetapkan Guru Sebagai Wali Kelas</h3>
        <form action="{{ route('admin.wali_kelas.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            @csrf
            <div class="sm:col-span-2">
                <label class="block font-bold text-slate-600 mb-1">Pilih Guru</label>
                <select name="guru_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Guru Pengajar --</option>
                    @foreach($guruList as $g)
                        <option value="{{ $g->id }}">{{ $g->nama }} ({{ $g->nik_nuptk ?? $g->id }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-600 mb-1">Tingkat Kelas</label>
                <select name="kode_kelas" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-blue-500">
                    <option value="10">Kelas 10</option>
                    <option value="11">Kelas 11</option>
                    <option value="12">Kelas 12</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-600 mb-1">Rombel</label>
                <select name="rombel" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-blue-500">
                    <option value="A">Rombel A</option>
                    <option value="B">Rombel B</option>
                    <option value="C">Rombel C</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-600 mb-1">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" value="{{ $profil->tahun_ajaran }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block font-bold text-slate-600 mb-1">Semester</label>
                <input type="text" name="semester" value="{{ $profil->semester }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="sm:col-span-2 flex items-end">
                <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold transition shadow-sm">
                    ✓ Tetapkan Sebagai Wali Kelas Resmi
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Wali Kelas -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center text-xs">
            <span class="font-bold text-slate-700">Daftar Wali Kelas Terdaftar</span>
            <span class="text-slate-500 font-semibold">Total: {{ $waliList->count() }} Kelas Ditetapkan</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-100/70 border-b border-slate-200 text-slate-700 font-bold">
                        <th class="p-3 w-10 text-center">No</th>
                        <th class="p-3 text-center">Kelas &amp; Rombel</th>
                        <th class="p-3">Nama Wali Kelas</th>
                        <th class="p-3">Periode Aktif</th>
                        <th class="p-3 text-center">Hak Akses Menu</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($waliList as $idx => $w)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3 text-center text-slate-400">{{ $idx + 1 }}</td>
                            <td class="p-3 text-center">
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-lg font-extrabold text-xs">
                                    {{ $w->kode_kelas }} - {{ $w->rombel }}
                                </span>
                            </td>
                            <td class="p-3 font-bold text-slate-800">{{ $w->nama_guru }}</td>
                            <td class="p-3 text-slate-500">TA {{ $w->tahun_ajaran }} ({{ $w->semester }})</td>
                            <td class="p-3 text-center">
                                <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 font-semibold text-[10px]">
                                    Presensi &amp; Rekap Bulanan Aktif
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                <form action="{{ route('admin.wali_kelas.destroy', $w->id) }}" method="POST" onsubmit="return confirm('Batalkan penugasan wali kelas ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold text-[11px]">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Belum ada guru yang ditetapkan sebagai wali kelas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
