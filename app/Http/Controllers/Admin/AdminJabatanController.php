<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Hash;

class AdminJabatanController extends Controller
{
    public function index()
    {
        $jabatans = Jabatan::all();
        return view('admin.adminindexjabatan', compact('jabatans'));
    }

    public function edit($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        return view('admin.admineditjabatan', compact('jabatan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
        ]);

        $jabatan = Jabatan::findOrFail($id);
        $jabatan->nama_jabatan = $request->nama_jabatan;
        $jabatan->save();

        return redirect()->route('admin.jabatan.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        if ($jabatan->id == 1 || strtolower($jabatan->nama_jabatan) == 'owner') {
            return redirect()->back()->withErrors('Jabatan Owner tidak boleh dihapus.');
        }

        $jabatan->delete();
        return redirect()->route('admin.jabatan.index')->with('success', 'Jabatan berhasil dihapus.');
    }

    public function create()
    {
        return view('admin.tambahjabatan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|unique:jabatans,nama_jabatan|string|max:255',
        ]);

        Jabatan::create([
            'nama_jabatan' => $request->nama_jabatan,
        ]);

        return redirect()->route('admin.jabatan.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function pegawai($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $pegawais = $jabatan->pegawais; // pastikan relasi tersedia
        return view('admin.pegawaiperjabatan', compact('jabatan', 'pegawais'));
    }

    public function createPegawai($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        return view('admin.tambahpegawaiperjabatan', compact('jabatan'));
    }

    public function storePegawai(Request $request, $id)
    {
        $jabatan = Jabatan::findOrFail($id);

        $request->validate([
            'username' => 'required|string|unique:pegawais,username',
            'email' => 'required|email|unique:pegawais,email',
            'nama_lengkap' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date',
            'alamat_rumah' => 'nullable|string',
            'password' => 'required|string|min:6',
        ]);

        Pegawai::create([
            'username' => $request->username,
            'email' => $request->email,
            'nama_lengkap' => $request->nama_lengkap,
            'no_telp' => $request->no_telp,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat_rumah' => $request->alamat_rumah,
            'password' => Hash::make($request->password),
            'jabatan_id' => $jabatan->id,
        ]);

        return redirect()->route('admin.jabatan.pegawai', $jabatan->id)
            ->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function destroyPegawai($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $jabatan_id = $pegawai->jabatan_id;

        $pegawai->delete();

        return redirect()->route('admin.jabatan.pegawai', $jabatan_id)
            ->with('success', 'Pegawai berhasil dihapus.');
    }

    public function editPegawai($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $jabatans = Jabatan::all(); // jika ingin ubah jabatan juga
        return view('admin.editpegawaiperjabatan', compact('pegawai', 'jabatans'));
    }

    public function updatePegawai(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $request->validate([
            'username' => 'required|string|unique:pegawais,username,' . $pegawai->id,
            'email' => 'required|email|unique:pegawais,email,' . $pegawai->id,
            'nama_lengkap' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date',
            'alamat_rumah' => 'nullable|string',
            'password' => 'nullable|string|min:6',
            'jabatan_id' => 'required|exists:jabatans,id',
        ]);

        $pegawai->update([
            'username' => $request->username,
            'email' => $request->email,
            'nama_lengkap' => $request->nama_lengkap,
            'no_telp' => $request->no_telp,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat_rumah' => $request->alamat_rumah,
            'jabatan_id' => $request->jabatan_id,
            'password' => $request->password ? Hash::make($request->password) : $pegawai->password,
        ]);

        return redirect()->route('admin.jabatan.pegawai', $pegawai->jabatan_id)
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }
}
