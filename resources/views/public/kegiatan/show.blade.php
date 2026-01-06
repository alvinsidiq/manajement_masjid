@extends('layouts.landing', ['title'=>$kegiatan->nama_kegiatan])
@section('content')
@php($jenis = $kegiatan->jenis_kegiatan->value)
@php($tel = preg_replace('/[^0-9]/','', $kegiatan->no_telephone))
@php($wa = $tel ? (str_starts_with($tel,'0') ? '62'.substr($tel,1) : $tel) : null)

<div class="max-w-6xl mx-auto px-4 py-10 space-y-8 text-gray-900">
  @if(session('status'))
    <div class="p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>
  @endif
  <!-- Hero with image (if any) -->
  <section class="overflow-hidden rounded-2xl border bg-white" x-data="{ open:false }">
    @if($kegiatan->foto)
      <img src="{{ asset('storage/'.$kegiatan->foto) }}" alt="{{ $kegiatan->nama_kegiatan }}" class="w-full h-80 md:h-96 object-cover cursor-zoom-in" @click="open=true">
      <!-- Lightbox Preview -->
      <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4" @click.self="open=false" @keydown.escape.window="open=false">
        <img src="{{ asset('storage/'.$kegiatan->foto) }}" alt="{{ $kegiatan->nama_kegiatan }}" class="max-h-[90vh] w-auto object-contain rounded shadow-2xl">
      </div>
    @endif
    <div class="p-8 md:p-10 space-y-4">
      <div class="flex flex-wrap gap-2 items-center">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-900">{{ ucfirst($jenis) }}</span>
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
          <span class="w-2 h-2 rounded-full bg-emerald-600"></span> Disetujui Takmir
        </span>
        @if($sudahDaftar)
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
          <span class="w-2 h-2 rounded-full bg-blue-600"></span> Anda sudah terdaftar
        </span>
        @endif
      </div>
      <h1 class="text-2xl md:text-3xl font-bold">{{ $kegiatan->nama_kegiatan }}</h1>
      <div>Penanggung jawab: <span class="font-semibold">{{ $kegiatan->penanggung_jawab }}</span></div>
      <div class="flex flex-wrap gap-2 pt-2">
        <a href="tel:{{ $tel }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border bg-white text-gray-900 hover:bg-gray-50">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path d="M2 3a1 1 0 0 1 1-1h2.153a1 1 0 0 1 .986.836l.74 4.067a1 1 0 0 1-.54 1.09l-1.548.774a11.042 11.042 0 0 0 5.292 5.292l.774-1.548a1 1 0 0 1 1.09-.54l4.067.74a1 1 0 0 1 .836.986V17a1 1 0 0 1-1 1h-2A13 13 0 0 1 2 5V3Z"/></svg>
          {{ $kegiatan->no_telephone }}
        </a>
        @if($wa)
        <a href="https://wa.me/{{ $wa }}?text={{ urlencode('Assalamualaikum, saya ingin info kegiatan: '.$kegiatan->nama_kegiatan) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border bg-white text-gray-900 hover:bg-gray-50">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M20 12a8 8 0 1 1-14.32 4.906L4 21l4.207-1.143A8 8 0 1 1 20 12Zm-8.57-3.143c-.21-.468-.432-.477-.632-.486-.164-.007-.352-.006-.54-.006-.19 0-.5.071-.762.357-.263.285-1.002.98-1.002 2.389 0 1.408 1.026 2.77 1.17 2.958.143.19 1.986 3.172 4.92 4.317 2.43.957 2.926.765 3.455.716.528-.048 1.704-.693 1.945-1.362.24-.668.24-1.24.168-1.362-.072-.12-.264-.192-.552-.336-.288-.143-1.704-.84-1.967-.936-.264-.096-.456-.144-.648.144-.192.288-.744.936-.912 1.128-.168.192-.336.216-.624.072-.288-.144-1.217-.449-2.319-1.43-.858-.764-1.438-1.705-1.607-1.993-.168-.288-.018-.444.126-.588.13-.13.288-.336.432-.504.144-.168.192-.288.288-.48.096-.192.048-.36-.024-.504-.072-.144-.636-1.538-.888-2.102Z"/></svg>
          WhatsApp
        </a>
        @endif
      </div>
    </div>
  </section>

  <!-- Content + Sidebar -->
  <div class="grid md:grid-cols-3 gap-6">
    <div class="md:col-span-2 bg-white rounded-2xl border p-6 md:p-8">
      <div class="prose max-w-none prose-p:text-gray-900 prose-li:text-gray-900 prose-strong:text-gray-900">{!! nl2br(e($kegiatan->deskripsi)) !!}</div>
    </div>
    <div class="space-y-4">
      @if(isset($upcoming) && $upcoming->count())
      <div class="bg-white rounded-2xl border p-5">
        <div class="font-semibold mb-2">Jadwal Terdekat</div>
        <ul class="space-y-2 text-sm text-gray-900">
          @foreach($upcoming as $j)
            <li class="flex items-start gap-2">
              <span class="mt-1 inline-block w-2 h-2 rounded-full @class([
                'bg-teal-600' => $jenis==='rutin',
                'bg-amber-600' => $jenis==='berkala',
                'bg-indigo-600' => $jenis==='khusus',
              ])"></span>
              <div>
                <div class="font-medium">{{ $j->tanggal_mulai?->timezone($tz)->format('d M Y') }}</div>
                <div>{{ $j->tanggal_mulai?->timezone($tz)->format('H:i') }} – {{ $j->tanggal_selesai?->timezone($tz)->format('H:i') }}</div>
                @if($j->catatan)
                <div>{{ $j->catatan }}</div>
                @endif
              </div>
            </li>
          @endforeach
        </ul>
      </div>
      @endif
      <div class="bg-white rounded-2xl border p-5">
        <div class="font-semibold mb-2">Kontak & Informasi</div>
        <div class="text-sm">Penanggung jawab: <span class="font-medium">{{ $kegiatan->penanggung_jawab }}</span></div>
        <div class="text-sm">Telepon: <a class="underline" href="tel:{{ $tel }}">{{ $kegiatan->no_telephone }}</a></div>
      </div>
      <div class="bg-white rounded-2xl border p-5">
        <div class="font-semibold mb-3">Daftar Mengikuti Kegiatan</div>
        @if($sudahDaftar)
          <div class="p-3 rounded bg-blue-50 text-blue-800 text-sm">Anda sudah terdaftar pada kegiatan ini.</div>
        @else
          <form method="post" action="{{ route('public.kegiatan.daftar', $kegiatan) }}" class="space-y-3">
            @csrf
            <div>
              <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
              <input name="nama" value="{{ old('nama', auth()->user()->name ?? auth()->user()->username ?? '') }}" class="w-full border rounded px-3 py-2" required>
              @error('nama')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Email</label>
              <input name="email" type="email" value="{{ old('email', auth()->user()->email ?? '') }}" class="w-full border rounded px-3 py-2" required>
              @error('email')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">No. Telepon</label>
              <input name="no_telephone" value="{{ old('no_telephone') }}" class="w-full border rounded px-3 py-2" placeholder="08xxxxxxxxxx atau +62 ..." required>
              @error('no_telephone')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Catatan (opsional)</label>
              <textarea name="catatan" rows="3" class="w-full border rounded px-3 py-2" placeholder="Contoh: jumlah peserta, kebutuhan khusus.">{{ old('catatan') }}</textarea>
              @error('catatan')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
            </div>
            <button class="w-full px-4 py-2 rounded bg-emerald-600 text-white hover:bg-emerald-700">Daftar Sekarang</button>
          </form>
        @endif
      </div>
    </div>
  </div>

  <div>
    <a href="{{ route('public.kegiatan.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded border bg-white text-gray-900 hover:bg-gray-50">« Kembali ke daftar kegiatan</a>
  </div>
</div>
@endsection
