@extends('layouts.app-organisasi')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Edit Request Donasi</h2>

    <form method="POST" action="{{ route('organisasi.request.update', $request->id) }}">
        @csrf

        <div class="mb-4">
            <label class="block mb-1">Jenis Barang</label>
            <input type="text" name="jenis_barang" value="{{ $request->jenis_barang }}" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Alasan</label>
            <textarea name="alasan" rows="4" class="w-full border p-2 rounded" required>{{ $request->alasan }}</textarea>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection
