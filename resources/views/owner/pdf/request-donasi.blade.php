<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; vertical-align: top; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <strong>ReUse Mart</strong><br>
    Jl. Green Eco Park No. 456 Yogyakarta<br><br>

    <strong>LAPORAN REQUEST DONASI</strong><br>
    Tanggal cetak: {{ $tanggalCetak }}<br><br>

    <table>
        <thead>
            <tr>
                <th>ID Organisasi</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Request</th>
            </tr>
        </thead>
        <tbody>
    @forelse ($requestDonasi as $req)
        <tr>
            <td>
                @if ($req->organisasi && $req->organisasi->id)
                    {{ 'ORG' . str_pad($req->organisasi->id, 2, '0', STR_PAD_LEFT) }}
                @else
                    -
                @endif
            </td>
            <td>{{ $req->organisasi->username ?? '-' }}</td>
            <td>{{ $req->organisasi->alamat ?? '-' }}</td>
            <td>{{ $req->jenis_barang }} - {{ $req->alasan }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="4" style="text-align: center;">Tidak ada data request donasi.</td>
        </tr>
    @endforelse
</tbody>
    </table>
</body>
</html>