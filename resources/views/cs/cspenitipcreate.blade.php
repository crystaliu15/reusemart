@extends('layouts.app-cs')

@section('content')
<div class="max-w-lg mx-auto mt-6 bg-white p-6 rounded shadow">
    <div class="mb-4">
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-xl font-bold mb-4">Tambah Penitip</h2>

    @if($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('cs.penitip.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-3">
            <label>No Telp</label>
            <input type="text" name="no_telp" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="w-full border rounded p-2" required></textarea>
        </div>
        <div class="mb-3">
            <label>No KTP</label>
            <input type="text" name="no_ktp" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-3">
            <label>Foto KTP</label>
            <input type="file" name="foto_ktp" accept="image/*" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="w-full border rounded p-2" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
    </form>
</div>
@endsection
