@extends('layouts.app-cs')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white rounded shadow mt-6">
    <h2 class="text-xl font-bold mb-4">Barang milik: {{ $penitip->username }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($barangs as $barang)
            <div class="border p-4 rounded shadow">
                <h3 class="font-bold">{{ $barang->nama }}</h3>
                <p>Harga: Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                <p>Status: {{ $barang->terjual ? 'Sudah Terjual' : 'Tersedia' }}</p>
            </div>
        @empty
            <p class="text-gray-500 italic">Penitip ini belum memiliki barang.</p>
        @endforelse
    </div>
</div>
@endsection
