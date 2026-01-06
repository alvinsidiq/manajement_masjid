@extends('layouts.admin', ['pageTitle'=>'Ubah Kegiatan'])
@section('content')
<form method="post" action="{{ route('admin.kegiatan.update',$kegiatan) }}" enctype="multipart/form-data" class="bg-white p-4 rounded-xl shadow grid gap-4 md:grid-cols-2">
  @csrf @method('PUT')
  <div>
    <label class="block text-sm font-medium mb-1">Nama Kegiatan</label>
    <input name="nama_kegiatan" value="{{ old('nama_kegiatan',$kegiatan->nama_kegiatan) }}" class="w-full border rounded px-3 py-2">
    @error('nama_kegiatan')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium mb-1">Jenis Kegiatan</label>
    <select name="jenis_kegiatan" class="w-full border rounded px-3 py-2">
      @foreach(['rutin','berkala','khusus'] as $s)
        <option value="{{ $s }}" @selected(old('jenis_kegiatan',$kegiatan->jenis_kegiatan->value)===$s)>{{ ucfirst($s) }}</option>
      @endforeach
    </select>
    @error('jenis_kegiatan')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium mb-1">Penanggung Jawab</label>
    <input name="penanggung_jawab" value="{{ old('penanggung_jawab',$kegiatan->penanggung_jawab) }}" class="w-full border rounded px-3 py-2">
    @error('penanggung_jawab')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium mb-1">No. Telepon</label>
    <input name="no_telephone" value="{{ old('no_telephone',$kegiatan->no_telephone) }}" class="w-full border rounded px-3 py-2">
    @error('no_telephone')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div class="md:col-span-2">
    <label class="block text-sm font-medium mb-1">Deskripsi</label>
    <textarea name="deskripsi" rows="4" class="w-full border rounded px-3 py-2">{{ old('deskripsi',$kegiatan->deskripsi) }}</textarea>
    @error('deskripsi')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>

  <div>
    <label class="block text-sm font-medium mb-1">Foto (opsional: unggah untuk ganti)</label>
    <input type="file" name="foto" class="w-full border rounded px-3 py-2">
    @error('foto')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
    @if($kegiatan->foto)
      <div class="mt-2">
        <img src="{{ asset('storage/'.$kegiatan->foto) }}" class="w-40 h-28 object-cover rounded" alt="{{ $kegiatan->nama_kegiatan }}">
      </div>
    @endif
  </div>
  <div>
    <label class="block text-sm font-medium mb-1">Dokumen pendukung (opsional: unggah untuk ganti)</label>
    <input type="file" name="dokumen" class="w-full border rounded px-3 py-2">
    @error('dokumen')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
    @if($kegiatan->dokumen)
      <div class="mt-2 text-sm">
        Dokumen saat ini:
        <a href="{{ asset('storage/'.$kegiatan->dokumen) }}" target="_blank" class="underline text-blue-600 hover:text-blue-800">
          {{ basename($kegiatan->dokumen) }}
        </a>
      </div>
    @endif
  </div>

  <div class="md:col-span-2 flex gap-2">
    <button class="px-4 py-2 rounded bg-blue-600 text-white">Simpan</button>
    <a href="{{ route('admin.kegiatan.index') }}" class="px-4 py-2 rounded bg-gray-200">Batal</a>
  </div>
</form>
@endsection
