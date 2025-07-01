@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <a href="{{ route('pembeli.alamat.index') }}" class="text-green-600 hover:underline">← Kembali</a>

    <h2 class="text-xl font-bold mt-4 mb-6">Tambah Alamat Baru</h2>

    <form method="POST" action="{{ route('pembeli.alamat.store') }}">
        @csrf

        <div class=" sm:grid-cols-1 gap-4">
            <div>
                <label class="font-bold block mb-1" for="jalan">Nama Jalan</label>
                <input type="text" id="jalan" name="jalan" class="border rounded px-3 py-2 w-full" placeholder="Nama Jalan" required>
            </div>

            <div>
                <label class="font-bold block mb-1" for="no_bangunan">No. Rumah / Bangunan</label>
                <input type="text" id="no_bangunan" name="no_bangunan" class="border rounded px-3 py-2 w-full" placeholder="No. Rumah / Bangunan (Cth : 03)" required>
            </div>

            <div>
                <label class="font-bold block mb-1" for="kelurahan">Kelurahan</label>
                <input type="text" id="kelurahan" name="kelurahan" class="border rounded px-3 py-2 w-full" placeholder="Kelurahan" required>
            </div>

            <div>
                <label class="font-bold block mb-1" for="kecamatan">Kecamatan</label>
                <input type="text" id="kecamatan" name="kecamatan" class="border rounded px-3 py-2 w-full" placeholder="Kecamatan" required>
            </div>

            <div>
                <label class="font-bold block mb-1" for="kabupaten">Kabupaten/Kota</label>
                <input type="text" id="kabupaten" name="kabupaten" class="border rounded px-3 py-2 w-full" placeholder="Kabupaten/Kota" required>
            </div>

            <div>
                <label class="font-bold block mb-1" for="provinsi">Provinsi</label>
                <input type="text" id="provinsi" name="provinsi" class="border rounded px-3 py-2 w-full" placeholder="Provinsi" required>
            </div>

            <div class="sm:col-span-2">
                <label class="font-bold block mb-1" for="kode_pos">Kode Pos</label>
                <input type="text" id="kode_pos" name="kode_pos" class="border rounded px-3 py-2 w-full" placeholder="Kode Pos" required>
            </div>
        </div>

        <button type="submit" class="mt-6 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Simpan Alamat
        </button>
    </form>
</div>
@endsection
