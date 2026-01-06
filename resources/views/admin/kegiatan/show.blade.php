@extends('layouts.admin', ['pageTitle'=>'Detail Kegiatan'])
@section('content')
@if(session('status'))
  <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>
@endif
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <div class="grid md:grid-cols-2 gap-4">
    <div>
      <div class="text-xl font-semibold">{{ $kegiatan->nama_kegiatan }}</div>
      <div class="text-sm text-gray-600">Jenis: {{ ucfirst($kegiatan->jenis_kegiatan->value) }}</div>
      <div class="mt-1 text-sm">
        @php($status = $kegiatan->approval_status?->value ?? 'pending')
        <span class="text-gray-500">Status:</span>
        <span class="px-2 py-1 rounded text-xs font-medium @class([
          'bg-yellow-100 text-yellow-800' => $status === 'pending',
          'bg-green-100 text-green-800' => $status === 'approved',
          'bg-red-100 text-red-800' => $status === 'rejected',
        ])">
          {{ $kegiatan->approval_status?->label() ?? 'Menunggu persetujuan takmir' }}
        </span>
      </div>
      <div class="mt-2"><span class="text-gray-500">Penanggung Jawab:</span> {{ $kegiatan->penanggung_jawab }}</div>
      <div><span class="text-gray-500">Telepon:</span> {{ $kegiatan->no_telephone }}</div>
      <div class="mt-2"><span class="text-gray-500">Status Arsip:</span> {{ $kegiatan->is_archived ? 'Diarsipkan' : 'Aktif' }}</div>
      @if($kegiatan->is_archived)
        <div><span class="text-gray-500">Diarsipkan Pada:</span> {{ optional($kegiatan->archived_at)->timezone('Asia/Jakarta')->format('d M Y H:i') }}</div>
        <div><span class="text-gray-500">Alasan:</span> {{ $kegiatan->archive_reason }}</div>
      @endif
      @if($kegiatan->dokumen)
        <div class="mt-2 text-sm">
          <span class="text-gray-500">Dokumen:</span>
          <a href="{{ asset('storage/'.$kegiatan->dokumen) }}" target="_blank" class="underline text-blue-600 hover:text-blue-800">
            {{ basename($kegiatan->dokumen) }}
          </a>
        </div>
      @endif
    </div>
    <div>
      @if($kegiatan->foto)
        <img src="{{ asset('storage/'.$kegiatan->foto) }}" class="w-full h-56 object-cover rounded" alt="{{ $kegiatan->nama_kegiatan }}">
      @endif
      <div class="font-medium mb-1">Deskripsi</div>
      <div class="prose max-w-none">{{ $kegiatan->deskripsi }}</div>
    </div>
  </div>

  <div class="pt-3 flex gap-2">
    <a href="{{ route('admin.kegiatan.index') }}" class="px-3 py-2 rounded bg-gray-200">Kembali</a>
    <a href="{{ route('admin.kegiatan.edit',$kegiatan) }}" class="px-3 py-2 rounded bg-yellow-500 text-white">Ubah</a>
    @if(!$kegiatan->is_archived)
      <form method="post" action="{{ route('admin.kegiatan.archive', $kegiatan) }}" class="flex items-end gap-2">
        @csrf
        <div>
          <label class="block text-sm font-medium">Alasan Arsip</label>
          <input name="archive_reason" required class="border rounded px-3 py-2" placeholder="Contoh: acara sudah selesai">
          @error('archive_reason')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <button class="px-3 py-2 rounded bg-red-600 text-white">Arsipkan</button>
      </form>
    @else
      <form method="post" action="{{ route('admin.kegiatan.unarchive', $kegiatan) }}">
        @csrf
        <button class="px-3 py-2 rounded bg-green-600 text-white">Buka Kembali</button>
      </form>
    @endif
  </div>

  <div class="mt-6 p-3 rounded border bg-gray-50 text-sm">
    <div class="font-medium">Integrasi Jadwal</div>
    <div>Kegiatan ini akan dapat dihubungkan dengan entri <em>Jadwal</em> pada SESI 10.</div>
  </div>
</div>
@endsection
