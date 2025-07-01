@extends('layouts.app-gudang')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white shadow rounded mt-6">
    <div class="mb-4">
        <a href="{{ route('gudang.barang.transaksi') }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-xl font-bold mb-4">Jadwalkan Pengambilan</h2>

    @if(session('error'))
        <div class="text-red-600 mb-2">{{ session('error') }}</div>
    @endif

    {{-- Tampilkan info jadwal yang sudah disimpan --}}
    @if(isset($barang->jadwalPengambilan) && $barang->jadwalPengambilan)
        <div class="bg-gray-100 p-4 rounded mb-4">
            <h3 class="font-semibold text-lg mb-2 text-green-700">Jadwal Tersimpan</h3>
            <p><strong>Tanggal & Waktu:</strong> {{ \Carbon\Carbon::parse($barang->jadwalPengambilan->jadwal_pengambilan)->translatedFormat('d F Y, H:i') }}</p>
            <p><strong>Pembeli:</strong> {{ $barang->transaksi->pembeli->username ?? '-' }}</p>
        </div>
    @endif

    <div class="bg-white rounded mb-4">
        <h3 class="font-semibold text-lg mb-2">Informasi Barang</h3>
        <p><strong>Nama:</strong> {{ $barang->nama }}</p>
        <p><strong>Deskripsi:</strong> {{ $barang->deskripsi ?? '-' }}</p>
        <p><strong>Pembeli:</strong> {{ $barang->transaksi->pembeli->username ?? '-' }}</p>
    </div>

    <form method="POST" action="{{ route('gudang.jadwal-ambil.simpan', $barang->id) }}">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold mb-1">Jadwal Pengambilan:</label>
            <input type="datetime-local" 
                   name="jadwal_pengambilan" 
                   id="jadwal_pengambilan"
                   class="border w-full p-2 rounded @error('jadwal_pengambilan') border-red-500 @enderror" 
                   required
                   value="{{ old('jadwal_pengambilan', optional($barang->jadwalPengambilan)->jadwal_pengambilan ? \Carbon\Carbon::parse($barang->jadwalPengambilan->jadwal_pengambilan)->format('Y-m-d\TH:i') : '') }}">
            
            @error('jadwal_pengambilan')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Simpan Jadwal</button>
            <a href="{{ route('gudang.barang.transaksi') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
        </div>
    </form>
</div>
@endsection