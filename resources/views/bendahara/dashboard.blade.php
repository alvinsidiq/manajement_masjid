@extends('layouts.admin', ['pageTitle'=>'Dashboard Bendahara'])
@section('content')
<div class="bg-white p-6 rounded-xl shadow space-y-4">
  <div class="text-xl font-semibold">Selamat datang, {{ auth()->user()->username ?? 'Bendahara' }}.</div>
  <p class="text-gray-600">Silakan pilih tindakan cepat di bawah ini.</p>

  <div class="flex flex-wrap gap-3">
    <a href="{{ route('bendahara.payment.create') }}" class="inline-flex items-center px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Input Pembayaran</a>
    <a href="{{ route('bendahara.payment.index') }}" class="inline-flex items-center px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">Laporan Transaksi</a>
    <a href="{{ route('user.booking.index') }}" class="inline-flex items-center px-4 py-2 rounded bg-gray-800 text-white hover:bg-gray-900">Lihat Booking</a>
  </div>
</div>
@endsection
