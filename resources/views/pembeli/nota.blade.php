<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Transaksi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #000; padding: 6px; text-align: left; }
        .header { text-align: center; font-weight: bold; margin-bottom: 20px; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>ReuseMart</h2>
        <p>Nota Transaksi</p>
    </div>

    <p><strong>ID Transaksi:</strong> #{{ $transaksi->id }}</p>
    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d M Y, H:i') }}</p>
    <p><strong>Status:</strong> {{ ucfirst($transaksi->status) }}</p>
    <p><strong>Metode:</strong> {{ ucfirst($transaksi->tipe_pengiriman) }}</p>

    @if($transaksi->tipe_pengiriman === 'kirim' && $transaksi->alamat)
        <p><strong>Alamat Pengiriman:</strong> {{ $transaksi->alamat->alamat }}</p>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Barang</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi->detail as $item)
                <tr>
                    <td>{{ $item->barang->nama }}</td>
                    <td>Rp {{ number_format($item->barang->harga, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <table class="table">
        <tr>
            <td><strong>Poin Ditukar:</strong></td>
            <td class="right">{{ $transaksi->poin_ditukar }}</td>
        </tr>
        <tr>
            <td><strong>Potongan:</strong></td>
            <td class="right">Rp {{ number_format($transaksi->potongan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Total Bayar:</strong></td>
            <td class="right">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
        </tr>
    </table>
</body>
</html>
