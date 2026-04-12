<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthProfessor
{
    public function handle(Request $request, Closure $next)
    {
        // Evitar redirecionamento na página de login ou logout
        if ($request->is('login') || $request->is('logout')) {
            return $next($request);
        }

        if (!Auth::guard('professor')->check()) {
            return redirect('/login')->with('error', 'Acesso restrito a docentes.');
        }
        return $next($request);
    }
}
