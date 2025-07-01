@extends('layouts.app-cs')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow mt-6">
    <h2 class="text-xl font-bold mb-4">Tambah Barang Baru</h2>

    <form method="POST" action="{{ route('cs.barang.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Nama Barang</label>
            <input type="text" name="nama" class="w-full border p-2 rounded" required>
        </div>
        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" class="w-full border p-2 rounded" required>
        </div>
        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="3" class="w-full border p-2 rounded"></textarea>
        </div>
        <div class="mb-3">
            <label>Penitip</label>
            <select name="penitip_id" class="w-full border p-2 rounded" required>
                <option value="">-- Pilih Penitip --</option>
                @foreach($penitips as $p)
                    <option value="{{ $p->id }}">{{ $p->username }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Kategori</label>
            <select name="kategori_id" class="w-full border p-2 rounded" required>
                @foreach(\App\Models\Kategori::all() as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Thumbnail</label>
            <input type="file" name="thumbnail" accept="image/*" class="w-full border p-2 rounded" required>
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Simpan</button>
    </form>
</div>
@endsection
