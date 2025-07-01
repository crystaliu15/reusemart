@extends('layouts.app-hunter')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded shadow mt-6">
    <h2 class="text-2xl font-bold mb-4">Dashboard Hunter</h2>

    <div class="space-y-2">
        <p><strong>Username:</strong> {{ $hunter->username }}</p>
        <p><strong>Nama Lengkap:</strong> {{ $hunter->nama_lengkap }}</p>
        <p><strong>Email:</strong> {{ $hunter->email }}</p>
        <p><strong>No Telp:</strong> {{ $hunter->no_telp }}</p>
        <p><strong>Saldo:</strong> Rp{{ number_format($hunter->saldo, 0, ',', '.') }}</p>

        @if ($hunter->profile_picture)
            <img src="{{ asset('storage/' . $hunter->profile_picture) }}" alt="Foto Profil"
                 class="w-32 h-32 object-cover rounded-full mt-4">
        @else
            <p class="text-gray-500 mt-4">Belum mengunggah foto profil.</p>
        @endif
    </div>
</div>
@endsection
