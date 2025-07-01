<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Barang;

class GudangDashboardController extends Controller
{
    public function index()
    {
        $pegawai = Auth::guard('pegawai')->user();

        // Jika ingin validasi hanya pegawai gudang, misalnya jabatan_id = 4
        if ($pegawai->jabatan->nama_jabatan !== 'Pegawai Gudang') {
            abort(403, 'Akses ditolak.');
        }

        $barangs = Barang::where('quality_check', $pegawai->id)->with('kategori')->get();

        return view('gudang.gudangDashboard', compact('pegawai', 'barangs'));
    }
}
