<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Pembeli;
use App\Models\Penitip;
use App\Models\Transaksi;
use App\Models\Pegawai;
use App\Models\JadwalPengiriman;
use App\Models\JadwalPengambilan;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Notifications\JadwalPengambilanDibuat;
use App\Notifications\NotifikasiPengirimanDibuat;
use App\Notifications\NotifikasiKePenitip;
use App\Notifications\NotifikasiKeKurir;
use App\Notifications\NotifikasiBarangSelesai;
use Illuminate\Support\Facades\Validator;

class BarangGudangController extends Controller
{
    public function index(Request $request)
    {
        $pegawai = Auth::guard('pegawai')->user();
        $query = Barang::with(['kategori', 'penitip'])
            ->where('quality_check', $pegawai->id);

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where('nama', 'like', '%' . $searchTerm . '%');
        }

        $barangs = $query->get();

        return view('gudang.barangIndex', compact('barangs'));
    }

    public function formJumlahBarang()
    {
        $penitips = Penitip::all();
        return view('gudang.barang.form_jumlah', compact('penitips'));
    }

    public function multiCreate(Request $request)
    {
        $request->validate([
            'penitip_id' => 'required|exists:penitips,id',
            'jumlah' => 'required|integer|min:1|max:10'
        ]);

        $penitip_id = $request->penitip_id;
        $jumlah = $request->jumlah;
        $kategoris = Kategori::all();
        $penitip = Penitip::findOrFail($penitip_id);

        return view('gudang.barang.multi_create', compact('jumlah', 'penitip', 'kategoris'));
    }

    public function multiStore(Request $request)
    {
        $pegawai = Auth::guard('pegawai')->user();

        $validator = Validator::make($request->all(), [
            'penitip_id' => 'required|exists:penitips,id',
            'nama' => 'required|array',
            'nama.*' => 'required|string|max:255',
            'kategori_id' => 'required|array',
            'kategori_id.*' => 'required|exists:kategoris,id',
            'deskripsi' => 'required|array',
            'deskripsi.*' => 'required|string',
            'harga' => 'required|array',
            'harga.*' => 'required|numeric',
            'berat' => 'required|array',
            'berat.*' => 'required|numeric|min:0.01',
            'thumbnail' => 'required|array',
            'thumbnail.*' => 'required|image|mimes:jpg,jpeg|max:20480',
            'foto_lain' => 'required|array',
            'foto_lain.*' => 'required|array|min:2',
            'foto_lain.*.*' => 'image|mimes:jpg,jpeg|max:20480',
            'punya_garansi' => 'required|array',
            'garansi_berlaku_hingga' => 'nullable|array',
        ], [
            'foto_lain.*.min' => 'Mohon untuk gambar lain masukkan minimal 2 gambar!'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $jumlah = count($request->nama);
        $barangIds = []; // ← penampung ID semua barang yang ditambahkan

        for ($i = 0; $i < $jumlah; $i++) {
            $garansi = ($request->punya_garansi[$i] == 1) ? $request->garansi_berlaku_hingga[$i] : null;

            $barang = Barang::create([
                'kategori_id' => $request->kategori_id[$i],
                'nama' => $request->nama[$i],
                'deskripsi' => $request->deskripsi[$i],
                'harga' => $request->harga[$i],
                'berat' => $request->berat[$i],
                'garansi_berlaku_hingga' => $garansi,
                'terjual' => false,
                'penitip_id' => $request->penitip_id,
                'quality_check' => $pegawai->id,
                'batas_waktu_titip' => now()->addDays(30),
                'thumbnail' => '',
                'foto_lain' => json_encode([]),
            ]);

            $barangIds[] = $barang->id; // ← simpan ID ke array

            $id = $barang->id;
            $folder = public_path("images/barang/$id");
            if (!File::exists($folder)) {
                File::makeDirectory($folder, 0775, true);
            }

            // Simpan thumbnail
            $thumb = $request->file('thumbnail')[$i];
            $thumbName = "{$id}.jpg";
            $thumb->move($folder, $thumbName);

            // Simpan foto lain
            $fotoLainPaths = [];
            $fotoLainFiles = $request->file('foto_lain')[$i];
            $index = 1;
            foreach ($fotoLainFiles as $foto) {
                $fotoName = "{$id}_{$index}.jpg";
                $foto->move($folder, $fotoName);
                $fotoLainPaths[] = $fotoName;
                $index++;
            }

            $barang->update([
                'thumbnail' => $thumbName,
                'foto_lain' => json_encode($fotoLainPaths),
            ]);

            // Simpan nota
            $barang->load(['kategori', 'penitip']);
            $pdf = Pdf::loadView('gudang.nota_pdf', ['barang' => $barang]);

            $pdfPath = storage_path("app/public/notas/nota-barang-{$id}.pdf");
            if (!File::exists(dirname($pdfPath))) {
                File::makeDirectory(dirname($pdfPath), 0755, true);
            }
            $pdf->save($pdfPath);
        }

        // Simpan ID-ID barang ke session
        $request->session()->put('barang_ids', $barangIds);

        return redirect()->route('gudang.barang.multiResult')->with('success', 'Semua barang berhasil ditambahkan.');
    }

    public function multiResult(Request $request)
    {
        $ids = session('barang_ids', []);

        if (empty($ids)) {
            return redirect()->route('gudang.barang.index')->with('error', 'Tidak ada barang baru ditemukan.');
        }

        $barangs = Barang::with(['kategori', 'penitip', 'qualityChecker'])->whereIn('id', $ids)->get();

        return view('gudang.barang.multi_result', compact('barangs'));
    }

    public function cetakNotaGabungan(Request $request)
    {
        $ids = [];

        if ($request->has('ids')) {
            $ids = explode(',', $request->ids);
        } elseif (session()->has('barang_ids')) {
            $ids = session('barang_ids', []);
        }

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada barang yang bisa dicetak.');
        }

        $barangs = Barang::with(['kategori', 'penitip', 'qualityChecker'])->whereIn('id', $ids)->get();

        if ($barangs->isEmpty()) {
            return back()->with('error', 'Barang tidak ditemukan.');
        }

        $penitip = $barangs->first()->penitip;
        $tanggal = $barangs->first()->created_at;
        $notaId = $barangs->first()->id;
        $kodeNota = $tanggal->format('y.m') . '.' . $notaId;

        $pdf = Pdf::loadView('gudang.nota_gabungan_pdf', compact('barangs', 'penitip', 'tanggal', 'kodeNota'));

        return $pdf->download('nota_penitipan_gabungan_' . $kodeNota . '.pdf');
    }


    public function daftarPenitip()
    {
        $penitips = Penitip::withCount('barangs')->get();

        return view('gudang.barang.penitip_list', compact('penitips'));
    }

    public function barangPerPenitip($id)
    {
        $penitip = Penitip::findOrFail($id);
        $barangs = Barang::with(['kategori', 'qualityChecker'])
            ->where('penitip_id', $id)
            ->get();

        return view('gudang.barang.multi_result', compact('barangs', 'penitip'));
    }


    public function create()
    {
        $kategoris = Kategori::all();
        $penitips = Penitip::all();

        return view('gudang.barangCreate', compact('kategoris', 'penitips'));
    }

    public function store(Request $request)
    {
        $pegawai = Auth::guard('pegawai')->user();

        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric',
            'berat' => 'required|numeric|min:0.01',
            'thumbnail' => 'required|image|mimes:jpg,jpeg|max:20480',
            'foto_lain.*' => 'nullable|image|mimes:jpg,jpeg|max:20480',
            'punya_garansi' => 'required|in:0,1',
            'garansi_berlaku_hingga' => 'nullable|date',
            'penitip_id' => 'required|exists:penitips,id',
        ]);

        $garansi = $request->punya_garansi == 1 ? $request->garansi_berlaku_hingga : null;

        // 1. Simpan barang untuk dapat ID
        $barang = Barang::create([
            'kategori_id' => $request->kategori_id,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'berat' => $request->berat,
            'garansi_berlaku_hingga' => $garansi,
            'terjual' => false,
            'penitip_id' => $request->penitip_id,
            'quality_check' => $pegawai->id,
            'batas_waktu_titip' => now()->addDays(30),
            'thumbnail' => '',
            'foto_lain' => json_encode([]),
        ]);

        $id = $barang->id;
        $folder = public_path("images/barang/$id");

        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0775, true);
        }

        // 2. Simpan thumbnail
        $thumbnailFile = $request->file('thumbnail');
        $thumbnailName = "{$id}.jpg";
        $thumbnailFile->move($folder, $thumbnailName);

        // 3. Simpan foto_lain[]
        $fotoLainPaths = [];
        if ($request->hasFile('foto_lain')) {
            $index = 1;
            foreach ($request->file('foto_lain') as $foto) {
                $fotoName = "{$id}_{$index}.jpg";
                $foto->move($folder, $fotoName);
                $fotoLainPaths[] = $fotoName;
                $index++;
            }
        }

        // 4. Update thumbnail dan foto_lain
        $barang->update([
            'thumbnail' => $thumbnailName,
            'foto_lain' => json_encode($fotoLainPaths),
        ]);

        // 5. Generate PDF Nota Penitipan
        $barang->load(['kategori', 'penitip']);
        $pdf = Pdf::loadView('gudang.nota_pdf', ['barang' => $barang]);

        $pdfPath = storage_path("app/public/notas/nota-barang-{$barang->id}.pdf");
        if (!File::exists(dirname($pdfPath))) {
            File::makeDirectory(dirname($pdfPath), 0755, true);
        }
        $pdf->save($pdfPath);

        return redirect()->route('gudang.barang.index')
            ->with('success', 'Barang berhasil ditambahkan dan nota penitipan telah dibuat.');
    }

    public function show($id)
    {
        $barang = Barang::with(['kategori', 'penitip'])->findOrFail($id);
        return view('gudang.barangShow', compact('barang'));
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $kategoris = Kategori::all();
        $penitips = Penitip::all();

        return view('gudang.barangEdit', compact('barang', 'kategoris', 'penitips'));
    }

    public function update(Request $request, $id)
    {
        $pegawai = Auth::guard('pegawai')->user();
        $barang = Barang::findOrFail($id);

        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric',
            'berat' => 'required|numeric|min:0.01',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg|max:20480',
            'foto_lain.*' => 'nullable|image|mimes:jpg,jpeg|max:20480',
            'punya_garansi' => 'required|in:0,1',
            'garansi_berlaku_hingga' => 'nullable|date',
            'penitip_id' => 'required|exists:penitips,id',
        ]);

        $garansi = $request->punya_garansi == 1 ? $request->garansi_berlaku_hingga : null;

        $folder = public_path("images/barang/{$barang->id}");
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0775, true);
        }

        if ($request->hasFile('thumbnail')) {
            $thumbnailName = "{$barang->id}.jpg";
            $request->file('thumbnail')->move($folder, $thumbnailName);
            $barang->thumbnail = $thumbnailName;
        }

        if ($request->hasFile('foto_lain')) {
            // Hapus semua file lama
            $oldFotoLain = json_decode($barang->foto_lain, true);
            if (is_array($oldFotoLain)) {
                foreach ($oldFotoLain as $oldFile) {
                    $oldPath = $folder . '/' . $oldFile;
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }
            }

            // Simpan dua foto baru
            $fotoLainPaths = [];
            $index = 1;
            foreach ($request->file('foto_lain') as $foto) {
                $fotoName = "{$barang->id}_{$index}.jpg";
                $foto->move($folder, $fotoName);
                $fotoLainPaths[] = $fotoName;
                $index++;
            }

            // Update di database
            $barang->foto_lain = json_encode($fotoLainPaths);
        }

        $barang->update([
            'kategori_id' => $request->kategori_id,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'berat' => $request->berat,
            'garansi_berlaku_hingga' => $garansi,
            'penitip_id' => $request->penitip_id,
        ]);

        return redirect()->route('gudang.barang.barangPerPenitip', $barang->penitip_id)->with('success', 'Barang berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);

        $folder = public_path("images/barang/{$barang->id}");
        if (File::exists($folder)) {
            File::deleteDirectory($folder);
        }

        $notaPath = storage_path("app/public/notas/nota-barang-{$barang->id}.pdf");
        if (File::exists($notaPath)) {
            File::delete($notaPath);
        }

        $barang->delete();

        return redirect()->route('gudang.barang.index')->with('success', 'Barang berhasil dihapus.');
    }

       public function formPengambilan($id)
    {
        $barang = Barang::findOrFail($id);

        // Validasi bahwa barang sudah dikonfirmasi akan diambil oleh penitip
        if ($barang->status_pengambilan !== 'dikonfirmasi') {
            return redirect()->route('gudang.barang.index')->with('error', 'Barang belum dikonfirmasi untuk diambil oleh penitip.');
        }

        return view('gudang.barang.form_ambil', compact('barang'));
    }

    public function simpanPengambilan(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        // Set status sebagai sudah diambil kembali (misal status = 1)
        $barang->diambil_kembali = 1; // diambil kembali
        $barang->tanggal_diambil_kembali = Carbon::now();
        $barang->save();

        return redirect()->route('gudang.barang.index')->with('success', 'Barang telah dicatat sebagai sudah diambil.');
    }

    public function catatPengambilan($id)
    {
        $barang = Barang::findOrFail($id);

        // Cek dulu apakah status_pengambilan sudah 1 dan status masih 0
        if ($barang->status_pengambilan == 1 && $barang->diambil_kembali == 0) {
            $barang->diambil_kembali = 1; // sudah diambil
            $barang->tanggal_diambil_kembali = now();
            $barang->save();

            return redirect()->route('gudang.barang.index')->with('success', 'Pengambilan barang berhasil dicatat.');
        }

        return redirect()->route('gudang.barang.index')->with('error', 'Barang tidak dapat dicatat pengambilannya.');
    }

        public function transaksi()
    {
        $pegawai = Auth::guard('pegawai')->user();

        // Barang yang harus dikirim
        $barangKirim = Barang::with(['transaksi.pembeli', 'jadwalPengirimen'])
            ->where('quality_check', $pegawai->id)
            ->whereHas('transaksi', function ($query) {
                $query->where('tipe_pengiriman', 'kirim');
            })
            ->get();

        // Barang yang harus diambil
        $barangAmbil = Barang::with(['transaksi.pembeli', 'jadwalPengambilan'])
            ->where('quality_check', $pegawai->id)
            ->whereHas('transaksi', function ($query) {
                $query->where('tipe_pengiriman', 'ambil');
            })
            ->get();

        // Tambahkan status jadwal
        foreach ($barangKirim as $barang) {
            $barang->status_jadwal = $barang->jadwalPengirimen ? 'dikirim' : 'belum';
        }

        foreach ($barangAmbil as $barang) {
            $barang->status_jadwal = $barang->jadwalPengambilan ? 'diambil' : 'belum';
        }

        return view('gudang.barangtransaksi', compact('barangKirim', 'barangAmbil'));
    }


    public function formJadwalKirim($id)
    {
        $barang = Barang::with(['transaksi', 'jadwalPengirimen.pegawai'])->findOrFail($id);
        $pegawais = Pegawai::whereHas('jabatan', function($q) {
            $q->where('nama_jabatan', 'Kurir');
        })->get();

        $jadwal = $barang->jadwalPengirimen; // relasi satu-satu

        return view('gudang.jadwal-kirim', compact('barang', 'pegawais', 'jadwal'));
    }


     public function simpanJadwalKirim(Request $request, $id)
    {
        $request->validate([
            'jadwal_kirim' => 'required|date',
            'pegawai_id' => 'required|exists:pegawais,id',
        ]);

        $barang = Barang::with(['transaksi.pembeli', 'penitip'])->findOrFail($id);

        $jadwal = JadwalPengiriman::updateOrCreate(
            ['barang_id' => $barang->id],
            [
                'jadwal_kirim' => $request->jadwal_kirim,
                'pegawai_id' => $request->pegawai_id,
            ]
        );

        // Reload relasi agar jadwal tersedia untuk notifikasi
        $barang->load(['jadwalPengirimen', 'transaksi.pembeli', 'penitip']);

        // Kirim notifikasi ke pembeli
        if ($barang->transaksi && $barang->transaksi->pembeli) {
            $barang->transaksi->pembeli->notify(new NotifikasiPengirimanDibuat($barang));
        }

        // Kirim notifikasi ke penitip
        if ($barang->penitip) {
            $barang->penitip->notify(new NotifikasiKePenitip($barang));
        }

        // Kirim notifikasi ke kurir
        $kurir = Pegawai::find($request->pegawai_id);
        if ($kurir) {
            $kurir->notify(new NotifikasiKeKurir($barang));
        }

        return redirect()->route('gudang.barang.transaksi')->with('success', 'Jadwal pengiriman berhasil disimpan dan notifikasi telah dikirim.');
    }

    public function cetakNota($id)
    {
        $barang = Barang::with([
            'transaksi.pembeli',
            'transaksi.detail.barang',
            'transaksi.alamat',
            'penitip',
            'jadwalPengirimen.pegawai',
            'qualityChecker',
        ])->findOrFail($id);

        $transaksi = $barang->transaksi;

        if (!$transaksi || !$transaksi->pembeli) {
            return back()->with('error', 'Barang ini belum memiliki transaksi atau pembeli.');
        }

        $pembeli = $transaksi->pembeli;
        $alamat = $transaksi->alamat->alamat ?? 'Alamat tidak tersedia';
        $penitip = $barang->penitip;
        $kurir = $barang->jadwalPengirimen->pegawai ?? null;
        $tanggalLunas = $transaksi->updated_at;
        $qc = $barang->qualityChecker;

        // Hitung poin yang didapat dari transaksi
        $poinTransaksi = 0;
        foreach ($transaksi->detail as $detail) {
            $harga = $detail->barang->harga;
            $poin = floor($harga / 10000);
            if ($harga > 500000) {
                $poin += floor($harga * 0.2 / 10000);
            }
            $poinTransaksi += $poin;
        }

        // Total poin milik pembeli saat ini
        $totalPoinPembeli = $pembeli->poin;

        $pdf = Pdf::loadView('gudang.nota-pdf', compact(
            'barang',
            'transaksi',
            'pembeli',
            'alamat',
            'penitip',
            'kurir',
            'tanggalLunas',
            'poinTransaksi',
            'totalPoinPembeli',
            'qc'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('nota_pengiriman_' . $barang->id . '.pdf');
    }

    public function formJadwalAmbil($id)
    {
        $barang = Barang::with('jadwalPengambilan')->findOrFail($id);
        return view('gudang.jadwal-ambil', compact('barang'));
    }

    public function simpanJadwalAmbil(Request $request, $id)
    {
        $request->validate([
            'jadwal_pengambilan' => 'required|date|after_or_equal:today',
        ]);

        // Memuat relasi transaksi.pembeli dan penitip
        $barang = Barang::with(['transaksi.pembeli', 'penitip'])->findOrFail($id);

        $pembeli = $barang->transaksi->pembeli ?? null;
        $penitip = $barang->penitip ?? null;

        if (!$pembeli) {
            return back()->with('error', 'Barang ini belum memiliki pembeli.');
        }

        // Membuat atau memperbarui jadwal pengambilan
        $jadwal = JadwalPengambilan::updateOrCreate(
            ['barang_id' => $barang->id],
            [
                'jadwal_pengambilan' => $request->jadwal_pengambilan,
                'pembeli_id' => $pembeli->id,
            ]
        );

        // Menetapkan jadwal_pengambilan_id pada transaksi
        $transaksi = $barang->transaksi;
        $transaksi->jadwal_pengambilan_id = $jadwal->id;
        $transaksi->save();

        // Mengirim notifikasi ke pembeli
        $pembeli->notify(new JadwalPengambilanDibuat($barang, $request->jadwal_pengambilan));

        // Mengirim notifikasi ke penitip (jika ada)
        if ($penitip) {
            $penitip->notify(new JadwalPengambilanDibuat($barang, $request->jadwal_pengambilan));
        }

        return redirect()->route('gudang.barang.transaksi')->with('success', 'Jadwal pengambilan berhasil disimpan.');
    }

    public function cetakNotaPengambilan($id)
    {
        $barang = Barang::with([
            'transaksi.pembeli',
            'transaksi.detail.barang',
            'transaksi.alamat',
            'penitip',
            'jadwalPengambilan',
            'qualityChecker',
        ])->findOrFail($id);

        $transaksi = $barang->transaksi;

        if (!$transaksi || !$transaksi->pembeli) {
            return back()->with('error', 'Barang belum memiliki transaksi atau pembeli.');
        }

        $pembeli = $transaksi->pembeli;
        $alamat = $transaksi->alamat->alamat ?? 'Alamat tidak tersedia';
        $penitip = $barang->penitip;
        $tanggalLunas = $transaksi->updated_at;
        $qc = $barang->qualityChecker;

        // Hitung poin yang didapat dari transaksi
        $poinTransaksi = 0;
        foreach ($transaksi->detail as $detail) {
            $harga = $detail->barang->harga;
            $poin = floor($harga / 10000);
            if ($harga > 500000) {
                $poin += floor($harga * 0.2 / 10000);
            }
            $poinTransaksi += $poin;
        }

        // Total poin milik pembeli saat ini
        $totalPoinPembeli = $pembeli->poin;

        $pdf = PDF::loadView('gudang.nota', compact(
            'barang',
            'transaksi',
            'pembeli',
            'alamat',
            'penitip',
            'tanggalLunas',
            'poinTransaksi',
            'totalPoinPembeli',
            'qc'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('nota_pengambilan_' . $barang->id . '.pdf');
    }

    public function konfirmasiPengambilan($id)
    {
        $barang = Barang::findOrFail($id);

        // Update status barang
        $barang->status = 'Sold Out';
        $barang->save();

        // Ambil jadwal pengambilan dan isi diambil_pada
        $jadwal = $barang->jadwalPengambilan;
        if ($jadwal) {
            $jadwal->diambil_pada = now(); // waktu sekarang
            $jadwal->save();
        }

        // Ambil penitip dan pembeli
        $penitip = $barang->penitip;
        $pembeli = $barang->transaksi->pembeli ?? null; // gunakan null-safe untuk jaga-jaga

        // Kirim notifikasi jika penitip dan pembeli ada
        if ($penitip) {
            $penitip->notify(new NotifikasiBarangSelesai($barang, 'penitip'));
        }

        if ($pembeli) {
            $pembeli->notify(new NotifikasiBarangSelesai($barang, 'pembeli'));
        }

        return redirect()->back()->with('success', 'Pengambilan barang dikonfirmasi dan notifikasi telah dikirim.');
    }

    public function barangMendekatiBatasTitip()
    {
        $pegawai = Auth::guard('pegawai')->user();

        $today = now()->startOfDay();
        $limit = now()->addDays(3)->endOfDay();

        $barangs = Barang::with(['penitip', 'kategori'])
            ->whereBetween('batas_waktu_titip', [$today, $limit])
            ->where('quality_check', $pegawai->id)
            ->orderBy('batas_waktu_titip', 'asc')
            ->get()
            ->map(function ($barang) {
                $batas = \Carbon\Carbon::parse($barang->batas_waktu_titip)->startOfDay();
                $barang->sisa_hari = now()->startOfDay()->diffInDays($batas, false);
                return $barang;
            });

        return view('gudang.barang.batas_titip', compact('barangs'));
    }

    public function barangDiambilKembali()
    {
        $pegawai = Auth::guard('pegawai')->user();

        // Ambil barang yang dicek oleh pegawai ini dan sudah diambil kembali
        $barangs = Barang::with(['kategori', 'penitip'])
            ->where('quality_check', $pegawai->id)
            ->where('diambil_kembali', 1)
            ->orderByDesc('tanggal_diambil_kembali')
            ->get();

        return view('gudang.barangDiambilKembali', compact('barangs'));
    }


}
