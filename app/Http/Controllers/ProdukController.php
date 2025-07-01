<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    public function index()
    {
        $barangs = Barang::where('status', 'tersedia')->latest()->paginate(30);
        return view('produk.index', compact('barangs'));
    }
}
