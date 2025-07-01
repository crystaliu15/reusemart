@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-8 bg-white p-6 rounded shadow">
    <div class="mb-4">
        <a href="{{ url('/pembeli/riwayat') }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>

    <h2 class="text-2xl font-bold mb-6">Detail Transaksi</h2>

    <!-- Info Transaksi -->
    <div class="mb-4 space-y-2">
        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d M Y, H:i') }}</p>
        <p><strong>Status:</strong>
            <span class="inline-block px-2 py-1 rounded text-white
                @if($transaksi->status === 'selesai') bg-green-600
                @elseif($transaksi->status === 'diproses') bg-blue-600
                @elseif($transaksi->status === 'menunggu konfirmasi') bg-yellow-600
                @elseif($transaksi->status === 'menunggu pembayaran') bg-orange-600
                @else bg-red-600
                @endif">
                {{ ucfirst($transaksi->status) }}
            </span>
        </p>
        <p><strong>Metode:</strong> {{ ucfirst($transaksi->tipe_pengiriman) }}</p>
        @if($transaksi->tipe_pengiriman === 'kirim' && $transaksi->alamat)
            <p><strong>Alamat Pengiriman:</strong><br>{{ $transaksi->alamat->alamat }}</p>
        @endif
    </div>

    <!-- Barang dan Rating -->
    @foreach($transaksi->detail as $item)
        @if($item->barang)
            @php
                $barang = $item->barang;
                $sudahRating = $ratings[$barang->id] ?? null;
            @endphp

            <div class="mb-6 border rounded p-4 bg-gray-50">
                <div class="flex flex-col sm:flex-row gap-6">
                    <!-- Thumbnail Barang -->
                    <div class="sm:w-56 w-full">
                        <img src="{{ asset('images/barang/' . $barang->id . '/' . $barang->thumbnail) }}"
                             alt="{{ $barang->nama }}"
                             class="w-full h-auto aspect-[4/3] object-cover rounded"
                             onerror="this.src='{{ asset('images/not-found.jpg') }}'">
                    </div>

                    <!-- Info + Rating -->
                    <div class="flex-1">
                        <p class="text-lg font-semibold">{{ $barang->nama }}</p>
                        <p class="text-sm text-gray-600">Harga: Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                        <p class="text-sm">Subtotal: Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>

                        @if($transaksi->status === 'selesai')
                            <div class="mt-4">
                                @if($sudahRating)
                                    <p class="text-green-600 font-semibold mb-2">⭐ Anda memberikan rating:</p>
                                    <div class="flex text-yellow-400 text-3xl space-x-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $sudahRating->rating)
                                                <span>&#9733;</span>
                                            @else
                                                <span class="text-gray-300">&#9733;</span>
                                            @endif
                                        @endfor
                                    </div>
                                @else
                                    <form action="{{ route('pembeli.rating.submit', $barang->id) }}" method="POST" class="mt-2">
                                        @csrf
                                        <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
                                        <input type="hidden" name="penitip_id" value="{{ $barang->penitip_id }}">
                                        <input type="hidden" name="rating" id="ratingInput-{{ $barang->id }}">

                                        <label class="block font-semibold text-base mb-2">Beri Rating:</label>
                                        <div class="flex items-center space-x-2 text-yellow-400 text-4xl cursor-pointer" id="ratingStars-{{ $barang->id }}">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span data-value="{{ $i }}">&#9733;</span>
                                            @endfor
                                        </div>

                                        <button type="submit" class="mt-3 bg-blue-600 text-white text-base px-4 py-2 rounded hover:bg-blue-700">
                                            Kirim
                                        </button>
                                    </form>

                                    <script>
                                        const stars{{ $barang->id }} = document.getElementById('ratingStars-{{ $barang->id }}');
                                        const input{{ $barang->id }} = document.getElementById('ratingInput-{{ $barang->id }}');
                                        if (stars{{ $barang->id }}) {
                                            stars{{ $barang->id }}.addEventListener('click', function(e) {
                                                if (e.target.tagName === 'SPAN') {
                                                    const selected = parseInt(e.target.getAttribute('data-value'));
                                                    input{{ $barang->id }}.value = selected;
                                                    const spans = stars{{ $barang->id }}.querySelectorAll('span');
                                                    spans.forEach((star, idx) => {
                                                        star.style.color = (idx < selected) ? '#facc15' : '#d1d5db';
                                                    });
                                                }
                                            });
                                        }
                                    </script>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <!-- Ringkasan Total -->
    <div class="border-t pt-4 text-right space-y-1">
        <p class="text-sm">Poin Ditukar: {{ $transaksi->poin_ditukar }}</p>
        <p class="text-sm">Potongan: Rp {{ number_format($transaksi->potongan, 0, ',', '.') }}</p>
        <p class="text-sm font-bold">Total Dibayar: Rp {{ number_format($transaksi->total, 0, ',', '.') }}</p>
    </div>

    <!-- Cetak Nota -->
    <div class="mt-6 text-right">
        <a href="{{ route('pembeli.transaksi.cetakNota', $transaksi->id) }}"
           target="_blank"
           class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Cetak Nota
        </a>
    </div>
</div>
@endsection