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
        .bold { font-weight: bold; }
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

    <div class="row"><span class="label">No Nota</span>: {{ $kodeNota }}</div>
    <div class="row"><span class="label">Tanggal penitipan</span>: {{ $tanggal->format('d/m/Y H:i:s') }}</div>
    <div class="row"><span class="label">Masa penitipan sampai</span>: {{ $tanggal->copy()->addMonth()->format('d/m/Y') }}</div>

    <div class="row" style="margin-top: 10px;">
        <span class="bold">Penitip :</span> T{{ $penitip->id }}/ {{ $penitip->username }}
    </div>
    <div class="row">{{ $penitip->alamat }}</div>

    <hr>

    {{-- BARANG-BARANG --}}
    @foreach ($barangs as $barang)
        <div class="row" style="display: flex; justify-content: space-between;">
            <span>{{ $barang->nama }}</span>
            <span>Rp{{ number_format($barang->harga, 0, ',', '.') }}</span>
        </div>
        <div class="row">Berat barang: {{ $barang->berat }} kg</div>
    @endforeach

    @php
        $checker = $barangs->first()->qualityChecker;
    @endphp

    <br><br>
    <div class="row bold">Diterima dan QC oleh:</div>
    <br>
    <div>
        P{{ $checker->id ?? '-' }} – {{ $checker->nama_lengkap ?? '-' }}
    </div>
</div>

</body>
</html>
