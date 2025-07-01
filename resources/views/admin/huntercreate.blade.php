@extends('layouts.app-admin')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow mt-10">
    <div class="mb-4">
        <a href="{{ url('/admin/dashboard') }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali ke Dashboard
        </a>
    </div>

    <h2 class="text-2xl font-bold mb-6">Tambah Hunter Baru</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.hunter.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold mb-1">Username</label>
            <input type="text" name="username" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Email</label>
            <input type="email" name="email" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">No. Telepon</label>
            <input type="text" name="no_telp" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-6">
            <label class="block font-semibold mb-1">Password</label>
            <input type="password" name="password" class="w-full border p-2 rounded" required>
        </div>

        <button type="submit"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Simpan Hunter
        </button>
    </form>
</div>
@endsection
