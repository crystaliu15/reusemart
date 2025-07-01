<?php

namespace App\Http\Controllers\Penitip;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Barang;

class DashboardPenitipController extends Controller
{
    public function index(Request $request)
    {
        $penitip = Auth::guard('penitip')->user();
        $keyword = $request->input('cari');

        // Query barang aktif
        $barangAktifQuery = Barang::where('penitip_id', $penitip->id)
            ->where('terjual', false);

        if ($keyword) {
            $barangAktifQuery->where('nama', 'like', '%' . $keyword . '%');
        }

        $barangAktif = $barangAktifQuery->paginate(6)->withQueryString();

        // Query barang terjual
        $barangTerjual = Barang::where('penitip_id', $penitip->id)
            ->where('terjual', true)
            ->paginate(6);

        $barangDikonfirmasi = Barang::where('penitip_id', $penitip->id)
                            ->where('status_pengambilan', true)
                            ->paginate(6);

        return view('penitip.dashboard', compact('penitip', 'barangAktif', 'barangTerjual', 'barangDikonfirmasi'));
    }
}

