<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penitip;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PenitipAuthController extends Controller
{
    public function loginForm() {
        return view('auth.penitip.login');
    }

    public function registerForm() {
        return view('auth.penitip.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!\App\Models\Penitip::where('email', $credentials['email'])->exists()) {
            return back()->withErrors(['email' => 'Email tidak terdaftar.']);
        }

        if (!Auth::guard('penitip')->attempt($credentials)) {
            return back()->withErrors(['password' => 'Password salah.']);
        }

        return redirect()->route('penitip.dashboard')->with('success', 'Berhasil login sebagai penitip.');
    }

    public function register(Request $request) {
        $request->validate([
            'username' => 'required|string|unique:penitips,username',
            'email' => 'required|email|unique:penitips,email',
            'no_telp' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        Penitip::create([
            'username' => $request->username,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => Hash::make($request->password),
            'plaintext_password' => $request->password,
        ]);

        return redirect()->route('penitip.login.form')->with('success', 'Berhasil daftar, silakan login.');
    }

    public function lupaPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:penitips,email',
        ]);

        $penitip = \App\Models\Penitip::where('email', $request->email)->first();

        return back()->with('password_info', 'Password Anda adalah: ' . $penitip->plaintext_password);
    }

    public function logout(Request $request) {
        Auth::guard('penitip')->logout();
        return redirect('/');
    }
}
