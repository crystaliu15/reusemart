@extends('layouts.app')

@php 
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Auth;
    $pembeli = Auth::guard('pembeli')->user();
@endphp



@section('content')
<div class="p-6">
    <div class="flex flex-col md:flex-row gap-6">

        <!-- FOTO PRODUK -->
        <div class="flex-1">
            <img id="mainImage"
                 src="{{ asset('images/barang/' . $barang->id . '/' . $barang->thumbnail) }}"
                 class="w-full rounded shadow mb-4 object-contain max-h-[500px]">

            <div class="grid grid-cols-4 sm:grid-cols-5 gap-2">
                <img onclick="changeMainImage(this)"
                     src="{{ asset('images/barang/' . $barang->id . '/' . $barang->thumbnail) }}"
                     class="h-24 w-full object-cover cursor-pointer border-2 border-green-500 rounded">

                @foreach(json_decode($barang->foto_lain) as $foto)
                <img onclick="changeMainImage(this)"
                     src="{{ asset('images/barang/' . $barang->id . '/' . $foto) }}"
                     class="h-24 w-full object-cover cursor-pointer rounded hover:ring-2 ring-green-400">
                @endforeach
            </div>
        </div>

        <!-- INFO PRODUK -->
        <div class="flex-1">
            <h1 class="text-2xl font-bold mb-2">{{ $barang->nama }}</h1>
            <p class="text-xl text-orange-600 font-semibold mb-4">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>

            <p class="mb-2"><strong>Kategori:</strong>
                <a href="{{ route('kategori.show', $barang->kategori->id) }}" class="text-blue-600 underline">
                    {{ $barang->kategori->nama }}
                </a>
            </p>

            <p class="mb-2"><strong>Deskripsi:</strong></p>
            <p class="text-sm text-gray-700 mb-4">{{ $barang->deskripsi }}</p>

            <!-- TAMBAHAN: Nama Penitip & Rating -->
            @php
                $penitip = $barang->penitip;
                $avgRating = round($penitip->averageRating(), 1);
            @endphp

            <p class="mb-2"><strong>Penitip:</strong> {{ $penitip->username }}</p>
            <p class="mb-4">
                <strong>Rating Penitip:</strong>
                @if ($avgRating > 0)
                    <span class="text-yellow-500">
                        {{ str_repeat('★', floor($avgRating)) }}{{ $avgRating < 5 ? str_repeat('☆', 5 - floor($avgRating)) : '' }}
                    </span>
                    <span class="text-sm text-gray-600">({{ $avgRating }} / 5)</span>
                @else
                    <span class="text-sm text-gray-500">Belum ada rating</span>
                @endif
            </p>

            <p><strong>Garansi:</strong>
                @if ($barang->garansi_berlaku_hingga && $barang->garansi_berlaku_hingga->isFuture())
                    Ada (aktif hingga {{ $barang->garansi_berlaku_hingga->translatedFormat('d F Y') }})
                @else
                    Tidak Ada
                @endif
            </p>

            <p><strong>Status:</strong> {{ $barang->terjual ? 'Sold Out' : 'Tersedia' }}</p>
        </div>

        <!-- CARD AKSI -->
        <div class="md:w-1/4">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 text-green-800 p-2 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 bg-red-100 text-red-800 p-2 rounded">
                        {{ session('error') }}
                    </div>
                @endif
            <div class="bg-white p-4 rounded shadow space-y-3 sticky top-24">
                <h1 class="text-2xl font-bold mb-2">{{ $barang->nama }}</h1>
            <p class="text-xl text-orange-600 font-semibold mb-4">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                @if ($pembeli)
                    <form method="POST" action="{{ route('keranjang.tambah', $barang->id) }}">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">+ Keranjang</button>
                    </form>
                    <button class="w-full bg-orange-500 text-white py-2 rounded hover:bg-orange-600">Beli Sekarang</button>
                @else
                    <button onclick="showLoginPrompt()"
                            class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">+ Keranjang</button>
                    <button onclick="showLoginPrompt()"
                            class="w-full bg-orange-500 text-white py-2 rounded hover:bg-orange-600">Beli Sekarang</button>
                @endif

                <!-- Bagikan bisa siapa saja -->
                <button onclick="copyProductLink()"
                        class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Bagikan</button>

                <button onclick="document.getElementById('diskusi').scrollIntoView({ behavior: 'smooth' });"
                        class="w-full bg-purple-600 text-white py-2 rounded hover:bg-purple-700">Diskusi</button>
            </div>
        </div>
    </div>

    <!-- DISKUSI -->
    <div id="diskusi" class="mt-12 bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Diskusi & Pertanyaan</h2>

        @if ($pembeli && !$userDiskusi)
            <div class="flex items-center justify-between mb-4 bg-yellow-100 text-yellow-800 p-4 rounded">
                <p>Punya pertanyaan? Langsung diskusi dengan penjual saja</p>
                <button onclick="scrollAndFocusTextarea()"
                        class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                    Mulai Diskusi
                </button>
            </div>
        @elseif (!$pembeli)
            <div class="flex items-center justify-between mb-4 bg-yellow-100 text-yellow-800 p-4 rounded">
                <p>Punya pertanyaan? Langsung diskusi dengan penjual saja</p>
                <button onclick="showLoginPrompt()"
                        class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                    Mulai Diskusi
                </button>
            </div>
        @endif

        @if ($pembeli)
        <form id="diskusiForm" action="{{ route('diskusi.store') }}" method="POST" class="mb-4">
            @csrf
            <input type="hidden" name="barang_id" value="{{ $barang->id }}">
            <textarea name="isi" rows="3" class="w-full border rounded p-2"
                      placeholder="Tulis pertanyaan atau komentar..."></textarea>
            <button type="submit" class="mt-2 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Kirim</button>
        </form>
        @endif

        @foreach($barang->diskusis as $diskusi)
            <div class="border-t py-2">
                <span class="text-sm text-gray-500">{{ $diskusi->created_at->diffForHumans() }}</span>
                <p class="text-sm">{{ $diskusi->isi }}</p>
            </div>
        @endforeach
    </div>

    <!-- REKOMENDASI -->
    <div class="mt-12">
        <h2 class="text-xl font-bold mb-4">Barang Serupa dari Kategori {{ $barang->kategori->nama }}</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-6">
            @forelse($rekomendasi as $item)
            <a href="{{ route('barang.show', $item->id) }}" class="bg-white rounded shadow hover:shadow-lg transition p-4">
                <img src="{{ asset('images/barang/' . $item->id . '/' . $item->thumbnail) }}"
                    class="w-full h-40 object-cover rounded mb-2">
                <h3 class="text-sm font-semibold">{{ $item->nama }}</h3>
                <p class="text-orange-600 font-bold text-sm">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
            </a>
            @empty
            <p class="text-gray-500 italic">Belum ada barang lain di kategori ini.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- SCRIPT -->
