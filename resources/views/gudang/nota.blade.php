<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Pengambilan Barang</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 20px; }
        .header { text-align: center; font-size: 14px; margin-bottom: 10px; }
        .info-box { border: 1px solid #000; padding: 10px; }
        .row { margin-bottom: 5px; }
        .label { display: inline-block; width: 160px; font-weight: bold; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>

<div class="header">
    <h3>Nota Pengambilan Barang</h3>
</div>

<div class="info-box">
    <div class="bold">ReuseMart</div>
    <div>Jl. Green Eco Park No. 456 Yogyakarta</div>
    <hr>

    <div class="row"><span class="label">No Nota</span>: {{ $transaksi->no_nota }}</div>
    <div class="row"><span class="label">Tanggal Pesan</span>: {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y H:i') }}</div>
    <div class="row"><span class="label">Tanggal Lunas</span>: {{ \Carbon\Carbon::parse($tanggalLunas)->format('d/m/Y H:i') }}</div>
    <div class="row"><span class="label">Tanggal Ambil</span>: 
        {{ optional($barang->jadwalPengambilan)->jadwal_pengambilan ? \Carbon\Carbon::parse($barang->jadwalPengambilan->jadwal_pengambilan)->format('d/m/Y') : '-' }}
    </div>

    <div class="row" style="margin-top: 10px;">
        <span class="bold">Pembeli :</span> {{ $pembeli->email }} / {{ $pembeli->username }}
    </div>
    <div class="row">{{ $alamat }}</div>

    <div class="row"><span class="label">Delivery</span>: - (diambil sendiri)</div>

    <hr>

    @foreach ($transaksi->detail as $detail)
        <div class="row" style="display: flex; justify-content: space-between;">
            <span>{{ $detail->barang->nama }}</span>
            <span>Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
        </div>
    @endforeach

    @php
        $hargaBarang = $transaksi->detail->sum('subtotal');
        $ongkir = 0;
        $total = $hargaBarang;
        $poin = $transaksi->poin_ditukar ?? 0;
        $totalSetelahPotongan = $total - ($poin * 10000);
    @endphp

    <hr>
    <div class="row"><span class="label">Total Harga Barang</span>: Rp{{ number_format($hargaBarang, 0, ',', '.') }}</div>
    <div class="row"><span class="label">Ongkos Kirim</span>: Rp0</div>
    <div class="row"><span class="label">Potongan ({{ $poin }} poin)</span>: Rp{{ number_format($poin * 10000, 0, ',', '.') }}</div>
    <div class="row bold"><span class="label">Total Setelah Potongan</span>: Rp{{ number_format($totalSetelahPotongan, 0, ',', '.') }}</div>

    <br><br>
    <div class="row"><span class="label">Poin dari transaksi ini</span>: {{ $poinTransaksi }} poin</div>
    <div class="row"><span class="label">Total poin pembeli saat ini</span>: {{ $totalPoinPembeli }} poin</div>

    <br><br>
    <div class="row"><span class="label">QC oleh</span>: P{{ $qc->id ?? '-' }} – {{ $qc->nama_lengkap ?? '-' }}</div>

    <br><br>
    <div class="bold">Diterima oleh:</div>
    <br><br><br><br>
    <div>(.........................................)</div>
    <div>Tanggal: .......................</div>
</div>

</body>
</html>
