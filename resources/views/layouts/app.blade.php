@php
    $pembeli = Auth::guard('pembeli')->user();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ReuseMart</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
</head>
    
<!-- Modal Peringatan Belum Login -->
<div id="loginPromptModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white w-96 rounded-lg p-6 shadow-lg text-center">
        <h2 class="text-lg font-bold mb-2 text-gray-800">Anda belum login</h2>
        <p class="text-sm text-gray-600 mb-6">Login dulu yuk untuk bisa akses keranjang dan fitur lainnya.</p>
        <div class="flex justify-between space-x-2">
            <a href="{{ route('login.universal.form') }}" class="flex-1 bg-green-600 text-white py-2 rounded hover:bg-green-700">Login</a>
            <a href="{{ route('pembeli.register.form') }}" class="flex-1 bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Daftar</a>
            <button onclick="document.getElementById('loginPromptModal').classList.add('hidden')"
                    class="flex-1 bg-gray-300 text-black py-2 rounded hover:bg-gray-400">Nanti saja</button>
        </div>
    </div>
</div>

<body class="bg-gray-100 font-sans">

    @if(session('success'))
        <div id="toast"
            class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-blue-600 text-white px-6 py-3 rounded shadow z-50 animate-bounce-in-up text-sm">
            {{ session('success') }}
        </div>
    @endif

    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast');
            if (toast) toast.remove();
        }, 3000);
    </script>

    <style>
    @keyframes bounce-in-up {
        0% {
            opacity: 0;
            transform: translateY(40px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-bounce-in-up {
        animation: bounce-in-up 0.3s ease-out;
    }
    </style>

    <!-- Topbar -->
    <div class="bg-green-600 text-white sticky top-0 z-50 text-sm flex justify-between px-4 py-2">
        <div class="flex space-x-2 items-center">
            <a href="{{ route('bantuan') }}" class="flex items-center space-x-2 font-bold hover:underline transition">
                <img src="{{ asset('images/customer-service.jpg') }}" alt="Customer Service" class="w-8 h-8 object-cover rounded-full hover:scale-105 transition" />
                <span>Bantuan</span>
            </a>
        </div>
        <div class="flex space-x-4 items-center">
            @if($pembeli)
                <div class="flex items-center space-x-2">
                    <a href="{{ route('pembeli.profil') }}" class="font-bold flex items-center space-x-2 hover:underline">
                        <img src="{{ $pembeli->profile_picture ? asset('storage/' . $pembeli->profile_picture) : asset('images/default-user.png') }}"
                            alt="Profile" class="h-8 w-8 rounded-full object-cover">
                        <span>{{ $pembeli->username }}</span>
                    </a>
                </div>
                <form method="POST" action="{{ route('pembeli.logout') }}">
                    @csrf
                    <button type="submit" class="font-bold hover:underline">Logout</button>
                </form>
            @else
                <a href="{{ route('login.universal.form') }}"class="font-bold hover:underline">Login</a>
                <a href="#">|</a>
                <a href="{{ route('register.all') }}"class="font-bold hover:underline">Daftar</a>
            @endif
        </div>
    </div>

    <!-- Header utama -->
    <header class="bg-green-500 text-white sticky top-[32px] z-40 flex items-center justify-between px-6 py-3 shadow">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="flex items-center space-x-3">
            <img src="{{ asset('images/logo-reusemart.png') }}" alt="ReuseMart Logo" class="h-12">
            <div class="text-2xl md:text-3xl font-bold">ReuseMart</div>
        </a>

        <!-- Search bar -->
        <form id="searchForm" action="{{ route('search') }}" method="GET" class="flex-1 px-12">
            <div class="flex items-center">
                <input type="text"
                       id="searchInput"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari produk"
                       class="w-full px-4 py-2 rounded-l bg-white text-black placeholder-black-600 border-t border-b border-l border-gray-300 focus:outline-none" />

                <!-- Tombol Search -->
                <button type="submit"
                        class="bg-green-700 text-white px-4 py-2 ml-2 rounded hover:bg-green-800 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
                    </svg>
                </button>

                <!-- Tombol Keranjang -->
                @if($pembeli)
                    <a href="{{ route('cart.index') }}"
                        class="relative bg-green-700 text-white px-4 py-2 ml-2 rounded hover:bg-green-800 flex items-center justify-center"
                        title="Keranjang">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.6 8m12.2-8l1.6 8M6 21a1 1 0 100-2 1 1 0 000 2zm12 0a1 1 0 100-2 1 1 0 000 2z" />
                        </svg>
                        @if(isset($jumlahKeranjang) && $jumlahKeranjang > 0)
                            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                                {{ $jumlahKeranjang }}
                            </span>
                        @endif
                    </a>
                @else
                    <button type="button"
                            onclick="document.getElementById('loginPromptModal').classList.remove('hidden')"
                            class="bg-green-700 text-white px-4 py-2 ml-2 rounded hover:bg-green-800 flex items-center justify-center"
                            title="Keranjang">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.6 8m12.2-8l1.6 8M6 21a1 1 0 100-2 1 1 0 000 2zm12 0a1 1 0 100-2 1 1 0 000 2z" />
                        </svg>
                    </button>
                @endif
            </div>
        </form>
    </header>

    <!-- Konten -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-green-600 text-white px-2 py-10 mt-32">
        <div class="max-w-5xl mx-auto text-center space-y-4">
            <h2 class="text-2xl font-bold">Tentang ReuseMart</h2>
            <p class="text-sm text-white">
                ReuseMart adalah platform jual beli barang bekas berkualitas, dengan sistem terpercaya dan terintegrasi.
                Temukan berbagai produk second-hand dari kategori Elektronik, Fashion, Perabotan, dan banyak lagi!
            </p>
            <div class="text-sm mt-4">
                🎁 <strong>Promosi:</strong> Gratis ongkir untuk pembelian di atas Rp 1.000.000  
                <br>
                ⭐ <strong>Loyalty Program:</strong> Kumpulkan poin dan tukarkan dengan hadiah menarik!
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            const input = document.getElementById('searchInput');
            if (!input.value.trim()) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
