@extends('layouts.landing', ['title'=>$r->nama_ruangan])
@section('content')
<div class="max-w-6xl mx-auto px-4 py-10 space-y-6">
  <div class="grid md:grid-cols-12 gap-6">
    <div class="md:col-span-7 rounded-xl overflow-hidden border bg-white" x-data="{ open:false }">
      @if($r->foto)
        <img src="{{ asset('storage/'.$r->foto) }}" class="w-full h-80 md:h-96 object-cover cursor-zoom-in" alt="{{ $r->nama_ruangan }}" @click="open=true">
        <!-- Lightbox Preview -->
        <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4" @click.self="open=false" @keydown.escape.window="open=false">
          <img src="{{ asset('storage/'.$r->foto) }}" alt="{{ $r->nama_ruangan }}" class="max-h-[90vh] w-auto object-contain rounded shadow-2xl">
        </div>
      @else
        <div class="w-full h-80 md:h-96 bg-gray-100"></div>
      @endif
      <div class="p-4">
        <h1 class="text-2xl font-bold">{{ $r->nama_ruangan }}</h1>
        <div class="mt-2 text-sm text-gray-500">
          Status: <span class="px-2 py-0.5 rounded-full border {{ $r->status==='aktif' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">{{ ucfirst($r->status) }}</span>
        </div>
        <div class="mt-1 text-lg font-semibold text-gray-900">Rp {{ number_format($r->harga ?? 0,0,',','.') }}</div>
        <p class="mt-3">{!! nl2br(e($r->deskripsi)) !!}</p>
        @php($fas = is_array($r->fasilitas) ? $r->fasilitas : (json_decode($r->fasilitas ?? '[]', true) ?? []))
        @if($fas)
        <div class="mt-3 flex flex-wrap gap-2">
          @foreach($fas as $f)
            <span class="text-xs px-2 py-1 rounded bg-gray-100">{{ $f }}</span>
          @endforeach
        </div>
        @endif
      </div>
    </div>
    <div class="md:col-span-5 space-y-4">
      <div class="rounded-xl border bg-white">
        <div class="p-4 border-b font-semibold">Ketersediaan ({{ $rangeStart->format('d M') }} – {{ $rangeEnd->format('d M') }})</div>
        <div class="p-4 text-sm">
          @if($blocked->isEmpty())
            <div class="text-gray-500">Belum ada jadwal terblokir 14 hari ke depan.</div>
          @else
            <ul class="space-y-3">
              @foreach($blocked as $p)
                <li class="rounded border p-3">
                  @if($p->booking)
                    <div><span class="font-medium">Tanggal:</span> {{ $p->booking->hari_tanggal?->timezone('Asia/Jakarta')->format('d M Y') }}</div>
                    <div><span class="font-medium">Jam:</span> {{ $p->booking->jam }}</div>
                  @elseif($p->jadwal)
                    <div><span class="font-medium">Mulai:</span> {{ $p->jadwal->tanggal_mulai?->timezone('Asia/Jakarta')->format('d M Y H:i') }}</div>
                    <div><span class="font-medium">Selesai:</span> {{ $p->jadwal->tanggal_selesai?->timezone('Asia/Jakarta')->format('d M Y H:i') }}</div>
                  @else
                    <div class="text-gray-500">(Waktu tidak tercantum)</div>
                  @endif
                  <div class="mt-1"><span class="font-medium">Kegiatan/Tujuan:</span> {{ $p->tujuan_pemesanan ?? '-' }}</div>
                </li>
              @endforeach
            </ul>
          @endif
          <div class="mt-4 text-right">
            <a href="{{ url('/booking') }}" class="px-3 py-2 text-sm rounded bg-indigo-600 text-white">Booking Ruangan Ini</a>
          </div>
        </div>
      </div>
      <div class="rounded-xl border bg-white p-4">
        <div class="font-semibold mb-2">Kontak Takmir</div>
        <p class="text-sm text-gray-600">Silakan hubungi pengurus untuk informasi lebih lanjut mengenai penyewaan.</p>
      </div>
    </div>
  </div>
  <div><a href="{{ route('public.ruangan.index') }}" class="text-indigo-600">« Kembali ke katalog</a></div>
  </div>
@endsection
