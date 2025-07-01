<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembeli;
use App\Models\Organisasi;
use Illuminate\Support\Facades\Hash;


class RegisterAllController extends Controller
{
    public function showForm()
    {
        return view('auth.registerAll');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:pembelis,username|unique:organisasis,username',
            'email' => 'required|email|unique:pembelis,email|unique:organisasis,email',
            'no_telp' => 'required',
            'password' => 'required|min:6',
            'role' => 'required|in:pembeli,organisasi',
        ]);

        if ($request->role === 'pembeli') {
            Pembeli::create([
                'username' => $request->username,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'plaintext_password' => $request->password,
                'password' => Hash::make($request->password),
            ]);
        } else {
            Organisasi::create([
                'username' => $request->username,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'plaintext_password' => $request->password,
                'password' => Hash::make($request->password),
                'alamat' => $request->alamat,
            ]);
        }

        return redirect()->route('login.universal')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}