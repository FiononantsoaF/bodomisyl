<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PrestataireBackoffice
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'prestataire') {
            return $next($request);
        }
        return redirect()->route('dashboard')->with('error', 'Accès réservé aux prestataires.');
    }
}
