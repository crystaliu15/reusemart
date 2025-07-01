@extends('layouts.app-admin')

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow p-10 rounded mt-10">
    <div class="mb-4">
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-xl font-bold mb-4">Edit Organisasi</h2>

    <form method="POST" action="{{ route('admin.organisasi.update', $organisasi->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label>Nama Organisasi</label>
            <input type="text" name="username" value="{{ old('username', $organisasi->username) }}" class="w-full border rounded px-2 py-1">
        </div>

        <div class="mb-4">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $organisasi->email) }}" class="w-full border rounded px-2 py-1">
        </div>

        <div class="mb-4">
            <label>No Telp</label>
            <input type="text" name="no_telp" value="{{ old('no_telp', $organisasi->no_telp) }}" class="w-full border rounded px-2 py-1">
        </div>

        <div class="mb-4">
            <label>Alamat</label>
            <textarea name="alamat" class="w-full border rounded px-2 py-1">{{ old('alamat', $organisasi->alamat) }}</textarea>
        </div>

        <div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
