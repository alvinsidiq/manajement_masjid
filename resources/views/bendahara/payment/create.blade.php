@extends('layouts.admin', ['pageTitle'=>'Tambah Payment'])
@section('content')
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <form method="post" action="{{ route('bendahara.payment.store') }}">
    @include('bendahara.payment._form')
  </form>
  <div class="pt-2"><a href="{{ route('bendahara.payment.index') }}" class="px-3 py-2 rounded bg-gray-200">Kembali</a></div>
</div>
@endsection

