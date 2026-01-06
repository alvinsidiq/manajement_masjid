@extends('layouts.admin', ['pageTitle'=>'Dashboard Admin'])
@section('content')
<div class="grid md:grid-cols-3 gap-4">
  <div class="bg-white p-4 rounded-xl shadow">
    <div class="text-sm text-gray-500">Total Pengguna</div>
    <div class="mt-2 text-3xl font-semibold">{{ number_format($stats['users'] ?? 0) }}</div>
    <div class="mt-3">
      <a class="text-sm text-blue-600 hover:underline" href="{{ route('admin.users.index') }}">Lihat pengguna</a>
    </div>
  </div>

  <div class="bg-white p-4 rounded-xl shadow">
    <div class="text-sm text-gray-500">Total Booking</div>
    <div class="mt-2 text-3xl font-semibold">{{ number_format($stats['booking'] ?? 0) }}</div>
    <div class="mt-3">
      <a class="text-sm text-blue-600 hover:underline" href="{{ route('admin.booking.index') }}">Lihat booking</a>
    </div>
  </div>

  <div class="bg-white p-4 rounded-xl shadow">
    <div class="text-sm text-gray-500">Total Ruangan</div>
    <div class="mt-2 text-3xl font-semibold">{{ number_format($stats['ruangan'] ?? 0) }}</div>
    <div class="mt-3">
      <a class="text-sm text-blue-600 hover:underline" href="{{ route('admin.ruangan.index') }}">Kelola ruangan</a>
    </div>
  </div>
</div>
@endsection
