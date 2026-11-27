<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REKAP_ABSENSI_BULAN_{{ $bulan }}_{{ $tahun }}_KELAS_{{ $wali->kode_kelas }}{{ $wali->rombel }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            @page { size: A4 landscape; margin: 10mm; }
            body { background: white !important; padding: 0 !important; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-8 text-slate-900">

    <div class="max-w-[297mm] mx-auto mb-4 bg-white p-3 rounded-2xl shadow-sm flex items-center justify-between no-print">
        <span class="text-xs font-bold text-slate-700">
            Cetak Rekapitulasi Presensi Bulanan: {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}
        </span>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                🖨️ Cetak Rekap (Landscape A4)
            </button>
            <button onclick="window.close()" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition">
                Tutup
            </button>
        </div>
    </div>

    <div class="max-w-[297mm] mx-auto bg-white p-8 shadow-md rounded-xl print:p-0 print:shadow-none print:rounded-none">
        <!-- KOP SURAT -->
        <div class="border-b-2 border-slate-900 pb-3 mb-4 flex items-center gap-4">
            @if(!empty($profil->logo_url))
                <img src="{{ $profil->logo_url }}" alt="Logo" class="w-14 h-14 object-contain">
            @endif
            <div class="flex-1 text-center">
                <h1 class="text-base font-black uppercase text-slate-900">{{ $profil->nama_sekolah }}</h1>
                <p class="text-xs text-slate-600">REKAPITULASI KEHADIRAN BULANAN PESERTA DIDIK</p>
                <p class="text-[11px] text-slate-500">Kelas: {{ $wali->kode_kelas }} - {{ $wali->rombel }} | Periode: {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}</p>
            </div>
        </div>

        <!-- TABEL REKAP BULANAN -->
        <table class="w-full text-left border-collapse border border-slate-300 text-xs mb-6">
            <thead>
                <tr class="bg-slate-100 text-slate-800 font-bold border-b border-slate-300 text-center">
                    <th class="p-2 border border-slate-300 w-10">No</th>
                    <th class="p-2 border border-slate-300 text-left">Nama Peserta Didik</th>
                    <th class="p-2 border border-slate-300 w-24">NISN</th>
                    <th class="p-2 border border-slate-300 w-20 text-emerald-700">Hadir (H)</th>
                    <th class="p-2 border border-slate-300 w-20 text-blue-700">Sakit (S)</th>
                    <th class="p-2 border border-slate-300 w-20 text-amber-700">Izin (I)</th>
                    <th class="p-2 border border-slate-300 w-20 text-rose-700">Alpa (A)</th>
                    <th class="p-2 border border-slate-300 w-24">% Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekapData as $idx => $r)
                    <tr>
                        <td class="p-2 border border-slate-300 text-center">{{ $idx + 1 }}</td>
                        <td class="p-2 border border-slate-300 font-bold">{{ $r['siswa']->nama }}</td>
                        <td class="p-2 border border-slate-300 text-center font-mono">{{ $r['siswa']->nisn ?? '-' }}</td>
                        <td class="p-2 border border-slate-300 text-center font-bold text-emerald-700">{{ $r['h'] }}</td>
                        <td class="p-2 border border-slate-300 text-center font-bold text-blue-700">{{ $r['s'] }}</td>
                        <td class="p-2 border border-slate-300 text-center font-bold text-amber-700">{{ $r['i'] }}</td>
                        <td class="p-2 border border-slate-300 text-center font-bold text-rose-700">{{ $r['a'] }}</td>
                        <td class="p-2 border border-slate-300 text-center font-extrabold {{ $r['persen'] < 75 ? 'text-rose-600' : 'text-slate-800' }}">
                            {{ $r['persen'] }}%
                        </td>
                    </tr>
                @endforeach
                <tr class="bg-slate-50 font-bold border-t-2 border-slate-400">
                    <td colspan="3" class="p-2 border border-slate-300 text-center uppercase">Total Akumulasi Kelas</td>
                    <td class="p-2 border border-slate-300 text-center text-emerald-700">{{ $grandH }}</td>
                    <td class="p-2 border border-slate-300 text-center text-blue-700">{{ $grandS }}</td>
                    <td class="p-2 border border-slate-300 text-center text-amber-700">{{ $grandI }}</td>
                    <td class="p-2 border border-slate-300 text-center text-rose-700">{{ $grandA }}</td>
                    <td class="p-2 border border-slate-300 text-center">
                        @php $gTot = $grandH + $grandS + $grandI + $grandA; echo $gTot > 0 ? round(($grandH / $gTot) * 100) . '%' : '100%'; @endphp
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- TTE TANDA TANGAN -->
        <div class="grid grid-cols-2 gap-8 text-center text-xs mt-6">
            <div>
                <p class="text-slate-600 mb-1">Mengetahui, Kepala {{ $profil->nama_sekolah }}</p>
                <div class="h-16 flex items-center justify-center">
                    <canvas id="qrKepsek" class="w-14 h-14"></canvas>
                </div>
                <p class="font-bold text-slate-800 underline mt-1">{{ $profil->kepala_nama }}</p>
                <p class="text-[10px] text-slate-500">NIP: {{ $profil->kepala_nip }}</p>
            </div>
            <div>
                <p class="text-slate-600 mb-1">Wali Kelas {{ $wali->kode_kelas }}-{{ $wali->rombel }}</p>
                <div class="h-16 flex items-center justify-center">
                    <canvas id="qrWali" class="w-14 h-14"></canvas>
                </div>
                <p class="font-bold text-slate-800 underline mt-1">{{ $wali->nama_guru }}</p>
                <p class="text-[10px] text-slate-500">NUPTK: {{ $wali->guru_id ?? '-' }}</p>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            QRCode.toCanvas(document.getElementById('qrKepsek'), 'TTE Kepala Sekolah - Rekap Absensi', { width: 56, margin: 1 });
            QRCode.toCanvas(document.getElementById('qrWali'), 'TTE Wali Kelas - Rekap Absensi', { width: 56, margin: 1 });
        };
    </script>
</body>
</html>
