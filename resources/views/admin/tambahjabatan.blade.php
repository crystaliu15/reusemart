@extends('layouts.app-admin')

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow p-10 rounded mt-10">
    <div class="mb-4">
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-xl font-bold mb-4">Tambah Jabatan Baru</h2>

    <form method="POST" action="{{ route('admin.jabatan.store') }}">
        @csrf

        <div class="mb-4">
            <label>Nama Jabatan</label>
            <input type="text" name="nama_jabatan" class="w-full border px-2 py-1 rounded"
                   value="{{ old('nama_jabatan') }}" required>
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
    </form>
</div>
@endsection
