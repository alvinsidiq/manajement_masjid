@extends('layouts.admin', ['pageTitle'=>'Detail Jadwal'])
@section('content')
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <div class="grid md:grid-cols-2 gap-4">
    <div>
      <div class="text-xl font-semibold">{{ $j->kegiatan->nama_kegiatan }}</div>
      <div class="text-sm text-gray-600">Status: {{ ucfirst($j->status->value) }}</div>
      <div class="mt-2"><span class="text-gray-500">Ruangan:</span> {{ $j->ruangan?->nama_ruangan ?? '-' }}</div>
      <div><span class="text-gray-500">Mulai:</span> {{ $j->tanggal_mulai->timezone('Asia/Jakarta')->format('d M Y H:i') }}</div>
      <div><span class="text-gray-500">Selesai:</span> {{ $j->tanggal_selesai->timezone('Asia/Jakarta')->format('d M Y H:i') }}</div>
    </div>
    <div>
      <div class="font-medium mb-1">Catatan</div>
      <div class="prose max-w-none">{{ $j->catatan ?: '-' }}</div>
    </div>
  </div>
  <div class="pt-3"><a href="{{ route('admin.jadwal.index') }}" class="px-3 py-2 rounded bg-gray-200">Kembali</a></div>
  
</div>
@endsection

