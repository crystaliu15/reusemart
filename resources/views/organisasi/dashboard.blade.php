@extends('layouts.app-organisasi')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Dashboard Organisasi</h2>

    <div class="flex items-center gap-6 mb-6">
        <img src="{{ asset('storage/' . $organisasi->profile_picture) }}"
            class="w-20 h-20 rounded-full object-cover"
            alt="Foto Profil">
        <div>
            <p><strong>Nama Organisasi:</strong> {{ $organisasi->username }}</p>
            <p><strong>Email:</strong> {{ $organisasi->email }}</p>
            <p><strong>No. Telepon:</strong> {{ $organisasi->no_telp }}</p>
            <p><strong>Alamat:</strong>
                @if ($organisasi->alamat)
                    {{ $organisasi->alamat }}
                @else
                    <span class="text-gray-500 italic">Belum mengisi alamat</span>
                @endif
            </p>
        </div>
    </div>

    <a href="{{ route('organisasi.edit') }}" class="inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 mt-4">
        Edit Profil
    </a>


    <a href="{{ route('organisasi.request.create') }}"
       class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mb-6 inline-block">
        + Request Donasi
    </a>

    <h3 class="text-xl font-semibold mt-8 mb-2">Request Donasi Anda</h3>
        <ul class="space-y-3">
            @forelse ($ownRequests as $req)
                <li class="border p-3 rounded bg-white shadow-sm">
                    <p><strong>Jenis Barang:</strong> {{ $req->jenis_barang }}</p>
                    <p><strong>Alasan:</strong> {{ $req->alasan }}</p>

                    <div class="mt-2 flex gap-2">
                        <a href="{{ route('organisasi.request.edit', $req->id) }}"
                        class="text-blue-600 hover:underline">Edit</a>

                        <form action="{{ route('organisasi.request.destroy', $req->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus request ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </div>
                </li>
            @empty
                <p class="text-gray-500">Belum ada request donasi yang Anda buat.</p>
            @endforelse
        </ul>
    <h3 class="text-xl font-semibold mt-6 mb-2">Request Donasi dari Organisasi Lain</h3>
    <ul class="space-y-3">
        @forelse ($allRequests as $req)
            <li class="border p-3 rounded bg-gray-50">
                <p><strong>Jenis Barang:</strong> {{ $req->jenis_barang }}</p>
                <p><strong>Alasan:</strong> {{ $req->alasan }}</p>
                <p class="text-sm text-gray-500">
                    Dikirim oleh Organisasi:
                    {{ $req->organisasi->username ?? 'Tidak diketahui' }}
                </p>
            </li>
        @empty
            <p class="text-gray-500">Belum ada request dari organisasi lain.</p>
        @endforelse
    </ul>

    @if(session('success'))
    <div class="mb-4 bg-green-100 text-green-800 p-3 rounded">
        {{ session('success') }}
    </div>
@endif

</div>
@endsection
