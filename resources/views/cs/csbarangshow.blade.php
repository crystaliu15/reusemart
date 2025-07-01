@extends('layouts.app-cs')

@section('content')
<div class="p-6">
    <div class="flex flex-col md:flex-row gap-6">
        <div class="mb-4">
            <a href="{{ url('/cs/semua-barang') }}"
            class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
                ← Kembali
            </a>
        </div>
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
                {{ $barang->kategori->nama }}
            </p>

            <p class="mb-2"><strong>Deskripsi:</strong></p>
            <p class="text-sm text-gray-700 mb-4">{{ $barang->deskripsi }}</p>

            <p><strong>Garansi:</strong>
                @if ($barang->garansi_berlaku_hingga && $barang->garansi_berlaku_hingga->isFuture())
                    Ada (aktif hingga {{ $barang->garansi_berlaku_hingga->translatedFormat('d F Y') }})
                @else
                    Tidak Ada
                @endif
            </p>

            <p><strong>Status:</strong> {{ $barang->terjual ? 'Sudah Terjual' : 'Tersedia' }}</p>

            <a href="{{ route('cs.barang.edit', $barang->id) }}"
                class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mt-4">
                ✏️ Edit Barang
            </a>
        </div>
    </div>
    

    <!-- DISKUSI -->
    <div class="mt-10 bg-white p-6 rounded shadow">
        <h3 class="text-xl font-bold mb-4">Diskusi Produk</h3>

        @forelse($barang->diskusis as $diskusi)
            <div class="border-t pt-4 mt-4">
                <p><strong>{{ $diskusi->user->username }}</strong> <span class="text-sm text-gray-500">({{ $diskusi->created_at->diffForHumans() }})</span></p>
                <p class="mb-2 text-gray-700">{{ $diskusi->isi }}</p>

                @if($diskusi->balasan)
                    <div class="ml-4 p-3 bg-green-50 border-l-4 border-green-400 rounded text-sm">
                        <strong class="text-green-800">Balasan CS:</strong>
                        <p class="text-gray-800">{{ $diskusi->balasan }}</p>
                    </div>
                @else
                    <form action="{{ route('cs.diskusi.balas', $diskusi->id) }}" method="POST" class="mt-3">
                        @csrf
                        <textarea name="balasan" rows="2" placeholder="Tulis balasan CS..."
                                  class="w-full border rounded p-2 text-sm" required></textarea>
                        <button type="submit"
                                class="mt-2 bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">
                            Balas
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <p class="text-gray-500 italic">Belum ada pertanyaan dari pembeli.</p>
        @endforelse
    </div>
</div>

<script>
    function changeMainImage(thumbnail) {
        const mainImage = document.getElementById('mainImage');
        mainImage.src = thumbnail.src;
    }
</script>
@endsection
