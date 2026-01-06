@extends('layouts.admin', ['pageTitle'=>'Tambah Pemesanan'])
@section('content')
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <form method="post" action="{{ route('admin.pemesanan.store') }}">
    @include('admin.pemesanan._form')
  </form>
  <div class="pt-2"><a href="{{ route('admin.pemesanan.index') }}" class="px-3 py-2 rounded bg-gray-200">Kembali</a></div>
</div>
@endsection

