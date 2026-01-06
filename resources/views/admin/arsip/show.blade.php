@extends('layouts.admin', ['pageTitle'=>'Detail Arsip'])
@section('content')
@if(session('status'))
  <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>
@endif
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <div class="flex justify-between items-start gap-4">
    <div>
      <div class="text-xl font-semibold">{{ $arsip->judul }}</div>
      <div class="text-sm text-gray-600">Diunggah oleh: {{ optional($arsip->uploader)->username ?? optional($arsip->uploader)->name ?? 'Admin' }}</div>
      <div class="text-sm text-gray-600">Tanggal: {{ optional($arsip->created_at)->timezone('Asia/Jakarta')->format('d M Y H:i') }}</div>
    </div>
    <div class="flex gap-2">
      <a href="{{ asset('storage/'.$arsip->dokumen) }}" target="_blank" class="px-3 py-2 rounded bg-blue-600 text-white">Lihat / Unduh</a>
      <a href="{{ route('admin.arsip.edit',$arsip) }}" class="px-3 py-2 rounded bg-yellow-500 text-white">Ubah</a>
      <a href="{{ route('admin.arsip.index') }}" class="px-3 py-2 rounded bg-gray-200">Kembali</a>
    </div>
  </div>

  @if($arsip->deskripsi)
    <div class="prose max-w-none">{{ $arsip->deskripsi }}</div>
  @else
    <div class="text-gray-500 text-sm">Tidak ada deskripsi.</div>
  @endif
</div>
@endsection
