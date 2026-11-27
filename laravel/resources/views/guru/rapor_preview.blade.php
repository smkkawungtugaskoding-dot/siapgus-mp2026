<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAPOR_{{ strtoupper($mode) }}_{{ preg_replace('/[^a-zA-Z0-9]/', '_', $siswa->nama) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            @page { size: A4 portrait; margin: 10mm 15mm; }
            body { background: white !important; padding: 0 !important; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-8 text-slate-900">

    <!-- Top Action Bar -->
    <div class="max-w-[210mm] mx-auto mb-4 bg-white p-3 rounded-2xl shadow-sm flex items-center justify-between no-print">
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
            <span class="text-xs font-bold text-slate-700">
                Mode Cetak Rapor: {{ $mode === 'sisipan' ? 'Rapor Sisipan (Tengah Semester)' : 'Rapor Akhir Semester (SAS)' }}
            </span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                🖨️ Cetak / Simpan PDF (A4)
            </button>
            <button onclick="window.close()" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition">
                Tutup
            </button>
        </div>
    </div>

    <!-- Halaman Dokumen Rapor A4 -->
    <div class="max-w-[210mm] mx-auto bg-white p-8 sm:p-12 shadow-md rounded-xl print:p-0 print:shadow-none print:rounded-none">
        <!-- KOP SURAT RESMI -->
        <div class="border-b-2 border-slate-900 pb-3 mb-6 flex items-center gap-4">
            @if(!empty($profil->logo_url))
                <img src="{{ $profil->logo_url }}" alt="Logo" class="w-16 h-16 object-contain">
            @else
                <div class="w-16 h-16 rounded-xl bg-blue-600 text-white font-extrabold text-2xl flex items-center justify-center shrink-0">
                    S
                </div>
            @endif
            <div class="flex-1 text-center">
                <h1 class="text-lg font-black uppercase tracking-wide text-slate-900">{{ $profil->nama_sekolah }}</h1>
                <p class="text-xs text-slate-600 mt-0.5">NPSN: {{ $profil->npsn ?? '-' }} | Telp: {{ $profil->telp ?? '-' }}</p>
                <p class="text-xs text-slate-600">{{ $profil->alamat_sekolah }}</p>
            </div>
        </div>

        <!-- JUDUL LAPORAN -->
        <div class="text-center mb-6">
            <h2 class="text-sm font-extrabold uppercase tracking-wide underline">
                {{ $mode === 'sisipan' ? 'LAPORAN HASIL BELAJAR PESERTA DIDIK (SISIPAN)' : 'LAPORAN HASIL CAPAIAN BELAJAR PESERTA DIDIK' }}
            </h2>
            <p class="text-[11px] text-slate-500 mt-0.5">
                Tahun Ajaran: {{ $profil->tahun_ajaran }} | Semester: {{ $profil->semester }} | Kurikulum: {{ $profil->kurikulum }}
            </p>
        </div>

        <!-- IDENTITAS SISWA -->
        <div class="grid grid-cols-2 gap-x-6 gap-y-1 text-xs mb-5 bg-slate-50/70 p-3 rounded-lg border border-slate-200">
            <div><span class="text-slate-500">Nama Siswa:</span> <strong class="text-slate-800 ml-1">{{ $siswa->nama }}</strong></div>
            <div><span class="text-slate-500">Kelas / Rombel:</span> <strong class="text-slate-800 ml-1">{{ $siswa->kelas }} - {{ $siswa->rombel }}</strong></div>
            <div><span class="text-slate-500">NISN / Nomor Induk:</span> <strong class="text-slate-800 ml-1">{{ $siswa->nisn ?? $siswa->id }}</strong></div>
            <div><span class="text-slate-500">Wali Kelas:</span> <strong class="text-slate-800 ml-1">{{ $wali->nama_guru }}</strong></div>
        </div>

        <!-- TABEL CAPAIAN HASIL BELAJAR -->
        <table class="w-full text-left border-collapse border border-slate-300 text-xs mb-5">
            <thead>
                <tr class="bg-slate-100 text-slate-800 font-bold border-b border-slate-300">
                    <th class="p-2 border border-slate-300 w-8 text-center">No</th>
                    <th class="p-2 border border-slate-300">Mata Pelajaran</th>
                    <th class="p-2 border border-slate-300 w-12 text-center">KKTP</th>
                    <th class="p-2 border border-slate-300 w-14 text-center">Nilai</th>
                    <th class="p-2 border border-slate-300 w-20 text-center">Predikat</th>
                    <th class="p-2 border border-slate-300">Capaian Kompetensi Pembelajaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rowsNilai as $r)
                    <tr>
                        <td class="p-2 border border-slate-300 text-center">{{ $r['no'] }}</td>
                        <td class="p-2 border border-slate-300 font-bold">{{ $r['nama_mapel'] }}</td>
                        <td class="p-2 border border-slate-300 text-center">{{ $r['kktp'] }}</td>
                        <td class="p-2 border border-slate-300 text-center font-bold">{{ $r['nilai'] }}</td>
                        <td class="p-2 border border-slate-300 text-center">{{ $r['predikat'] }}</td>
                        <td class="p-2 border border-slate-300 text-[11px] text-slate-700 leading-relaxed">{{ $r['capaian'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- REKAPITULASI KETIDAKHADIRAN -->
        <div class="mb-6">
            <h4 class="text-xs font-bold text-slate-800 mb-1.5 uppercase">Rekapitulasi Ketidakhadiran</h4>
            <table class="w-64 text-xs border border-slate-300">
                <tr><td class="p-1.5 border border-slate-300 bg-slate-50">Sakit (S)</td><td class="p-1.5 border border-slate-300 text-center font-bold">{{ $sakit }} Hari</td></tr>
                <tr><td class="p-1.5 border border-slate-300 bg-slate-50">Izin (I)</td><td class="p-1.5 border border-slate-300 text-center font-bold">{{ $izin }} Hari</td></tr>
                <tr><td class="p-1.5 border border-slate-300 bg-slate-50">Tanpa Keterangan (A)</td><td class="p-1.5 border border-slate-300 text-center font-bold">{{ $alpa }} Hari</td></tr>
            </table>
        </div>

        <!-- TANDA TANGAN ELEKTRONIK (TTE) DENGAN QR CODE RESMI -->
        <div class="grid grid-cols-3 gap-4 text-center text-xs mt-8 pt-4">
            <div>
                <p class="text-slate-600 mb-1">Mengetahui,</p>
                <p class="font-bold text-slate-800">Orang Tua / Wali</p>
                <div class="h-20 flex items-center justify-center">
                    <div class="text-[10px] text-slate-300 italic">( Tanda Tangan )</div>
                </div>
                <p class="font-bold text-slate-700 underline">......................................</p>
            </div>

            <div>
                <p class="text-slate-600 mb-1">Wali Kelas {{ $siswa->kelas }}{{ $siswa->rombel }}</p>
                <p class="font-bold text-slate-800">Tanda Tangan Elektronik (TTE)</p>
                <div class="h-20 flex items-center justify-center">
                    <canvas id="qrWali" class="w-16 h-16"></canvas>
                </div>
                <p class="font-bold text-slate-800 underline">{{ $wali->nama_guru }}</p>
                <p class="text-[10px] text-slate-500">NUPTK: {{ $wali->guru_id ?? '-' }}</p>
            </div>

            <div>
                <p class="text-slate-600 mb-1">Mengetahui,</p>
                <p class="font-bold text-slate-800">Kepala {{ $profil->nama_sekolah }}</p>
                <div class="h-20 flex items-center justify-center">
                    <canvas id="qrKepsek" class="w-16 h-16"></canvas>
                </div>
                <p class="font-bold text-slate-800 underline">{{ $profil->kepala_nama }}</p>
                <p class="text-[10px] text-slate-500">NIP: {{ $profil->kepala_nip }}</p>
            </div>
        </div>

        <div class="text-[9px] text-slate-400 mt-6 text-center italic border-t border-slate-100 pt-2">
            Dokumen ini ditandatangani secara elektronik resmi melalui Sistem Aplikasi Guru Sekolah (SiApgus). Keabsahan dokumen dapat diverifikasi melalui QR Code di atas.
        </div>
    </div>

    <script>
        window.onload = function() {
            QRCode.toCanvas(document.getElementById('qrKepsek'), {!! json_encode($tteKepsekPayload) !!}, { width: 68, margin: 1 });
            QRCode.toCanvas(document.getElementById('qrWali'), {!! json_encode($tteWaliPayload) !!}, { width: 68, margin: 1 });
        };
    </script>
</body>
</html>
