@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Mata Pelajaran &amp; KKTP</h2>
            <p class="text-xs text-slate-500">Kelola kurikulum, kriteria ketercapaian tujuan pembelajaran (KKTP)</p>
        </div>
    </div>

    <!-- Form Tambah Mapel -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Tambah Mata Pelajaran Baru</h3>
        <form action="{{ route('admin.mapel.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-slate-600 mb-1">ID Mapel</label>
                <input type="text" name="id" placeholder="Misal: MP-MAT" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block font-bold text-slate-600 mb-1">Kode Singkat</label>
                <input type="text" name="kode" placeholder="MAT" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="sm:col-span-2">
                <label class="block font-bold text-slate-600 mb-1">Nama Lengkap Mata Pelajaran</label>
                <input type="text" name="nama" placeholder="Matematika..." required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block font-bold text-slate-600 mb-1">KKTP (Rentang 0 - 100)</label>
                <input type="number" min="0" max="100" name="kktp" value="75" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block font-bold text-slate-600 mb-1">Kelompok</label>
                <select name="kelompok" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-blue-500">
                    <option value="Umum">Umum (Wajib)</option>
                    <option value="Kejuruan">Kejuruan</option>
                    <option value="Pilihan">Pilihan</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-600 mb-1">Fase Pembelajaran</label>
                <select name="fase" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-blue-500">
                    <option value="E">Fase E (Kelas 10)</option>
                    <option value="F">Fase F (Kelas 11 &amp; 12)</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition shadow-sm">
                    + Simpan Mapel
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Mapel -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center text-xs">
            <span class="font-bold text-slate-700">Daftar Mata Pelajaran Kurikulum Merdeka</span>
            <span class="text-slate-500 font-semibold">Total: {{ $mapelList->count() }} Mapel</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-100/70 border-b border-slate-200 text-slate-700 font-bold">
                        <th class="p-3 w-10 text-center">No</th>
                        <th class="p-3">Kode / ID</th>
                        <th class="p-3">Nama Mata Pelajaran</th>
                        <th class="p-3 text-center">KKTP</th>
                        <th class="p-3 text-center">Kelompok</th>
                        <th class="p-3 text-center">Fase</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($mapelList as $idx => $m)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3 text-center text-slate-400">{{ $idx + 1 }}</td>
                            <td class="p-3 font-mono font-bold text-slate-700">{{ $m->kode }}</td>
                            <td class="p-3 font-bold text-slate-800">{{ $m->nama }}</td>
                            <td class="p-3 text-center font-extrabold text-blue-700">{{ $m->kktp }}</td>
                            <td class="p-3 text-center text-slate-600">{{ $m->kelompok }}</td>
                            <td class="p-3 text-center"><span class="px-2 py-0.5 rounded-lg bg-indigo-50 font-bold text-indigo-700">Fase {{ $m->fase }}</span></td>
                            <td class="p-3 text-center">
                                <form action="{{ route('admin.mapel.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus mata pelajaran ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold text-[11px]">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">Belum ada mata pelajaran yang tersimpan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
