@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-12 bg-white p-6 rounded shadow text-center">
    <h2 class="text-xl font-bold mb-4">Ada Masalah?</h2>
    <p class="mb-6 text-gray-700">Silahkan menghubungi 2 nomor di bawah ini</p>

    <!-- Telepon -->
    <div class="space-y-3 mb-6">
        <a href="tel:+628112223344" class="block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            📞 Telepon: 0811-222-3344
        </a>
        <a href="tel:+6281234567890" class="block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            📞 Telepon: 0812-3456-7890
        </a>
    </div>

    <!-- WhatsApp -->
    <div class="space-y-3">
        <a href="https://wa.me/628112223344" target="_blank" class="block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700">
            💬 WhatsApp: 0811-222-3344
        </a>
        <a href="https://wa.me/6281234567890" target="_blank" class="block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700">
            💬 WhatsApp: 0812-3456-7890
        </a>
    </div>
</div>
@endsection
