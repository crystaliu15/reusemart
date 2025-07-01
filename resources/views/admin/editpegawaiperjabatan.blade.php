@extends('layouts.app-admin')

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow p-10 rounded mt-10">
    <div class="mb-4">
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-xl font-bold mb-4">Edit Pegawai</h2>

    <form method="POST" action="{{ route('admin.pegawai.update', $pegawai->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="w-full border px-2 py-1 rounded"
                   value="{{ old('username', $pegawai->username) }}" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="w-full border px-2 py-1 rounded"
                   value="{{ old('email', $pegawai->email) }}" required>
        </div>

        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="w-full border px-2 py-1 rounded"
                   value="{{ old('nama_lengkap', $pegawai->nama_lengkap) }}" required>
        </div>

        <div class="mb-3">
            <label>No Telp</label>
            <input type="text" name="no_telp" class="w-full border px-2 py-1 rounded"
                   value="{{ old('no_telp', $pegawai->no_telp) }}" required>
        </div>

        <div class="mb-3">
            <label>Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" class="w-full border px-2 py-1 rounded"
                   value="{{ old('tanggal_lahir', $pegawai->tanggal_lahir) }}" required>
        </div>

        <div class="mb-3">
            <label>Alamat Rumah</label>
            <textarea name="alamat_rumah" class="w-full border px-2 py-1 rounded">{{ old('alamat_rumah', $pegawai->alamat_rumah) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Jabatan</label>
            <select name="jabatan_id" class="w-full border rounded px-2 py-1">
                @foreach($jabatans as $jabatan)
                    <option value="{{ $jabatan->id }}" @if($pegawai->jabatan_id == $jabatan->id) selected @endif>
                        {{ $jabatan->nama_jabatan }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Password (biarkan kosong jika tidak ingin ubah)</label>
            <input type="password" name="password" class="w-full border px-2 py-1 rounded">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan Perubahan</button>
    </form>
</div>
@endsection
