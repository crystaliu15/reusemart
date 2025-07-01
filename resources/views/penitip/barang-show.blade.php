@extends('layouts.app-penitip')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">
    {{-- Tombol Kembali --}}
    <div class="mb-4">
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-2xl font-bold mb-4">{{ $barang->nama }}</h2>

    <div class="flex flex-col md:flex-row gap-6">
        {{-- Gambar utama --}}
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

        {{-- Detail --}}
        <div class="md:w-1/2 space-y-3">
            <p><strong>Harga:</strong> Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
            <p><strong>Kategori:</strong> {{ $barang->kategori->nama }}</p>
            <p><strong>Deskripsi:</strong> {{ $barang->deskripsi }} </p>
            <p><strong>Massa Titip:</strong> {{ \Carbon\Carbon::parse($barang->batas_waktu_titip)->format('d-m-Y') }}</p>

            <p><strong>Garansi:</strong>
                @if($barang->garansi && $barang->garansi_berlaku_hingga && \Carbon\Carbon::now()->lt($barang->garansi_berlaku_hingga))
                    Aktif hingga {{ \Carbon\Carbon::parse($barang->garansi_berlaku_hingga)->format('d M Y') }}
                @else
                    Tidak ada
                @endif
            </p>
            
            <p><strong>Pengambilan:</strong>
                @if (!$barang->status_pengambilan)
                    Belum Siap Diambil
                @else
                    Siap Diambil
                @endif
            </p>

            <p><strong>Status:</strong>
                @if($barang->terjual)
                    <span class="text-red-600">Sudah Terjual</span>
                @else
                    <span class="text-green-600">Belum Terjual</span>
                @endif
            </p>
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
        document.getElementById('loginPromptModal').classList.remove('hidden');
    }

    function closeLoginPrompt() {
        document.getElementById('loginPromptModal').classList.add('hidden');
    }
</script>
@endsection

