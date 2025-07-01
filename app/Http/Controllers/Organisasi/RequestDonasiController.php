<?php

namespace App\Http\Controllers\Organisasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RequestDonasi;
use Illuminate\Support\Facades\Auth;

class RequestDonasiController extends Controller
{
    public function create()
    {
        return view('organisasi.request_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_barang' => 'required|string|max:255',
            'alasan' => 'required|string',
        ]);

        RequestDonasi::create([
            'organisasi_id' => Auth::guard('organisasi')->id(),
            'jenis_barang' => $request->jenis_barang,
            'alasan' => $request->alasan,
        ]);

        return redirect()->route('organisasi.dashboard')->with('success', 'Request donasi berhasil dikirim.');
    }

    public function edit($id)
    {
        $request = RequestDonasi::findOrFail($id);

        // Pastikan hanya organisasi pemilik yang bisa mengedit
        if ($request->organisasi_id !== Auth::guard('organisasi')->id()) {
            abort(403);
        }

        return view('organisasi.request_edit', compact('request'));
    }

    public function update(Request $requestData, $id)
    {
        $request = RequestDonasi::findOrFail($id);

        if ($request->organisasi_id !== Auth::guard('organisasi')->id()) {
            abort(403);
        }

        $requestData->validate([
            'jenis_barang' => 'required',
            'alasan' => 'required',
        ]);

        $request->update([
            'jenis_barang' => $requestData->jenis_barang,
            'alasan' => $requestData->alasan,
        ]);

        return redirect()->route('organisasi.dashboard')->with('success', 'Request berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $request = RequestDonasi::findOrFail($id);

        if ($request->organisasi_id !== Auth::guard('organisasi')->id()) {
            abort(403);
        }

        $request->delete();

        return redirect()->route('organisasi.dashboard')->with('success', 'Request berhasil dihapus.');
    }
}
