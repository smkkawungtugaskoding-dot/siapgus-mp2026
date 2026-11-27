@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Manajemen Data Siswa</h2>
            <p class="text-xs text-slate-500">Kelola database peserta didik, kelas, rombel, dan data wali</p>
        </div>
    </div>

    <!-- Form Tambah Siswa -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Tambah Data Siswa Baru</h3>
        <form action="{{ route('admin.siswa.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-slate-600 mb-1">ID Siswa / NIS</label>
                <input type="text" name="id" placeholder="Misal: SIS-001" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block font-bold text-slate-600 mb-1">NISN (10 Digit)</label>
                <input type="text" name="nisn" placeholder="0071234567" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="sm:col-span-2">
                <label class="block font-bold text-slate-600 mb-1">Nama Lengkap Siswa</label>
                <input type="text" name="nama" placeholder="Nama lengkap siswa..." required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block font-bold text-slate-600 mb-1">Kelas</label>
                <select name="kelas" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-blue-500">
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
                <label class="block font-bold text-slate-600 mb-1">Jenis Kelamin</label>
                <select name="jenis_kelamin" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-blue-500">
                    <option value="L">Laki-laki (L)</option>
                    <option value="P">Perempuan (P)</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition shadow-sm">
                    + Simpan Siswa
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Siswa -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center text-xs">
            <span class="font-bold text-slate-700">Daftar Seluruh Peserta Didik</span>
            <span class="text-slate-500 font-semibold">Total: {{ $siswaList->count() }} Siswa</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-100/70 border-b border-slate-200 text-slate-700 font-bold">
                        <th class="p-3 w-10 text-center">No</th>
                        <th class="p-3">ID / NISN</th>
                        <th class="p-3">Nama Siswa</th>
                        <th class="p-3 text-center">Kelas &amp; Rombel</th>
                        <th class="p-3 text-center">L/P</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($siswaList as $idx => $s)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3 text-center text-slate-400">{{ $idx + 1 }}</td>
                            <td class="p-3 font-mono font-bold text-slate-700">{{ $s->id }} <span class="text-slate-400 font-normal">({{ $s->nisn ?? '-' }})</span></td>
                            <td class="p-3 font-bold text-slate-800">{{ $s->nama }}</td>
                            <td class="p-3 text-center"><span class="px-2 py-0.5 rounded-lg bg-slate-100 font-bold text-slate-700">{{ $s->kelas }}-{{ $s->rombel }}</span></td>
                            <td class="p-3 text-center font-bold {{ $s->jenis_kelamin === 'L' ? 'text-blue-600' : 'text-pink-600' }}">{{ $s->jenis_kelamin }}</td>
                            <td class="p-3 text-center">
                                <form action="{{ route('admin.siswa.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus siswa ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold text-[11px]">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Belum ada data siswa yang tersimpan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
