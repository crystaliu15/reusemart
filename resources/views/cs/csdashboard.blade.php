@extends('layouts.app-cs')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded shadow mt-6">
    <h2 class="text-2xl font-bold mb-4">Dashboard CS</h2>

    <!-- Profil CS -->
    <div class="mb-6 border rounded p-4">
        <h3 class="text-lg font-semibold mb-2">Profil Anda</h3>
        <p><strong>Username:</strong> {{ $cs->username }}</p>
        <p><strong>Nama Lengkap:</strong> {{ $cs->nama_lengkap }}</p>
        <p><strong>No. Telepon:</strong> {{ $cs->no_telp }}</p>
        <p><strong>Jabatan:</strong> {{ $cs->jabatan->nama_jabatan }}</p>
    </div>

    <!-- Aksi -->
    <div class="space-y-4"> 
        <a href="{{ route('cs.penitip.index') }}"
           class="block bg-blue-600 text-white px-4 py-3 rounded hover:bg-blue-700 text-center font-semibold">
            Kelola Data Penitip
        </a>

        <a href="{{ route('cs.diskusi.belumdibalas') }}"
            class="block bg-orange-600 text-white px-4 py-3 rounded hover:bg-orange-700 text-center font-semibold relative">
            💬 Lihat Diskusi Belum Dibalas
            @if($jumlahDiskusiBelumDibalas > 0)
                <span class="absolute top-0 right-0 -mt-1 -mr-2 bg-white text-red-600 font-bold text-xs rounded-full px-2 py-1 shadow">
                    {{ $jumlahDiskusiBelumDibalas }}
                </span>
            @endif
        </a>

        <a href="{{ route('cs.konfirmasi.index') }}"
            class="block bg-green-600 text-white px-4 py-3 rounded hover:bg-green-700 text-center font-semibold">
            ✅ Konfirmasi Bukti Transfer
        </a>

        <a href="{{ route('cs.barang.diproses') }}"
            class="block bg-purple-600 text-white px-4 py-3 rounded hover:bg-purple-700 text-center font-semibold">
            🚚 Barang Sedang Diproses
        </a>
    </div>
</div>
@endsection
