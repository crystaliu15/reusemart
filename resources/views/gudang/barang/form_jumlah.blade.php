@extends('layouts.app-gudang')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white rounded shadow mt-6">
    <div class="mb-4">
        <a href="{{ url('/gudang/dashboard') }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-2xl font-bold mb-4">Titipkan Barang</h2>
    <form action="{{ route('gudang.barang.multiCreate') }}" method="GET">
        <div class="mb-4">
            <label class="block font-semibold">Pilih Penitip</label>
            <select name="penitip_id" required class="w-full border rounded px-3 py-2">
                <option value="">-- Pilih Penitip --</option>
                @foreach ($penitips as $penitip)
                    <option value="{{ $penitip->id }}">{{ $penitip->username }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Jumlah Barang</label>
            <input type="number" name="jumlah" min="1" max="10" required class="w-full border rounded px-3 py-2">
        </div>

        <div class="text-right">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Lanjut Isi Data Barang
            </button>
        </div>
    </form>
</div>
@endsection
