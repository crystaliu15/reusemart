<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 6px; text-align: center; }
        .no-border { border: none; }
    </style>
</head>
<body>
    <strong>ReUse Mart</strong><br>
    Jl. Green Eco Park No. 456 Yogyakarta

    <h4>LAPORAN TRANSAKSI PENITIP</h4>
    <p>
        @php
    $idMap = [
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
    $penitipName = $penitip->username ?? '-';
    $penitipId = $idMap[$penitipName] ?? '-';
@endphp
ID Penitip : {{ $penitipId }}<br>

        Nama Penitip : {{ $penitip->username ?? '-' }}<br>
        Bulan : {{ $bulan }}<br>
        Tahun : {{ $tahun }}<br>
        Tanggal cetak: {{ $tanggalCetak }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Tanggal Masuk</th>
                <th>Tanggal Laku</th>
                <th>Harga Jual Bersih (sudah dipotong Komisi)</th>
                <th>Bonus terjual cepat</th>
                <th>Pendapatan</th>
            </tr>
        </thead>
        <tbody>
        @php
            $totalHarga = 0;
            $totalBonus = 0;
            $totalPendapatan = 0;
        @endphp
        @foreach ($komisiLogs as $log)
            @php
                $namaBarang = $log->barang->nama ?? '-';
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
                <td>{{ $kodeProduk }}</td>
                <td>{{ $namaBarang }}</td>
                <td>{{ $tanggalMasuk->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $tanggalLaku->format('d/m/Y') ?? '-' }}</td>
                <td>{{ number_format($harga, 0, ',', '.') }}</td>
                <td>{{ number_format($bonus, 0, ',', '.') }}</td>
                <td>{{ number_format($pendapatan, 0, ',', '.') }}</td>
            </tr>
        @endforeach
            <tr>
                <td colspan="4"><strong>TOTAL</strong></td>
                <td><strong>{{ number_format($totalHarga, 0, ',', '.') }}</strong></td>
                <td><strong>{{ number_format($totalBonus, 0, ',', '.') }}</strong></td>
                <td><strong>{{ number_format($totalPendapatan, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
