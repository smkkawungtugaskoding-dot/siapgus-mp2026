@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Data Penilaian Rapor (Kurikulum Merdeka)</h2>
            <p class="text-xs text-slate-500">Pengisian Nilai TP, STS, SAS, Ujian Praktek (0-100), dan Cetak Rapor Siswa</p>
        </div>
    </div>

    <!-- 1. Filter Pilihan Kelas, Rombel, & Mata Pelajaran (Wajib Dipilih Dahulu) -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">
            Pilih Kelas &amp; Kurikulum Yang Anda Ajar
        </h3>
        <form action="{{ route('guru.penilaian') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Tingkat Kelas</label>
                <select name="kelas" required class="w-full px-3 py-2 bg-slate-50 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Tingkat Kelas --</option>
                    <option value="10" {{ $selectedKelas === '10' ? 'selected' : '' }}>Kelas 10 (Fase E)</option>
                    <option value="11" {{ $selectedKelas === '11' ? 'selected' : '' }}>Kelas 11 (Fase F)</option>
                    <option value="12" {{ $selectedKelas === '12' ? 'selected' : '' }}>Kelas 12 (Fase F)</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Rombongan Belajar (Rombel)</label>
                <select name="rombel" required class="w-full px-3 py-2 bg-slate-50 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Rombel --</option>
                    <option value="A" {{ $selectedRombel === 'A' ? 'selected' : '' }}>Rombel A</option>
                    <option value="B" {{ $selectedRombel === 'B' ? 'selected' : '' }}>Rombel B</option>
                    <option value="C" {{ $selectedRombel === 'C' ? 'selected' : '' }}>Rombel C</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Mata Pelajaran</label>
                <select name="mapel_id" required class="w-full px-3 py-2 bg-slate-50 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach($allMapel as $m)
                        <option value="{{ $m->id }}" {{ $selectedMapel === $m->id ? 'selected' : '' }}>
                            {{ $m->nama }} (KKTP: {{ $m->kktp }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                    Tampilkan Data Siswa
                </button>
            </div>
        </form>
    </div>

    <!-- 2. Tabel Penilaian Siswa (Hanya Tampil Jika Kelas & Mapel Sudah Dipilih) -->
    @if(!empty($selectedKelas) && !empty($selectedRombel) && !empty($selectedMapel))
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">
                        Daftar Nilai Kelas {{ $selectedKelas }}-{{ $selectedRombel }} &bull; {{ $activeMapel->nama ?? 'Mapel' }}
                    </h3>
                    <p class="text-[11px] text-slate-500">
                        Ketik nilai pada kotak Ujian Praktek lalu klik tombol Simpan pada baris siswa. Kotak nilai ujian/ulangan siswa akan otomatis berubah warna hijau.
                    </p>
                </div>
                <div class="text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-bold">
                    KKTP: {{ $activeMapel->kktp ?? 75 }}
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-100/70 border-b border-slate-200 text-slate-700 font-bold">
                            <th class="p-3 w-10 text-center">No</th>
                            <th class="p-3">Nama Siswa</th>
                            <th class="p-3 w-16 text-center">TP1</th>
                            <th class="p-3 w-16 text-center">TP2</th>
                            <th class="p-3 w-16 text-center">TP3</th>
                            <th class="p-3 w-16 text-center">STS</th>
                            <th class="p-3 w-16 text-center">SAS</th>
                            <!-- KOTAK ISIAN NILAI UJIAN PRAKTEK (0 - 100) DI SEBELAH KANAN SAS -->
                            <th class="p-3 w-24 text-center bg-indigo-50/70 text-indigo-900 border-x border-indigo-100">
                                Ujian Praktek<br><span class="text-[9px] font-normal text-indigo-600">(0 - 100)</span>
                            </th>
                            <th class="p-3 w-20 text-center">Hasil Akhir</th>
                            <th class="p-3 text-center">Aksi Simpan</th>
                            <th class="p-3 text-center bg-slate-50">Cetak Rapor (Sejajar Baris)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($siswaList as $idx => $s)
                            @php
                                $nilai = $penilaianList->get($s->id);
                                $isSaved = session('saved_siswa_id') === $s->id || ($nilai && $nilai->ujian_praktek > 0);
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="p-3 text-center text-slate-400 font-medium">{{ $idx + 1 }}</td>
                                <td class="p-3">
                                    <p class="font-bold text-slate-800">{{ $s->nama }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono">NISN: {{ $s->nisn ?? $s->id }}</p>
                                </td>

                                <form action="{{ route('guru.penilaian.simpan') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="siswa_id" value="{{ $s->id }}">
                                    <input type="hidden" name="mapel_id" value="{{ $selectedMapel }}">
                                    <input type="hidden" name="kelas" value="{{ $selectedKelas }}">
                                    <input type="hidden" name="rombel" value="{{ $selectedRombel }}">

                                    <td class="p-2 text-center">
                                        <input type="number" min="0" max="100" name="tp1" value="{{ $nilai->tp1 ?? '' }}"
                                               class="w-14 text-center px-1 py-1 rounded-lg border border-slate-200 text-xs {{ $isSaved ? 'bg-emerald-50 text-emerald-900 border-emerald-400 font-bold' : '' }}">
                                    </td>
                                    <td class="p-2 text-center">
                                        <input type="number" min="0" max="100" name="tp2" value="{{ $nilai->tp2 ?? '' }}"
                                               class="w-14 text-center px-1 py-1 rounded-lg border border-slate-200 text-xs {{ $isSaved ? 'bg-emerald-50 text-emerald-900 border-emerald-400 font-bold' : '' }}">
                                    </td>
                                    <td class="p-2 text-center">
                                        <input type="number" min="0" max="100" name="tp3" value="{{ $nilai->tp3 ?? '' }}"
                                               class="w-14 text-center px-1 py-1 rounded-lg border border-slate-200 text-xs {{ $isSaved ? 'bg-emerald-50 text-emerald-900 border-emerald-400 font-bold' : '' }}">
                                    </td>
                                    <td class="p-2 text-center">
                                        <input type="number" min="0" max="100" name="sts" value="{{ $nilai->sts ?? '' }}"
                                               class="w-14 text-center px-1 py-1 rounded-lg border border-slate-200 text-xs {{ $isSaved ? 'bg-emerald-50 text-emerald-900 border-emerald-400 font-bold' : '' }}">
                                    </td>
                                    <td class="p-2 text-center">
                                        <input type="number" min="0" max="100" name="sas" value="{{ $nilai->sas ?? '' }}"
                                               class="w-14 text-center px-1 py-1 rounded-lg border border-slate-200 text-xs {{ $isSaved ? 'bg-emerald-50 text-emerald-900 border-emerald-400 font-bold' : '' }}">
                                    </td>

                                    <!-- KOTAK ISIAN NILAI UJIAN PRAKTEK (RENTANG 0 - 100) -->
                                    <td class="p-2 text-center bg-indigo-50/30 border-x border-indigo-100">
                                        <input type="number" min="0" max="100" name="ujian_praktek" value="{{ $nilai->ujian_praktek ?? '' }}"
                                               placeholder="0-100"
                                               class="w-16 text-center px-2 py-1 rounded-lg text-xs font-bold transition shadow-2xs {{ $isSaved ? 'bg-emerald-100 text-emerald-900 border-2 border-emerald-500 ring-2 ring-emerald-200' : 'bg-white border border-indigo-300 text-indigo-900' }}">
                                    </td>

                                    <td class="p-3 text-center font-extrabold text-sm {{ ($nilai->nilai_akhir ?? 0) >= ($activeMapel->kktp ?? 75) ? 'text-emerald-700' : 'text-slate-700' }}">
                                        {{ $nilai->nilai_akhir ?? '0' }}
                                    </td>

                                    <td class="p-3 text-center">
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition shadow-xs">
                                            Simpan
                                        </button>
                                    </td>
                                </form>

                                <!-- TOMBOL CETAK RAPOR SEJAJAR DENGAN NAMA SISWA -->
                                <td class="p-3 text-center bg-slate-50/70">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Cetak Rapor Sisipan -->
                                        <a href="{{ route('guru.rapor.sisipan', $s->id) }}" target="_blank"
                                           class="px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded-lg text-[11px] font-bold transition flex items-center gap-1"
                                           title="Nilai otomatis ditarik dari Input Rapor: Nilai Akhir Sisipan & Capaian STS. Gunakan tombol Print Preview untuk mengecek tampilan A4 sebelum mencetak atau menyimpan PDF. Tanda tangan kepsek sdh menggunakan tanda tangan elektronik.">
                                            📄 Cetak Sisipan
                                        </a>

                                        <!-- Cetak Rapor Akhir -->
                                        <a href="{{ route('guru.rapor.akhir', $s->id) }}" target="_blank"
                                           class="px-2.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-800 border border-blue-200 rounded-lg text-[11px] font-bold transition flex items-center gap-1"
                                           title="Nilai otomatis ditarik dari Input Rapor: Nilai Akhir & Capaian SAS. Gunakan tombol Print Preview untuk mengecek tampilan A4 sebelum mencetak atau menyimpan PDF. Tanda tangan kepsek sdh menggunakan tanda tangan elektronik.">
                                            📜 Cetak Akhir
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="p-8 text-center text-slate-400">
                                    Belum ada data siswa di kelas {{ $selectedKelas }}-{{ $selectedRombel }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="p-12 text-center bg-white rounded-2xl border border-dashed border-slate-200 text-slate-400 space-y-2">
            <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-xl">
                📋
            </div>
            <p class="font-bold text-slate-600 text-sm">Pilih Kelas, Rombel, dan Mata Pelajaran Terlebih Dahulu</p>
            <p class="text-xs text-slate-400">Daftar siswa dan kotak penilaian akan muncul setelah pilihan di atas diatur.</p>
        </div>
    @endif
</div>
@endsection
