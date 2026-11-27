@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Manajemen Data Guru &amp; Tenaga Pendidik</h2>
            <p class="text-xs text-slate-500">Kelola guru pengajar dan otomatis buat akun login sistem</p>
        </div>
    </div>

    <!-- Form Tambah Guru -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Tambah Guru Baru</h3>
        <form action="{{ route('admin.guru.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-slate-600 mb-1">ID Guru / Kode</label>
                <input type="text" name="id" placeholder="Misal: GURU-002" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block font-bold text-slate-600 mb-1">NUPTK / NIK</label>
                <input type="text" name="nik_nuptk" placeholder="1988..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="sm:col-span-2">
                <label class="block font-bold text-slate-600 mb-1">Nama Lengkap &amp; Gelar</label>
                <input type="text" name="nama" placeholder="Nama lengkap & gelar..." required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block font-bold text-slate-600 mb-1">Jenis Kelamin</label>
                <select name="jenis_kelamin" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-blue-500">
                    <option value="L">Laki-laki (L)</option>
                    <option value="P">Perempuan (P)</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-600 mb-1">Email</label>
                <input type="email" name="email" placeholder="email@sekolah.sch.id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block font-bold text-slate-600 mb-1">No. WhatsApp / HP</label>
                <input type="text" name="no_hp" placeholder="08..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition shadow-sm">
                    + Simpan Guru
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Guru -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center text-xs">
            <span class="font-bold text-slate-700">Daftar Guru Pengajar</span>
            <span class="text-slate-500 font-semibold">Total: {{ $guruList->count() }} Guru</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-100/70 border-b border-slate-200 text-slate-700 font-bold">
                        <th class="p-3 w-10 text-center">No</th>
                        <th class="p-3">ID / NUPTK</th>
                        <th class="p-3">Nama Guru</th>
                        <th class="p-3 text-center">L/P</th>
                        <th class="p-3">Kontak</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($guruList as $idx => $g)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3 text-center text-slate-400">{{ $idx + 1 }}</td>
                            <td class="p-3 font-mono font-bold text-slate-700">{{ $g->id }} <span class="text-slate-400 font-normal">({{ $g->nik_nuptk ?? '-' }})</span></td>
                            <td class="p-3 font-bold text-slate-800">{{ $g->nama }}</td>
                            <td class="p-3 text-center font-bold text-slate-600">{{ $g->jenis_kelamin }}</td>
                            <td class="p-3 text-slate-500">{{ $g->email ?? '-' }} &bull; {{ $g->no_hp ?? '-' }}</td>
                            <td class="p-3 text-center">
                                <form action="{{ route('admin.guru.destroy', $g->id) }}" method="POST" onsubmit="return confirm('Hapus guru ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold text-[11px]">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Belum ada data guru yang tersimpan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
