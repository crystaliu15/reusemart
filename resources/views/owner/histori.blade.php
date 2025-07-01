@extends('layouts.app-owner')

@section('content')
<div class="max-w-6xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <div class="mb-4">
        <a href="{{ url('/owner/dashboard') }}"
            class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-xl font-bold mb-4">Histori Barang Donasi</h2>

    <form method="GET" action="{{ route('owner.histori') }}" class="mb-6">
        <label for="alamat" class="font-medium">Cari berdasarkan alamat organisasi:</label>
        <div class="flex gap-2 mt-2">
            <input type="text" name="alamat" id="alamat"
                value="{{ request('alamat') }}"
                class="w-full border rounded px-3 py-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Cari
            </button>
        </div>
    </form>

    @if($historiDonasi->isEmpty())
        <p class="text-gray-500">Belum ada barang yang didonasikan.</p>
    @else
    <div class="mb-4">
        <a href="{{ url('/owner/histori-donasi/pdf') }}" target="_blank"
            class="inline-block bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
            🖨️ Cetak PDF
        </a>
        <a href="{{ url('/owner/histori-donasi/html') }}" target="_blank"
            class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            📄 Cetak Data
        </a>
    </div>

    <table class="w-full text-sm">
        <thead>
            <tr class="text-left border-b">
        <th>Kode Produk</th>
        <th>Nama Produk</th>
        <th>ID Penitip</th> {{-- Kolom baru --}}
        <th>Nama Penitip</th> {{-- Kolom baru --}}
        <th>Tanggal Donasi</th>
        <th>Organisasi</th>
        <th>Nama Penerima</th>
        <th>Aksi</th>
    </tr>
        </thead>
        <tbody>
            @php
    $kodeProdukMap = [
        'Kipas Angin Portable' => 'K101',
        'Headset Gigi Biru' => 'K102',
        'LEGO Classic Creative Bricks (10696)' => 'K103',
        'Barbie Dreamhouse Playset' => 'K104',
        'Transformers One Blokees Bumblbee' => 'K105',
        'Transformers One Blokees Optimus Prime' => 'K106',
        'Transformers Shockwave BMB' => 'K107',
        'Transformers Classic Bumblebee ROTB' => 'K108',
        'Transformers Blokees Galaxy Grimlock' => 'K109',
        'Transformers ROBOT PERANG' => 'K110',
        'Transformers Optimum Pride' => 'K111',
        'PlayStation 5 Console' => 'K112',
        'Nintendo Switch OLED' => 'K113',
        'Jordan XXXV Jayson Tatum Women in Power PE' => 'K201',
        'Jordan 4 Retro SE Sashiko' => 'K202',
        'Jordan 7 Retro Miro' => 'K203',
        'Jordan 1 High Zoom Air CMFT 2 Día De Muertos' => 'K204',
        'Jordan 5 Retro Wings' => 'K205',
        'Jordan 3 Retro Patchwork Camo' => 'K206',
        'Jordan 4 Retro What The' => 'K207',
        'Jordan 5 Retro What The' => 'K208',
        'Rak Buku 5 Tingkat' => 'K301',
        'Ban Mobil Bridgestone 185/65 R15' => 'K401',
        'Helm Full Face KYT RC Seven' => 'K402',
        'Stroller Bayi Cocolatte' => 'K501',
        'Botol Susu Pigeon 240ml' => 'K502',
        'Raket Badminton Yonex' => 'K601',
        'Dumbbell Set 20kg' => 'K602',
        'Set Pensil Warna Faber Castell 48' => 'K701',
        'Stabilo Highlighter Set 6 Warna' => 'K702',
        'Jam Dinding Antik Kayu Jati' => 'K801',
        'Keris Jawa Kuno dengan Warangka' => 'K802',
        'Gitar Elektrik Fender Squier' => 'K901',
        'Cajon Meinl Percussion' => 'K902',
        'Sprayer Elektrik 16L' => 'K121',
        'Cangkul Garpu Stainless' => 'K122',
    ];
     $idPenitipMap = [
        'Carlos' => 'T01',
        'Badrol' => 'T02',
        'Bambang' => 'T03',
        'Purnomo' => 'T04',
        'Janti' => 'T05',
        'Keulana' => 'T06',
        'Yulianti' => 'T07',
        'Rahayu' => 'T08',
        'Fransiskus' => 'T09',
        'Asisi' => 'T10',
        'Timmy' => 'T11',
        'Budi' => 'T12',
        'Yudhantara' => 'T13',
        'Rodjim' => 'T14',
        'Bae' => 'T15',
        'Warsito' => 'T16',
        'Nugroho' => 'T17',
        'Petrus' => 'T18',
        'Yohanes' => 'T19',
        'Santoso' => 'T20',
        'Ahmad Rizki Pratama' => 'T21',
        'Siti Nurhaliza Dewi' => 'T22',
        'Budi Santoso Wijaya' => 'T23',
        'Rina Kusuma Wardani' => 'T24',
        'Dedi Kurniawan' => 'T25',
        'Maya Sari Indah' => 'T26',
        'Agus Setiawan' => 'T27',
        'Fitri Ramadhani' => 'T28',
        'Hendra Gunawan' => 'T29',
        'Lestari Wulandari' => 'T30',
    ];
@endphp

    @foreach($historiDonasi as $donasi)
    <tr class="border-b">
        <td class="py-2">{{ $kodeProdukMap[$donasi->nama_barang] ?? '-' }}</td>
        <td class="py-2">{{ $donasi->nama_barang }}</td>
        <td class="py-2">
    {{ $idPenitipMap[$donasi->penitip->username] ?? 'T00' }}
</td>

        <td class="py-2">{{ $donasi->penitip->username ?? 'Tidak diketahui' }}</td>
        <td class="py-2">{{ $donasi->tanggal_donasi ?? '-' }}</td>
        <td class="py-2">{{ $donasi->organisasi->username ?? 'Tidak diketahui' }}</td>
        <td class="py-2">{{ $donasi->nama_penerima ?? 'Tidak diketahui' }}</td>
        <td class="py-2">
            <a href="{{ route('owner.donasi.edit', $donasi->id) }}"
               class="text-sm text-white bg-yellow-600 px-3 py-1 rounded hover:bg-orange-700">Edit</a>
        </td>
    </tr>
@endforeach

</tbody>

    </table>
    @endif
</div>
@endsection
