@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <div class="mb-4">
        <a href="{{ url('/profil') }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <div class="max-w-2xl mx-auto mt-6">
        
        <h2 class="text-xl font-bold mb-4">Daftar Alamat</h2>

        <div class="mb-6">
            <a href="{{ route('pembeli.alamat.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                + Tambah Alamat Baru
            </a>
        </div>

        <form method="GET" action="{{ route('pembeli.alamat.index') }}" class="mb-6">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari alamat..." class="w-full sm:w-1/2 border px-4 py-2 rounded">
            <button type="submit" class="mt-2 sm:mt-0 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Cari
            </button>
        </form>

        @if(session('success'))
            <div class="mb-4 bg-green-100 text-green-800 p-2 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-4">
            @if($alamatList->isEmpty())
                <div class="bg-yellow-100 text-yellow-800 p-4 rounded text-sm text-center">
                    @if(request('search'))
                        Alamat dengan kata kunci <strong>"{{ request('search') }}"</strong> tidak ditemukan.
                    @else
                        Anda belum menambahkan alamat Anda.<br>
                        <strong>Ayo tambahkan alamat untuk bisa membeli barang di ReuseMart!</strong>
                    @endif
                </div>
            @else
                <div class="grid gap-4">
                    @foreach($alamatList as $alamat)
                        <div class="p-4 border rounded {{ $alamat->id == $defaultAlamatId ? 'bg-green-50 border-green-600' : 'border-gray-300' }}">
                            <p class="mb-2">{{ $alamat->alamat }}</p>

                            <div class="flex gap-4 items-center">
                                @if($alamat->id == $defaultAlamatId)
                                    <span class="text-green-600 font-semibold">Alamat Utama</span>
                                @else
                                    <form action="{{ route('pembeli.alamat.setDefault', $alamat->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:underline">Jadikan Utama</button>
                                    </form>
                                @endif

                                <a href="{{ route('pembeli.alamat.edit', $alamat->id) }}" class="text-yellow-600 hover:underline">Edit</a>

                                <form action="{{ route('pembeli.alamat.destroy', $alamat->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus alamat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        
    </div>
</div>
@endsection
