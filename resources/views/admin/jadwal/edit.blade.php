@extends('layouts.admin', ['pageTitle'=>'Ubah Jadwal'])
@section('content')
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <form method="post" action="{{ route('admin.jadwal.update',$jadwal) }}">
    @csrf
    @method('PUT')
    @include('admin.jadwal._form', ['jadwal'=>$jadwal])
  </form>
  <div class="pt-2"><a href="{{ route('admin.jadwal.index') }}" class="px-3 py-2 rounded bg-gray-200">Kembali</a></div>
</div>
@endsection

