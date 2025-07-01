@extends('layouts.app-owner')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow p-8 rounded mt-10">
    <div class="mb-4">
            <a href="{{ url('/owner/histori-donasi') }}"
            class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
                ← Kembali
            </a>
        </div>
    <h2 class="text-xl font-bold mb-6">Edit Donasi Barang</h2>

    <form method="POST" action="{{ route('owner.donasi.update', $donasi->id) }}">
        @csrf
        @method('PUT')

        <!-- Nama Organisasi - Hanya tampil -->
        <div class="mb-4">
            <label class="block font-medium">Organisasi Penerima</label>
            <p class="text-gray-700">{{ $donasi->organisasi->username }}</p>
        </div>

        <!-- Nama Penerima -->
        <div class="mb-4">
            <label for="nama_penerima" class="block font-medium">Nama Penerima (Orang)</label>
            <input type="text" name="nama_penerima" value="{{ old('nama_penerima', $donasi->nama_penerima) }}"
                   class="w-full border rounded px-3 py-2" required>
        </div>

        <!-- Tanggal Donasi -->
        <div class="mb-4">
            <label for="tanggal_donasi" class="block font-medium">Tanggal Donasi</label>
            <input type="date" name="tanggal_donasi" value="{{ $donasi->tanggal_donasi }}"
                   class="w-full border rounded px-3 py-2" required>
        </div>

        <!-- Status Barang -->
        <div class="mb-4">
            <label for="status_barang" class="block font-medium">Status Barang</label>
            <select name="status_barang" class="w-full border rounded px-3 py-2">
                <option value="didonasikan" {{ $barang?->status == 'didonasikan' ? 'selected' : '' }}>Didonasikan</option>
                <option value="didonasikan" {{ $barang?->status == 'didonasikan' ? 'selected' : '' }}>donasi</option>
                <option value="selesai" {{ $barang?->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="dibatalkan" {{ $barang?->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
