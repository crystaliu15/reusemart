@extends('layouts.app')

@section('content')

@if(session('gagal_pembayaran'))
<div id="modalGagal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded shadow-lg text-center">
        <h2 class="text-xl font-bold text-red-600 mb-2">Pembayaran Gagal</h2>
        <p class="mb-4 text-sm text-gray-700">Waktu pembayaran telah habis. Silakan lakukan pemesanan ulang.</p>
        <button onclick="document.getElementById('modalGagal').remove()"
                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Tutup</button>
    </div>
</div>
@endif

<!-- Navigasi kategori teks -->
<div class="bg-orange-100 text-sm px-6 py-2 flex justify-center space-x-6 overflow-x-auto sticky top-[96px] z-30">
    @php
        $tags = ['Kipas Angin', 'Tas Selempang', 'Headset Bluetooth', 'Kaos Pria', 'Kacamata', 'Daster Kekinian', 'Meja'];
    @endphp
    @foreach ($tags as $tag)
        <a href="{{ route('search', ['search' => $tag]) }}" class="hover:underline text-green-800 font-medium">
            {{ $tag }}
        </a>
    @endforeach
</div>

<!-- Banner utama -->
<div class="flex flex-col md:flex-row gap-1 px-20 pt-2 pb-4">
    <div class="md:w-3/4 flex justify-center">
        <img src="{{ asset('images/banner-besar.jpg') }}" alt="Banner" class="w-[70%] rounded shadow" />
    </div>
    <div class="md:w-1/3 flex flex-col items-center gap-6">
        <img src="{{ asset('images/banner-kecil-1.jpg') }}" alt="Promo 1" class="w-[75%] rounded shadow" />
        <img src="{{ asset('images/banner-kecil-2.jpg') }}" alt="Promo 2" class="w-[75%] rounded shadow" />
    </div>
</div>

<!-- Ikon layanan -->
<div class="grid grid-cols-4 md:grid-cols-8 gap-4 px-6 py-4 bg-white text-center">
    <a href="{{ route('kategori.baru') }}">
        <div>
            <img src="{{ asset('images/ico1.png') }}" class="mx-auto h-12">
            <p class="text-xs mt-2">Baru Masuk</p>
        </div>
    </a>

    <a href="{{ route('kategori.show', ['id' => 1]) }}">
        <div>
            <img src="{{ asset('images/ico2.png') }}" class="mx-auto h-12">
            <p class="text-xs mt-2">Elektronik</p>
        </div>
    </a>
    <a href="{{ route('kategori.show', ['id' => 11]) }}">
        <div>
            <img src="{{ asset('images/ico3.png') }}" class="mx-auto h-12">
            <p class="text-xs mt-2">Hobi & Game</p>
        </div>
    </a>
    <a href="{{ route('kategori.show', ['id' => 2]) }}">
        <div>
            <img src="{{ asset('images/ico4.png') }}" class="mx-auto h-12">
            <p class="text-xs mt-2">Fashion</p>
        </div>
    </a>
    <a href="{{ route('kategori.show', ['id' => 12]) }}">
        <div>
            <img src="{{ asset('images/ico5.png') }}" class="mx-auto h-12">
            <p class="text-xs mt-2">Perkakas</p>
        </div>
    </a>
    <a href="{{ route('kategori.show', ['id' => 6]) }}">
        <div>
            <img src="{{ asset('images/ico6.png') }}" class="mx-auto h-12">
            <p class="text-xs mt-2">Olahraga</p>
        </div>
    </a>
    <a href="{{ route('kategori.show', ['id' => 3]) }}">
        <div>
            <img src="{{ asset('images/ico7.png') }}" class="mx-auto h-12">
            <p class="text-xs mt-2">Perabotan Rumah</p>
        </div>
    </a>
    <a href="{{ route('kategori.show', ['id' => 8]) }}">
        <div>
            <img src="{{ asset('images/ico8.png') }}" class="mx-auto h-12">
            <p class="text-xs mt-2">Dekorasi</p>
        </div>
    </a>
    <div class="col-span-4 md:col-span-8 mt-4 text-center">
        <a href="{{ route('transaksi.batal') }}"
           class="inline-block bg-red-600 text-white px-6 py-3 rounded hover:bg-red-700 shadow-md">
            🛑 Lihat Pembatalan Transaksi Valid
        </a>
    </div>
</div>

<!-- Kategori utama -->
<section class="p-8 bg-white mt-4">
    <h2 class="text-3xl font-bold mb-7">KATEGORI</h2>
    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-5 text-center">
        @foreach($kategoris as $kategori)
        <a href="{{ route('kategori.show', $kategori->id) }}">
            <div>
                <img src="{{ asset('images/' . strtolower(str_replace(' ', '-', $kategori->nama)) . '.jpg') }}"
                     class="w-20 h-20 mx-auto rounded-full shadow">
                <p class="mt-2 text-sm">{{ $kategori->nama }}</p>
            </div>
        </a>
        @endforeach
    </div>
</section>

<!-- Produk -->
<section class="p-6">
    @if(request('search'))
        <h1 class="text-2xl font-bold mb-2">Hasil pencarian untuk: "{{ request('search') }}"</h1>
    @else
        <h1 class="text-4xl font-bold mb-4">Produk Terbaru</h1>
    @endif

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
        <p class="text-gray-500">Tidak ada produk ditemukan.</p>
    @endif
</section>

<div class="text-center mt-6">
    <a href="{{ route('produk.index') }}" class="inline-block bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700">
        Lihat Produk Lainnya
    </a>
</div>

<!-- Script untuk trigger pencarian -->
<script>
    function triggerSearch(keyword) {
        const input = document.querySelector('input[placeholder="Cari produk"]');
        const cards = document.querySelectorAll('.product-card');
        input.value = keyword;

        cards.forEach(card => {
            const name = card.getAttribute('data-nama');
            if (name.includes(keyword.toLowerCase())) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }
        });
    }
</script>

@endsection
