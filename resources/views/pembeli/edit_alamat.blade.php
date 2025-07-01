@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-6 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Edit Alamat</h2>

    <form method="POST" action="{{ route('pembeli.alamat.update', $alamat->id) }}">
        @csrf
        @method('PUT')

        <textarea name="alamat" rows="3" class="w-full border rounded p-2" required>{{ old('alamat', $alamat->alamat) }}</textarea>

        <div class="mt-4 flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
