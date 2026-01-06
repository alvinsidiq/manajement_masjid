@extends('layouts.landing', ['title'=>'Katalog Ruangan'])
@section('content')
<div class="max-w-6xl mx-auto px-4 py-10 space-y-6">
  <form method="get" class="grid md:grid-cols-12 gap-3 bg-white p-4 rounded-xl border">
    <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama/desk…" class="border rounded px-3 py-2 md:col-span-4">
    <select name="status" class="border rounded px-3 py-2 md:col-span-2">
      <option value="">Semua Status</option>
      @foreach(['aktif','nonaktif','maintenance'] as $s)
        <option value="{{ $s }}" @selected(($st ?? '')==$s)>{{ ucfirst($s) }}</option>
      @endforeach
    </select>
    <select name="fasilitas" class="border rounded px-3 py-2 md:col-span-3">
      <option value="">Semua Fasilitas</option>
      @foreach($fasilitasList as $f)
        <option value="{{ $f }}" @selected(($fa ?? '')==$f)>{{ $f }}</option>
      @endforeach
    </select>
    <select name="sort" class="border rounded px-3 py-2 md:col-span-2">
      <option value="nama_ruangan" @selected(($sort ?? '')==='nama_ruangan')>Urut Nama</option>
      <option value="created_at" @selected(($sort ?? '')==='created_at')>Terbaru</option>
    </select>
    <select name="dir" class="border rounded px-3 py-2 md:col-span-1">
      <option value="asc" @selected(($dir ?? '')==='asc')>Asc</option>
      <option value="desc" @selected(($dir ?? '')==='desc')>Desc</option>
    </select>
  </form>

  <div class="grid md:grid-cols-3 gap-4">
    @forelse($items as $r)
      <a href="{{ route('public.ruangan.show', $r->ruangan_id) }}" class="rounded-xl border overflow-hidden bg-white block">
        @if($r->foto)
          <img src="{{ asset('storage/'.$r->foto) }}" alt="{{ $r->nama_ruangan }}" class="w-full h-40 object-cover">
        @else
          <div class="w-full h-40 bg-gray-100"></div>
        @endif
        <div class="p-4">
          <div class="flex items-center justify-between">
            <div class="font-semibold">{{ $r->nama_ruangan }}</div>
            <span class="text-xs px-2 py-1 rounded-full border {{ $r->status==='aktif' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">{{ ucfirst($r->status) }}</span>
          </div>
          <div class="text-sm text-gray-800 mt-1 font-semibold">Rp {{ number_format($r->harga ?? 0,0,',','.') }}</div>
          <p class="text-sm text-gray-600 mt-1">{{ str($r->deskripsi)->limit(80) }}</p>
          @php($fas = is_array($r->fasilitas) ? $r->fasilitas : (json_decode($r->fasilitas ?? '[]', true) ?? []))
          <div class="mt-2 flex flex-wrap gap-1">
            @foreach(array_slice($fas,0,4) as $f)
              <span class="text-[11px] px-2 py-1 rounded bg-gray-100">{{ $f }}</span>
            @endforeach
          </div>
        </div>
      </a>
    @empty
      <div class="text-gray-500">Tidak ada ruangan.</div>
    @endforelse
  </div>

  {{ $items->links() }}
</div>
@endsection
