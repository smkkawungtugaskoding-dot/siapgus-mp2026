<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     * Mengarahkan pengguna yang sudah login ke dashboard sesuai role mereka.
     */
    public function create(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request using Laravel Breeze LoginRequest.
     * Memproses otentikasi dan melakukan redirect otomatis berdasarkan role user.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        // Redirect sesuai role pengguna (Admin atau Guru)
        return $this->redirectBasedOnRole($user);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah berhasil keluar dari sistem SiApgus.');
    }

    /**
     * Helper untuk redirect otorisasi berbasis role (Admin & Guru)
     */
    protected function redirectBasedOnRole($user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', "Selamat datang kembali, {$user->full_name} (Administrator)!");
        }

        return redirect()->intended(route('guru.dashboard'))
            ->with('success', "Selamat datang kembali, {$user->full_name}!");
    }
}
