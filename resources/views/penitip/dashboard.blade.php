@extends('layouts.app-penitip')

@section('content')
<div class="max-w-5xl mx-auto mt-10 bg-white p-6 rounded shadow space-y-8">
    {{-- Notifikasi --}}
    <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-700 p-4 rounded">
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-lg font-semibold"><i class="fas fa-bell mr-2"></i>Notifikasi Terbaru</h2>
            <form method="POST" action="{{ route('penitip.notifikasi.baca-semua') }}">
                @csrf
                <button type="submit" class="text-xs text-blue-600 hover:underline">Tandai Semua Sudah Dibaca</button>
            </form>
        </div>

        @if(auth('penitip')->user()->unreadNotifications->isEmpty())
            <p class="text-sm text-gray-600">Tidak ada notifikasi baru.</p>
        @else
            <ul class="space-y-2 text-sm">
                @foreach(auth('penitip')->user()->unreadNotifications as $notif)
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

    {{-- Informasi Penitip --}}
    <div class="flex items-center space-x-4">
        <img src="{{ $penitip->profile_picture ? asset('storage/' . $penitip->profile_picture) : asset('images/default-user.png') }}"
             class="w-24 h-24 rounded-full object-cover border">
        <div>
            <h2 class="text-xl font-bold">Username : {{ $penitip->username }}</h2>
            <p><i class="fas fa-envelope mr-1"></i>Email✉️ : {{ $penitip->email }}</p>
            <p><i class="fas fa-phone mr-1"></i>No Telp☎️ : {{ $penitip->no_telp }}</p>
            <p class="text-lg">Saldo: Rp{{ number_format($penitip->saldo, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Tombol Edit --}}
    <div class="mt-3">
        <a href="{{ route('penitip.profil.edit') }}"
        class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
        Edit Profil
        </a>
    </div>

    {{-- Barang Aktif dengan Pencarian --}}
    <div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">
        <h2 class="text-xl font-bold mb-4">Barang yang Sedang Dititipkan</h2>

        <form method="GET" class="mb-4">
            <div class="flex items-center">
                <input type="text" name="cari" placeholder="Cari nama barang..." class="border px-3 py-2 rounded flex-1" value="{{ request('cari') }}">
                <button class="bg-blue-500 text-white px-3 py-2 rounded ml-2 hover:bg-blue-600 transition">
                    <i class="fas fa-search mr-1"></i> Cari
                </button>
            </div>
        </form>

        @if($barangAktif->isEmpty())
            <p class="text-sm text-gray-500 py-4 text-center">Tidak ada barang aktif saat ini.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                @foreach($barangAktif as $barang)
                    <div class="border rounded p-3 bg-white hover:shadow-md transition duration-200 flex flex-col">
                        <div class="relative">
                            <img src="{{ asset('images/barang/' . $barang->id . '/' . $barang->thumbnail) }}"
                                 class="w-full h-40 object-cover rounded mb-2">
                            <span class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full">Aktif</span>
                        </div>
                        <h4 class="font-semibold text-sm">{{ $barang->nama }}</h4>
                        <p class="text-sm text-gray-600">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">Kategori: {{ $barang->kategori->nama ?? 'Tanpa kategori' }}</p>
                        
                        {{-- Informasi masa penitipan --}}
                        <p class="text-xs text-gray-600 mt-2">Masa Titip Hingga: <strong>{{ \Carbon\Carbon::parse($barang->batas_waktu_titip)->format('d-m-Y') }}</strong></p>

                        {{-- Status perpanjangan --}}
                        <p class="text-xs mt-1">
                            Status Perpanjangan:
                            @if($barang->status_perpanjangan)
                                <span class="text-green-600 font-medium">TRUE ✅</span>
                            @else
                                <span class="text-red-600 font-medium">FALSE ❌</span>
                            @endif
                        </p>

                        {{-- Tombol Perpanjangan --}}
                        @if(!$barang->status_perpanjangan)
                            <form action="{{ route('penitip.barang.perpanjang', $barang->id) }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit" onclick="return confirm('Perpanjang masa penitipan barang ini selama 30 hari?')"
                                    class="bg-yellow-500 text-white text-xs px-3 py-1 rounded hover:bg-yellow-600 w-full">
                                    ⏳ Perpanjang 30 Hari
                                </button>
                            </form>
                        @endif

                        <div class="mt-auto pt-3 flex flex-col space-y-2">

                            {{-- Tombol Konfirmasi --}}
                            @if (!$barang->status_pengambilan)
                                <form action="{{ route('penitip.barang.konfirmasi-pengambilan', $barang->id) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirm('Apakah Anda yakin akan mengkonfirmasi pengambilan barang ini?')"
                                            class="w-full text-xs bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600 transition">
                                        Konfirmasi Pengambilan
                                    </button>
                                </form>
                            @else
                                <div class="text-xs text-green-600 font-medium text-center bg-green-100 px-2 py-1 rounded">
                                    ✅ Siap Diambil
                                </div>
                            @endif

                            {{-- Tombol Detail --}}
                            <a href="{{ route('penitip.barang.show', $barang->id) }}" 
                               class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded inline-block text-center">
                               Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-4">
                {{ $barangAktif->links() }}
            </div>
        @endif
    </div>

    {{-- Barang Terjual (Riwayat) --}}
    <div>
        <h3 class="text-lg font-semibold mb-3 mt-8">Riwayat Barang yang Sudah Terjual</h3>
        @if($barangTerjual->isEmpty())
            <p class="text-sm text-gray-500">Belum ada barang yang terjual.</p>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($barangTerjual as $barang)
                    <a href="{{ route('penitip.barang.riwayat', $barang->id) }}" class="border rounded p-3 hover:shadow transition">
                        <img src="{{ asset('images/barang/' . $barang->id . '/' . $barang->thumbnail) }}"
                             class="w-full h-32 object-cover rounded mb-2">
                        <h4 class="font-semibold text-sm">{{ $barang->nama }}</h4>
                        <p class="text-sm text-gray-600">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                        <span class="text-xs text-red-600 font-medium">✅ Sudah Terjual</span>
                    </a>
                @endforeach
            </div>
            
            <div class="mt-4">
                {{ $barangTerjual->links() }}
            </div>
        @endif
    </div>

</div>
@endsection