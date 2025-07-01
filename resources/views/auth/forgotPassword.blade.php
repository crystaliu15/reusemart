@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Reset Password</h2>

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

    <form method="POST" action="{{ route('password.send') }}">
        @csrf

        <label for="email" class="block mb-1">Masukkan Email Anda</label>
        <input type="email" name="email" placeholder="contoh@email.com"
               class="w-full border p-2 mb-4 rounded" required>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Kirim Link Reset Password
        </button>
    </form>
</div>
@endsection
