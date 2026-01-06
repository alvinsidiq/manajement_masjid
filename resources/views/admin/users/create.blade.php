@extends('layouts.admin', ['pageTitle' => 'Tambah Pengguna'])
@section('content')
<form method="post" action="{{ route('admin.users.store') }}" class="bg-white p-4 rounded-xl shadow grid gap-4 md:grid-cols-2">
  @csrf

  <div>
    <label class="block text-sm font-medium mb-1">Username</label>
    <input name="username" value="{{ old('username') }}" class="w-full border rounded px-3 py-2">
    @error('username')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium mb-1">Email</label>
    <input name="email" type="email" value="{{ old('email') }}" class="w-full border rounded px-3 py-2">
    @error('email')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium mb-1">Password</label>
    <input name="password" type="password" class="w-full border rounded px-3 py-2">
    @error('password')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium mb-1">Peran</label>
    <select name="role" class="w-full border rounded px-3 py-2">
      @foreach(['admin','user','bendahara','takmir'] as $r)
        <option value="{{ $r }}" @selected(old('role')===$r)>{{ ucfirst($r) }}</option>
      @endforeach
    </select>
    @error('role')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium mb-1">Status</label>
    <select name="is_active" class="w-full border rounded px-3 py-2">
      <option value="1" @selected(old('is_active','1')==='1')>Aktif</option>
      <option value="0" @selected(old('is_active')==='0')>Nonaktif</option>
    </select>
    @error('is_active')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="md:col-span-2 flex gap-2">
    <button class="px-4 py-2 rounded bg-blue-600 text-white">Simpan</button>
    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded bg-gray-200">Batal</a>
  </div>
</form>
@endsection

