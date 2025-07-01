<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Penitipan Barang</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 20px; }
        .header { text-align: center; font-size: 14px; margin-bottom: 10px; }
        .info-box {
            border: 1px solid #000;
            padding: 10px;
        }
        .row { margin-bottom: 5px; }
        .label { display: inline-block; width: 160px; font-weight: bold; }
        .right-align { float: right; }
        .bold { font-weight: bold; }
        pre { white-space: pre-line; margin: 0; }
    </style>
</head>
<body>

<div class="header">
    <h3>Nota Penitipan Barang</h3>
</div>

<div class="info-box">
    <div class="bold">ReUse Mart</div>
    <div>Jl. Green Eco Park No. 456 Yogyakarta</div>
    <hr>

    @php
        $created = $barang->created_at ?? now();
        $transaksiId = $barang->transaksi_id ?? $barang->id;
        $kodeTransaksi = $created->format('y.m') . '.' . $transaksiId;
    @endphp

    <div class="row"><span class="label">No Nota</span>: {{ $kodeTransaksi }}</div>
    <div class="row"><span class="label">Tanggal penitipan</span>: {{ $barang->created_at->format('d/m/Y H:i:s') }}</div>
    <div class="row"><span class="label">Masa penitipan sampai</span>: {{ $barang->batas_waktu_titip->format('d/m/Y') }}</div>

    {{-- PENITIP --}}
    @php
        $penitip = $barang->penitip;
    @endphp

    <div class="row" style="margin-top: 10px;">
        <span class="bold">Penitip :</span> T{{ $penitip->id }}/ {{ $penitip->username }}
    </div>
    <div class="row">{{ $penitip->alamat }}</div>

    <hr>

    {{-- BARANG --}}
    <div class="row" style="display: flex; justify-content: space-between;">
        <span>{{ $barang->nama }}</span>
        <span>Rp{{ number_format($barang->harga, 0, ',', '.') }}</span>
    </div>

    @if ($barang->garansi_berlaku_hingga)
        <div class="row">Garansi aktif hingga: {{ \Carbon\Carbon::parse($barang->garansi_berlaku_hingga)->format('d/m/Y') }}</div>
    @endif

    @if ($barang->berat)
        <div class="row">Berat barang: {{ $barang->berat }} kg</div>
    @endif

    <br><br>
    <div class="row bold">Diterima dan QC oleh:</div>
    <br>
    <div>
        P{{ $barang->qualityChecker->id ?? '-' }} – {{ $barang->qualityChecker->nama_lengkap ?? '-' }}
    </div>
</div>

</body>
</html>
