<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ReuseMart</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
</head>

<body class="bg-gray-100 font-sans">

    @if(session('success'))
        <div id="toast"
            class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded shadow z-50 animate-bounce-in-up text-sm">
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
            <form method="POST" action="{{ route('organisasi.logout') }}">
                @csrf
                <button type="submit" class="font-bold hover:underline">Logout</button> 
            </form>
        </div>
    </div>

    <!-- Header utama -->
    <header class="bg-green-500 text-white sticky top-[32px] z-40 flex items-center justify-between px-6 py-3 shadow">
        <!-- Logo -->
        <a href="{{ url('/owner/dashboard') }}" class="flex items-center space-x-3">
            <img src="{{ asset('images/logo-reusemart.png') }}" alt="ReuseMart Logo" class="h-12">
            <div class="text-2xl md:text-3xl font-bold">ReuseMart</div>
        </a>
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
