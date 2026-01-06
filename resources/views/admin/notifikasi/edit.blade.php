@extends('layouts.admin', ['pageTitle'=>'Ubah Notifikasi'])
@section('content')
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <form method="post" action="{{ route('admin.notifikasi.update',$notifikasi) }}">
    @csrf @method('PUT')
    @include('admin.notifikasi._form', ['notifikasi'=>$notifikasi])
  </form>
  <div class="pt-2"><a href="{{ route('admin.notifikasi.index') }}" class="px-3 py-2 rounded bg-gray-200">Kembali</a></div>
</div>
@endsection

