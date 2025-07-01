@extends('layouts.app-admin')

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow p-10 rounded mt-10">
    <div class="mb-4">
        <a href="{{ url('/admin/dashboard') }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-xl font-bold mb-4">Kelola Jabatan</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-800 p-2 rounded mb-3">
            {{ $errors->first() }}
        </div>
    @endif

    <a href="{{ route('admin.jabatan.create') }}" class="bg-green-600 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-green-700">
        + Tambah Jabatan Baru
    </a>

    <a href="{{ route('admin.hunter.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded mb-6 inline-block hover:bg-indigo-700 ml-2">
        + Tambah Hunter Baru
    </a>

    <table class="w-full border border-gray-300 table-auto">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 border border-gray-300 text-left">ID</th>
                <th class="p-3 border border-gray-300 text-left">Nama Jabatan</th>
                <th class="p-3 border border-gray-300 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($jabatans as $jabatan)
                <tr class="hover:bg-gray-50">
                    <td class="p-3 border border-gray-300">{{ $jabatan->id }}</td>
                    <td class="p-3 border border-gray-300">{{ $jabatan->nama_jabatan }}</td>
                    <td class="p-3 border border-gray-300">
                        <div class="mt-1 flex flex-wrap gap-3">
                            <a href="{{ route('admin.jabatan.edit', $jabatan->id) }}" class="text-blue-600 hover:underline">Edit</a>

                            <form method="POST" action="{{ route('admin.jabatan.destroy', $jabatan->id) }}" onsubmit="return confirm('Hapus jabatan ini?')" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>

                            <a href="{{ route('admin.jabatan.pegawai', $jabatan->id) }}" class="text-indigo-600 hover:underline">Lihat Pegawai</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
