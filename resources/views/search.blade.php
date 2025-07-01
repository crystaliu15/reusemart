@extends('layouts.app')

@section('content')
<section class="p-6">
    <h1 class="text-2xl font-bold mb-4">Hasil pencarian untuk: "{{ $keyword }}"</h1>

    @if($barangs->count())
    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-6">
        @foreach($barangs as $barang)
        <a href="{{ route('barang.show', $barang->id) }}" class="bg-white rounded shadow hover:shadow-lg transition p-4">
            <img src="{{ asset('images/barang/' . $barang->id . '/' . $barang->thumbnail) }}"
                 class="w-full h-40 object-cover rounded mb-2">
            <h3 class="text-sm font-semibold">{{ $barang->nama }}</h3>
            <p class="text-orange-600 font-bold text-sm">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
        </a>
        @endforeach
    </div>
    @else
        <p class="text-gray-500">Tidak ada produk ditemukan untuk kata kunci tersebut.</p>
    @endif
</section>
@endsection
