@extends('layouts.admin', ['pageTitle'=>'Detail Arsip Kegiatan'])
@section('content')
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <div class="grid md:grid-cols-2 gap-4">
    <div>
      <div class="text-xl font-semibold">{{ $kegiatan->nama_kegiatan }}</div>
      <div class="text-sm text-gray-600">Jenis: {{ ucfirst($kegiatan->jenis_kegiatan->value) }}</div>
      <div class="mt-2"><span class="text-gray-500">Penanggung Jawab:</span> {{ $kegiatan->penanggung_jawab }}</div>
      <div><span class="text-gray-500">Telepon:</span> {{ $kegiatan->no_telephone }}</div>
      <div class="mt-2"><span class="text-gray-500">Diarsipkan Pada:</span> {{ optional($kegiatan->archived_at)->timezone('Asia/Jakarta')->format('d M Y H:i') }}</div>
      <div><span class="text-gray-500">Diarsipkan Oleh:</span> {{ optional($kegiatan->archivedByUser)->username }}</div>
      <div><span class="text-gray-500">Alasan Arsip:</span> {{ $kegiatan->archive_reason }}</div>
    </div>
    <div>
      <div class="font-medium mb-1">Deskripsi</div>
      <div class="prose max-w-none">{{ $kegiatan->deskripsi }}</div>
    </div>
  </div>

  <div class="pt-3 flex gap-2">
    <a href="{{ route('admin.kegiatan-arsip.index') }}" class="px-3 py-2 rounded bg-gray-200">Kembali</a>
    <a href="{{ route('admin.kegiatan.edit',$kegiatan) }}" class="px-3 py-2 rounded bg-yellow-500 text-white">Edit Arsip</a>
    <form method="post" action="{{ route('admin.kegiatan.destroy',$kegiatan) }}" onsubmit="return confirm('Hapus arsip kegiatan ini?');">
      @csrf @method('DELETE')
      <button class="px-3 py-2 rounded bg-red-600 text-white">Hapus</button>
    </form>
    <form method="post" action="{{ route('admin.kegiatan.unarchive',$kegiatan) }}">
      @csrf
      <button class="px-3 py-2 rounded bg-green-600 text-white">Buka Kembali</button>
    </form>
  </div>
</div>
@endsection
