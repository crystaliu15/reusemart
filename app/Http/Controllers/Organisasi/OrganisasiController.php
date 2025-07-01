<?php

namespace App\Http\Controllers\Organisasi;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use App\Models\RequestDonasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrganisasiController extends Controller
{
    public function index()
    {
        $organisasi = Auth::guard('organisasi')->user();
        $allRequests = RequestDonasi::where('organisasi_id', '!=', $organisasi->id)->get();

        $ownRequests = RequestDonasi::where('organisasi_id', $organisasi->id)->get();
        $otherRequests = RequestDonasi::with('organisasi')
        ->where('organisasi_id', '!=', $organisasi->id)
        ->get();

        return view('organisasi.dashboard', compact('organisasi', 'allRequests', 'ownRequests', 'otherRequests'));
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|max:2048'
        ]);

        $organisasi = Auth::guard('organisasi')->user();

        $file = $request->file('profile_picture');
        $filename = 'org_' . $organisasi->id . '.' . $file->getClientOriginalExtension();

        // Simpan file ke storage
        $path = $file->storeAs('public/profile_pictures', $filename);

        // Simpan nama file ke database
        $organisasi->profile_picture = 'profile_pictures/' . $filename;
        $organisasi->save();

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function edit()
    {
        $organisasi = Auth::guard('organisasi')->user();
        return view('organisasi.edit_profil', compact('organisasi'));
    }

    public function update(Request $request)
{
    $request->validate([
        'username' => 'required',
        'email' => 'required|email',
        'no_telp' => 'required',
        'alamat' => 'required',
        'profile_picture' => 'nullable|image|max:2048'
    ]);

    $organisasi = Auth::guard('organisasi')->user();

    $organisasi->username = $request->username;
    $organisasi->email = $request->email;
    $organisasi->no_telp = $request->no_telp;
    $organisasi->alamat = $request->alamat;

    if ($request->hasFile('profile_picture')) {
        $file = $request->file('profile_picture');
        $filename = 'org_' . $organisasi->id . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/profile_pictures', $filename);
        $organisasi->profile_picture = 'profile_pictures/' . $filename;
    }

    $organisasi->save();

    return redirect()->route('organisasi.dashboard')->with('success', 'Profil berhasil diperbarui.');
}


}
