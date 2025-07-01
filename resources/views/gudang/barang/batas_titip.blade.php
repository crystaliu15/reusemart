@extends('layouts.app-gudang')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white rounded shadow mt-6">
    <div class="mb-4">
        <a href="{{ url('/gudang/dashboard') }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-2xl font-bold mb-4 text-red-700">Barang Mendekati Batas Titip (≤ 3 Hari)</h2>

    @if ($barangs->isEmpty())
        <p class="text-gray-600">Tidak ada barang yang mendekati batas titip dalam 3 hari ke depan.</p>
    @else
        <table class="w-full border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2">Nama</th>
                    <th class="border px-3 py-2">Kategori</th>
                    <th class="border px-3 py-2">Batas Titip</th>
                    <th class="border px-3 py-2">Sisa Hari</th>
                    <th class="border px-3 py-2">Penitip</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangs as $barang)
                <tr class="bg-red-50">
                    <td class="border px-3 py-2 font-semibold">{{ $barang->nama }}</td>
                    <td class="border px-3 py-2">{{ $barang->kategori->nama ?? '-' }}</td>
                    <td class="border px-3 py-2">{{ \Carbon\Carbon::parse($barang->batas_waktu_titip)->format('d M Y') }}</td>
                    <td class="border px-3 py-2 text-red-700 font-bold">
                        @if ($barang->sisa_hari === 0)
                            🔥 Hari Ini
                        @else
                            ⚠️ Sisa {{ $barang->sisa_hari }} Hari
                        @endif
                    </td>
                    <td class="border px-3 py-2">{{ $barang->penitip->username }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
