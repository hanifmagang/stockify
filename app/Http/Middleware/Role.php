<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Role
{
    public function handle(Request $request, Closure $next, ...$role)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, $role)) {
            return redirect('404'); // Ganti dengan rute yang sesuai jika akses ditolak
        }

        return $next($request);
    }
}
