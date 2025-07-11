<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                switch ($guard) {
                    case 'owner':
                        return redirect()->route('owner.dashboard');
                    case 'pembeli':
                        return redirect('/');
                    case 'penitip':
                        return redirect()->route('penitip.dashboard');
                    case 'organisasi':
                        return redirect()->route('organisasi.dashboard');
                    case 'pegawai':
                        return redirect()->route('admin.dashboard');
                }
            }
        }

        return $next($request);
    }
}
