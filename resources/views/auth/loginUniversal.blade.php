@extends('layouts.app-logreg')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Login Akun</h2>

    @if(session('success'))
        <div class="mb-4 text-green-700 bg-green-100 px-4 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 text-red-600 bg-red-100 px-4 py-2 rounded">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.universal') }}">
        @csrf
        <input type="text" name="username" placeholder="Username"
               class="w-full border p-2 mb-3 rounded" required>

        <input type="password" name="password" placeholder="Password"
               class="w-full border p-2 mb-4 rounded" required>

        <button class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
            Login
        </button>

        <div class="mt-4 text-center">
    <a href="{{ route('password.forgot') }}" class="text-blue-600 hover:underline">
        Lupa Password?
    </a>
</div>

        <div class="mt-4 text-center">
            <p>Belum memiliki akun?
                <a href="{{ route('register.all') }}" class="text-blue-600 hover:underline">Daftar di sini</a>
            </p>
        </div>
    </form>
</div>
@endsection
