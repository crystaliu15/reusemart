@extends('layouts.app-gudang')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow mt-6">
    <div class="mb-4">
        <a href="{{ url('/gudang/dashboard') }}"
           class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-2xl font-bold mb-4">Tambah Barang Titipan</h2>

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

    <form action="{{ route('gudang.barang.store') }}" method="POST" enctype="multipart/form-data">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @csrf

        <div class="mb-4">
            <label class="block font-semibold">Kategori</label>
            <select name="kategori_id" class="w-full border rounded px-3 py-2 @error('kategori_id') border-red-500 @enderror" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->id }}">
                        {{ $kategori->nama }}
                    </option>
                @endforeach
            </select>
            @error('kategori_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Nama Barang</label>
            <input type="text" name="nama" value="{{ old('nama') }}"
                class="w-full border rounded px-3 py-2 @error('nama') border-red-500 @enderror" required maxlength="255">
            @error('nama')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Deskripsi</label>
            <textarea name="deskripsi" class="w-full border rounded px-3 py-2 @error('deskripsi') border-red-500 @enderror" rows="3">{{ old('deskripsi') }}</textarea>
            @error('deskripsi')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Harga</label>
            <input type="text" name="harga" value="{{ old('harga') }}"
                pattern="[0-9]+" title="Hanya angka tanpa titik/koma"
                class="w-full border rounded px-3 py-2 @error('harga') border-red-500 @enderror" required>
            @error('harga')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-3">
            <label>Berat Barang (kg)</label>
            <input type="number" step="0.01" name="berat" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-2">Thumbnail</label>
            <label for="thumbnailInput" class="cursor-pointer inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Pilih Thumbnail
            </label>
            <input type="file" name="thumbnail" id="thumbnailInput" accept="image/jpeg" class="hidden @error('thumbnail') border-red-500 @enderror" required>
            <div id="thumbnailPreview" class="mt-4"></div>
            @error('thumbnail')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-2">Foto Lain (boleh lebih dari satu)</label>
            <label for="fotoLainInput" class="cursor-pointer inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Pilih Foto
            </label>
            <input type="file" id="fotoLainInput" name="foto_lain[]" accept="image/jpeg" class="hidden" multiple>
            <div id="previewContainer" class="mt-4 flex flex-wrap gap-4"></div>
            @error('foto_lain.*')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Garansi</label>
            <div class="flex items-center gap-4">
                <label class="inline-flex items-center">
                    <input type="radio" name="punya_garansi" value="1" class="mr-2" onchange="toggleGaransi(true)">
                    Ada Garansi
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="punya_garansi" value="0" class="mr-2" onchange="toggleGaransi(false)">
                    Tidak Ada Garansi
                </label>
            </div>
            @error('punya_garansi')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4" id="tanggalGaransiField" style="display: {{ old('punya_garansi') == '1' ? 'block' : 'none' }};">
            <label class="block font-semibold">Tanggal Garansi Berlaku Hingga</label>
            <input type="date" name="garansi_berlaku_hingga" value="{{ old('garansi_berlaku_hingga') }}" class="w-full border rounded px-3 py-2 @error('garansi_berlaku_hingga') border-red-500 @enderror">
            @error('garansi_berlaku_hingga')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Penitip</label>
            <select name="penitip_id" class="w-full border rounded px-3 py-2 @error('penitip_id') border-red-500 @enderror" required>
                <option value="">-- Pilih Penitip --</option>
                @foreach ($penitips as $penitip)
                    <option value="{{ $penitip->id }}">{{ $penitip->username }}</option>
                @endforeach
            </select>
            @error('penitip_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="text-right">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Simpan Barang
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

    const thumbnailInput = document.getElementById('thumbnailInput');
    const thumbnailPreview = document.getElementById('thumbnailPreview');
    thumbnailInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (!file || !file.type.includes('jpeg')) {
            alert('Hanya file .jpg yang diperbolehkan untuk thumbnail.');
            thumbnailInput.value = '';
            thumbnailPreview.innerHTML = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function (e) {
            thumbnailPreview.innerHTML = `<img src="${e.target.result}" class="w-32 h-32 object-cover rounded shadow" alt="Preview Thumbnail">`;
        };
        reader.readAsDataURL(file);
    });

    const fotoInput = document.getElementById('fotoLainInput');
    const previewContainer = document.getElementById('previewContainer');
    fotoInput.addEventListener('change', function (event) {
        const newFiles = Array.from(event.target.files);
        previewContainer.innerHTML = '';
        newFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const wrapper = document.createElement('div');
                wrapper.classList.add('relative', 'w-32', 'h-32');
                const image = document.createElement('img');
                image.src = e.target.result;
                image.classList.add('w-full', 'h-full', 'object-cover', 'rounded', 'shadow');
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.innerHTML = '✕';
                removeBtn.classList.add('absolute', 'top-0', 'right-0', 'bg-red-500', 'text-white', 'rounded-full', 'px-1', 'text-xs');
                removeBtn.onclick = () => wrapper.remove();
                wrapper.appendChild(image);
                wrapper.appendChild(removeBtn);
                previewContainer.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush
@endsection