<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user && !$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda nonaktif. Hubungi admin.'
            ]);
        }
        return $next($request);
    }
}

