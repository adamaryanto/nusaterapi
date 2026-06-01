<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TherapistMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'therapist') {
            if (Auth::check() && Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('landing');
        }

        // Check if therapist profile exists in database
        $hasProfile = \App\Models\Therapist::where('user_id', Auth::id())->exists();
        if (!$hasProfile) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda berstatus terapis, tetapi profil terapis belum dibuat oleh Admin. Silakan hubungi Administrator.',
            ]);
        }

        return $next($request);
    }
}
