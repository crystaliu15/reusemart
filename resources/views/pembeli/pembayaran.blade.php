@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-6 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Pembayaran</h2>

    @php
        $totalBarang = 0;
    @endphp

    <div class="space-y-4">
        @foreach($items as $item)
            @php
                $barang = $item->barang;
                $harga = $barang->harga ?? 0;
                $totalBarang += $harga;
            @endphp
            <div class="flex items-center justify-between border-b pb-2">
                <div>
                    <p class="font-semibold">{{ $barang->nama }}</p>
                    <p class="text-sm text-gray-600">Rp {{ number_format($harga, 0, ',', '.') }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Metode Pengiriman --}}
    <div class="mt-6">
        <h3 class="font-bold text-lg">Pilih Metode</h3>
        <div class="mt-2 space-y-2">
            <label class="inline-flex items-center">
                <input type="radio" name="tipe_pengiriman" value="ambil" checked onchange="toggleAlamat(false)">
                <span class="ml-2">Ambil di Tempat</span>
            </label>
            <label class="inline-flex items-center">
                <input type="radio" name="tipe_pengiriman" value="kirim" onchange="toggleAlamat(true)">
                <span class="ml-2">Pengiriman</span>
            </label>
        </div>
    </div>

    {{-- Alamat Pengiriman --}}
    <div id="alamatSection" class="mt-4 hidden">
        <h4 class="font-semibold mb-2">Pilih Alamat Pengiriman</h4>
        <select class="w-full border rounded px-3 py-2">
            @foreach ($alamatList as $alamat)
                <option value="{{ $alamat->id }}" {{ $alamatDefault && $alamatDefault->id == $alamat->id ? 'selected' : '' }}>
                    {{ $alamat->alamat }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Tukar Poin --}}
    <div class="mt-6">
        <label class="font-semibold block mb-1">Tukar Poin (100 poin = Rp 10.000)</label>
        <p class="text-sm text-gray-600 mb-2">Poin yang kamu miliki: <strong>{{ $poinPembeli }}</strong></p>

        <div class="flex items-center space-x-2">
            <button type="button" onclick="adjustPoin(-100)" class="px-3 py-1 bg-gray-300 rounded">-</button>
            <input type="text" id="poinInput" class="border rounded px-3 py-2 w-24 text-center"
                value="0" readonly>
            <button type="button" onclick="adjustPoin(100)" class="px-3 py-1 bg-gray-300 rounded">+</button>
        </div>
    </div>

    {{-- Total --}}
    <div class="mt-6 border-t pt-4 text-right">
        <p>Total Barang: <strong id="totalBarangText">Rp {{ number_format($totalBarang, 0, ',', '.') }}</strong></p>
        <p>Ongkos Kirim: <strong id="ongkirText">Rp 0</strong></p>
        <p>Potongan dari Poin: <strong id="potonganText">Rp 0</strong></p>
        <h3 class="text-xl font-bold mt-2">Total Bayar: <span id="totalBayarText">Rp {{ number_format($totalBarang, 0, ',', '.') }}</span></h3>
    </div>

    <input type="hidden" id="totalBarangValue" value="{{ $totalBarang }}">
    <input type="hidden" id="maxPoinPembeli" value="{{ $poinPembeli }}">

    {{-- Tombol Bayar --}}
    <div class="mt-6 text-right">
        <form method="POST" action="{{ route('pembeli.transaksi.proses') }}">
            @csrf

            <input type="hidden" name="tipe_pengiriman" id="tipe_pengiriman_hidden" value="ambil">
            <input type="hidden" name="alamat_pengiriman_id" id="alamat_pengiriman_hidden" value="{{ $alamatDefault->id ?? '' }}">
            <input type="hidden" name="poin_ditukar" id="poin_ditukar_hidden" value="0">

            <button type="submit" class="mt-6 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Lanjutkan Pembayaran
            </button>
        </form>
    </div>
</div>

{{-- Script --}}
<script>
    function toggleAlamat(show) {
        const alamatSection = document.getElementById('alamatSection');
        alamatSection.style.display = show ? 'block' : 'none';
        updateOngkir();
    }

    function updateOngkir() {
        const totalBarang = parseInt(document.getElementById('totalBarangValue').value);
        const isPengiriman = document.querySelector('input[name="tipe_pengiriman"]:checked').value === 'kirim';

        let ongkir = 0;
        if (isPengiriman) {
            ongkir = totalBarang < 1500000 ? 100000 : 0;
        }

        const maxPoin = parseInt(document.getElementById('maxPoinPembeli').value);
        let poinDitukar = parseInt(document.getElementById('poinInput').value) || 0;

        if (poinDitukar > maxPoin) poinDitukar = Math.floor(maxPoin / 100) * 100;
        if (poinDitukar < 0) poinDitukar = 0;

        const potongan = Math.floor(poinDitukar / 100) * 10000;
        const totalBayar = totalBarang + ongkir - potongan;

        document.getElementById('poinInput').value = poinDitukar;
        document.getElementById('ongkirText').innerText = formatRupiah(ongkir);
        document.getElementById('potonganText').innerText = formatRupiah(potongan);
        document.getElementById('totalBayarText').innerText = formatRupiah(Math.max(totalBayar, 0));

        document.getElementById('tipe_pengiriman_hidden').value = isPengiriman ? 'kirim' : 'ambil';
        document.getElementById('poin_ditukar_hidden').value = poinDitukar;

        const alamatSelect = document.querySelector('#alamatSection select');
        if (alamatSelect) {
            document.getElementById('alamat_pengiriman_hidden').value = alamatSelect.value;
        }
    }


    function formatRupiah(number) {
        return 'Rp ' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function adjustPoin(change) {
        const maxPoin = parseInt(document.getElementById('maxPoinPembeli').value);
        let poinDitukar = parseInt(document.getElementById('poinInput').value) || 0;

        poinDitukar += change;

        // Pastikan kelipatan 100 dan dalam batas
        if (poinDitukar < 0) poinDitukar = 0;
        if (poinDitukar > maxPoin) poinDitukar = Math.floor(maxPoin / 100) * 100;

        // Update input dan tampilan
        document.getElementById('poinInput').value = poinDitukar;
        updateOngkir();
    }

    // Inisialisasi
    document.querySelectorAll('input[name="tipe_pengiriman"]').forEach(el => {
        el.addEventListener('change', updateOngkir);
    });
    updateOngkir();
</script>

@endsection
