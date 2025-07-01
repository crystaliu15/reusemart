@extends('layouts.app-admin')

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow p-8 rounded mt-10">
    <div class="mb-4">
        <a href="{{ url('/admin/dashboard') }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-xl font-bold mb-6">Data Pegawai</h2>

    <form method="GET" action="{{ route('admin.pegawai.index') }}" class="mb-6">
        <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Cari pegawai..." class="border px-3 py-2 rounded w-full sm:w-1/2">
        <button type="submit" class="mt-2 sm:mt-0 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Cari
        </button>
    </form>

    <table class="w-full text-sm border">
        <thead>
            <tr class="bg-gray-100">
                <th class="text-left py-2 px-3">Username</th>
                <th class="text-left py-2 px-3">Nama Lengkap</th>
                <th class="text-left py-2 px-3">Jabatan</th>
                <th class="text-left py-2 px-3">No. Telp</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pegawais as $pegawai)
            <tr class="border-t">
                <td class="py-2 px-3">{{ $pegawai->username }}</td>
                <td class="py-2 px-3">{{ $pegawai->nama_lengkap }}</td>
                <td class="py-2 px-3">{{ $pegawai->jabatan->nama_jabatan ?? '-' }}</td>
                <td class="py-2 px-3">{{ $pegawai->no_telp }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="py-2 px-3 text-center text-gray-500">Tidak ada data pegawai.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
