@extends('layouts.app-gudang')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white rounded shadow mt-6">
        <div class="mb-4">
        <a href="{{ url('/gudang/form-jumlah') }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-2xl font-bold mb-6">Isi Data {{ $jumlah }} Barang untuk Penitip: {{ $penitip->username }}</h2>

    <form action="{{ route('gudang.barang.multiStore') }}" method="POST" enctype="multipart/form-data">
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @csrf
        <input type="hidden" name="penitip_id" value="{{ $penitip->id }}">
        <input type="hidden" name="jumlah" value="{{ $jumlah }}">

        @for ($i = 0; $i < $jumlah; $i++)
        <div class="mb-8 border-t pt-6">
            <h3 class="text-lg font-semibold mb-4">Barang ke-{{ $i + 1 }}</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold">Nama</label>
                    <input type="text" name="nama[]" required class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block font-semibold">Kategori</label>
                    <select name="kategori_id[]" required class="w-full border rounded px-3 py-2">
                        <option value="">-- Pilih --</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="block font-semibold">Deskripsi</label>
                    <textarea name="deskripsi[]" class="w-full border rounded px-3 py-2" rows="3"></textarea>
                </div>

                <div>
                    <label class="block font-semibold">Harga</label>
                    <input type="number" name="harga[]" required class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block font-semibold">Berat (kg)</label>
                    <input type="number" step="0.01" name="berat[]" required class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block font-semibold">Thumbnail (.jpg)</label>
                    <input type="file" name="thumbnail[]" accept="image/jpeg" required class="w-full">
                </div>

                <div>
                    <label class="block font-semibold">Foto Lain (Minimal 2)</label>
                    <input type="file" name="foto_lain[{{ $i }}][]" accept="image/jpeg" multiple required class="w-full">
                </div>

                <div>
                    <label class="block font-semibold">Garansi</label>
                    <select name="punya_garansi[]" class="w-full border rounded px-3 py-2" onchange="toggleGaransi(this, {{ $i }})">
                        <option value="0">Tidak Ada</option>
                        <option value="1">Ada</option>
                    </select>
                </div>

                <div id="garansi_{{ $i }}" style="display:none;">
                    <label class="block font-semibold">Tanggal Garansi Berlaku Hingga</label>
                    <input type="date" name="garansi_berlaku_hingga[]" class="w-full border rounded px-3 py-2">
                </div>
            </div>
        </div>
        @endfor

        <div class="text-right">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Simpan Semua Barang
            </button>
        </div>
    </form>
</div>

<script>
    function toggleGaransi(select, index) {
        const field = document.getElementById(`garansi_${index}`);
        field.style.display = select.value == '1' ? 'block' : 'none';
    }
</script>


@endsection
