@extends('layouts.app-cs')

@section('content')
<div class="max-w-6xl mx-auto mt-8 bg-white p-6 rounded shadow">
    <div class="mb-4">
        <a href="{{ url('/cs/dashboard') }}"
        class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-2xl font-bold mb-4">Barang dari Transaksi yang Sedang Diproses</h2>

    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-800 p-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($transaksis->isEmpty())
        <p class="text-gray-600">Tidak ada transaksi yang sedang diproses.</p>
    @else
        <div class="space-y-6">
            @foreach($transaksis as $transaksi)
                <div class="border rounded p-4 shadow-sm">
                    <div class="flex justify-between items-center">
                        <div>
                            <p><strong>ID Transaksi:</strong> #{{ $transaksi->id }}</p>
                            <p><strong>Nama Pembeli:</strong> {{ $transaksi->pembeli->username }}</p>
                            <p><strong>Total:</strong> Rp {{ number_format($transaksi->total, 0, ',', '.') }}</p>
                        </div>
                        <a href="{{ route('cs.barang.diproses.detail', $transaksi->id) }}"
                           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            🔍 Lihat Detail
                        </a>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-4">
                        @foreach($transaksi->detail as $item)
                            @if($item->barang)
                            <div class="border p-2 bg-gray-50 rounded">
                                <img src="{{ asset('images/barang/' . $item->barang->id . '/' . $item->barang->thumbnail) }}"
                                     class="w-full h-24 object-cover rounded mb-1">
                                <p class="text-sm font-semibold">{{ $item->barang->nama }}</p>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
