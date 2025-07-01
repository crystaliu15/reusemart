@extends('layouts.app-cs')

@section('content')
<div class="max-w-xl mx-auto mt-6 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Edit Barang</h2>

    <form method="POST" action="{{ route('cs.barang.update', $barang->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Barang</label>
            <input type="text" name="nama" value="{{ $barang->nama }}" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" value="{{ $barang->harga }}" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="3" class="w-full border p-2 rounded">{{ $barang->deskripsi }}</textarea>
        </div>

        <div class="mb-3">
            <label>Kategori</label>
            <select name="kategori_id" class="w-full border p-2 rounded" required>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" {{ $kategori->id == $barang->kategori_id ? 'selected' : '' }}>
                        {{ $kategori->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Penitip</label>
            <select name="penitip_id" class="w-full border p-2 rounded" required>
                @foreach($penitips as $p)
                    <option value="{{ $p->id }}" {{ $p->id == $barang->penitip_id ? 'selected' : '' }}>
                        {{ $p->username }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Thumbnail (opsional)</label><br>
            <img src="{{ asset('images/barang/' . $barang->id . '/' . $barang->thumbnail) }}"
                 class="h-24 mb-2 rounded shadow">
            <input type="file" name="thumbnail" class="w-full border p-2 rounded">
        </div>

        <div class="mb-3">
            <label>Foto Lainnya (bisa pilih lebih dari 1)</label>
            <input type="file" name="foto_lain[]" multiple accept="image/*" class="w-full border p-2 rounded">
        </div>

        @if($barang->foto_lain)
            <div class="mb-3">
                <label class="block mb-1 font-semibold">Foto Lain Saat Ini:</label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(json_decode($barang->foto_lain) as $foto)
                        <img src="{{ asset('images/barang/' . $barang->id . '/' . $foto) }}"
                            alt="Foto Lain"
                            class="h-24 object-cover rounded shadow">
                    @endforeach
                </div>
            </div>
        @endif

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Simpan Perubahan</button>
    </form>
</div>
@endsection
