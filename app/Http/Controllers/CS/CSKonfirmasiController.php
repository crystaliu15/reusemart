<?php

namespace App\Http\Controllers\CS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use App\Models\Pembeli;

class CSKonfirmasiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['pembeli'])
            ->where('status', 'menunggu konfirmasi')
            ->get();

        return view('cs.konfirmasi-transfer', compact('transaksis'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'aksi' => 'required|in:diproses,pembayaran ditolak',
        ]);

        $transaksi = Transaksi::findOrFail($id);

        $transaksi->status = $request->aksi;
        $transaksi->dikonfirmasi_oleh = Auth::guard('pegawai')->id();
        $transaksi->save();

        return redirect()->route('cs.konfirmasi.index')->with('success', 'Status transaksi berhasil diperbarui.');
    }
}
