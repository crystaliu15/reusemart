<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Donasi Barang</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h3>Laporan Donasi Barang</h3>
    <p><strong>ReUse Mart</strong><br>
       Jl. Green Eco Park No. 456 Yogyakarta</p>

    <p><strong>LAPORAN Donasi Barang</strong><br>
       Tahun : {{ $tahun }}<br>
       Tanggal cetak: {{ $tanggalCetak }}</p>

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

        $idPenitipMap = [
            'Carlos' => 'T01', 'Badrol' => 'T02', 'Bambang' => 'T03', 'Purnomo' => 'T04',
            'Janti' => 'T05', 'Keulana' => 'T06', 'Yulianti' => 'T07', 'Rahayu' => 'T08',
            'Fransiskus' => 'T09', 'Asisi' => 'T10', 'Timmy' => 'T11', 'Budi' => 'T12',
            'Yudhantara' => 'T13', 'Rodjim' => 'T14', 'Bae' => 'T15', 'Warsito' => 'T16',
            'Nugroho' => 'T17', 'Petrus' => 'T18', 'Yohanes' => 'T19', 'Santoso' => 'T20',
            'Ahmad Rizki Pratama' => 'T21', 'Siti Nurhaliza Dewi' => 'T22',
            'Budi Santoso Wijaya' => 'T23', 'Rina Kusuma Wardani' => 'T24',
            'Dedi Kurniawan' => 'T25', 'Maya Sari Indah' => 'T26', 'Agus Setiawan' => 'T27',
            'Fitri Ramadhani' => 'T28', 'Hendra Gunawan' => 'T29', 'Lestari Wulandari' => 'T30',
        ];
    @endphp

    <table>
        <thead>
            <tr>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Id Penitip</th>
                <th>Nama Penitip</th>
                <th>Tanggal Donasi</th>
                <th>Organisasi</th>
                <th>Nama Penerima</th>
            </tr>
        </thead>
        <tbody>
            @foreach($historiDonasi as $item)
                @php
                    $namaBarang = $item->nama_barang;
                    $kodeProduk = $kodeProdukMap[$namaBarang] ?? 'K000';

                    $namaPenitip = $item->penitip->username ?? null;
                    $idPenitip = $namaPenitip ? ($idPenitipMap[$namaPenitip] ?? 'T00') : 'T00';

                    $tanggalDonasi = $item->tanggal_donasi 
                        ? \Carbon\Carbon::parse($item->tanggal_donasi)->format('d/m/Y') 
                        : '-';
                @endphp
                <tr>
                    <td>{{ $kodeProduk }}</td>
                    <td>{{ $namaBarang }}</td>
                    <td>{{ $idPenitip }}</td>
                    <td>{{ $namaPenitip ?? 'Tidak diketahui' }}</td>
                    <td>{{ $tanggalDonasi }}</td>
                    <td>{{ $item->organisasi->username ?? 'Tidak diketahui' }}</td>
                    <td>{{ $item->nama_penerima ?? 'Tidak diketahui' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
