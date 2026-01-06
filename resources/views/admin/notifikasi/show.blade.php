@extends('layouts.admin', ['pageTitle'=>'Detail Notifikasi'])
@section('content')
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <div class="grid md:grid-cols-2 gap-4">
    <div>
      <div class="text-xl font-semibold">#{{ $n->notifikasi_id }}</div>
      <div class="text-sm text-gray-600">User: {{ $n->user->username }} ({{ $n->user->email }})</div>
      <div class="mt-2"><span class="text-gray-500">Jenis:</span> {{ ucfirst($n->jenis_referensi->value ?? $n->jenis_referensi) }}</div>
      <div><span class="text-gray-500">Referensi ID:</span> {{ $n->referensi_id ?? '-' }}</div>
      <div><span class="text-gray-500">Status:</span> {{ $n->terkirim ? 'Terkirim' : ($n->status_pengiriman ?? '-') }}</div>
      <div><span class="text-gray-500">Waktu Kirim:</span> {{ $n->waktu_kirim?->timezone('Asia/Jakarta')->format('d M Y H:i') ?? '-' }}</div>
    </div>
    <div>
      <div class="font-medium mb-1">Pesan</div>
      <div class="prose max-w-none">{{ $n->pesan }}</div>
    </div>
  </div>
  <div class="pt-3 flex gap-2">
    <a href="{{ route('admin.notifikasi.index') }}" class="px-3 py-2 rounded bg-gray-200">Kembali</a>
    <form method="post" action="{{ route('admin.notifikasi.resend',$n) }}">@csrf
      <button class="px-3 py-2 rounded bg-indigo-600 text-white">Kirim Ulang</button>
    </form>
  </div>
</div>
@endsection
