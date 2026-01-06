@extends('layouts.admin', ['pageTitle'=>'Dashboard Pengguna'])
@section('content')
<div class="bg-white p-4 rounded-xl shadow">
  Halo {{ auth()->user()->username }}. Selamat datang di dashboard.
  Gunakan menu “Ruangan” di navbar untuk melihat katalog ruangan.
  </div>
@endsection
