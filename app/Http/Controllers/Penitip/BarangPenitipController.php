<?php

namespace App\Http\Controllers\Penitip;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangPenitipController extends Controller
{
    public function show($id)
    {
        $barang = Barang::with('kategori')->findOrFail($id);

        // Pastikan barang ini milik penitip yang sedang login
        if ($barang->penitip_id !== Auth::guard('penitip')->id()) {
            abort(403);// Cegah akses oleh penitip lain
        }

        return view('penitip.barang-show', compact('barang'));
    }
}
