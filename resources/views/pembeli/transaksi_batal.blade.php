@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-6">Pembatalan Transaksi Valid</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <table class="w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2">No Transaksi</th>
                <th class="border px-4 py-2">Tanggal</th>
                <th class="border px-4 py-2">Total</th>
                <th class="border px-4 py-2">Status</th>
                <th class="border px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $transaksi)
                @php
    $poin = floor($transaksi->total / 10000);
    $totalPoin = $user ? ($user->poin + $poin) : $poin;
@endphp

                <tr>
                    <td class="border px-4 py-2">{{ $transaksi->id }}</td>
                    <td class="border px-4 py-2">{{ $transaksi->created_at->format('d-m-Y') }}</td>
                    <td class="border px-4 py-2">Rp{{ number_format($transaksi->total, 0, ',', '.') }}</td>
                    <td>Disiapkan</td>
                    <td class="border px-4 py-2">
                        @if($transaksi->status === 'disiapkan')
    <form method="POST" action="{{ route('transaksi.batal.proses', $transaksi->id) }}"
          onsubmit="return confirm('Apakah anda yakin akan membatalkan transaksi ini, dengan total transaksi Rp{{ number_format($transaksi->total, 0, ',', '.') }} dan dikonversi menjadi poin reward sebanyak {{ $poin }}? Total poin anda setelah ini adalah {{ $totalPoin }}.')">
        @csrf
        <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
            Batalkan Pesanan
        </button>
    </form>
@else
    <span class="text-gray-500 italic">Tidak dapat dibatalkan</span>
@endif

                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center p-4">Tidak ada transaksi disiapkan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
