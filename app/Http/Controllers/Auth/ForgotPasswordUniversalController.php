<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembeli;
use App\Models\Penitip;
use App\Models\Organisasi;
use App\Models\Pegawai;
use App\Models\Owner;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordUniversalController extends Controller
{
    public function showForm()
    {
        return view('auth.forgotPassword');
    }

        public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;

        $user = Pembeli::where('email', $email)->first()
            ?? Penitip::where('email', $email)->first()
            ?? Organisasi::where('email', $email)->first();

        $pegawai = Pegawai::where('email', $email)->first();
        if ($pegawai) {
            $tanggal_lahir = $pegawai->tanggal_lahir;
            $pegawai->password = Hash::make($tanggal_lahir);
            $pegawai->save();

            return redirect()->route('login.universal')
                ->with('success', 'Password pegawai berhasil direset. Gunakan tanggal lahir Anda (format DD-MM-YYYY) untuk login.');
        }

        $owner = Owner::where('email', $email)->first();
        if ($owner) {
            $tanggal_lahir = $owner->tanggal_lahir;
            $owner->password = Hash::make($tanggal_lahir);
            $owner->save();

            return redirect()->route('login.universal')
                ->with('success', 'Password berhasil direset. Gunakan tanggal lahir Anda (format DD-MM-YYYY) untuk login.');
        }

        if ($user) {
            // 🔐 Buat signed reset URL
            $url = URL::temporarySignedRoute(
                'password.reset.form',
                Carbon::now()->addMinutes(30),
                ['email' => $email]
            );

            // Kirim email via Mailtrap
            Mail::raw("Halo,\n\nKlik link berikut untuk mereset password Anda:\n$url\n\nLink ini berlaku selama 30 menit.",
                function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Reset Password Akun ReUseMart');
                });

            return back()->with('success', 'Link reset password telah dikirim ke email Anda.');
        }

        return back()->withErrors(['email' => 'Email tidak ditemukan di sistem kami.']);
    }
}