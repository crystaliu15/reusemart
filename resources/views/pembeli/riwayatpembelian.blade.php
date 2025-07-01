@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-8 bg-white p-6 rounded shadow">
    <div class="mb-4">
        <a href="{{ url('/profil')}}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-2xl font-bold mb-6">Riwayat Pembelian</h2>

    @if($transaksis->isEmpty())
        <p class="text-gray-600">Kamu belum memiliki riwayat transaksi.</p>
    @else
        @foreach($transaksis as $transaksi)
            <div class="mb-6 border border-gray-200 rounded shadow-sm p-4">
                <div class="flex justify-between items-center mb-2">
                    <div>
                        <p class="text-sm text-gray-600">Tanggal: {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d M Y, H:i') }}</p>
                        <p class="text-sm text-gray-600">Status: 
                            <span class="inline-block px-2 py-1 rounded text-white
                                @if($transaksi->status === 'selesai') bg-green-600
                                @elseif($transaksi->status === 'diproses') bg-blue-600
                                @elseif($transaksi->status === 'menunggu konfirmasi') bg-yellow-600
                                @elseif($transaksi->status === 'menunggu pembayaran') bg-orange-600
                                @else bg-red-600 @endif">
                                {{ ucfirst($transaksi->status) }}
                            </span>
                        </p>
                        <p class="text-sm text-gray-600">Total: <strong>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</strong></p>
                    </div>
                    <div>
                        <a href="{{ route('pembeli.transaksi.detail', $transaksi->id) }}">
                            <div class="text-sm bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                Lihat Detail
                            </div>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-3">
                    @foreach($transaksi->detail as $item)
                        @if($item->barang)
                        <div class="border rounded p-2 bg-gray-50">
                            <img src="{{ asset('images/barang/' . $item->barang->id . '/' . $item->barang->thumbnail) }}"
                                 alt="Foto Barang" class="w-full h-32 object-cover rounded mb-2">
                            <p class="text-sm font-semibold">{{ $item->barang->nama }}</p>
                            <p class="text-xs text-gray-600">Rp {{ number_format($item->barang->harga, 0, ',', '.') }}</p>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
