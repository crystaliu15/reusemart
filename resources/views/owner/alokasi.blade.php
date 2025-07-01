@extends('layouts.app-owner')

@section('content')
<div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded shadow space-y-6">
    <div class="mb-4">
            <a href="{{ url('/owner/request-donasi') }}"
            class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
                ← Kembali
            </a>
        </div>

    <h2 class="text-xl font-bold">Alokasikan Barang</h2>

    <p class="text-gray-700">Permintaan dari: <strong>{{ $requestDonasi->organisasi->nama }}</strong></p>
    <p class="text-gray-700 mb-4">Jenis Barang: {{ $requestDonasi->jenis_barang }}</p>
    <p class="text-gray-700 mb-6">Alasan: {{ $requestDonasi->alasan }}</p>

    <form action="{{ route('owner.alokasi.store', $requestDonasi->id) }}" method="POST">
        @csrf

        <!-- Pilih Barang -->
        <label for="barang_id" class="block mb-2 font-medium">Pilih Barang yang Akan Dialokasikan:</label>
        <select name="barang_id" id="barang_id" required class="w-full border rounded px-3 py-2 mb-4">
            <option value="">-- Pilih Barang --</option>
            @foreach($barangs as $barang)
                <option value="{{ $barang->id }}">{{ $barang->nama }} - {{ $barang->kategori_id }}</option>
            @endforeach
        </select>

        <!-- Nama Penerima -->
        <label for="nama_penerima" class="block mb-2 font-medium">Nama Penerima (Orang di Organisasi)</label>
        <input type="text" name="nama_penerima" id="nama_penerima" class="w-full border rounded px-3 py-2 mb-6"required>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Alokasikan Barang
        </button>
    </form>

</div>
@endsection
