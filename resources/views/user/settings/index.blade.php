@extends('layouts.admin', ['pageTitle'=>'Pengaturan'])
@section('content')
@if(session('status'))
  <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>
@endif
<div class="bg-white p-4 rounded-xl shadow">
  <div class="mb-4">Atur preferensi akun Anda.</div>
  <dl class="divide-y">
    <div class="py-3 flex justify-between">
      <dt>Mode Gelap</dt>
      <dd>{{ $setting->dark_mode ? 'Aktif' : 'Nonaktif' }}</dd>
    </div>
    <div class="py-3 flex justify-between">
      <dt>Halaman Awal</dt>
      <dd>{{ ucfirst($setting->preferred_landing) }}</dd>
    </div>
  </dl>
  <a href="{{ route('user.settings.edit', $setting) }}" class="mt-4 inline-block px-4 py-2 rounded bg-gray-800 text-white">Ubah</a>
</div>
@endsection

