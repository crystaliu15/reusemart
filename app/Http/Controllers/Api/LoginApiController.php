<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pembeli;
use App\Models\Penitip;
use App\Models\Hunter;
use App\Models\Pegawai;

class LoginApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $username = $request->username;
        $password = $request->password;

        // Cek Pembeli
        $pembeli = Pembeli::where('username', $username)->first();
        if ($pembeli && Hash::check($password, $pembeli->password)) {
            return response()->json([
                'status' => 'success',
                'role' => 'pembeli',
                'data' => [
                    'id' => $pembeli->id,
                    'username' => $pembeli->username,
                    'nama_lengkap' => $pembeli->nama_lengkap,
                    'token' => 'token-pembeli-'.$pembeli->id,
                ]
            ]);
        }

        // Penitip
        $penitip = Penitip::where('username', $username)->first();
        if ($penitip && Hash::check($password, $penitip->password)) {
            return response()->json([
                'status' => 'success',
                'role' => 'penitip',
                'data' => [
                    'id' => $penitip->id,
                    'username' => $penitip->username,
                    'nama_lengkap' => $penitip->nama_lengkap,
                    'token' => 'token-penitip-'.$penitip->id,
                ]
            ]);
        }

        // Hunter
        $hunter = Hunter::where('username', $username)->first();
        if ($hunter && Hash::check($password, $hunter->password)) {
            return response()->json([
                'status' => 'success',
                'role' => 'hunter',
                'data' => [
                    'id' => $hunter->id,
                    'username' => $hunter->username,
                    'nama_lengkap' => $hunter->nama_lengkap,
                    'token' => 'token-hunter-'.$hunter->id,
                ]
            ]);
        }

        // Pegawai Kurir
        $pegawai = Pegawai::where('username', $username)->first();
        if ($pegawai && Hash::check($password, $pegawai->password) &&
            strtolower($pegawai->jabatan->nama_jabatan) === 'kurir') {
            return response()->json([
                'status' => 'success',
                'role' => 'kurir',
                'data' => [
                    'id' => $pegawai->id,
                    'username' => $pegawai->username,
                    'nama_lengkap' => $pegawai->nama_lengkap,
                    'token' => 'token-kurir-'.$pegawai->id,
                ]
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Username atau password salah'
        ], 401);
    }
}
