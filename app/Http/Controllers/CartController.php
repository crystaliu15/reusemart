<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Kategori;

class CartController extends Controller
{
    public function add($id)
    {
        $cart = Session::get('cart', []);
        $cart[$id] = ($cart[$id] ?? 0) + 1;
        Session::put('cart', $cart);
        return redirect()->route('cart.index');
    }

    public function index()
    {
        $cart = Session::get('cart', []);
        return view('cart', compact('cart'));
    }
}
