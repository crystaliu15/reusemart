@extends('layouts.app-penitip')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <div class="mb-4">
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-xl font-bold mb-4">Edit Profil Penitip</h2>

    <form action="{{ route('penitip.profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm mb-1">Username</label>
            <input type="text" name="username" value="{{ $penitip->username }}"
                   class="w-full border px-3 py-2 rounded" required>
        </div>

        <div>
            <label class="block text-sm mb-1">Email</label>
            <input type="email" value="{{ $penitip->email }}"
                   class="w-full border px-3 py-2 rounded bg-gray-100" readonly>
        </div>

        <div>
            <label class="block text-sm mb-1">No. Telepon</label>
            <input type="text" name="no_telp" value="{{ $penitip->no_telp }}"
                   class="w-full border px-3 py-2 rounded" required>
        </div>

        <div>
            <label class="block text-sm mb-1">Ganti Foto Profil</label>
            <input type="file" name="foto" class="w-full border px-3 py-2 rounded">
        </div>

        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection
