@extends('layouts.admin', ['pageTitle'=>'Ubah Payment'])
@section('content')
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <form method="post" action="{{ route('bendahara.payment.update',$payment) }}">
    @csrf @method('PUT')
    @include('bendahara.payment._form', ['payment'=>$payment])
  </form>
  <div class="pt-2"><a href="{{ route('bendahara.payment.index') }}" class="px-3 py-2 rounded bg-gray-200">Kembali</a></div>
</div>
@endsection

