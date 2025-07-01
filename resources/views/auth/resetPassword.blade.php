@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Reset Password</h2>

    <div class="mb-4 bg-yellow-100 text-yellow-700 p-3 rounded">
        <p><strong>Simulasi:</strong> Password lama kamu adalah:</p>
        <p class="font-mono text-lg">{{ $password_lama }}</p>
    </div>

    <form method="POST" action="{{ route('password.reset.update', $email) }}">
        @csrf

        <div class="mb-4">
            <label>Password Baru</label>
            <input type="password" name="password_baru" class="w-full border p-2 rounded" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Simpan Password Baru
        </button>
    </form>
</div>
@endsection
