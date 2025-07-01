@extends('layouts.app-owner')

@section('content')
<div class="max-w-6xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <div class="mb-4">
            <a href="{{ url('/owner/dashboard') }}"
            class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
                ← Kembali
            </a>
        </div>
    <h2 class="text-xl font-bold mb-4">Barang Donasi Hari Ini</h2>

    @if($historiDonasi->isEmpty())
        <p class="text-gray-500">Belum ada barang yang didonasikan.</p>
    @else
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b">
                    <th>Organisasi</th>
                    <th>Nama Barang</th>
                    <th>Tanggal Donasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historiDonasi as $donasi)
                    <tr class="border-b">
                        <td class="py-2">{{ $donasi->organisasi->username ?? 'Tidak diketahui' }}</td>
                        <td>{{ $donasi->nama_barang }}</td>
                        <td>{{ $donasi->tanggal_donasi ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
