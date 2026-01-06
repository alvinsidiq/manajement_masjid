@extends('layouts.landing', ['title'=>'Beranda'])
@section('content')
<div class="grid md:grid-cols-2 gap-6 items-center">
    <div>
        <h1 class="text-3xl font-bold mb-3">Sistem Pengelolaan Masjid</h1>
        <p class="text-gray-700 mb-6">Lihat informasi masjid, kegiatan, dan lakukan pemesanan ruangan (melalui sesi berikutnya).</p>
        <div class="flex gap-3">
            <a class="px-4 py-2 rounded bg-blue-600 text-white" href="{{ route('public.informasi.index') }}">Informasi</a>
            @guest
            <a class="px-4 py-2 rounded bg-gray-900 text-white" href="{{ route('register') }}">Daftar</a>
            @endguest
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow">
        <div class="text-sm text-gray-500 mb-2">Tanggal & Waktu (WIB)</div>
        <div class="text-2xl font-mono">{{ now()->setTimezone('Asia/Jakarta')->translatedFormat('l, d F Y H:i') }}</div>
    </div>
</div>
@endsection
