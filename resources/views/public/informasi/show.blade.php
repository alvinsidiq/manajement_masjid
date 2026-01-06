@extends('layouts.landing', ['title'=>$info->judul])
@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
  <article class="prose max-w-none">
    <h1>{{ $info->judul }}</h1>
    <p class="text-sm text-gray-500">Dipublikasikan: {{ $info->published_at?->timezone('Asia/Jakarta')->format('d M Y H:i') }}</p>
    @if($info->foto)
      <img src="{{ asset('storage/'.$info->foto) }}" alt="{{ $info->judul }}" class="rounded-xl">
    @endif
    <div class="mt-4">{!! nl2br(e($info->isi)) !!}</div>
  </article>
  <div class="mt-6"><a href="{{ route('public.informasi.index') }}" class="text-indigo-600">« Kembali ke daftar</a></div>
  </div>
@endsection

