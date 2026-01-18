@extends('layouts.landing', ['pageTitle'=>'Daftar Booking Anda'])
@section('content')
<div class="max-w-6xl mx-auto px-4 py-10 space-y-8">
  <div class="rounded-3xl bg-gradient-to-r from-indigo-600 via-sky-600 to-cyan-500 text-white p-8 shadow-xl">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
      <div>
        <p class="text-sm uppercase tracking-[0.3em] text-white/80">Kelola kebutuhan acara</p>
        <h1 class="mt-2 text-3xl font-semibold">Daftar booking Anda</h1>
        <p class="mt-3 text-sm text-white/80 max-w-2xl">Pantau status penahanan (hold), konfirmasi jadwal, dan lanjutkan ke pemesanan resmi sebelum masa hold berakhir.</p>
      </div>
      <div class="flex items-center gap-3">
        <a href="{{ route('user.booking.create') }}" class="inline-flex items-center gap-2 rounded-full bg-white/15 px-5 py-3 text-sm font-semibold hover:bg-white/25 transition">
          <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white text-indigo-600 font-bold">+</span>
          Booking Baru
        </a>
      </div>
    </div>
  </div>

  @if(session('status'))
    <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 shadow-sm">{{ session('status') }}</div>
  @endif

  @php
    $statusOptions = [
      null => 'Semua',
      'hold' => 'Menunggu Pemesanan',
      'proses' => 'Proses',
      'setuju' => 'Setuju',
      'tolak' => 'Tolak',
      'cancelled' => 'Dibatalkan',
      'expired' => 'Expired',
    ];
    $statusColors = [
      'hold' => 'bg-amber-100 text-amber-700 border-amber-200',
      'proses' => 'bg-sky-100 text-sky-700 border-sky-200',
      'setuju' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
      'tolak' => 'bg-rose-100 text-rose-700 border-rose-200',
      'cancelled' => 'bg-rose-100 text-rose-700 border-rose-200',
      'expired' => 'bg-gray-200 text-gray-700 border-gray-300',
    ];
    $statusLabels = [
      'hold' => 'Menunggu Pemesanan',
      'proses' => 'Proses',
      'setuju' => 'Setuju',
      'tolak' => 'Tolak',
      'cancelled' => 'Dibatalkan',
      'expired' => 'Expired',
    ];
  @endphp

  <div class="flex flex-wrap items-center gap-3">
    @foreach($statusOptions as $key => $label)
      @php $active = ($st ?? null) === $key || ($key === null && empty($st)); @endphp
      <a href="{{ route('user.booking.index', array_filter(['status' => $key])) }}"
         class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm transition {{ $active ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-200 bg-white text-gray-600 hover:border-indigo-300 hover:text-indigo-600' }}">
        <span class="h-2 w-2 rounded-full {{ $active ? 'bg-indigo-500' : 'bg-gray-300' }}"></span>
        {{ $label }}
      </a>
    @endforeach
  </div>

  @if($items->isEmpty())
    <div class="rounded-3xl border-2 border-dashed border-indigo-200 bg-white p-12 text-center shadow-sm">
      <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m9-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
      </div>
      <h2 class="text-xl font-semibold text-gray-900">Belum ada booking</h2>
      <p class="mt-2 text-sm text-gray-500">Mulai dengan memilih ruangan dan waktu yang Anda inginkan. Slot akan di-hold selama 45 menit sebelum perlu dikonfirmasi.</p>
      <a href="{{ route('user.booking.create') }}" class="mt-6 inline-flex items-center gap-2 rounded-full bg-indigo-600 px-5 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Buat booking pertama</a>
    </div>
  @else
    <div class="grid gap-6 md:grid-cols-2">
      @foreach($items as $b)
        @php
          $statusClass = $statusColors[$b->status->value] ?? 'bg-gray-100 text-gray-700 border-gray-200';
          $statusLabel = $statusLabels[$b->status->value] ?? Str::headline($b->status->value);
          $holdEnds = $b->hold_expires_at?->timezone('Asia/Jakarta');
          $holdLabel = $holdEnds ? $holdEnds->format('d M Y · H:i') : null;
          $isHoldActive = $b->status->value === 'hold' && $holdEnds && $holdEnds->isFuture();
        @endphp
        <div class="relative overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm transition hover:shadow-lg">
          <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-indigo-50"></div>
          <div class="relative flex flex-col gap-6 p-6">
            <div class="flex items-start justify-between gap-4">
              <div>
                <p class="text-xs uppercase tracking-widest text-gray-400">Ruangan</p>
                <h3 class="mt-1 text-lg font-semibold text-gray-900">{{ $b->ruangan->nama_ruangan ?? 'Belum ditentukan' }}</h3>
              </div>
              <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                <span class="h-2 w-2 rounded-full bg-current/40"></span>
                {{ $statusLabel }}
              </span>
            </div>

            <dl class="grid grid-cols-2 gap-4 text-sm text-gray-600">
              <div>
                <dt class="text-xs uppercase tracking-wide text-gray-400">Tanggal</dt>
                <dd class="mt-1 font-medium text-gray-900">{{ $b->hari_tanggal->timezone('Asia/Jakarta')->translatedFormat('l, d M Y') }}</dd>
              </div>
              <div>
                <dt class="text-xs uppercase tracking-wide text-gray-400">Jam</dt>
                <dd class="mt-1 font-medium text-gray-900">{{ Str::of($b->jam)->substr(0,5) }} WIB</dd>
              </div>
              <div>
                <dt class="text-xs uppercase tracking-wide text-gray-400">Nomor</dt>
                <dd class="mt-1">#{{ $b->booking_id }}</dd>
              </div>
              <div>
                <dt class="text-xs uppercase tracking-wide text-gray-400">Relasi Pemesanan</dt>
                <dd class="mt-1">{{ $b->pemesanan ? 'Terhubung' : 'Belum dibuat' }}</dd>
              </div>
            </dl>

            @if($isHoldActive)
              <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-700">
                Slot akan ditahan hingga <span class="font-semibold">{{ $holdLabel }}</span>. Pastikan untuk mengonfirmasi sebelum waktu berakhir.
              </div>
            @elseif($b->status->value === 'hold' && $holdEnds)
              <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs text-rose-700">
                Masa hold berakhir pada {{ $holdLabel }}. Slot mungkin sudah kembali tersedia untuk pengguna lain.
              </div>
            @endif

            <div class="flex flex-wrap items-center gap-3">
              <a href="{{ route('user.booking.show',$b) }}" class="inline-flex items-center gap-2 rounded-full border border-indigo-200 px-4 py-2 text-xs font-semibold text-indigo-600 hover:border-indigo-300 hover:text-indigo-700">Lihat detail</a>
              @if(in_array($b->status->value,['hold','proses']))
                <a href="{{ route('user.booking.cancel.confirm', $b) }}" class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-4 py-2 text-xs font-semibold text-rose-600 hover:border-rose-300 hover:text-rose-700">Batalkan</a>
              @endif
            </div>
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
