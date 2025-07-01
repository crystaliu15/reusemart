<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DonasiBarang;
use App\Models\Organisasi;
use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class DonasiController extends Controller
{
    public function edit($id)
    {
        $donasi = DonasiBarang::with('organisasi')->findOrFail($id);
        $organisasis = Organisasi::all();
        $barang = Barang::where('nama', $donasi->nama_barang)->first(); // asumsi nama barang sama

        return view('owner.donasi.edit', compact('donasi', 'organisasis', 'barang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_penerima' => 'required|string|max:100',
            'tanggal_donasi' => 'required|date',
            'status_barang' => 'required|string'
        ]);

        $donasi = DonasiBarang::findOrFail($id);
        $barang = Barang::where('nama', $donasi->nama_barang)->first();

        $donasi->update([
            'nama_penerima' => $request->nama_penerima,
            'tanggal_donasi' => $request->tanggal_donasi,
        ]);

        if ($barang) {
            $barang->status = $request->status_barang;
            $barang->save();
        }

        return redirect()->route('owner.histori')->with('success', 'Donasi berhasil diperbarui!');
    }

    public function cetakPDF()
    {
        $historiDonasi = DonasiBarang::with(['organisasi', 'penitip'])->get();

        $tanggalCetak = Carbon::now()->format('j F Y');

        $pdf = Pdf::loadView('owner.laporan-donasi-pdf', [
            'historiDonasi' => $historiDonasi,
            'tahun' => Carbon::now()->year,
            'tanggalCetak' => $tanggalCetak,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-donasi-barang.pdf');
    }

    public function exportHTML(Request $request)
    {
        $historiDonasi = DonasiBarang::with(['organisasi', 'penitip'])->get();
        $tanggalCetak = Carbon::now()->format('d/m/Y');
        $tahun = Carbon::now()->format('Y');

        return view('owner.histori-html', compact('historiDonasi', 'tanggalCetak', 'tahun'));
    }
}
