@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-16 bg-white p-6 rounded shadow text-center space-y-6">
    <h2 class="text-xl font-bold">Silakan pilih jenis akun</h2>

    <div class="space-y-4">
        <div>
            <p class="font-semibold mb-2">Masuk sebagai:</p>
            <a href="{{ route('pembeli.login.form') }}" class="block bg-green-600 text-white py-2 rounded hover:bg-green-700">Login sebagai Pembeli</a>
            <a href="{{ route('penitip.login.form') }}" class="block bg-blue-600 text-white py-2 mt-2 rounded hover:bg-blue-700">Login sebagai Penitip</a>
        </div>

        <div>
            <p class="font-semibold mb-2">Belum punya akun?</p>
            <a href="{{ route('pembeli.register.form') }}" class="block bg-green-500 text-white py-2 rounded hover:bg-green-600">Daftar sebagai Pembeli</a>
            <a href="{{ route('penitip.register.form') }}" class="block bg-blue-500 text-white py-2 mt-2 rounded hover:bg-blue-600">Daftar sebagai Penitip</a>
        </div>
    </div>
</div>
@endsection
