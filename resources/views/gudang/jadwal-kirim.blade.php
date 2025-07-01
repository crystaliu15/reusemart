@extends('layouts.app-gudang')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white shadow rounded mt-6">
    <div class="mb-4">
        <a href="{{ route('gudang.barang.transaksi') }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-xl font-bold mb-4">Jadwalkan Pengiriman</h2>

    @if(session('error'))
        <div class="text-red-600 mb-2">{{ session('error') }}</div>
    @endif

    {{-- Tampilkan info jadwal yang sudah disimpan --}}
    @if($barang->jadwalPengirimen)
        <div class="bg-gray-100 p-4 rounded mb-4">
            <h3 class="font-semibold text-lg mb-2 text-green-700">Jadwal Tersimpan</h3>
            <p><strong>Tanggal & Waktu:</strong> {{ \Carbon\Carbon::parse($barang->jadwalPengirimen->jadwal_kirim)->translatedFormat('d F Y, H:i') }}</p>
            @foreach($pegawais as $pegawai)
                <p><strong>Kurir:</strong> {{ $pegawai->nama_lengkap }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('gudang.barang.simpanJadwal', $barang->id) }}">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold mb-1">Jadwal Kirim:</label>
            <input type="datetime-local" 
                   name="jadwal_kirim" 
                   class="border w-full p-2 rounded" 
                   required
                   value="{{ old('jadwal_kirim', $barang->jadwalPengirimen ? \Carbon\Carbon::parse($barang->jadwalPengirimen->jadwal_kirim)->format('Y-m-d\TH:i') : '') }}">
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Pilih Kurir (Pegawai):</label>
            <select name="pegawai_id" class="border w-full p-2 rounded" required>
                <option value="">-- Pilih Pegawai --</option>
                @foreach($pegawais as $pegawai)
                    <option value="{{ $pegawai->id }}" 
                        {{ old('pegawai_id', $barang->jadwalPengirimen->pegawai_id ?? '') == $pegawai->id ? 'selected' : '' }}>
                        {{ $pegawai->nama_lengkap }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Simpan Jadwal</button>
            <a href="{{ route('gudang.barang.transaksi') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
        </div>
    </form>
</div>
@endsection
