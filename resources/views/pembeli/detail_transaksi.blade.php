@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded shadow space-y-6">
    <div class="mb-4">
        <a href="{{ route('pembeli.profil') }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali ke Profil
        </a>
    </div>

    <h2 class="text-xl font-bold mb-4">Detail Pembelian Barang</h2>

    <!-- Status -->
    <div class="inline-block px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full mb-4">
        Status: <strong>Selesai</strong>
    </div>

    <!-- Detail Barang -->
    <div class="flex flex-col md:flex-row gap-6">
        <img src="{{ asset('images/barang/' . $barang->id . '/' . $barang->thumbnail) }}"
             class="w-full md:w-1/3 h-48 object-cover rounded shadow">

        <div class="flex-1 space-y-2">
            <h3 class="text-lg font-semibold">{{ $barang->nama }}</h3>
            <p class="text-sm text-gray-700">Harga: Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
            <p class="text-sm text-gray-700">Jumlah: {{ $jumlah }}</p>
            <p class="text-sm text-gray-700">Subtotal: <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong></p>
        </div>
    </div>

    <!-- Informasi Pengiriman -->
    <div class="mt-6 border-t pt-4">
        <h4 class="font-semibold mb-2">Informasi Pengiriman</h4>
        <p class="text-sm text-gray-700">Dikirim pada: {{ \Carbon\Carbon::parse($tanggalKirim)->translatedFormat('d F Y') }}</p>
        <p class="text-sm text-gray-700">Sampai tujuan: {{ \Carbon\Carbon::parse($tanggalSampai)->translatedFormat('d F Y') }}</p>
        <p class="text-sm text-gray-700">Dikirim ke alamat: {{ $alamatTujuan }}</p>
    </div>
</div>
@endsection
