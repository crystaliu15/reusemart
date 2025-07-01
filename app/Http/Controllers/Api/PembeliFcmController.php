<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembeli;

class PembeliFcmController extends Controller
{
    public function update(Request $request)
    {
        $pembeli = Pembeli::find($request->id);
        if (!$pembeli) {
            return response()->json(['message' => 'Pembeli tidak ditemukan'], 404);
        }

        $pembeli->fcm_token = $request->token;
        $pembeli->save();

        return response()->json(['message' => 'Token disimpan']);
    }
}
