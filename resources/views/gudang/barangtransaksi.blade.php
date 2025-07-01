@extends('layouts.app-gudang')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white rounded shadow mt-6">
    <div class="mb-4">
        <a href="{{ url('/gudang/dashboard') }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-2xl font-bold mb-4">Daftar Transaksi Pengiriman dan Pengambilan</h2>

    <h3 class="text-xl font-semibold mb-4">Barang yang Harus Dikirim</h3>
    @if($barangKirim->isEmpty())
        <p>Tidak ada barang yang harus dikirim.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($barangKirim as $barang)
                <div class="bg-white rounded shadow hover:shadow-md transition p-4 flex gap-4 items-center">
                    <img src="{{ asset('images/barang/' . $barang->id . '/' . $barang->thumbnail) }}"
                        alt="Thumbnail {{ $barang->nama }}"
                        class="w-24 h-24 object-cover rounded border" onerror="this.src='{{ asset('images/not-found.jpg') }}'">
                    
                    <div class="flex-1">
                        <h3 class="text-lg font-bold mb-1">
                            <a href="{{ route('gudang.barang.show', $barang->id) }}"
                            class="text-black hover:text-blue-600 hover:underline transition">
                                {{ $barang->nama }}
                            </a>
                        </h3>
                        <p class="text-sm text-gray-700 mb-1">Harga: Rp{{ number_format($barang->harga, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mb-2">Kategori: {{ $barang->kategori->nama ?? '-' }}</p>
                        <p class="text-sm text-gray-700 mb-3">Status: Dikirim</p>
                        
                        <div class="flex flex-col gap-1 mt-2">
                            <a href="{{ route('gudang.barang.jadwal', $barang->id) }}"
                                class="text-sm text-blue-600 underline hover:text-blue-800">Kirim</a>

                            @if($barang->status_jadwal == 'dikirim')
                                <a href="{{ route('gudang.barang.cetakNota', $barang->id) }}"
                                    class="text-sm text-red-600 underline hover:text-red-800" target="_blank">Cetak Nota</a>
                            @else
                                <span class="text-xs text-gray-400 italic">Jadwalkan dulu untuk mencetak nota</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <h3 class="text-xl font-semibold mb-4">Barang yang Harus Diambil</h3>
    @if($barangAmbil->isEmpty())
        <p>Tidak ada barang yang harus diambil.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($barangAmbil as $barang)
                <div class="bg-white rounded shadow hover:shadow-md transition p-4 flex gap-4 items-center">
                    <img src="{{ asset('images/barang/' . $barang->id . '/' . $barang->thumbnail) }}"
                        alt="Thumbnail {{ $barang->nama }}"
                        class="w-24 h-24 object-cover rounded border" onerror="this.src='{{ asset('images/not-found.jpg') }}'">
                    
                    <div class="flex-1">
                        <h3 class="text-lg font-bold mb-1">
                            <a href="{{ route('gudang.barang.show', $barang->id) }}"
                            class="text-black hover:text-blue-600 hover:underline transition">
                                {{ $barang->nama }}
                            </a>
                        </h3>
                        <p class="text-sm text-gray-700 mb-1">Harga: Rp{{ number_format($barang->harga, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mb-2">Kategori: {{ $barang->kategori->nama ?? '-' }}</p>
                        <p class="text-sm text-gray-700 mb-3">Status: Diambil</p>
                        
                        <div class="flex flex-col gap-1 mt-2">
                            <a href="{{ route('gudang.jadwal-ambil.form', $barang->id) }}"
                                class="text-sm text-blue-600 underline hover:text-blue-800">Ambil</a>

                            @if($barang->status_jadwal == 'diambil')
                                <a href="{{ route('gudang.barang.notaPengambilan', $barang->id) }}"
                                    class="text-sm text-red-600 underline hover:text-red-800" target="_blank">Cetak Nota</a>

                                <form method="POST" action="{{ route('gudang.barang.konfirmasi', $barang->id) }}">
                                    @csrf
                                    <button class="text-sm bg-green-600 text-white px-3 py-1 mt-2 rounded hover:bg-green-700"
                                        onclick="return confirm('Konfirmasi bahwa barang sudah diambil oleh pembeli?')">
                                        Konfirmasi Pengambilan
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400 italic">Ambil dulu untuk cetak nota & konfirmasi</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
