<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pembeli;
use App\Models\Penitip;
use App\Models\Organisasi;
use App\Models\Pegawai;
use App\Models\Hunter;

class UniversalLoginController extends Controller
{
    public function form()
    {
        return view('auth.loginUniversal');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        $username = $request->username;
        $password = $request->password;

        // Cek Pembeli
        $pembeli = Pembeli::where('username', $username)->first();
        if ($pembeli && Hash::check($password, $pembeli->password)) {
            Auth::guard('pembeli')->login($pembeli);
            return redirect('/')->with('success', 'Berhasil login sebagai pembeli');
        }

        // Cek Penitip
        $penitip = Penitip::where('username', $username)->first();
        if ($penitip && Hash::check($password, $penitip->password)) {
            Auth::guard('penitip')->login($penitip);
            return redirect()->route('penitip.dashboard')->with('success', 'Berhasil login sebagai penitip');
        }

        // Cek Organisasi
        $organisasi = Organisasi::where('username', $username)->first();
        if ($organisasi && Hash::check($password, $organisasi->password)) {
            Auth::guard('organisasi')->login($organisasi);
            return redirect()->route('organisasi.dashboard')->with('success', 'Berhasil login sebagai organisasi');
        }

        // Cek Owner
        $owner = \App\Models\Owner::where('username', $username)->first();
        if ($owner && Hash::check($password, $owner->password)) {
            Auth::guard('owner')->login($owner);
            return redirect()->route('owner.dashboard')->with('success', 'Berhasil login sebagai owner');
        }

        // ✅ Cek Hunter
        $hunter = \App\Models\Hunter::where('username', $username)->first();
        if ($hunter && Hash::check($password, $hunter->password)) {
            Auth::guard('hunter')->login($hunter);
            return redirect()->route('hunter.dashboard')->with('success', 'Berhasil login sebagai hunter');
        }

        // ✅ Cek Pegawai
        $pegawai = Pegawai::where('username', $username)->first();
        if ($pegawai && Hash::check($password, $pegawai->password)) {
            Auth::guard('pegawai')->login($pegawai);

            // Ambil nama jabatan
            $jabatan = strtolower($pegawai->jabatan->nama_jabatan); // misal: 'admin', 'owner'

            // Arahkan ke dashboard berdasarkan jabatan
            switch ($jabatan) {
                case 'admin':
                    return redirect()->route('admin.dashboard')->with('success', 'Berhasil login sebagai admin');
                case 'cs':
                    return redirect()->route('cs.dashboard')->with('success', 'Berhasil login sebagai CS');
                case 'pegawai gudang':
                    return redirect()->route('gudang.dashboard')->with('success', 'Berhasil login sebagai pegawai gudang');
                case 'kurir':
                    return redirect()->route('kurir.dashboard')->with('success', 'Berhasil login sebagai kurir');
                default:
                    return redirect('/')->with('success', 'Berhasil login sebagai pegawai');
            }
        }
        return back()->withErrors(['username' => 'Login gagal. Username atau password salah.']);
    }
}