<script>
    function changeMainImage(thumbnail) {
        const mainImage = document.getElementById('mainImage');
        mainImage.src = thumbnail.src;
    }

    function copyProductLink() {
        const dummy = document.createElement('input');
        dummy.value = window.location.href;
        document.body.appendChild(dummy);
        dummy.select();
        document.execCommand('copy');
        document.body.removeChild(dummy);
        alert('Link produk berhasil disalin!');
    }

    function showLoginPrompt() {
        const modal = document.getElementById('loginPromptModal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function closeLoginPrompt() {
        const modal = document.getElementById('loginPromptModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    function scrollAndFocusTextarea() {
        const form = document.getElementById('diskusiForm');
        const textarea = form?.querySelector('textarea');
        if (form && textarea) {
            form.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(() => textarea.focus(), 500);
        }
    }

    // Tambahan: bisa tutup modal dengan tombol ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            closeLoginPrompt();
        }
    });
</script>

<!-- MODAL LOGIN -->
<div id="loginPromptModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white w-96 rounded-lg p-6 shadow-lg text-center">
        <h2 class="text-lg font-bold mb-2 text-gray-800">Anda belum login</h2>
        <p class="text-sm text-gray-600 mb-6">Login dulu yuk untuk bisa akses fitur ini.</p>
        <div class="flex justify-between space-x-2">
            <a href="{{ route('pembeli.login.form') }}" class="flex-1 bg-green-600 text-white py-2 rounded hover:bg-green-700">Login</a>
            <a href="{{ route('pembeli.register.form') }}" class="flex-1 bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Daftar</a>
            <button onclick="closeLoginPrompt()" class="flex-1 bg-gray-300 text-black py-2 rounded hover:bg-gray-400">Nanti saja</button>
        </div>
    </div>
</div>
@endsection