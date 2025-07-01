@extends('layouts.app-owner')

@section('content')
<div class="max-w-6xl mx-auto mt-10 bg-white p-6 rounded shadow">
    
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">👥 Daftar Penitip Terdaftar</h2>
            <p class="text-gray-600 mt-1">Kelola dan pantau penitip yang terdaftar di sistem</p>
        </div>
        <a href="{{ route('owner.dashboard') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition duration-200">
            ← Kembali ke Dashboard
        </a>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="bg-blue-500 text-white p-2 rounded-full mr-3">
                    👥
                </div>
                <div>
                    <p class="text-sm text-blue-600 font-medium">Total Penitip</p>
                    <p class="text-2xl font-bold text-blue-800">{{ $penitips->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="bg-green-500 text-white p-2 rounded-full mr-3">
                    ✅
                </div>
                <div>
                    <p class="text-sm text-green-600 font-medium">Status Aktif</p>
                    <p class="text-2xl font-bold text-green-800">{{ $penitips->where('status', 'aktif')->count() ?? $penitips->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="bg-yellow-500 text-white p-2 rounded-full mr-3">
                    📅
                </div>
                <div>
                    <p class="text-sm text-yellow-600 font-medium">Terdaftar Bulan Ini</p>
                    <p class="text-2xl font-bold text-yellow-800">{{ $penitips->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter dan Pencarian --}}
    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-64">
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari Penitip</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari berdasarkan username, email, atau telepon..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition duration-200">
                    🔍 Cari
                </button>
            </div>
            @if(request('search'))
                <div>
                    <a href="{{ route('owner.penitip.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition duration-200">
                        Reset
                    </a>
                </div>
            @endif
        </form>
    </div>

    {{-- Tabel Daftar Penitip --}}
    @if($penitips->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Informasi Penitip</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($penitips as $index => $penitip)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium">
                                        {{ strtoupper(substr($penitip->username ?? 'P', 0, 1)) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $penitip->username ?? 'Username tidak tersedia' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <div>📧 {{ $penitip->email ?? '-' }}</div>
                                <div class="text-gray-500">📱 {{ $penitip->no_telp ?? '-' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $penitip->alamat ?? '-' }}">
                                📍 {{ $penitip->alamat ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($penitip->created_at)->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(isset($penitip->status))
                                @if($penitip->status == 'aktif')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        ✅ Aktif
                                    </span>
                                @elseif($penitip->status == 'nonaktif')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        ❌ Non-aktif
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        ⏳ {{ ucfirst($penitip->status) }}
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    ✅ Aktif
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination jika ada --}}
        @if(method_exists($penitips, 'links'))
            <div class="mt-6">
                {{ $penitips->links() }}
            </div>
        @endif
    @else
        {{-- Pesan jika tidak ada data --}}
        <div class="text-center py-12">
            <div class="text-6xl mb-4">👥</div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Penitip Terdaftar</h3>
            <p class="text-gray-500">Belum ada penitip yang terdaftar di sistem saat ini.</p>
        </div>
    @endif

</div>
@endsection