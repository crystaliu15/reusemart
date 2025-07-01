@extends('layouts.app-cs')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white rounded shadow mt-6">
    <div class="mb-4">
        <a href="{{ url('/cs/dashboard') }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-2xl font-bold mb-4">Kelola Data Barang</h2>

    <div class="mb-4 text-right">
        <a href="{{ route('cs.barang.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Tambah Barang Baru
        </a>
    </div>

    <!-- FILTER -->
    <form method="GET" action="{{ route('cs.barang.semua') }}" class="mb-4 text-right">
        <label for="filter_diskusi" class="mr-2 text-sm">Filter:</label>
        <select name="filter" id="filter_diskusi" onchange="this.form.submit()"
                class="border px-3 py-1 rounded text-sm">
            <option value="">Semua Barang</option>
            <option value="belum_dibalas" {{ request('filter') == 'belum_dibalas' ? 'selected' : '' }}>
                Barang dengan Diskusi Belum Dibalas
            </option>
        </select>
    </form>

    <!-- Grid 3 kolom -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($barangs as $barang)
            <a href="{{ route('cs.barang.show', $barang->id) }}"
               class="block bg-gray-50 border p-4 rounded shadow-sm hover:shadow-md transition hover:ring-2 hover:ring-blue-300">
                <img src="{{ asset('images/barang/' . $barang->id . '/' . $barang->thumbnail) }}"
                     alt="{{ $barang->nama }}"
                     class="w-full h-40 object-cover rounded mb-3">
                <h3 class="font-bold text-sm truncate flex items-center justify-between">
                    {{ $barang->nama }}

                    @if($barang->diskusi_belum_dibalas_count > 0)
                        <p class="text-xs text-red-600 font-semibold mt-1">
                            💬 {{ $barang->diskusi_belum_dibalas_count }} diskusi belum dibalas
                        </p>
                    @endif
                </h3>
                <p class="text-sm text-gray-600 mb-1">Harga: Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-600">Penitip: {{ $barang->penitip->username ?? 'Tidak diketahui' }}</p>
                <p class="text-xs mt-1 text-{{ $barang->terjual ? 'red' : 'green' }}-600 font-semibold">
                    {{ $barang->terjual ? 'Sudah Terjual' : 'Tersedia' }}
                </p>
            </a>
        @endforeach
    </div>

    <!-- PAGINATION -->
    <div class="mt-6">
        {{ $barangs->links() }}
    </div>
</div>
@endsection
