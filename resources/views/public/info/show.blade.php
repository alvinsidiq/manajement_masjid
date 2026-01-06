@extends('layouts.landing', ['title'=>$page['title']])
@section('content')
<div class="bg-white p-6 rounded-xl shadow">
    <h2 class="text-2xl font-bold mb-3">{{ $page['title'] }}</h2>
    <div>{{ $page['content'] }}</div>
</div>
@endsection

