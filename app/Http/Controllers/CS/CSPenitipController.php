<?php

namespace App\Http\Controllers\CS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penitip;
use App\Models\Barang;
use Illuminate\Support\Facades\Hash;

class CSPenitipController extends Controller
{
    public function index()
    {
        $penitips = Penitip::all();
        return view('cs.csindexpenitip', compact('penitips'));
    }

    public function barangPenitip($id)
    {
        $penitip = Penitip::findOrFail($id);
        $barangs = $penitip->barangs; // pastikan relasi ada di model
        return view('cs.csbarangpenitip', compact('penitip', 'barangs'));
    }

    public function semuaBarang()
    {
        $barangs = Barang::with('penitip')->paginate(21);
        return view('cs.cssemuabarang', compact('barangs'));
    }

    public function create()
    {
        return view('cs.cspenitipcreate');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:penitips,username',
            'email' => 'required|email|unique:penitips,email',
            'no_telp' => 'required',
            'alamat' => 'required|string|max:255',
            'no_ktp' => 'required|unique:penitips,no_ktp',
            'foto_ktp' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'required|min:6',
        ]);

        $path = $request->file('foto_ktp')->store('ktp_penitip', 'public');

        Penitip::create([
            'username' => $request->username,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'foto_ktp' => $path,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('cs.penitip.index')->with('success', 'Penitip berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $penitip = Penitip::findOrFail($id);
        return view('cs.cspenitipedit', compact('penitip'));
    }

    public function update(Request $request, $id)
    {
        $penitip = Penitip::findOrFail($id);

        $request->validate([
            'username' => 'required|unique:penitips,username,' . $id,
            'email' => 'required|email|unique:penitips,email,' . $id,
            'no_telp' => 'required',
            'alamat' => 'required|string|max:255',
            'no_ktp' => 'required|unique:penitips,no_ktp,' . $id,
            'foto_ktp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['username', 'email', 'no_telp', 'alamat' , 'no_ktp']);

        if ($request->hasFile('foto_ktp')) {
            $path = $request->file('foto_ktp')->store('ktp_penitip', 'public');
            $data['foto_ktp'] = $path;
        }

        $penitip->update($data);

        return redirect()->route('cs.penitip.index')->with('success', 'Data penitip diperbarui.');
    }

    public function destroy($id)
    {
        $penitip = Penitip::findOrFail($id);
        $penitip->delete();

        return redirect()->route('cs.penitip.index')->with('success', 'Penitip berhasil dihapus.');
    }
}
