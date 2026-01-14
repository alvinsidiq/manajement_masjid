@extends('layouts.admin', ['pageTitle' => 'Detail Pengguna'])
@section('content')
<div class="bg-white p-4 rounded-xl shadow space-y-3">
  <div><span class="text-gray-500">Username:</span> {{ $user->username }}</div>
  <div><span class="text-gray-500">Email:</span> {{ $user->email }}</div>
  <div><span class="text-gray-500">Peran:</span> {{ ucfirst($user->role) }}</div>
  <div><span class="text-gray-500">Status:</span> {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</div>
  <div><span class="text-gray-500">Verifikasi Email:</span> {{ $user->hasVerifiedEmail() ? 'Terverifikasi' : 'Belum Verifikasi' }}</div>
  <div class="pt-3">
    <a href="{{ route('admin.users.edit',$user) }}" class="px-3 py-2 rounded bg-yellow-500 text-white">Ubah</a>
    <a href="{{ route('admin.users.index') }}" class="px-3 py-2 rounded bg-gray-200">Kembali</a>
  </div>
</div>
@endsection
