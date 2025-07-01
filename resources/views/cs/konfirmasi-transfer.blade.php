@extends('layouts.app-cs')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white rounded shadow mt-6">
    <div class="mb-4">
        <a href="{{ url('/cs/dashboard') }}"
        class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold">
            ← Kembali
        </a>
    </div>
    <h2 class="text-2xl font-bold mb-4">Konfirmasi Bukti Transfer</h2>

    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-800 p-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($transaksis->isEmpty())
        <p class="text-gray-600">Tidak ada transaksi yang menunggu konfirmasi saat ini.</p>
    @else
        <div class="space-y-6">
            @foreach($transaksis as $transaksi)
                <div class="border rounded p-4 shadow-sm">
                    <div class="flex justify-between items-start">
                        <div>
                            <p><strong>Nama Pembeli:</strong> {{ $transaksi->pembeli->username }}</p>
                            <p><strong>Total Bayar:</strong> Rp {{ number_format($transaksi->total, 0, ',', '.') }}</p>
                            <p><strong>Status:</strong> {{ ucfirst($transaksi->status) }}</p>
                        </div>

                        @if($transaksi->bukti_transfer)
                        <div class="text-right">
                            <p class="text-sm text-gray-500 mb-1"><strong>Bukti Transfer:</strong></p>
                            <a href="{{ asset('storage/' . $transaksi->bukti_transfer) }}" target="_blank"
                                class="inline-block bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700 transition">
                                Lihat Bukti
                            </a>
                        </div>
                        @else
                            <p class="text-sm text-red-600 italic">Bukti belum tersedia</p>
                        @endif
                    </div>

                    <form action="{{ route('cs.konfirmasi.update', $transaksi->id) }}" method="POST" class="mt-4 flex gap-3">
                        @csrf
                        <button name="aksi" value="diproses"
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            ✅ Konfirmasi Pembayaran
                        </button>

                        <button name="aksi" value="pembayaran ditolak"
                                onclick="return confirm('Yakin ingin menolak pembayaran ini?')"
                                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            ❌ Tolak Pembayaran
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
