@extends('layouts.admin', ['pageTitle'=>'Konfirmasi Persetujuan'])
@section('content')
<div class="grid md:grid-cols-3 gap-4">
  <div class="md:col-span-2 bg-white p-5 rounded-xl shadow space-y-2">
    <div class="text-lg font-semibold">Setujui Pemesanan #{{ $pemesanan->pemesanan_id }}</div>
    <div class="text-sm text-gray-600">Pemesan: {{ $pemesanan->user->username }} ({{ $pemesanan->user->email }})</div>
    <div class="text-sm text-gray-600">Ruangan: {{ $pemesanan->ruangan->nama_ruangan }}</div>
    <div class="text-sm text-gray-600">Dibuat: {{ $pemesanan->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</div>
    <div class="pt-2">
      <div class="text-xs text-gray-500">Tujuan</div>
      <div class="font-medium">{{ $pemesanan->tujuan_pemesanan }}</div>
    </div>
  </div>
  <div class="bg-white p-5 rounded-xl shadow">
    <div class="font-medium mb-2">Konfirmasi Persetujuan</div>
    <form method="post" action="{{ route('takmir.verifikasi-jadwal.approve', $pemesanan) }}" class="space-y-2">
      @csrf
      <label class="block text-sm text-gray-700">Catatan (opsional)</label>
      <textarea name="catatan" rows="4" class="border rounded px-3 py-2 w-full" placeholder="Tambahkan catatan kepada pemesan jika diperlukan..."></textarea>
      <div class="pt-2 flex gap-2">
        <button class="flex-1 px-3 py-2 rounded bg-green-600 text-white hover:bg-green-700">Setujui</button>
        <a href="{{ route('takmir.verifikasi-jadwal.index') }}" class="px-3 py-2 rounded bg-gray-200 hover:bg-gray-300">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection
