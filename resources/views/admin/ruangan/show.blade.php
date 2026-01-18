@extends('layouts.admin', ['pageTitle'=>'Detail Ruangan'])
@section('content')
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <div class="flex gap-4 items-start">
    @if($ruangan->foto)
      <img src="{{ asset('storage/'.$ruangan->foto) }}" class="w-48 h-36 object-cover rounded" alt="{{ $ruangan->nama_ruangan }}">
    @else
      <div class="w-48 h-36 bg-gray-200 rounded"></div>
    @endif
    <div>
      <div class="text-xl font-semibold">{{ $ruangan->nama_ruangan }}</div>
      <div class="text-sm text-gray-700">Harga: Rp {{ number_format($ruangan->harga ?? 0,0,',','.') }}</div>
      <div class="text-sm text-gray-700">Kapasitas: {{ number_format($ruangan->kapasitas ?? 0,0,',','.') }} orang</div>
      <div class="text-sm text-gray-600">Status: {{ ucfirst($ruangan->status) }}</div>
      @if($ruangan->fasilitas)
      <div class="mt-2 flex flex-wrap gap-1">
        @foreach($ruangan->fasilitas as $f)
          <span class="px-2 py-0.5 rounded bg-gray-100">{{ $f }}</span>
        @endforeach
      </div>
      @endif
    </div>
  </div>
  <div>
    <div class="font-medium mb-1">Deskripsi</div>
    <div class="prose max-w-none">{{ $ruangan->deskripsi }}</div>
  </div>
  <div class="pt-3">
    <a href="{{ route('admin.ruangan.edit',$ruangan) }}" class="px-3 py-2 rounded bg-yellow-500 text-white">Ubah</a>
    <a href="{{ route('admin.ruangan.index') }}" class="px-3 py-2 rounded bg-gray-200">Kembali</a>
  </div>
</div>
@endsection
