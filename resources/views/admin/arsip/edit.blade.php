@extends('layouts.admin', ['pageTitle'=>'Ubah Arsip'])
@section('content')
<form method="post" action="{{ route('admin.arsip.update', $arsip) }}" enctype="multipart/form-data" class="bg-white p-4 rounded-xl shadow grid gap-4 md:grid-cols-2">
  @csrf @method('PUT')
  <div class="md:col-span-2">
    <label class="block text-sm font-medium mb-1">Judul Arsip</label>
    <input name="judul" value="{{ old('judul',$arsip->judul) }}" class="w-full border rounded px-3 py-2" required>
    @error('judul')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div class="md:col-span-2">
    <label class="block text-sm font-medium mb-1">Deskripsi</label>
    <textarea name="deskripsi" rows="4" class="w-full border rounded px-3 py-2" placeholder="Catatan penting, nomor dokumen, dll.">{{ old('deskripsi',$arsip->deskripsi) }}</textarea>
    @error('deskripsi')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div class="md:col-span-2 space-y-2">
    <div class="text-sm text-gray-700">
      <span class="text-gray-500">Dokumen saat ini:</span>
      <a href="{{ asset('storage/'.$arsip->dokumen) }}" target="_blank" class="underline text-blue-600 hover:text-blue-800">{{ basename($arsip->dokumen) }}</a>
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Ganti Dokumen (opsional)</label>
      <input type="file" name="dokumen" class="w-full border rounded px-3 py-2">
      @error('dokumen')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>
  </div>

  <div class="md:col-span-2 flex gap-2">
    <button class="px-4 py-2 rounded bg-blue-600 text-white">Simpan Perubahan</button>
    <a href="{{ route('admin.arsip.index') }}" class="px-4 py-2 rounded bg-gray-200">Batal</a>
  </div>
</form>
@endsection
