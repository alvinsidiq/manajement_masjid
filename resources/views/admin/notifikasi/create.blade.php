@extends('layouts.admin', ['pageTitle'=>'Broadcast Manual'])
@section('content')
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <form method="post" action="{{ route('admin.notifikasi.store') }}">
    @include('admin.notifikasi._form')
  </form>
  <div class="pt-2"><a href="{{ route('admin.notifikasi.index') }}" class="px-3 py-2 rounded bg-gray-200">Kembali</a></div>
</div>
@endsection

