<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AlamatPembeli;
use App\Models\Pembeli;

class AlamatController extends Controller
{
    public function index(Request $request)
    {
        $pembeli = Auth::guard('pembeli')->user();
        $query = $pembeli->alamat(); // relasi dari model Pembeli → alamat_pembelis
        if ($request->filled('search')) {
        $search = $request->search;
        $query->where('alamat', 'like', '%' . $search . '%');
        }

        $alamatList = $query->get();
        $defaultAlamatId = $pembeli->default_alamat_id;

        return view('pembeli.kelola_alamat', compact('alamatList', 'defaultAlamatId'));
    }

    public function setDefault($id)
    {
        $pembeli = Auth::guard('pembeli')->user();
        $alamat = AlamatPembeli::where('id', $id)->where('pembeli_id', $pembeli->id)->firstOrFail();

        $pembeli->default_alamat_id = $alamat->id;
        $pembeli->save();

        return redirect()->route('pembeli.alamat.index')->with('success', 'Alamat default berhasil diperbarui.');
    }

    public function create()
    {
        return view('pembeli.createalamatpembeli');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jalan'        => 'required|string',
            'no_bangunan'  => 'required|string',
            'kelurahan'    => 'required|string',
            'kecamatan'    => 'required|string',
            'kabupaten'    => 'required|string',
            'provinsi'     => 'required|string',
            'kode_pos'     => 'required|string',
        ]);

        $alamatStr = "Jl. {$request->jalan}, No. {$request->no_bangunan}, {$request->kelurahan}, Kec. {$request->kecamatan}, {$request->kabupaten}, {$request->provinsi}, {$request->kode_pos}";

        $pembeli = auth()->guard('pembeli')->user();

        $alamatBaru = AlamatPembeli::create([
            'pembeli_id' => $pembeli->id,
            'alamat'     => $alamatStr,
        ]);

        // Cek apakah ini satu-satunya alamat
        if ($pembeli->alamat()->count() === 1) {
            $pembeli->default_alamat_id = $alamatBaru->id;
            $pembeli->save();
        }

        return redirect()->route('pembeli.alamat.index')->with('success', 'Alamat baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $alamat = AlamatPembeli::where('id', $id)
                    ->where('pembeli_id', Auth::guard('pembeli')->id())
                    ->firstOrFail();

        return view('pembeli.edit_alamat', compact('alamat'));
    }

    public function update(Request $request, $id)
    {
        $alamat = AlamatPembeli::where('id', $id)
                    ->where('pembeli_id', Auth::guard('pembeli')->id())
                    ->firstOrFail();

        $request->validate([
            'alamat' => 'required|string|max:255'
        ]);

        $alamat->alamat = $request->alamat;
        $alamat->save();

        return redirect()->route('pembeli.alamat.index')->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pembeli = Auth::guard('pembeli')->user();

        $alamat = AlamatPembeli::where('id', $id)
                    ->where('pembeli_id', $pembeli->id)
                    ->firstOrFail();

        // Jika alamat yang dihapus adalah default
        $isDefault = $pembeli->default_alamat_id == $alamat->id;

        $alamat->delete();

        if ($isDefault) {
            // Cek alamat aktif yang tersisa dan ambil yang ID paling kecil
            $alamatTersisa = AlamatPembeli::where('pembeli_id', $pembeli->id)->orderBy('id')->first();

            $pembeli->default_alamat_id = $alamatTersisa?->id; // akan null jika tidak ada alamat sama sekali
            $pembeli->save();
        }

        return redirect()->route('pembeli.alamat.index')->with('success', 'Alamat berhasil dihapus.');
    }

}
