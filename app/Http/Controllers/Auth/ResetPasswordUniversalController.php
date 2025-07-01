<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembeli;
use App\Models\Penitip;
use App\Models\Organisasi;
use Illuminate\Support\Facades\Hash;

class ResetPasswordUniversalController extends Controller
{
    public function showForm($email)
    {
        $user = Pembeli::where('email', $email)->first()
            ?? Penitip::where('email', $email)->first()
            ?? Organisasi::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('login.universal')->withErrors(['email' => 'Akun tidak ditemukan.']);
        }

        return view('auth.resetPassword', [
            'email' => $email,
            'password_lama' => $user->plaintext_password ?? '(kosong)'
        ]);
    }

    public function update(Request $request, $email)
    {
        $request->validate([
            'password_baru' => 'required|min:6',
        ]);

        $user = Pembeli::where('email', $email)->first()
            ?? Penitip::where('email', $email)->first()
            ?? Organisasi::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('login.universal')->withErrors(['email' => 'Akun tidak ditemukan.']);
        }

        // Simpan password baru
        $user->password = Hash::make($request->password_baru);
        $user->plaintext_password = $request->password_baru; // ⚠️ hanya simulasi
        $user->save();

        return redirect()->route('login.universal')->with('success', 'Password berhasil diperbarui. Silakan login.');
    }
}
