<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KomisiLog;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class OwnerTransaksiController extends Controller
{
    public function index()
{
    // Ambil semua transaksi_id yang pernah muncul di komisi_logs
    $transaksiList = \App\Models\KomisiLog::pluck('transaksi_id')->unique();

    // Ambil daftar transaksi (distinct) dengan pagination manual
    $transaksiPaginated = $transaksiList->values()->chunk(10); // ambil 10 per halaman (simulasi pagination manual)

    $currentPage = request('page', 1);
    $currentChunk = $transaksiPaginated->get($currentPage - 1, collect());

    return view('owner.transaksi_index', [
        'transaksiList' => $currentChunk,
        'currentPage' => $currentPage,
        'lastPage' => $transaksiPaginated->count(),
    ]);
}

    public function show($id)
{
    $komisiLogs = KomisiLog::with(['barang', 'penitip'])
        ->where('transaksi_id', $id)
        ->get();

    $penitip = optional($komisiLogs->first())->penitip;
    $bulan = 'Juni'; // Bisa diganti dinamis pakai Carbon kalau mau
    $tahun = '2025'; // Sama
    $tanggalCetak = now()->format('d M Y');

    return view('owner.transaksi_detail', [
        'komisiLogs' => $komisiLogs,
        'penitip' => $penitip,
        'id' => $id,
        'bulan' => $bulan,
        'tahun' => $tahun,
        'tanggalCetak' => $tanggalCetak,
    ]);
}


    
public function downloadPdf($id)
{
    $komisiLogs = KomisiLog::where('transaksi_id', $id)
                ->with(['barang', 'penitip']) // <- penting!
                ->get();

    $penitip = optional($komisiLogs->first())->penitip; // Asumsikan relasi ke penitip ada
    $tanggalCetak = now()->format('d M Y');
    
    $pdf = Pdf::loadView('owner.transaksi.pdf', [
        'komisiLogs' => $komisiLogs,
        'penitip' => $penitip,
        'transaksiId' => $id,
        'tanggalCetak' => $tanggalCetak,
        'bulan' => 'Juni', // Dinamis jika perlu
        'tahun' => '2025',     // Dinamis jika perlu
    ]);

    return $pdf->download("Laporan_Transaksi_{$penitip->nama}_{$id}.pdf");
}
}
