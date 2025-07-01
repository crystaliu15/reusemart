<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pembeli;
use App\Models\Organisasi;

class RegisterController extends Controller
{
    /**
     * Tampilkan form registrasi.
     */
    public function showForm()
    {
        return view('auth.register');
    }

    /**
     * Proses data registrasi.
     */
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|unique:pembelis,username|unique:organisasis,username',
            'email' => 'required|email|unique:pembelis,email|unique:organisasis,email',
            'no_telp' => 'required',
            'password' => 'required|min:6',
            'role' => 'required|in:pembeli,organisasi',
        ]);

        // Simpan ke tabel sesuai role
        if ($request->role === 'pembeli') {
            Pembeli::create([
                'username' => $request->username,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'password' => Hash::make($request->password),
            ]);
        } else {
            Organisasi::create([
                'username' => $request->username,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'password' => Hash::make($request->password),
                'alamat' => $request->alamat,
            ]);
        }

        // Arahkan ke halaman login dengan notifikasi
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}

