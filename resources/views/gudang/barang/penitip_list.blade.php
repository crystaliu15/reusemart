@extends('layouts.app-gudang')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded shadow mt-6">
    <div class="mb-4">
        <a href="{{ url('/gudang/dashboard') }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-2xl font-bold mb-6">Daftar Penitip</h2>

    <table class="w-full border text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-3 py-2 text-left">Username</th>
                <th class="border px-3 py-2 text-left">Alamat</th>
                <th class="border px-3 py-2">Jumlah Barang</th>
                <th class="border px-3 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penitips as $penitip)
                <tr>
                    <td class="border px-3 py-2">{{ $penitip->username }}</td>
                    <td class="border px-3 py-2">{{ $penitip->alamat }}</td>
                    <td class="border px-3 py-2 text-center">{{ $penitip->barangs_count }}</td>
                    <td class="border px-3 py-2 text-center">
                        <a href="{{ route('gudang.barang.barangPerPenitip', $penitip->id) }}"
                            class="text-blue-600 hover:underline">📄 Lihat Barang</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
