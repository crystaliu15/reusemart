<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Hunter;

class AdminHunterController extends Controller
{
    public function create()
    {
        return view('admin.huntercreate');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:hunters,username',
            'email' => 'required|email|unique:hunters,email',
            'no_telp' => 'required',
            'password' => 'required|min:6',
            'nama_lengkap' => 'required',
        ]);

        Hunter::create([
            'username' => $request->username,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => Hash::make($request->password),
            'nama_lengkap' => $request->nama_lengkap,
            'saldo' => 0,
        ]);

        return redirect()->route('admin.hunter.create')->with('success', 'Hunter berhasil ditambahkan.');
    }
}
