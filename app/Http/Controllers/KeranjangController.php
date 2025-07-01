<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Keranjang;
use App\Models\Barang;

class KeranjangController extends Controller
{
    public function tambah(Request $request, Barang $barang)
    {
        $pembeli = Auth::guard('pembeli')->user();

        // Cek apakah barang sudah ada di keranjang
        $item = Keranjang::where('pembeli_id', $pembeli->id)
                        ->where('barang_id', $barang->id)
                        ->first();

        if ($item) {
            // ❌ Barang sudah ada, tidak boleh ditambahkan lagi
            return back()->with('error', 'Barang sudah dimasukkan ke keranjang.');
        }

        // ✅ Tambah barang ke keranjang
        Keranjang::create([
            'pembeli_id' => $pembeli->id,
            'barang_id' => $barang->id,
        ]);

        return back()->with('success', 'Barang berhasil ditambahkan ke keranjang.');
    }

    public function index()
    {
        $pembeli = Auth::guard('pembeli')->user();

        // Ambil item keranjang beserta barang-nya
        $items = Keranjang::with('barang')
            ->where('pembeli_id', $pembeli->id)
            ->get();

        // Tambahkan jumlah keranjang (untuk badge)
        $jumlahKeranjang = $items->count();

        return view('cart', compact('items'));
    }

    public function hapus($id)
    {
        $item = Keranjang::where('id', $id)
                        ->where('pembeli_id', Auth::guard('pembeli')->id())
                        ->firstOrFail();

        $item->delete();

        return redirect()->route('cart.index')->with('success', 'Barang dihapus dari keranjang.');
    }
}
