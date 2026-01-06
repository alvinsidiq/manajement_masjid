@extends('layouts.admin', ['pageTitle'=>'Ubah Ruangan'])
@section('content')
<form method="post" action="{{ route('admin.ruangan.update',$ruangan) }}" enctype="multipart/form-data" class="bg-white p-4 rounded-xl shadow grid gap-4 md:grid-cols-2">
  @csrf @method('PUT')
  <div>
    <label class="block text-sm font-medium mb-1">Nama Ruangan</label>
    <input name="nama_ruangan" value="{{ old('nama_ruangan',$ruangan->nama_ruangan) }}" class="w-full border rounded px-3 py-2">
    @error('nama_ruangan')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium mb-1">Harga (IDR)</label>
    <input name="harga" type="number" step="0.01" min="0" value="{{ old('harga',$ruangan->harga) }}" class="w-full border rounded px-3 py-2">
    @error('harga')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium mb-1">Status</label>
    <select name="status" class="w-full border rounded px-3 py-2">
      @foreach(['aktif','nonaktif','maintenance'] as $s)
        <option value="{{ $s }}" @selected(old('status',$ruangan->status)===$s)>{{ ucfirst($s) }}</option>
      @endforeach
    </select>
    @error('status')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div class="md:col-span-2">
    <label class="block text-sm font-medium mb-1">Deskripsi</label>
    <textarea name="deskripsi" rows="4" class="w-full border rounded px-3 py-2">{{ old('deskripsi',$ruangan->deskripsi) }}</textarea>
    @error('deskripsi')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div class="md:col-span-2">
    <label class="block text-sm font-medium mb-1">Fasilitas (pisahkan dengan koma)</label>
    <input name="fasilitas" value="{{ old('fasilitas', $ruangan->fasilitas ? implode(', ', $ruangan->fasilitas) : '') }}" class="w-full border rounded px-3 py-2">
    @error('fasilitas')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium mb-1">Foto (opsional: unggah untuk ganti)</label>
    <input type="file" name="foto" class="w-full border rounded px-3 py-2">
    @error('foto')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
    @if($ruangan->foto)
      <div class="mt-2">
        <img src="{{ asset('storage/'.$ruangan->foto) }}" class="w-32 h-24 object-cover rounded" alt="{{ $ruangan->nama_ruangan }}">
      </div>
    @endif
  </div>

  <div class="md:col-span-2 flex gap-2">
    <button class="px-4 py-2 rounded bg-blue-600 text-white">Simpan</button>
    <a href="{{ route('admin.ruangan.index') }}" class="px-4 py-2 rounded bg-gray-200">Batal</a>
  </div>
</form>
@endsection
