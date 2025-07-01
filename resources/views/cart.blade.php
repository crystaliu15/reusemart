@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-6 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Keranjang Belanja Anda</h2>

    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-800 p-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($items->isEmpty())
        <p class="text-gray-600">Keranjang Anda kosong.</p>
    @else
        @php $total = 0; @endphp

        <div class="space-y-4">
            @foreach($items as $item)
                @php
                    $barang = $item->barang;
                    $harga = $barang->harga ?? 0;
                    $total += $harga;
                @endphp

                <div class="flex bg-gray-50 rounded shadow p-4">
                    <!-- Thumbnail -->
                    <div class="w-40 h-40 flex-shrink-0">
                        <img src="{{ asset('images/barang/' . $barang->id . '/' . $barang->thumbnail) }}"
                        class="w-full h-full object-cover rounded mb-2">
                    </div>

                    <!-- Info -->
                    <div class="flex-1 ml-4">
                        <h3 class="text-lg font-semibold">{{ $barang->nama }}</h3>
                        <p class="text-gray-600">Harga: Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>

                        <form action="{{ route('keranjang.hapus', $item->id) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline text-sm"
                                    onclick="return confirm('Yakin ingin menghapus barang ini dari keranjang?')">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Tombol Bayar -->
        <div class="mt-4 text-right">
            <a href="{{ route('pembeli.pembayaran.form') }}"
            class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Bayar Sekarang
            </a>
        </div>

        <!-- Total -->
        <div class="mt-6 text-right">
            <h3 class="text-xl font-bold">Total: Rp {{ number_format($total, 0, ',', '.') }}</h3>
        </div>
    @endif
</div>
@endsection
