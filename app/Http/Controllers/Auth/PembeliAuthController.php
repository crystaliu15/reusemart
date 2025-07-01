<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembeli;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class PembeliAuthController extends Controller
{
    public function loginForm() {
        return view('auth.pembeli.login');
    }

    public function registerForm() {
        return view('auth.pembeli.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!\App\Models\Pembeli::where('email', $credentials['email'])->exists()) {
            return back()->withErrors(['email' => 'Email tidak terdaftar.']);
        }

        if (!Auth::guard('pembeli')->attempt($credentials)) {
            return back()->withErrors(['password' => 'Password salah.']);
        }

        return redirect('/')->with('success', 'Berhasil login sebagai pembeli.');
    }

    public function lupaPassword(Request $request)
    {
        $email = $request->email;

        $pembeli = \App\Models\Pembeli::where('email', $email)->first();

        if (!$pembeli) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        // Simpan email ke session
        session(['reset_email' => $email]);

        return redirect()->route('pembeli.reset.form')
            ->with('password_info', 'Password Anda adalah: ' . $pembeli->plaintext_password);
    }

    public function register(Request $request) {
        $request->validate([
            'username' => 'required|string|unique:pembelis,username',
            'email' => 'required|email|unique:pembelis,email',
            'no_telp' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        Pembeli::create([
            'username' => $request->username,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => Hash::make($request->password),
            'plaintext_password' => $request->password, // simpan asli
        ]);

        return redirect()->route('pembeli.login.form')->with('success', 'Berhasil daftar, silakan login.');
    }

    public function resetPasswordForm()
    {
        return view('auth.pembeli.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $email = session('reset_email');
        $pembeli = \App\Models\Pembeli::where('email', $email)->first();

        if ($pembeli) {
            $pembeli->update([
                'password' => Hash::make($request->password),
                'plaintext_password' => $request->password,
            ]);
        }

        session()->forget('reset_email');

        return redirect()->route('pembeli.login.form')->with('success', 'Password berhasil direset. Silakan login.');
    }

    public function logout(Request $request) {
        Auth::guard('pembeli')->logout();
        return redirect('/');
    }
}
