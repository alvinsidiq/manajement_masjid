@extends('layouts.landing', ['title'=>'Kegiatan Masjid'])
@section('content')
@php($jenisAktif = $jenis ?? '')
<div class="max-w-6xl mx-auto px-4 py-10 space-y-8">
  <!-- Hero / Jumbotron -->
  <section class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-teal-600 to-emerald-600 text-white">
    <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(ellipse at top left, rgba(255,255,255,0.45), transparent 55%), radial-gradient(circle at bottom right, rgba(255,255,255,0.25), transparent 55%);"></div>
    <div class="relative px-6 py-10 md:px-10 md:py-14 space-y-5">
      <div>
        <h1 class="text-2xl md:text-3xl font-bold">Kegiatan Masjid</h1>
        <p class="mt-2 text-white/90 max-w-2xl">Jelajahi kegiatan ibadah, kajian, dan program sosial yang diselenggarakan masjid. Temukan yang sesuai kebutuhan Anda.</p>
      </div>
      <form method="get" class="max-w-3xl">
        <div class="flex rounded-xl overflow-hidden shadow-lg">
          <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama kegiatan, penanggung jawab, atau deskripsi" class="w-full px-4 py-3 text-gray-900" />
          <button class="px-4 bg-teal-600 hover:bg-teal-700 text-white">Cari</button>
        </div>
        @if($jenisAktif)
          <input type="hidden" name="jenis" value="{{ $jenisAktif }}">
        @endif
      </form>
      <div class="flex flex-wrap gap-2">
        @php($base = request()->except('page'))
        @foreach([''=>'Semua','rutin'=>'Rutin','berkala'=>'Berkala','khusus'=>'Khusus'] as $val=>$label)
          @php($active = ($jenisAktif===$val))
          <a href="{{ route('public.kegiatan.index', array_merge($base, ['jenis'=>$val])) }}"
             class="px-3 py-1.5 rounded-full text-sm border @class([
                'bg-white/15 text-white border-white/40 hover:bg-white/20' => ! $active,
                'bg-gray-100 text-teal-700 border-white' => $active && $val==='',
                'bg-teal-600 text-white border-teal-600' => $active && $val==='rutin',
                'bg-amber-600 text-white border-amber-600' => $active && $val==='berkala',
                'bg-indigo-600 text-white border-indigo-600' => $active && $val==='khusus',
             ])">
            {{ $label }}
          </a>
        @endforeach
      </div>
    </div>
  </section>

  <!-- List Kegiatan -->
  <div class="flex items-center justify-between">
    <div class="text-sm text-gray-600">Total: {{ $items->total() }}</div>
    <div></div>
  </div>

  <div class="grid md:grid-cols-3 gap-5">
    @forelse($items as $k)
      <div class="group rounded-2xl border bg-white overflow-hidden hover:shadow-lg transition">
        <!-- Header image or gradient -->
        <div class="h-48 md:h-40 relative overflow-hidden">
          @if($k->foto)
            <img src="{{ asset('storage/'.$k->foto) }}" alt="{{ $k->nama_kegiatan }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
          @else
            <div class="absolute inset-0 @class([
              'bg-gradient-to-r from-teal-500 to-emerald-500' => $k->jenis_kegiatan->value==='rutin',
              'bg-gradient-to-r from-amber-500 to-orange-600' => $k->jenis_kegiatan->value==='berkala',
              'bg-gradient-to-r from-indigo-600 to-purple-600' => $k->jenis_kegiatan->value==='khusus',
            ])"></div>
          @endif
          <div class="absolute right-4 top-4">
            <span class="px-2 py-0.5 rounded text-xs font-medium bg-white/90 text-gray-800">
              {{ ucfirst($k->jenis_kegiatan->value) }}
            </span>
          </div>
        </div>
        <!-- Content -->
        <div class="p-5 space-y-2">
          <div class="text-lg font-semibold group-hover:text-teal-700">{{ $k->nama_kegiatan }}</div>
          <div class="text-sm text-gray-600">Penanggung jawab: {{ $k->penanggung_jawab }}</div>
          <div class="text-xs text-gray-500">Kontak: <span class="font-medium">{{ $k->no_telephone }}</span></div>
          <p class="mt-1 text-sm text-gray-700 line-clamp-3">{{ str($k->deskripsi)->limit(180) }}</p>
        </div>
        <!-- Footer CTA -->
        <div class="px-5 pb-5">
          <a href="{{ route('public.kegiatan.show',$k) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-white bg-green-600 hover:bg-green-700">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.75 4.75a.75.75 0 0 0-1.5 0v5.19L7.53 8.22a.75.75 0 1 0-1.06 1.06l3 3a.75.75 0 0 0 1.06 0l3-3a.75.75 0 0 0-1.06-1.06L10.75 9.94V4.75Z" clip-rule="evenodd"/><path d="M3.5 10a6.5 6.5 0 1 1 13 0 6.5 6.5 0 0 1-13 0Z"/></svg>
            Lihat Detail
          </a>
        </div>
      </div>
    @empty
      <div class="col-span-3">
        <div class="rounded-2xl border bg-white p-8 text-center text-gray-600">Belum ada kegiatan aktif yang dapat ditampilkan.</div>
      </div>
    @endforelse
  </div>

  <div>
    {{ $items->links() }}
  </div>
</div>
@endsection
