@extends('layouts.app-admin')

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow p-10 rounded mt-10">
    <div class="mb-4">
        <a href="{{ url('/jabatan') }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <a href="{{ route('admin.jabatan.pegawai.create', $jabatan->id) }}"
        class="bg-green-600 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-green-700">
        + Tambah Pegawai Baru
    </a>
    <h2 class="text-xl font-bold mb-4">Pegawai dengan Jabatan: {{ $jabatan->nama_jabatan }}</h2>

    <table class="w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2">Username</th>
                <th class="p-2">Email</th>
                <th class="p-2">Nama Lengkap</th>
                <th class="p-2">No Telp</th>
                <th class="p-2">Alamat</th>
                <th class="p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pegawais as $pegawai)
                <tr class="border-t">
                    <td class="p-2">{{ $pegawai->username }}</td>
                    <td class="p-2">{{ $pegawai->email }}</td>
                    <td class="p-2">{{ $pegawai->nama_lengkap }}</td>
                    <td class="p-2">{{ $pegawai->no_telp }}</td>
                    <td class="p-2">{{ $pegawai->alamat_rumah }}</td>
                    <td class="p-2">
                        <a href="{{ route('admin.pegawai.edit', $pegawai->id) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('admin.pegawai.destroy', $pegawai->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pegawai ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-4 text-center">Tidak ada pegawai untuk jabatan ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
