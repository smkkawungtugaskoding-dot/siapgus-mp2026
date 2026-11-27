@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                    Wali Kelas {{ $wali->kode_kelas }}-{{ $wali->rombel }}
                </span>
                <h2 class="text-xl font-bold text-slate-800">Presensi &amp; Rekap Absensi Siswa</h2>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Khusus Wali Kelas: Pencatatan Harian &amp; Rekap Absensi Bulanan Resmi</p>
        </div>

        <div class="flex items-center gap-2">
            <!-- Tombol Toggle Rekap Bulanan -->
            <a href="{{ route('guru.absensi', ['mode' => $mode === 'rekap' ? 'harian' : 'rekap', 'tanggal' => $tanggal, 'bulan' => $bulan, 'tahun' => $tahun]) }}"
               class="px-3 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $mode === 'rekap' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                <span>📊 {{ $mode === 'rekap' ? 'Kembali ke Presensi Harian' : 'Lihat Rekap Bulanan' }}</span>
            </a>

            <!-- Tombol Cetak Rekap Absensi Bulanan -->
            <a href="{{ route('guru.absensi.cetak', ['bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank"
               class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm flex items-center gap-1.5">
                <span>🖨️ Cetak Rekap Bulanan</span>
            </a>
        </div>
    </div>

    @if($mode === 'rekap')
        <!-- TAMPILAN REKAP BULANAN -->
        <div class="space-y-4">
            <!-- Filter Bulan & Tahun -->
            <form action="{{ route('guru.absensi') }}" method="GET" class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-wrap items-center gap-3">
                <input type="hidden" name="mode" value="rekap">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-1">Pilih Bulan</label>
                    <select name="bulan" onchange="this.form.submit()" class="px-3 py-1.5 bg-slate-50 rounded-xl border border-slate-200 text-xs font-bold">
                        @for($m = 1; $m <= 12; $m++)
                            @php $val = str_pad($m, 2, '0', STR_PAD_LEFT); @endphp
                            <option value="{{ $val }}" {{ $bulan == $val ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-1">Pilih Tahun</label>
                    <select name="tahun" onchange="this.form.submit()" class="px-3 py-1.5 bg-slate-50 rounded-xl border border-slate-200 text-xs font-bold">
                        <option value="2026" {{ $tahun == '2026' ? 'selected' : '' }}>2026</option>
                        <option value="2027" {{ $tahun == '2027' ? 'selected' : '' }}>2027</option>
                    </select>
                </div>
            </form>

            <!-- Tabel Rekap Bulanan -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-100/70 border-b border-slate-200 text-slate-700 font-bold">
                            <th class="p-3 w-10 text-center">No</th>
                            <th class="p-3">Nama Siswa</th>
                            <th class="p-3 w-20 text-center text-emerald-700">Hadir (H)</th>
                            <th class="p-3 w-20 text-center text-blue-700">Sakit (S)</th>
                            <th class="p-3 w-20 text-center text-amber-700">Izin (I)</th>
                            <th class="p-3 w-20 text-center text-rose-700">Alpa (A)</th>
                            <th class="p-3 w-28 text-center">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($siswaList as $idx => $s)
                            @php $rk = $rekapBulanan[$s->id] ?? ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpa' => 0, 'persen' => 100]; @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 text-center text-slate-400">{{ $idx + 1 }}</td>
                                <td class="p-3 font-bold text-slate-800">{{ $s->nama }}</td>
                                <td class="p-3 text-center font-bold text-emerald-600">{{ $rk['hadir'] }}</td>
                                <td class="p-3 text-center font-bold text-blue-600">{{ $rk['sakit'] }}</td>
                                <td class="p-3 text-center font-bold text-amber-600">{{ $rk['izin'] }}</td>
                                <td class="p-3 text-center font-bold text-rose-600">{{ $rk['alpa'] }}</td>
                                <td class="p-3 text-center font-extrabold {{ $rk['persen'] < 75 ? 'text-rose-600' : 'text-slate-700' }}">
                                    {{ $rk['persen'] }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- TAMPILAN PRESENSI HARIAN -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <form action="{{ route('guru.absensi') }}" method="GET" class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <label class="text-xs font-bold text-slate-600">Pilih Tanggal Presensi:</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()"
                           class="px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-blue-500">
                </div>
                <span class="text-xs text-slate-500">Total: {{ $siswaList->count() }} Siswa</span>
            </form>

            <form action="{{ route('guru.absensi.simpan') }}" method="POST">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-100/70 border-b border-slate-200 text-slate-700 font-bold">
                                <th class="p-3 w-10 text-center">No</th>
                                <th class="p-3">Nama Siswa</th>
                                <th class="p-3 text-center w-64">Status Presensi</th>
                                <th class="p-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($siswaList as $idx => $s)
                                @php $ab = $absensiHariIni->get($s->id); $curSt = $ab->status ?? 'H'; @endphp
                                <tr class="hover:bg-slate-50">
                                    <td class="p-3 text-center text-slate-400">{{ $idx + 1 }}</td>
                                    <td class="p-3">
                                        <p class="font-bold text-slate-800">{{ $s->nama }}</p>
                                        <p class="text-[10px] text-slate-400">NISN: {{ $s->nisn ?? '-' }}</p>
                                    </td>
                                    <td class="p-3 text-center">
                                        <div class="inline-flex rounded-xl p-1 bg-slate-100 border border-slate-200 gap-1">
                                            <label class="px-2.5 py-1 rounded-lg cursor-pointer text-xs font-bold {{ $curSt === 'H' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-200' }}">
                                                <input type="radio" name="status[{{ $s->id }}]" value="H" {{ $curSt === 'H' ? 'checked' : '' }} class="sr-only"> Hadir
                                            </label>
                                            <label class="px-2.5 py-1 rounded-lg cursor-pointer text-xs font-bold {{ $curSt === 'S' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-200' }}">
                                                <input type="radio" name="status[{{ $s->id }}]" value="S" {{ $curSt === 'S' ? 'checked' : '' }} class="sr-only"> Sakit
                                            </label>
                                            <label class="px-2.5 py-1 rounded-lg cursor-pointer text-xs font-bold {{ $curSt === 'I' ? 'bg-amber-500 text-white' : 'text-slate-600 hover:bg-slate-200' }}">
                                                <input type="radio" name="status[{{ $s->id }}]" value="I" {{ $curSt === 'I' ? 'checked' : '' }} class="sr-only"> Izin
                                            </label>
                                            <label class="px-2.5 py-1 rounded-lg cursor-pointer text-xs font-bold {{ $curSt === 'A' ? 'bg-rose-600 text-white' : 'text-slate-600 hover:bg-slate-200' }}">
                                                <input type="radio" name="status[{{ $s->id }}]" value="A" {{ $curSt === 'A' ? 'checked' : '' }} class="sr-only"> Alpa
                                            </label>
                                        </div>
                                    </td>
                                    <td class="p-3">
                                        <input type="text" name="keterangan[{{ $s->id }}]" value="{{ $ab->keterangan ?? '' }}" placeholder="Keterangan (opsional)..."
                                               class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                        Simpan Seluruh Presensi Siswa
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection
