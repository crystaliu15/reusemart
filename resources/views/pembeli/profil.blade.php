@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded shadow space-y-6">
    <div class="mb-4">
        <a href="{{ url('/') }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>

    <h2 class="text-xl font-bold mb-4">Profil Pembeli</h2>

    {{-- Informasi Umum --}}
    <div class="flex items-center space-x-6">
        <img src="{{ $pembeli->profile_picture ? asset('storage/' . $pembeli->profile_picture) : asset('images/default-user.png') }}"
             class="w-24 h-24 rounded-full object-cover border">
        <div class="space-y-1 text-sm text-gray-800">
            <p><strong>Nama:</strong> {{ $pembeli->username }}</p>
            <p><strong>Email:</strong> {{ $pembeli->email }}</p>
            <p><strong>No. Telepon:</strong> {{ $pembeli->no_telp }}</p>
            <p><strong>Alamat Utama:</strong>
                {{ $pembeli->defaultAlamat ? $pembeli->defaultAlamat->alamat : 'Belum ada alamat default' }}
            </p>
            <p><strong>Poin:</strong> 🎁 {{ $pembeli->poin }} poin</p>
        </div>
    </div>

    {{-- Tombol Edit Profil --}}
    <div class="mt-4">
        <a href="{{ route('pembeli.profil.edit') }}"
           class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Edit Profil
        </a>

        <a href="{{ route('pembeli.alamat.index') }}"
            class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mt-4">
            Kelola Alamat
        </a>

        <a href="{{ route('pembeli.transaksi.riwayat') }}"
            class="inline-block bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 mt-4">
            Riwayat Pembelian
        </a>
    </div>
    {{-- Notifikasi --}}
    <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-700 p-4 rounded">
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-lg font-semibold"><i class="fas fa-bell mr-2"></i>Notifikasi Terbaru</h2>
            <form method="POST" action="{{ route('pembeli.notifikasi.baca-semua') }}">
                @csrf
                <button type="submit" class="text-xs text-blue-600 hover:underline">Tandai Semua Sudah Dibaca</button>
            </form>
        </div>

        @if(auth('pembeli')->user()->unreadNotifications->isEmpty())
            <p class="text-sm text-gray-600">Tidak ada notifikasi baru.</p>
        @else
            <ul class="space-y-2 text-sm">
                @foreach(auth('pembeli')->user()->unreadNotifications as $notif)
                    <li class="p-2 bg-white border rounded shadow-sm">
                        <div class="flex justify-between">
                            <div>
                                <p class="font-medium text-gray-800">
                                    {{ $notif->data['pesan'] ?? 'Ada notifikasi baru.' }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</div>
@endsection
