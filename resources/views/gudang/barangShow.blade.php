@extends('layouts.app-gudang')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded shadow mt-6">
    <a href="{{ url()->previous() }}"
       class="text-green-600 hover:text-green-800 text-sm font-semibold mb-4 inline-block">← Kembali</a>

    <div class="flex flex-col md:flex-row gap-6">
        <div class="flex-1">
            <img src="{{ asset('images/barang/' . $barang->id . '/' . $barang->thumbnail) }}"
                 alt="Thumbnail" class="w-full rounded shadow mb-4 max-h-[400px] object-contain">

            <div class="grid grid-cols-3 gap-2">
                @php $fotoLain = json_decode($barang->foto_lain, true); @endphp
                @if(is_array($fotoLain))
                    @foreach($fotoLain as $foto)
                        <img src="{{ asset('images/barang/' . $barang->id . '/' . $foto) }}"
                             class="h-24 w-full object-cover rounded shadow">
                    @endforeach
                @endif
            </div>
        </div>

        <div class="flex-1">
            <h2 class="text-2xl font-bold mb-2">{{ $barang->nama }}</h2>
            <p class="text-orange-600 text-lg font-semibold mb-4">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>

            <p><strong>Kategori:</strong> {{ $barang->kategori->nama ?? '-' }}</p>
            <p><strong>Deskripsi:</strong> {{ $barang->deskripsi }}</p>
            <p><strong>Garansi:</strong>
                @if ($barang->garansi_berlaku_hingga)
                    Hingga {{ \Carbon\Carbon::parse($barang->garansi_berlaku_hingga)->translatedFormat('d F Y') }}
                @else
                    Tidak ada
                @endif
            </p>
            <p><strong>Status:</strong> {{ $barang->terjual ? 'Sudah Terjual' : 'Tersedia' }}</p>
            <p><strong>Penitip:</strong> {{ $barang->penitip->username ?? '-' }}</p>
            <p><strong>Batas Waktu Titip:</strong> {{ \Carbon\Carbon::parse($barang->batas_waktu_titip)->translatedFormat('d F Y') }}</p>
            <p><strong>Tanggal Diambil:</strong> 
                @if($barang->tanggal_diambil_kembali)
                    {{ \Carbon\Carbon::parse($barang->tanggal_diambil_kembali)->format('d M Y') }}
                @else
                    Tidak ada
                @endif
            </p>
            <p><strong>Status Pengambilan:</strong>
                @if ($barang->status_pengambilan == 1 && $barang->diambil_kembali == 1)
                    Diambil Kembali
                @else
                    Belum diambil kembali
                @endif
            </p>
            <div class="mt-6 flex gap-4">
                <a href="{{ route('gudang.barang.edit', $barang->id) }}"
                class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">✏ Edit</a>

                <button onclick="openModal()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">🗑 Hapus</button>
            </div>
            <div class="mt-6 flex gap-4">
                <a href="{{ asset('storage/notas/nota-barang-' . $barang->id . '.pdf') }}"
                    class="bg-indigo-600 text-white px-3 py-2 rounded hover:bg-indigo-700"
                    target="_blank">
                    📄 Download Nota Penitipan
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hapus Barang -->
<div id="deleteModal" class="fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 animate-fade-in">
        <h2 class="text-xl font-bold text-gray-800 mb-3">Hapus Barang?</h2>
        <p class="text-sm text-gray-600 mb-6">
            Apakah kamu yakin ingin menghapus barang <strong>{{ $barang->nama }}</strong>? Tindakan ini tidak bisa dibatalkan.
        </p>

        <div class="flex justify-end gap-3">
            <button onclick="closeModal()"
                    class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">
                Batal
            </button>

            <form action="{{ route('gudang.barang.destroy', $barang->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endpush

@endsection
