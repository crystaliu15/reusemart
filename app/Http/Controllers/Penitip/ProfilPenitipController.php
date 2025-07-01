<?php

namespace App\Http\Controllers\Penitip;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilPenitipController extends Controller
{
    public function edit()
    {
        $penitip = Auth::guard('penitip')->user();
        return view('penitip.edit', compact('penitip'));
    }

    public function update(Request $request)
    {
        $penitip = Auth::guard('penitip')->user();

        $request->validate([
            'username' => 'required|string',
            'no_telp' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('penitip_foto', 'public');
            $penitip->profile_picture = $path;
        }

        $penitip->username = $request->username;
        $penitip->no_telp = $request->no_telp;
        $penitip->save();

        return redirect()->route('penitip.dashboard')->with('success', 'Profil berhasil diperbarui.');
    }
}
