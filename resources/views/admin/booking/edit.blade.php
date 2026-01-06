@extends('layouts.admin', ['pageTitle'=>'Ubah Booking'])
@section('content')
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <form method="post" action="{{ route('admin.booking.update',$booking) }}">
    @csrf @method('PUT')
    @include('admin.booking._form', ['booking'=>$booking])
  </form>
  <div class="pt-2"><a href="{{ route('admin.booking.index') }}" class="px-3 py-2 rounded bg-gray-200">Kembali</a></div>
</div>
@endsection

