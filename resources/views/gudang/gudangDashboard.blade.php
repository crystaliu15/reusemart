@extends('layouts.app-gudang')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white rounded shadow mt-6">
    <h2 class="text-2xl font-bold mb-4">Dashboard Pegawai Gudang</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-gray-100 p-4 rounded shadow">
            <h3 class="font-semibold text-lg mb-2">Selamat datang, {{ Auth::guard('pegawai')->user()->nama_lengkap }}!</h3>
            <p class="text-sm text-gray-600">Email: {{ Auth::guard('pegawai')->user()->email }}</p>
            <p class="text-sm text-gray-600">No Telp: {{ Auth::guard('pegawai')->user()->no_telp }}</p>
        </div>

        <div class="bg-blue-100 p-4 rounded shadow">
            <h3 class="font-semibold text-lg mb-2">Informasi Jabatan</h3>
            <p class="text-sm text-gray-700">Jabatan: Pegawai Gudang</p>
            {{-- Jika ingin tampilkan dari relasi, bisa ganti ke: Auth::guard('pegawai')->user()->jabatan->nama_jabatan --}}
        </div>
    </div>
    
    <div class="mt-6">
        <a href="{{ route('gudang.barang.formJumlah') }}" 
            class="block bg-green-600 text-white text-center py-3 rounded shadow hover:bg-green-700 transition">
            + Tambah Barang Titipan
        </a>
    </div>

    <div class="mt-6">
        <a href="{{ route('gudang.barang.penitipList') }}"
        class="block bg-indigo-600 text-white text-center py-3 rounded shadow hover:bg-indigo-700 transition">
            📦 Lihat Daftar Penitip
        </a>
    </div>

    <div class="mt-6">
        <a href="{{ route('gudang.barang.index') }}"
        class="block bg-gray-600 text-white text-center py-3 rounded shadow hover:bg-gray-700 transition">
            📋 Lihat Semua Barang Titipan
        </a>
    </div>

    <div class="mt-6">
        <a href="{{ route('gudang.barang.mendekatiBatas') }}"
        class="block bg-red-600 text-white text-center py-3 rounded shadow hover:bg-red-700 transition">
            ⏳ Barang Mendekati Batas Titip (≤ 3 Hari)
        </a>
    </div>

    <div class="mt-6">
        <a href="{{ route('gudang.barang.transaksi') }}"
            class="block bg-yellow-500 text-white text-center py-3 rounded shadow hover:bg-yellow-600 transition">
            📑 Lihat Daftar Transaksi Pengiriman
        </a>
    </div>
</div>
@endsection
