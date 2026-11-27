<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SiApgus') }} - Sistem Aplikasi Guru Sekolah</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Dynamic Favicon -->
    @php
        $globalProfil = \App\Models\ProfilSekolah::first();
        $favUrl = $globalProfil && !empty($globalProfil->logo_url) ? $globalProfil->logo_url : asset('favicon.ico');
    @endphp
    <link rel="icon" type="image/x-icon" href="{{ $favUrl }}" id="appFavicon">
    <link rel="shortcut icon" href="{{ $favUrl }}" id="appFaviconShortcut">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .tabular-nums { font-variant-numeric: tabular-nums; }
        @media print {
            @page { size: A4 portrait; margin: 0; }
            body { background: #fff !important; padding: 12mm 15mm !important; margin: 0 !important; }
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col">

    <!-- Top Navigation -->
    <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-30 no-print">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center font-extrabold text-lg shadow-sm">
                S
            </div>
            <div>
                <h1 class="text-sm font-bold text-slate-800 leading-tight">
                    {{ $globalProfil->nama_sekolah ?? 'SiApgus Sekolah' }}
                </h1>
                <p class="text-[10px] text-slate-500 font-medium">
                    Kurikulum Merdeka • TA {{ $globalProfil->tahun_ajaran ?? '2026/2027' }} ({{ $globalProfil->semester ?? 'Ganjil' }})
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block text-xs">
                <p class="font-bold text-slate-800">{{ Auth::user()->full_name }}</p>
                <p class="text-[10px] text-slate-500 capitalize">{{ Auth::user()->role === 'admin' ? 'Administrator Sekolah' : 'Guru / Wali Kelas' }}</p>
            </div>
            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center">
                {{ strtoupper(substr(Auth::user()->full_name, 0, 1)) }}
            </div>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-2.5 py-1.5 text-xs text-rose-600 hover:bg-rose-50 rounded-lg font-semibold transition" title="Keluar">
                    Keluar
                </button>
            </form>
        </div>
    </header>

    <div class="flex flex-1">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-white border-r border-slate-200 p-4 space-y-6 shrink-0 hidden md:block no-print">
            @if(Auth::user()->role === 'admin')
                <div class="space-y-1">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider px-3 mb-2">Menu Administrator</p>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100' }}">
                        <span>📊 Dashboard</span>
                    </a>
                    <a href="{{ route('admin.profil') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold {{ request()->routeIs('admin.profil') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100' }}">
                        <span>🏛️ Profil Sekolah & Logo</span>
                    </a>
                    <a href="{{ route('admin.siswa') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold {{ request()->routeIs('admin.siswa') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100' }}">
                        <span>👥 Data Siswa</span>
                    </a>
                    <a href="{{ route('admin.guru') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold {{ request()->routeIs('admin.guru') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100' }}">
                        <span>👨‍🏫 Data Guru</span>
                    </a>
                    <a href="{{ route('admin.mapel') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold {{ request()->routeIs('admin.mapel') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100' }}">
                        <span>📚 Mata Pelajaran</span>
                    </a>
                    <a href="{{ route('admin.wali_kelas') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold {{ request()->routeIs('admin.wali_kelas') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100' }}">
                        <span>📋 Penetapan Wali Kelas</span>
                    </a>
                </div>
            @else
                @php
                    $isWali = \App\Models\WaliKelas::where('guru_id', Auth::user()->id)
                        ->orWhere('nama_guru', Auth::user()->full_name)
                        ->first();
                @endphp
                <div class="space-y-1">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider px-3 mb-2">Menu Guru</p>
                    <a href="{{ route('guru.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold {{ request()->routeIs('guru.dashboard') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100' }}">
                        <span>🏠 Dashboard Guru</span>
                    </a>
                    <a href="{{ route('guru.penilaian') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold {{ request()->routeIs('guru.penilaian') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100' }}">
                        <span>📝 Data Penilaian Rapor</span>
                    </a>

                    {{-- Absensi HANYA untuk Wali Kelas yang ditunjuk --}}
                    @if($isWali)
                        <div class="pt-3">
                            <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider px-3 mb-2 flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                Wali Kelas {{ $isWali->kode_kelas }}-{{ $isWali->rombel }}
                            </p>
                            <a href="{{ route('guru.absensi') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold {{ request()->routeIs('guru.absensi') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-600 hover:bg-slate-100' }}">
                                <span>📅 Absensi Siswa & Rekap</span>
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full">
            @if(session('success'))
                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold flex items-center justify-between no-print">
                    <span>✓ {{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-semibold flex items-center justify-between no-print">
                    <span>✕ {{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
