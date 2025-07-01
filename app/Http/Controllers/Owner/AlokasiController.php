<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RequestDonasi;
use App\Models\DonasiBarang;
use App\Models\Barang;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class AlokasiController extends Controller
{
    public function form(RequestDonasi $requestDonasi)
    {
        $barangs = Barang::all(); // Tanpa filter
        return view('owner.alokasi', compact('requestDonasi', 'barangs'));
    }

    public function store(Request $request, RequestDonasi $requestDonasi)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'nama_penerima' => 'required|string|max:100'
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        // Simpan ke donasi_barangs
        DonasiBarang::create([
            'organisasi_id' => $requestDonasi->organisasi_id,
            'nama_penerima'   => $request->nama_penerima,
            'nama_barang'   => $barang->nama,
            'kategori_id'   => $barang->kategori_id,
            'deskripsi'     => $barang->deskripsi,
            'tanggal_donasi'=> now(),
        ]);

        // Ubah status barang
        $barang->status = 'didonasikan';
        $barang->save();

        // Hapus request donasi
        $requestDonasi->delete();

        // ✅ Tambah poin untuk penitip jika ada
        if ($barang->penitip_id) {
            $penitip = \App\Models\Penitip::find($barang->penitip_id);
            if ($penitip && $barang->harga) {
                $tambahanPoin = floor($barang->harga / 10000);
                $penitip->increment('poin', $tambahanPoin);
            }
        }

        return redirect()->route('owner.histori')->with('success', 'Barang berhasil dialokasikan dan poin penitip ditambahkan!');
    }

    public function exportPdf()
{
    $requestDonasi = RequestDonasi::with('organisasi')->get();
    $tanggalCetak = Carbon::now()->translatedFormat('d F Y');

    $pdf = Pdf::loadView('owner.pdf.request-donasi', compact('requestDonasi', 'tanggalCetak'));
    return $pdf->download('laporan_request_donasi.pdf'); // atau ->stream() jika ingin tampil di browser
}
}
