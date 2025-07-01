@extends('layouts.app-penitip')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 mt-10 rounded shadow space-y-6">
    <h2 class="text-2xl font-bold mb-4">Detail Riwayat Barang</h2>

    {{-- Gambar utama --}}
<div class="flex-1">
    <img id="mainImage"
         src="{{ asset('images/barang/' . $barang->id . '/' . $barang->thumbnail) }}"
         class="w-full rounded shadow mb-4 object-contain max-h-[500px]">

    {{-- Galeri kecil --}}
    <div class="grid grid-cols-4 sm:grid-cols-5 gap-2">
        {{-- Thumbnail utama --}}
        <img onclick="changeMainImage(this)"
             src="{{ asset('images/barang/' . $barang->id . '/' . $barang->thumbnail) }}"
             class="h-24 w-full object-cover cursor-pointer border-2 border-green-500 rounded">

        {{-- Foto lainnya --}}
        @if($barang->foto_lain)
            @foreach(json_decode($barang->foto_lain) as $foto)
                <img onclick="changeMainImage(this)"
                     src="{{ asset('images/barang/' . $barang->id . '/' . $foto) }}"
                     class="h-24 w-full object-cover cursor-pointer rounded hover:ring-2 ring-green-400">
            @endforeach
        @endif
    </div>
</div>

    {{-- Informasi Barang --}}
    <div>
        <h3 class="text-xl font-semibold mt-6">{{ $barang->nama }}</h3>
        <p class="text-gray-700 mt-2">Harga Jual: <span class="font-medium">Rp{{ number_format($barang->harga, 0, ',', '.') }}</span></p>
    </div>

    {{-- Info Transaksi --}}
    <div class="mt-6">
        <h4 class="text-lg font-semibold mb-2">Informasi Transaksi</h4>
        <p>Nama Pembeli: <strong>{{ $pembeli ? $pembeli->username : 'Optimum' }}</strong></p>
        <p>Poin yang Diperoleh Pembeli: <strong>{{ $poinPembeli }}</strong></p>
        <p>Komisi Penitip: <strong>Rp{{ number_format($komisi, 0, ',', '.') }}</strong></p>
    </div>

    <div class="mt-6">
        <a href="{{ route('penitip.dashboard') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
            ⬅️ Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection

<script>
    function changeMainImage(imgElement) {
        const mainImage = document.getElementById('mainImage');
        mainImage.src = imgElement.src;
    }
</script>
