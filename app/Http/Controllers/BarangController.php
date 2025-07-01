<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Diskusi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    public function show($id)
    {
        // Ambil barang dengan relasi kategori dan diskusi + user
        $barang = Barang::with(['kategori', 'diskusis.user'])->findOrFail($id);

        // Cek apakah user login pernah berdiskusi
        $userDiskusi = null;

        $pembeli = Auth::guard('pembeli')->user();

        if ($pembeli) {
            $userDiskusi = Diskusi::where('barang_id', $barang->id)
                                ->where('user_id', $pembeli->id)
                                ->first();
        }

        // Ambil barang rekomendasi dari kategori sama
        $rekomendasi = Barang::where('kategori_id', $barang->kategori_id)
                             ->where('id', '!=', $barang->id)
                             ->latest()
                             ->take(5)
                             ->get();

        return view('barang.show', compact('barang', 'userDiskusi', 'rekomendasi' , 'pembeli'));
    }
}

