<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RequestDonasi;
use App\Models\DonasiBarang;
use App\Models\Penitip; // Tambahkan import model Penitip
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $owner = Auth::guard('owner')->user();
        return view('owner.dashboard', compact('owner'));
    }

    public function requestDonasi()
    {
        $requestDonasi = RequestDonasi::with('organisasi')->latest()->get();
        return view('owner.request', compact('requestDonasi'));
    }

    public function historiDonasi(Request $request)
    {
        $query = DonasiBarang::with('organisasi');

        if ($request->filled('alamat')) {
            $alamat = $request->input('alamat');
            $query->whereHas('organisasi', function ($q) use ($alamat) {
                $q->where('alamat', 'like', '%' . $alamat . '%');
            });
        }

        $historiDonasi = DonasiBarang::with('organisasi')->orderBy('tanggal_donasi', 'desc')->get();

        return view('owner.histori', compact('historiDonasi'));
    }

    // Method baru untuk menampilkan daftar penitip
    public function penitipIndex()
    {
        // Ambil semua data penitip yang terdaftar, urutkan berdasarkan username
        $penitips = Penitip::orderBy('username', 'asc')->get();
        
        return view('owner.penitip.index', compact('penitips'));
    }
}