<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PegawaiController extends Controller
{
    public function dashboard()
    {
        $pegawai = Auth::guard('pegawai')->user();
        return view('pegawai.dashboard', compact('pegawai'));
    }
}
