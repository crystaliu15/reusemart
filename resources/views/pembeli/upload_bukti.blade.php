@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Upload Bukti Transfer</h2>

    <p class="mb-2 text-gray-700">
        Silakan transfer ke rekening berikut:
    </p>
    <div class="bg-gray-100 p-4 rounded mb-4">
        <p><strong>Bank:</strong> BCA</p>
        <p><strong>No. Rek:</strong> 123-456-7890</p>
        <p><strong>Nama:</strong> ReuseMart Official</p>
    </div>

    {{-- Tampilkan waktu batas upload --}}
    @php
        use Carbon\Carbon;
        $deadline = Carbon::parse($transaksi->deadline_pembayaran)->timezone('Asia/Jakarta');
        $deadlineISO = $deadline->toIso8601String(); // untuk JS
        $deadlineDisplay = $deadline->format('d M Y') . ' pukul ' . $deadline->format('H:i') . ' WIB';
    @endphp

    <p class="mb-2 text-gray-800 text-sm">
        <strong>Upload sebelum:</strong> {{ $deadlineDisplay }}
    </p>

    <p class="mb-4 text-sm text-red-600">
        Waktu tersisa untuk upload bukti: <strong id="countdown"></strong>
    </p>

    <form method="POST" action="{{ route('pembeli.transaksi.submitBukti', $transaksi->id) }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold mb-1">Upload Bukti Transfer (.jpg/.jpeg)</label>
            <input type="file" name="bukti_transfer" accept="image/jpeg" required class="border px-3 py-2 rounded w-full">
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Kirim Bukti Transfer
        </button>
    </form>
</div>

{{-- Script countdown dan redirect --}}
<script>
    const deadline = new Date("{{ $deadlineISO }}").getTime();
    const countdownElement = document.getElementById("countdown");

    const x = setInterval(function () {
        const now = new Date().getTime();
        const distance = deadline - now;

        if (distance <= 0) {
            clearInterval(x);
            countdownElement.innerHTML = "Waktu habis";

            // Redirect otomatis ke halaman gagal
            window.location.href = "{{ route('pembeli.transaksi.gagalBayar', $transaksi->id) }}";
        } else {
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            countdownElement.innerHTML = `${minutes}m ${seconds}s`;
        }
    }, 1000);
</script>
@endsection
