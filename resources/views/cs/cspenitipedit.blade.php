@extends('layouts.app-cs')

@section('content')
<div class="max-w-lg mx-auto mt-6 bg-white p-6 rounded shadow">
    <div class="mb-4">
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-xl font-bold mb-4">Edit Penitip</h2>

    <form method="POST" action="{{ route('cs.penitip.update', $penitip->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" value="{{ $penitip->username }}" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ $penitip->email }}" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-3">
            <label>No Telp</label>
            <input type="text" name="no_telp" value="{{ $penitip->no_telp }}" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="w-full border rounded p-2" required>{{ $penitip->alamat }}</textarea>
        </div>
        <div class="mb-3">
            <label>No KTP</label>
            <input type="text" name="no_ktp" value="{{ $penitip->no_ktp }}" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-3">
            <label>Foto KTP</label>
            @if($penitip->foto_ktp)
                <img src="{{ asset('storage/' . $penitip->foto_ktp) }}" alt="Foto KTP"
                    class="h-24 mb-2 rounded shadow border">
            @endif
            <input type="file" name="foto_ktp" accept="image/*" class="w-full border rounded p-2">
            <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti.</p>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
    </form>
</div>
@endsection
