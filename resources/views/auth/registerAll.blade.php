@extends('layouts.app-logreg')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Daftar Akun Baru</h2>

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

    <form method="POST" action="{{ route('register.all.submit') }}">
        @csrf

        <input type="text" name="username" placeholder="Username"
               class="w-full border p-2 mb-3 rounded" required>

        <input type="email" name="email" placeholder="Email"
               class="w-full border p-2 mb-3 rounded" required>

        <input type="text" name="no_telp" placeholder="No Telepon"
               class="w-full border p-2 mb-3 rounded" required>

        <input type="password" name="password" placeholder="Password"
               class="w-full border p-2 mb-3 rounded" required>

        <select name="role" id="role" class="w-full border p-2 mb-4 rounded" required>
            <option value="">-- Pilih Role --</option>
            <option value="pembeli">Pembeli</option>
            <option value="organisasi">Organisasi</option>
        </select>

        <!-- Field alamat, tersembunyi awalnya -->
        <div id="alamat-field" class="hidden">
            <input type="text" name="alamat" id="alamat"
                   placeholder="Alamat Organisasi"
                   class="w-full border p-2 mb-3 rounded">
        </div>

        <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Daftar
        </button>

        <div class="mt-4 text-center">
            <p>Sudah punya akun?
                <a href="{{ route('login.universal') }}" class="text-blue-600 hover:underline">Login di sini</a>
            </p>
        </div>
    </form>
</div>

<!-- Script untuk menampilkan alamat jika role organisasi -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roleSelect = document.getElementById('role');
        const alamatField = document.getElementById('alamat-field');
        const alamatInput = document.getElementById('alamat');

        roleSelect.addEventListener('change', function () {
            if (this.value === 'organisasi') {
                alamatField.classList.remove('hidden');
                alamatInput.setAttribute('required', 'required');
            } else {
                alamatField.classList.add('hidden');
                alamatInput.removeAttribute('required');
            }
        });
    });
</script>
@endsection