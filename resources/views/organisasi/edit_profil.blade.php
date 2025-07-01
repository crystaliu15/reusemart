@extends('layouts.app-organisasi')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Edit Profil Organisasi</h2>

    <form action="{{ route('organisasi.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="text" name="username" value="{{ $organisasi->username }}" class="w-full border p-2 mb-3 rounded" required>
        <input type="email" name="email" value="{{ $organisasi->email }}" class="w-full border p-2 mb-3 rounded" required>
        <input type="text" name="no_telp" value="{{ $organisasi->no_telp }}" class="w-full border p-2 mb-3 rounded" required>
        <input type="text" name="alamat" value="{{ $organisasi->alamat }}" class="w-full border p-2 mb-3 rounded" required>

        <label class="block mb-1">Ubah Foto Profil (Opsional)</label>
        <input type="file" name="profile_picture" class="mb-4">

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection
