@extends('layouts.app-gudang')

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Barang yang Telah Diambil Kembali oleh Penitip</h2>

    @if($barangs->isEmpty())
        <div class="alert alert-warning">
            Tidak ada barang yang telah diambil kembali.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Penitip</th>
                        <th>Tanggal Diambil</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barangs as $barang)
                        <tr>
                            <td>
                                @if($barang->thumbnail)
                                    <img src="{{ asset('images/barang/' . $barang->id . '/' . $barang->thumbnail) }}" 
                                         alt="Gambar Barang" width="100" class="img-thumbnail">
                                @else
                                    <span class="text-muted">Tidak ada gambar</span>
                                @endif
                            </td>
                            <td>{{ $barang->nama }}</td>
                            <td>{{ $barang->kategori->nama }}</td>
                            <td>{{ $barang->penitip->nama }}</td>
                            <td>{{ \Carbon\Carbon::parse($barang->tanggal_diambil_kembali)->format('d-m-Y H:i') }}</td>
                            <td><span class="badge bg-success">Sudah Diambil</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
