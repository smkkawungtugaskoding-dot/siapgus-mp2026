<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Memverifikasi apakah user yang login memiliki role yang diizinkan (admin / guru).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu untuk mengakses menu ini.');
        }

        $user = Auth::user();

        // Jika roles kosong, lanjutkan
        if (empty($roles)) {
            return $next($request);
        }

        // Cek apakah role pengguna termasuk dalam roles yang diizinkan (guru dan teacher dianggap setara)
        $normalizedUserRoles = [$user->role];
        if ($user->role === 'guru') $normalizedUserRoles[] = 'teacher';
        if ($user->role === 'teacher') $normalizedUserRoles[] = 'guru';

        $hasAccess = false;
        foreach ($roles as $r) {
            if (in_array($r, $normalizedUserRoles, true)) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            $targetRoute = $user->isAdmin() ? 'admin.dashboard' : 'guru.dashboard';
            return redirect()->route($targetRoute)->with('error', 'Akses ditolak: Anda tidak memiliki wewenang untuk membuka halaman tersebut.');
        }

        return $next($request);
    }
}
