<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('login');
        }

        // 2. Ambil data user yang sedang login
        $user = Auth::user();

        // 3. Loop setiap role yang diizinkan untuk rute ini (dikirim dari file web.php)
        foreach ($roles as $role) {
            // 4. Cek apakah user memiliki role tersebut
            //    $user->roles adalah method relasi yang kita buat di Model User
            if ($user->roles()->where('name', $role)->exists()) {
                // 5. Jika punya, izinkan akses ke halaman
                return $next($request);
            }
        }

        // 6. Jika setelah dicek semua role dan user tidak punya satupun, tolak aksesnya
        abort(403, 'AKSI TIDAK DIIZINKAN.');
    }
}
