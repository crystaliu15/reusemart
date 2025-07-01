<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organisasi;

class AdminOrganisasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Organisasi::query();

        if ($request->filled('search')) {
            $query->where('username', 'like', '%' . $request->search . '%');
        }

        $organisasis = $query->get();

        return view('admin.admineditorganisasi', compact('organisasis'));
    }

    public function edit($id)
    {
        $organisasi = Organisasi::findOrFail($id);
        return view('admin.edit_organisasi_form', compact('organisasi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email',
            'no_telp' => 'required|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $organisasi = Organisasi::findOrFail($id);
        $organisasi->update([
            'username' => $request->username,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin.organisasi.index')->with('success', 'Data organisasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $organisasi = Organisasi::findOrFail($id);
        $organisasi->delete();

        return redirect()->route('admin.organisasi.index')->with('success', 'Organisasi berhasil dihapus.');
    }
}
