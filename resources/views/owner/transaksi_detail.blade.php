@extends('layouts.app-owner')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded shadow mt-6">
    <a href="{{ url()->previous() }}" class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
        ← Kembali
    </a>
    
    <h2 class="text-2xl font-bold mt-4 mb-2">Laporan Transaksi Penitip</h2>

    <table class="w-full border text-sm mt-4">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">Kode Produk</th>
                <th class="p-2 border">Nama Produk</th>
                <th class="p-2 border">Tanggal Masuk</th>
                <th class="p-2 border">Tanggal Laku</th>
                <th class="p-2 border">Harga Jual Bersih</th>
                <th class="p-2 border">Bonus Terjual Cepat</th>
                <th class="p-2 border">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalHarga = 0;
                $totalBonus = 0;
                $totalPendapatan = 0;

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
                    'Sprayer Elektrik 16L' => 'K121',
                    'Cangkul Garpu Stainless' => 'K122',
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
                ];
            @endphp

            @foreach ($komisiLogs as $log)
                @php
                    $namaBarang = $log->barang->nama ?? '-';
                    $kodeProduk = $kodeProdukMap[$namaBarang] ?? '-';

                    $harga = ($log->total_harga ?? 0) - ($log->komisi_owner ?? 0);
                    $tanggalMasuk = \Carbon\Carbon::parse($log->barang->created_at);
                    $tanggalLaku = \Carbon\Carbon::parse($log->barang->tanggal_laku);
                    $selisihHari = $tanggalMasuk->diffInDays($tanggalLaku);
                    $bonus = $selisihHari < 7 ? (($harga * 1.25) * 0.2) * 0.1 : 0;
                    $pendapatan = $harga + $bonus;

                    $totalHarga += $harga;
                    $totalBonus += $bonus;
                    $totalPendapatan += $pendapatan;
                @endphp
                <tr>
                    <td class="p-2 border">{{ $kodeProduk }}</td>
                    <td class="p-2 border">{{ $namaBarang }}</td>
                    <td class="p-2 border">{{ $tanggalMasuk->format('d/m/Y') }}</td>
                    <td class="p-2 border">{{ $tanggalLaku->format('d/m/Y') }}</td>
                    <td class="p-2 border">Rp{{ number_format($harga, 0, ',', '.') }}</td>
                    <td class="p-2 border">Rp{{ number_format($bonus, 0, ',', '.') }}</td>
                    <td class="p-2 border">Rp{{ number_format($pendapatan, 0, ',', '.') }}</td>
                </tr>
            @endforeach

            <tr class="bg-gray-100 font-semibold">
                <td colspan="4" class="p-2 border text-center">TOTAL</td>
                <td class="p-2 border">Rp{{ number_format($totalHarga, 0, ',', '.') }}</td>
                <td class="p-2 border">Rp{{ number_format($totalBonus, 0, ',', '.') }}</td>
                <td class="p-2 border">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
