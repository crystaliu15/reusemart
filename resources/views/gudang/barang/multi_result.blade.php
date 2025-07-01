@extends('layouts.app-gudang')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white rounded shadow mt-6">
    <div class="mb-4">
        <a href="{{ url('/gudang/penitip-barang') }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-2xl font-bold mb-4">
        @isset($penitip)
            Daftar Barang Milik Penitip: {{ $penitip->username }}
        @else
            Barang Berhasil Ditambahkan
        @endisset
    </h2>

    <div class="flex justify-end mb-4">
        @if (!empty($barangs) && count($barangs) > 0)
            @if (isset($penitip))
                <form action="{{ route('gudang.barang.cetakNotaGabungan') }}" method="POST" target="_blank">
                    @csrf
                    <input type="hidden" name="ids" value="{{ implode(',', $barangs->pluck('id')->toArray()) }}">
                    <button type="submit"
                        class="inline-block bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 shadow">
                        📄 Cetak Nota Gabungan
                    </button>
                </form>
            @else
                <a href="{{ route('gudang.barang.cetakNotaGabungan') }}"
                   class="inline-block bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 shadow">
                    📄 Cetak Nota Gabungan
                </a>
            @endif
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border border-gray-300 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2">Nama</th>
                    <th class="border px-3 py-2">Kategori</th>
                    <th class="border px-3 py-2">Harga</th>
                    <th class="border px-3 py-2">Berat</th>
                    <th class="border px-3 py-2">Garansi</th>
                    <th class="border px-3 py-2">Tgl Masuk</th>
                    <th class="border px-3 py-2">Batas Titip</th>
                    <th class="border px-3 py-2">Quality Check</th>
                    <th class="border px-3 py-2">Penitip</th>
                    <th class="border px-3 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangs as $barang)
                <tr>
                    <td class="border px-3 py-2">
                        {{ $barang->nama }}
                        <a href="{{ route('gudang.barang.show', $barang->id) }}"
                        class="text-blue-600 hover:underline ml-1 text-sm inline-block align-middle" title="Lihat Detail">
                            🔍
                        </a>
                    </td>
                    <td class="border px-3 py-2">{{ $barang->kategori->nama }}</td>
                    <td class="border px-3 py-2">Rp{{ number_format($barang->harga, 0, ',', '.') }}</td>
                    <td class="border px-3 py-2">{{ $barang->berat }} kg</td>
                    <td class="border px-3 py-2">
                        @if ($barang->garansi_berlaku_hingga)
                            {{ \Carbon\Carbon::parse($barang->garansi_berlaku_hingga)->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="border px-3 py-2">{{ $barang->created_at->format('d/m/Y H:i') }}</td>
                    <td class="border px-3 py-2">{{ $barang->batas_waktu_titip ? \Carbon\Carbon::parse($barang->batas_waktu_titip)->format('d/m/Y') : '-' }}</td>
                    <td class="border px-3 py-2">
                        {{ $barang->qualityChecker->nama_lengkap ?? '-' }}
                    </td>
                    <td class="border px-3 py-2">{{ $barang->penitip->username }}</td>
                    <td class="border px-3 py-2 text-center space-x-2">
                        <a href="{{ route('gudang.barang.edit', $barang->id) }}?from=penitip&id={{ $barang->penitip->id }}"
                            class="text-yellow-600 hover:underline">✏️</a>
                        <form action="{{ route('gudang.barang.destroy', $barang->id) }}"
                            method="POST" class="inline"
                            onsubmit="return confirm('Yakin ingin hapus barang ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">❌</button>
                        </form> 
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
