@extends('layouts.landing', ['title'=>'Informasi Masjid'])
@section('content')
<form method="get" class="mb-4">
    <input name="q" value="{{ $q }}" class="border rounded px-3 py-2 w-full md:w-1/2" placeholder="Cari informasi...">
    </form>
<div class="grid md:grid-cols-2 gap-4">
@forelse($pages as $p)
  <a href="{{ route('public.info.show',$p['slug']) }}" class="block bg-white p-4 rounded-xl shadow hover:shadow-md">
      <div class="font-semibold mb-1">{{ $p['title'] }}</div>
      <div class="text-sm text-gray-600 line-clamp-2">{{ $p['content'] }}</div>
  </a>
@empty
  <div class="text-gray-500">Tidak ada informasi.</div>
@endforelse
    </div>
@endsection

