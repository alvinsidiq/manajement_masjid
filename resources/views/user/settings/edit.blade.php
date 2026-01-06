@extends('layouts.admin', ['pageTitle'=>'Ubah Pengaturan'])
@section('content')
<form method="post" action="{{ route('user.settings.update', $setting) }}" class="bg-white p-4 rounded-xl shadow grid gap-4 md:grid-cols-2">
  @csrf
  @method('PUT')

  <div>
    <label class="block text-sm font-medium mb-1">Mode Gelap</label>
    <select name="dark_mode" class="w-full border rounded px-3 py-2">
      <option value="0" @selected(!$setting->dark_mode)>Nonaktif</option>
      <option value="1" @selected($setting->dark_mode)>Aktif</option>
    </select>
    @error('dark_mode')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>

  <div>
    <label class="block text-sm font-medium mb-1">Halaman Awal</label>
    <select name="preferred_landing" class="w-full border rounded px-3 py-2">
      <option value="dashboard" @selected($setting->preferred_landing==='dashboard')>Dashboard</option>
      <option value="home" @selected($setting->preferred_landing==='home')>Home (landing publik)</option>
    </select>
    @error('preferred_landing')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="md:col-span-2 flex gap-2">
    <button class="px-4 py-2 rounded bg-blue-600 text-white">Simpan</button>
    <a href="{{ route('user.settings.index') }}" class="px-4 py-2 rounded bg-gray-200">Batal</a>
  </div>
</form>
@endsection

