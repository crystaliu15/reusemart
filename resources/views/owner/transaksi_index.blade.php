@extends('layouts.app-owner')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-6 rounded shadow mt-6">
    <div class="mb-4">
        <a href="{{ url('/owner/dashboard') }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-2xl font-bold mb-4">Daftar Transaksi</h2>

    <table class="w-full border text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">ID Transaksi</th>
                <th class="p-2 border">Jumlah Barang</th>
                <th class="p-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksiList as $transaksiId)
                @php
                    $jumlahBarang = \App\Models\KomisiLog::where('transaksi_id', $transaksiId)->count();
                @endphp
                <tr>
                    <td class="p-2 border">{{ $transaksiId }}</td>
                    <td class="p-2 border">{{ $jumlahBarang }}</td>
                    <td class="p-2 border">
                        <a href="{{ route('owner.transaksi.show', $transaksiId) }}" class="text-blue-600 hover:underline">
                            Lihat Detail
                        </a>
                        |
                        <a href="{{ route('owner.transaksi.downloadPdf', $transaksiId) }}" 
                           class="text-green-600 hover:underline" 
                           target="_blank">
                            Download PDF
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Manual Pagination --}}
    <div class="flex justify-between mt-4">
        @if ($currentPage > 1)
            <a href="?page={{ $currentPage - 1 }}" class="text-blue-600">← Sebelumnya</a>
        @else
            <span></span>
        @endif

        @if ($currentPage < $lastPage)
            <a href="?page={{ $currentPage + 1 }}" class="text-blue-600">Berikutnya →</a>
        @endif
    </div>
</div>
@endsection
