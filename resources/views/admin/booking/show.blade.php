@extends('layouts.admin', ['pageTitle'=>'Detail Booking'])
@section('content')
@php
  $statusLabels = [
    'hold' => 'Hold',
    'proses' => 'Proses',
    'setuju' => 'Setuju',
    'tolak' => 'Tolak',
    'expired' => 'Expired',
    'cancelled' => 'Dibatalkan',
  ];
  $statusClasses = [
    'hold' => 'bg-amber-100 text-amber-800',
    'proses' => 'bg-sky-100 text-sky-800',
    'setuju' => 'bg-emerald-100 text-emerald-800',
    'tolak' => 'bg-rose-100 text-rose-800',
    'expired' => 'bg-gray-200 text-gray-700',
    'cancelled' => 'bg-gray-200 text-gray-700',
  ];
  $statusValue = $b->status->value;
@endphp
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <div class="grid md:grid-cols-2 gap-4">
    <div>
      <div class="text-xl font-semibold">{{ $b->ruangan->nama_ruangan }}</div>
      <div class="text-sm text-gray-600">Pemesan: {{ $b->user->username }} ({{ $b->user->email }})</div>
      <div class="mt-2"><span class="text-gray-500">Tanggal:</span> {{ $b->hari_tanggal->timezone('Asia/Jakarta')->format('d M Y') }}</div>
      <div><span class="text-gray-500">Jam:</span> {{ $b->jam }}</div>
      <div class="flex items-center gap-2">
        <span class="text-gray-500">Status:</span>
        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $statusClasses[$statusValue] ?? 'bg-gray-100 text-gray-700' }}">
          {{ $statusLabels[$statusValue] ?? ucfirst($statusValue) }}
        </span>
      </div>
      <div><span class="text-gray-500">Expire:</span> {{ optional($b->hold_expires_at)->timezone('Asia/Jakarta')->format('d M Y H:i') }}</div>
    </div>
    <div>
      <div class="text-sm text-gray-600">Dibuat: {{ $b->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</div>
      <div class="text-sm text-gray-600">Diubah: {{ $b->updated_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</div>
    </div>
  </div>
  <div class="pt-3">
    <a href="{{ route('admin.booking.index') }}" class="px-3 py-2 rounded bg-gray-200">Kembali</a>
  </div>
</div>
@endsection
