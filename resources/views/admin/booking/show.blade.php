@extends('layouts.admin', ['pageTitle'=>'Detail Booking'])
@section('content')
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <div class="grid md:grid-cols-2 gap-4">
    <div>
      <div class="text-xl font-semibold">{{ $b->ruangan->nama_ruangan }}</div>
      <div class="text-sm text-gray-600">Pemesan: {{ $b->user->username }} ({{ $b->user->email }})</div>
      <div class="mt-2"><span class="text-gray-500">Tanggal:</span> {{ $b->hari_tanggal->timezone('Asia/Jakarta')->format('d M Y') }}</div>
      <div><span class="text-gray-500">Jam:</span> {{ $b->jam }}</div>
      <div><span class="text-gray-500">Status:</span> {{ ucfirst($b->status->value) }}</div>
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

