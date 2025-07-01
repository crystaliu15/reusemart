@extends('layouts.app-admin')

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow p-10 rounded mt-10">
    <div class="mb-4">
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-xl font-bold mb-4">Tambah Pegawai untuk Jabatan: {{ $jabatan->nama_jabatan }}</h2>

    <form method="POST" action="{{ route('admin.jabatan.pegawai.store', $jabatan->id) }}">
        @csrf

        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="w-full border rounded px-2 py-1" value="{{ old('username') }}" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="w-full border rounded px-2 py-1" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="w-full border rounded px-2 py-1" value="{{ old('nama_lengkap') }}" required>
        </div>

        <div class="mb-3">
            <label>No Telp</label>
            <input type="text" name="no_telp" class="w-full border rounded px-2 py-1" value="{{ old('no_telp') }}" required>
        </div>

        <div class="mb-3">
            <label>Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" class="w-full border rounded px-2 py-1" value="{{ old('tanggal_lahir') }}" required>
        </div>

        <div class="mb-3">
            <label>Alamat Rumah</label>
            <textarea name="alamat_rumah" class="w-full border rounded px-2 py-1">{{ old('alamat_rumah') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="w-full border rounded px-2 py-1" required>
        </div>

        <div class="mb-3">
            <label>Jabatan</label>
            <input type="text" class="w-full border rounded px-2 py-1 bg-gray-100" value="{{ $jabatan->nama_jabatan }}" disabled>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan Pegawai</button>
    </form>
</div>
@endsection
