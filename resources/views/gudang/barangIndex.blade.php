@extends('layouts.app-gudang')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white rounded shadow mt-6">
    <div class="mb-4">
        <a href="{{ url('/gudang/dashboard') }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-2xl font-bold mb-4">Daftar Barang Titipan</h2>
    <form method="GET" action="{{ route('gudang.barang.index') }}" class="mb-6 flex flex-col sm:flex-row items-center gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari nama barang..."
            class="w-full sm:w-1/2 px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500">

        <button type="submit"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 shadow">
            Cari
        </button>
    </form>

    @if ($barangs->isEmpty())
        <p class="text-gray-600">Belum ada barang yang Anda quality check.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($barangs as $barang)
            <div class="bg-white rounded shadow hover:shadow-md transition p-4 flex gap-4 items-center">
                <img src="{{ asset('images/barang/' . $barang->id . '/' . $barang->thumbnail) }}"
                    alt="Thumbnail {{ $barang->nama }}"
                    class="w-24 h-24 object-cover rounded border" onerror="this.src='{{ asset('images/not-found.jpg') }}'">

                <div class="flex-1">
                    <h3 class="text-lg font-bold mb-1">{{ $barang->nama }}</h3>
                    <p class="text-sm text-gray-700 mb-1">Harga: Rp{{ number_format($barang->harga, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mb-2">Kategori: {{ $barang->kategori->nama ?? '-' }}</p>

                    <div class="flex flex-col gap-1">
                        <a href="{{ route('gudang.barang.show', $barang->id) }}"
                            class="text-sm text-blue-600 underline hover:text-blue-800">Lihat Detail</a>

                        @if ($barang->status_pengambilan == 1 && $barang->diambil_kembali == 0)
                            <form action="{{ route('gudang.barang.simpanPengambilan', $barang->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-sm text-red-600 underline hover:text-red-800">Catat Pengambilan</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    @endif
</div>
@endsection