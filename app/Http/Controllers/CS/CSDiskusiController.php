<?php

namespace App\Http\Controllers\CS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Diskusi;

class CSDiskusiController extends Controller
{
    public function balas(Request $request, $id)
    {
        $request->validate([
            'balasan' => 'required|string|max:1000',
        ]);

        $diskusi = Diskusi::findOrFail($id);
        $diskusi->balasan = $request->balasan;
        $diskusi->save();

        return back()->with('success', 'Balasan berhasil dikirim.');
    }
}
