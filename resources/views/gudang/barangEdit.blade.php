@extends('layouts.app-gudang')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow mt-6">
    <div class="mb-4">
        <a href="{{ route('gudang.barang.index') }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-2xl font-bold mb-4">Edit Barang Titipan</h2>

    <form action="{{ route('gudang.barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Kategori --}}
        <div class="mb-4">
            <label class="block font-semibold">Kategori</label>
            <select name="kategori_id" class="w-full border rounded px-3 py-2" required>
                @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" {{ $barang->kategori_id == $kategori->id ? 'selected' : '' }}>
                        {{ $kategori->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Nama --}}
        <div class="mb-4">
            <label class="block font-semibold">Nama Barang</label>
            <input type="text" name="nama" value="{{ old('nama', $barang->nama) }}"
                   class="w-full border rounded px-3 py-2" required maxlength="255">
        </div>

        {{-- Deskripsi --}}
        <div class="mb-4">
            <label class="block font-semibold">Deskripsi</label>
            <textarea name="deskripsi" rows="3" class="w-full border rounded px-3 py-2" required>{{ old('deskripsi', $barang->deskripsi) }}</textarea>
        </div>

        {{-- Harga --}}
        <div class="mb-4">
            <label class="block font-semibold">Harga</label>
            <input type="text" name="harga" value="{{ old('harga', $barang->harga) }}"
                   class="w-full border rounded px-3 py-2" pattern="[0-9]+" title="Hanya angka tanpa titik/koma" required>
        </div>

        <div class="mb-3">
            <label>Berat Barang (kg)</label>
            <input type="number" step="0.01" name="berat" value="{{ $barang->berat }}" class="w-full border rounded p-2" required>
        </div>

        {{-- Thumbnail --}}
        <div class="mb-4">
            <label class="block font-semibold">Thumbnail Saat Ini</label>
            <img src="{{ asset('images/barang/' . $barang->id . '/' . $barang->thumbnail) }}"
                 class="w-32 h-32 object-cover rounded shadow mb-2">

            <label class="block font-semibold">Ganti Thumbnail (opsional)</label>
            <input type="file" name="thumbnail" accept="image/jpeg" class="w-full">
            <p class="text-sm text-gray-500">Kosongkan jika tidak ingin mengganti thumbnail.</p>
        </div>

        {{-- Foto Lain --}}
        <div class="mb-4">
            <label class="block font-semibold">Foto Lain Saat Ini</label>
            @php $fotoLain = json_decode($barang->foto_lain, true); @endphp
            <div class="flex flex-wrap gap-2 mt-2">
                @if (is_array($fotoLain))
                    @foreach ($fotoLain as $foto)
                        <img src="{{ asset('images/barang/' . $barang->id . '/' . $foto) }}"
                             class="w-24 h-24 object-cover rounded border">
                    @endforeach
                @endif
            </div>

            <label class="block font-semibold mt-4">Tambah Foto Lain Baru (opsional)</label>
            <input type="file" name="foto_lain[]" multiple accept="image/jpeg" class="w-full">
            <p class="text-sm text-gray-500">Foto lama tidak akan dihapus. Kosongkan jika tidak menambah foto baru.</p>
        </div>

        {{-- Garansi --}}
        <div class="mb-4">
            <label class="block font-semibold">Garansi</label>
            <div class="flex items-center gap-4">
                <label class="inline-flex items-center">
                    <input type="radio" name="punya_garansi" value="1"
                           {{ $barang->garansi_berlaku_hingga ? 'checked' : '' }}
                           onchange="toggleGaransi(true)">
                    <span class="ml-2">Ada Garansi</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="punya_garansi" value="0"
                           {{ !$barang->garansi_berlaku_hingga ? 'checked' : '' }}
                           onchange="toggleGaransi(false)">
                    <span class="ml-2">Tidak Ada</span>
                </label>
            </div>
        </div>

        <div class="mb-4" id="tanggalGaransiField" style="{{ !$barang->garansi_berlaku_hingga ? 'display: none;' : '' }}">
            <label class="block font-semibold">Tanggal Garansi Berlaku Hingga</label>
            <input type="date" name="garansi_berlaku_hingga"
                   value="{{ old('garansi_berlaku_hingga', optional($barang->garansi_berlaku_hingga)->format('Y-m-d')) }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        {{-- Penitip --}}
        <div class="mb-4">
            <label class="block font-semibold">Penitip</label>
            <select name="penitip_id" class="w-full border rounded px-3 py-2" required>
                @foreach ($penitips as $penitip)
                    <option value="{{ $penitip->id }}" {{ $barang->penitip_id == $penitip->id ? 'selected' : '' }}>
                        {{ $penitip->username }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Submit --}}
        <div class="text-right">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function toggleGaransi(show) {
        const field = document.getElementById('tanggalGaransiField');
        field.style.display = show ? 'block' : 'none';
        if (!show) {
            const input = field.querySelector('input[name="garansi_berlaku_hingga"]');
            if (input) input.value = '';
        }
    }
</script>
@endpush

@endsection
