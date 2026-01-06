@extends('layouts.landing', ['pageTitle'=>'Daftar Ruangan'])
@section('content')
<div class="max-w-6xl mx-auto px-4 py-10 space-y-8">
  <div class="rounded-3xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white p-8 shadow-xl">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
      <div>
        <p class="text-sm uppercase tracking-[0.3em] text-white/80">Pilih ruangan</p>
        <h1 class="mt-2 text-3xl font-semibold">Daftar Ruangan</h1>
        <p class="mt-3 text-sm text-white/80 max-w-2xl">Berikut ruangan yang tersedia untuk booking. Klik detail untuk melihat informasi lengkap atau langsung lakukan booking.</p>
      </div>
      <form method="get" class="w-full md:w-80">
        <div class="flex rounded-xl overflow-hidden shadow-lg">
          <input type="text" name="q" value="{{ $q }}" placeholder="Cari ruangan/fasilitas" class="w-full px-4 py-3 text-gray-900" />
          <button class="px-4 bg-emerald-700 hover:bg-emerald-800 text-white">Cari</button>
        </div>
      </form>
    </div>
  </div>

  @if($items->isEmpty())
    <div class="rounded-3xl border-2 border-dashed border-emerald-200 bg-white p-12 text-center shadow-sm">
      <h2 class="text-xl font-semibold text-gray-900">Belum ada ruangan aktif</h2>
      <p class="mt-2 text-sm text-gray-500">Silakan kembali lagi nanti.</p>
    </div>
  @else
    <div class="grid gap-6 md:grid-cols-3">
      @foreach($items as $r)
        <div class="group rounded-2xl border bg-white overflow-hidden hover:shadow-lg transition" x-data="{ open:false }">
          <div class="h-48 md:h-40 relative overflow-hidden">
            @if($r->foto)
              <img src="{{ asset('storage/'.$r->foto) }}" alt="{{ $r->nama_ruangan }}" class="w-full h-full object-cover cursor-zoom-in" @click="open=true">
              <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
            @else
              <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
            @endif
            <!-- Lightbox Preview -->
            @if($r->foto)
            <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4" @click.self="open=false" @keydown.escape.window="open=false">
              <img src="{{ asset('storage/'.$r->foto) }}" alt="{{ $r->nama_ruangan }}" class="max-h-[90vh] w-auto object-contain rounded shadow-2xl">
            </div>
            @endif
          </div>
          <div class="p-5 space-y-2">
            <div class="text-lg font-semibold group-hover:text-emerald-700">{{ $r->nama_ruangan }}</div>
            <div class="text-sm font-semibold text-emerald-700">Rp {{ number_format($r->harga ?? 0,0,',','.') }}</div>
            @if($r->fasilitas)
              <div class="flex flex-wrap gap-1">
                @foreach(array_slice($r->fasilitas,0,4) as $f)
                  <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-700 text-xs">{{ $f }}</span>
                @endforeach
              </div>
            @endif
            <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($r->deskripsi, 120) }}</p>
          </div>
          <div class="flex items-center gap-2 px-5 pb-5">
            <a href="{{ route('public.ruangan.show',$r) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border bg-white hover:bg-gray-50 text-sm">Detail</a>
            <a href="{{ route('user.booking.create', ['ruangan_id'=>$r->ruangan_id]) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm">Booking</a>
          </div>
        </div>
      @endforeach
    </div>

    <div class="pt-4">
      {{ $items->links() }}
    </div>
  @endif
</div>
@endsection
