<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\AlamatPembeli;

class ProfilController extends Controller
{
    public function index()
    {
        $pembeli = Auth::guard('pembeli')->user();
        $transaksis = $pembeli->transaksis()->latest()->with('detail.barang')->get();

        return view('pembeli.profil', compact('pembeli', 'transaksis'));
    }

    public function edit()
    {
        $pembeli = Auth::guard('pembeli')->user();
        return view('pembeli.edit', compact('pembeli'));
    }

    public function update(Request $request)
    {
        $pembeli = Auth::guard('pembeli')->user();

        $request->validate([
            'username' => 'required|string',
            'no_telp' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('pembeli_foto', 'public');
            $pembeli->profile_picture = $path;
        }

        $pembeli->username = $request->username;
        $pembeli->no_telp = $request->no_telp;
        $pembeli->save();

        return redirect()->route('pembeli.profil')->with('success', 'Profil berhasil diperbarui.');
    }

    public function uploadFoto(Request $request)
    {
        $pembeli = Auth::guard('pembeli')->user();
        $request->validate([
            'foto' => 'required|image|max:2048',
        ]);
        $path = $request->file('foto')->store('pembeli_foto', 'public');
        $pembeli->update(['profile_picture' => $path]);
        return back()->with('success', 'Foto profil diperbarui.');
    }

    public function tambahAlamat(Request $request)
    {
        $pembeli = Auth::guard('pembeli')->user();
        $request->validate(['alamat' => 'required|string']);

        $alamat = $pembeli->alamat()->create(['alamat' => $request->alamat]);

        // Jika pembeli belum punya default_alamat_id, set alamat pertama jadi default
        if (!$pembeli->default_alamat_id) {
            $pembeli->update(['default_alamat_id' => $alamat->id]);
        }

        return back()->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function setDefaultAlamat($id)
    {
        $pembeli = Auth::guard('pembeli')->user();
        $pembeli->update(['default_alamat_id' => $id]);
        return back()->with('success', 'Alamat default diperbarui.');
    }

    public function riwayatPembelian()
    {
        $pembeli = auth()->guard('pembeli')->user();

        $transaksis = $pembeli->transaksis()->with('detail.barang')->get();

        return view('pembeli.riwayatpembelian', compact('transaksis'));
    }

    
    
}
