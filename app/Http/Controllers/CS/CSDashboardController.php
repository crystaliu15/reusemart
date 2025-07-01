<?php

namespace App\Http\Controllers\CS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Diskusi;

class CSDashboardController extends Controller
{
    public function index()
    {
        $cs = Auth::guard('pegawai')->user();

        $jumlahDiskusiBelumDibalas = Diskusi::whereNull('balasan')->whereNotNull('barang_id')->count();

        return view('cs.csdashboard', compact('cs', 'jumlahDiskusiBelumDibalas'));
    }

    public function dashboard()
    {
        $cs = auth()->guard('pegawai')->user(); // atau sesuai guard kamu

        $jumlahDiskusiBelumDibalas = Diskusi::whereNull('balasan')->whereNotNull('barang_id')->count();

        return view('cs.dashboard', compact('cs', 'jumlahDiskusiBelumDibalas'));
    }
}
