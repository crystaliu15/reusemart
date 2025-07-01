@extends('layouts.app-cs')

@section('content')
<div class="max-w-5xl mx-auto mt-8 bg-white p-6 rounded shadow">
    <div class="mb-4">
        <a href="{{ url('/cs/barang-diproses') }}"
        class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-2xl font-bold mb-4">Detail Transaksi #{{ $transaksi->id }}</h2>

    <!-- Informasi Pembeli & Transaksi -->
    <div class="mb-4">
        <p><strong>Nama Pembeli:</strong> {{ $transaksi->pembeli->username }}</p>
        <p><strong>Total:</strong> Rp {{ number_format($transaksi->total, 0, ',', '.') }}</p>
        <p><strong>Status:</strong> <span class="text-yellow-700 font-semibold">{{ ucfirst($transaksi->status) }}</span></p>
    </div>

    <!-- Daftar Barang -->
    <h3 class="text-lg font-semibold mb-2">Barang dalam Transaksi:</h3>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-6">
        @foreach($transaksi->detail as $item)
            @if($item->barang)
            <div class="border p-2 bg-gray-50 rounded">
                <img src="{{ asset('images/barang/' . $item->barang->id . '/' . $item->barang->thumbnail) }}"
                     class="w-full h-24 object-cover rounded mb-2">
                <p class="text-sm font-semibold">{{ $item->barang->nama }}</p>
                <p class="text-xs text-gray-600">Rp {{ number_format($item->barang->harga, 0, ',', '.') }}</p>
            </div>
            @endif
        @endforeach
    </div>

    <!-- Aksi -->
    <form method="POST" action="{{ route('cs.barang.diproses.selesaikan', $transaksi->id) }}"
          onsubmit="return confirm('Yakin ingin menyelesaikan transaksi ini?')">
        @csrf
        <div class="text-right">
            <button class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700">
                ✅ Tandai Transaksi Selesai
            </button>
        </div>
    </form>
</div>
@endsection
