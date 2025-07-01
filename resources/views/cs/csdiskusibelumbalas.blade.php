@extends('layouts.app-cs')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded shadow mt-6">

    <div class="mb-4">
        <a href="{{ url('/cs/dashboard') }}"
        class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-xl font-bold mb-4">Diskusi Belum Dibalas</h2>

    @if($diskusis->isEmpty())
        <div class="text-center text-gray-500 italic">Tidak ada diskusi yang menunggu balasan.</div>
    @else
        <table class="w-full text-sm border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Nama Barang</th>
                    <th class="p-2 text-left">Pertanyaan</th>
                    <th class="p-2 text-left">Tanggal</th>
                    <th class="p-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($diskusis as $diskusi)
                <tr class="border-t">
                    <td class="p-2">{{ $diskusi->barang->nama ?? '-' }}</td>
                    <td class="p-2">{{ $diskusi->isi }}</td>
                    <td class="p-2">{{ $diskusi->created_at->format('d M Y H:i') }}</td>
                    <td class="p-2">
                        <a href="{{ route('cs.barang.show', $diskusi->barang_id) }}"
                           class="text-blue-600 hover:underline text-sm">
                           Lihat Barang
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
