<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diskusi;
use Illuminate\Support\Facades\Auth;

class DiskusiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'isi' => 'required|string',
        ]);

        Diskusi::create([
            'user_id' => Auth::guard('pembeli')->id(),
            'barang_id' => $request->barang_id,
            'isi' => $request->isi
        ]);

        return back()->with('success', 'Pertanyaan berhasil dikirim.');
    }
}
