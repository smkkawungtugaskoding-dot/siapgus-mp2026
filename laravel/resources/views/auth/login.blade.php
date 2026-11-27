<x-guest-layout>
    @php
        $profil = \App\Models\ProfilSekolah::first();
    @endphp

    <div class="space-y-6">
        <!-- Logo & Judul Aplikasi -->
        <div class="text-center space-y-2">
            @if($profil && !empty($profil->logo_url))
                <img src="{{ $profil->logo_url }}" alt="Logo" class="w-14 h-14 object-contain mx-auto">
            @else
                <div class="w-14 h-14 bg-blue-600 rounded-2xl text-white font-extrabold text-2xl flex items-center justify-center mx-auto shadow-lg shadow-blue-500/30">
                    S
                </div>
            @endif
            <h2 class="text-xl font-extrabold text-slate-800">
                {{ $profil->nama_sekolah ?? 'SiApgus Sekolah' }}
            </h2>
            <p class="text-xs text-slate-500">Sistem Aplikasi Guru Sekolah • Otentikasi Laravel Breeze</p>
        </div>

        <!-- Session Status Alert -->
        <x-auth-session-status class="mb-4" :status="session('status') ?? session('info')" />

        @if(session('error'))
            <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs font-semibold">
                ✕ {{ session('error') }}
            </div>
        @endif

        <!-- Form Login Laravel Breeze -->
        <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
            @csrf

            <!-- Username / Email -->
            <div>
                <x-input-label for="username" value="Username Pengguna" />
                <x-text-input id="username" type="text" name="username" :value="old('username', 'admin')" required autofocus autocomplete="username" placeholder="Masukkan username..." />
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" value="Kata Sandi (Password)" />
                <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" value="admin123" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-xs">
                <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                    <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-blue-600 shadow-2xs focus:ring-blue-500" name="remember">
                    <span class="ms-2 text-slate-600 font-medium">Ingat Saya di Perangkat Ini</span>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <x-primary-button>
                    Masuk ke Sistem
                </x-primary-button>
            </div>
        </form>

        <!-- Role Quick Demo Fill (Admin & Guru) -->
        <div class="pt-4 border-t border-slate-100 space-y-2">
            <p class="text-[11px] font-bold text-slate-400 text-center uppercase tracking-wider">Akses Cepat Pengujian Role</p>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <button type="button" onclick="setDemoAccount('admin', 'admin123')"
                        class="p-2.5 bg-blue-50 hover:bg-blue-100 text-blue-800 border border-blue-200 rounded-xl font-bold text-center transition cursor-pointer">
                    👤 Role: Admin Sekolah
                    <span class="block text-[10px] text-blue-600 font-normal">admin / admin123</span>
                </button>
                <button type="button" onclick="setDemoAccount('guru', 'guru123')"
                        class="p-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-xl font-bold text-center transition cursor-pointer">
                    👨‍🏫 Role: Guru / Wali
                    <span class="block text-[10px] text-emerald-600 font-normal">guru / guru123</span>
                </button>
            </div>
            <p class="text-[10px] text-slate-400 text-center mt-2">
                Sistem akan secara otomatis mendeteksi role Anda dan mengarahkan ke dashboard yang tepat.
            </p>
        </div>
    </div>

    <script>
        function setDemoAccount(user, pass) {
            document.getElementById('username').value = user;
            document.getElementById('password').value = pass;
        }
    </script>
</x-guest-layout>
