@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Dashboard Pegawai</h2>

    <h5>Notifikasi</h5>
    <ul>
    @foreach(auth()->user()->unreadNotifications as $notif)
        <li>
            {{ $notif->data['pesan'] ?? 'Ada notifikasi baru' }} <br>
            <small>{{ $notif->created_at->diffForHumans() }}</small>
        </li>
    @endforeach
    </ul>

    <p>Selamat datang, {{ $pegawai->nama_lengkap }} ({{ $pegawai->jabatan->nama_jabatan }})</p>

    <p class="mt-4 text-gray-500 italic">Halaman ini masih kosong.</p>
</div>
@endsection
