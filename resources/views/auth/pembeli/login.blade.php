@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">

    <h2 class="text-xl font-bold mb-4">Login Pembeli</h2>

    @if(session('password_info'))
        <script>alert("{{ session('password_info') }}");</script>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <strong>Oops!</strong> {{ $errors->first() }}
        </div>
    @endif

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif


    <!-- Form Login -->
    <form method="POST" action="{{ route('pembeli.login') }}">
        @csrf
        <input type="email" name="email" placeholder="Email"
               class="w-full border p-2 mb-3 rounded" required>

        <input type="password" name="password" placeholder="Password"
               class="w-full border p-2 mb-4 rounded" required>

        <button class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
            Login
        </button>
    </form>

    <!-- Garis pembatas -->
    <div class="my-4 text-center text-sm text-gray-500">Atau</div>

    <!-- Form Lupa Password -->
    <form method="POST" action="{{ route('pembeli.lupa.password') }}">
        @csrf
        <input type="email" name="email" placeholder="Masukkan email untuk reset password"
               class="w-full border p-2 mb-3 rounded" required>

        <button class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">
            Lupa Password?
        </button>
    </form>

    <!-- Tambahan: Belum punya akun -->
    <p class="text-sm text-center mt-4">
        Belum punya akun?
        <a href="{{ route('pembeli.register.form') }}" class="text-blue-600 hover:underline">
            Daftar dahulu
        </a>
    </p>

</div>
@endsection
