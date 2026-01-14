@extends('layouts.landing', ['pageTitle'=>'Daftar Ruangan'])
@section('content')
@php
  $statusPalette = [
    'hold' => ['label' => 'Hold', 'bg' => 'bg-amber-100 text-amber-700 border-amber-200'],
    'proses' => ['label' => 'Proses', 'bg' => 'bg-sky-100 text-sky-700 border-sky-200'],
    'setuju' => ['label' => 'Setuju', 'bg' => 'bg-emerald-100 text-emerald-700 border-emerald-200'],
    'tolak' => ['label' => 'Tolak', 'bg' => 'bg-rose-100 text-rose-700 border-rose-200'],
    'cancelled' => ['label' => 'Dibatalkan', 'bg' => 'bg-rose-100 text-rose-700 border-rose-200'],
    'expired' => ['label' => 'Expired', 'bg' => 'bg-gray-200 text-gray-600 border-gray-300'],
  ];
  $palette = $statusPalette[$b->status->value] ?? ['label' => ucfirst($b->status->value), 'bg' => 'bg-gray-200 text-gray-600 border-gray-300'];
  $holdEnds = $b->hold_expires_at?->timezone('Asia/Jakarta');
  $holdActive = $b->status->value === 'hold' && $holdEnds && $holdEnds->isFuture();
  $pemesanan = $b->pemesanan;
  $steps = [
    ['title' => 'Hold Booking', 'desc' => 'Slot berhasil ditahan sementara.', 'status' => $b->created_at],
    ['title' => 'Pemesanan', 'desc' => 'Lengkapi formulir pemesanan untuk konfirmasi.', 'status' => $pemesanan ? 'done' : 'pending'],
    ['title' => 'Persetujuan', 'desc' => 'Takmir melakukan review dan konfirmasi.', 'status' => ($pemesanan && $pemesanan->status?->value === 'diterima') ? 'done' : 'pending'],
  ];
@endphp

<div class="max-w-5xl mx-auto px-4 py-10 space-y-6">
  <div class="rounded-3xl bg-gradient-to-r from-indigo-600 via-sky-600 to-cyan-500 p-8 text-white shadow-xl">
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
      <div>
        <p class="text-xs uppercase tracking-[0.3em] text-white/80">Kode Booking</p>
        <h1 class="mt-1 text-3xl font-semibold">#{{ $b->booking_id }}</h1>
        <p class="mt-3 text-sm text-white/80 max-w-xl">Pastikan Anda mengonfirmasi pemesanan sebelum masa hold berakhir agar ruangan tetap menjadi milik Anda.</p>
      </div>
      <span class="inline-flex items-center gap-2 rounded-full border border-white/40 bg-white/10 px-4 py-2 text-xs font-semibold">
        Status
        <span class="rounded-full bg-white px-3 py-1 text-indigo-600">{{ $palette['label'] }}</span>
      </span>
    </div>
  </div>

  @if(session('status'))
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-sm">{{ session('status') }}</div>
  @endif

  <div class="grid gap-6 lg:grid-cols-[1.4fr,1fr]">
    <div class="space-y-6">
      <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
        <div class="grid gap-6 sm:grid-cols-2">
          <div>
            <p class="text-xs uppercase tracking-wide text-gray-400">Ruangan</p>
            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $b->ruangan->nama_ruangan ?? 'Belum ditentukan' }}</p>
          </div>
          <div>
            <p class="text-xs uppercase tracking-wide text-gray-400">Tanggal</p>
            <p class="mt-1 text-sm font-medium text-gray-900">{{ $b->hari_tanggal->timezone('Asia/Jakarta')->translatedFormat('l, d M Y') }}</p>
          </div>
          <div>
            <p class="text-xs uppercase tracking-wide text-gray-400">Jam</p>
            <p class="mt-1 text-sm font-medium text-gray-900">{{ Str::of($b->jam)->substr(0,5) }} WIB</p>
          </div>
          <div>
            <p class="text-xs uppercase tracking-wide text-gray-400">Harga</p>
            <p class="mt-1 text-sm font-medium text-gray-900">Rp {{ number_format($b->ruangan->harga ?? 0,0,',','.') }}</p>
          </div>
          <div>
            <p class="text-xs uppercase tracking-wide text-gray-400">Tujuan</p>
            <p class="mt-1 text-sm text-gray-700">{{ $tujuan ?? 'Akan diisi saat pemesanan' }}</p>
          </div>
        </div>

        <div class="mt-6 rounded-2xl border {{ $holdActive ? 'border-amber-200 bg-amber-50 text-amber-700' : 'border-gray-200 bg-gray-50 text-gray-600' }} px-4 py-4 text-sm">
          @if($holdActive)
            Slot akan ditahan hingga <strong>{{ $holdEnds->format('d M Y · H:i') }}</strong>. <span class="font-medium">Segera lanjutkan ke pemesanan untuk mengamankan ruangan.</span>
          @elseif($holdEnds)
            Masa hold berakhir pada {{ $holdEnds->format('d M Y · H:i') }}. Jika masih diperlukan, lakukan booking ulang.
          @else
            Hold tidak memiliki batas waktu yang dapat dihitung otomatis.
          @endif
        </div>
      </div>

      <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-700">Progress booking</h3>
        <div class="mt-4 space-y-4">
          @foreach($steps as $stepIndex => $step)
            @php
              $isDone = $step['status'] === 'done' || ($step['status'] instanceof \Illuminate\Support\Carbon && $step['status']);
              $badgeClass = $isDone ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-gray-100 text-gray-500 border-gray-200';
              $timelineClass = $isDone ? 'bg-emerald-500' : 'bg-gray-300';
            @endphp
            <div class="flex gap-4">
              <div class="relative flex flex-col items-center">
                <span class="flex h-8 w-8 items-center justify-center rounded-full border {{ $badgeClass }} text-xs font-semibold">{{ $stepIndex + 1 }}</span>
                @if(!$loop->last)
                  <span class="mt-1 h-full w-1 rounded-full {{ $timelineClass }}"></span>
                @endif
              </div>
              <div class="flex-1">
                <p class="text-sm font-semibold text-gray-800">{{ $step['title'] }}</p>
                <p class="text-xs text-gray-500">{{ $step['desc'] }}</p>
                @if($step['status'] instanceof \Illuminate\Support\Carbon)
                  <p class="mt-2 text-[11px] uppercase tracking-wide text-gray-400">{{ $step['status']->timezone('Asia/Jakarta')->format('d M Y · H:i') }}</p>
                @elseif($step['status'] === 'done')
                  <p class="mt-2 text-[11px] uppercase tracking-wide text-emerald-500">Selesai</p>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      </div>

      @if($pemesanan && $pemesanan->status?->value === 'diterima')
        @include('user.booking.payment-card', ['pemesanan'=>$pemesanan])
      @endif
    </div>

    <aside class="space-y-6">
      <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-700">Tindakan</h3>
        <div class="mt-4 space-y-3">
          @if(Route::has('user.pemesanan.create') && in_array($b->status->value, ['hold']))
            <a href="{{ route('user.pemesanan.create', ['booking_id'=>$b->booking_id,'tujuan'=>$tujuan]) }}" class="flex w-full items-center justify-center gap-2 rounded-full bg-indigo-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-indigo-500">
              Lanjutkan ke Pemesanan
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
          @elseif($pemesanan && $pemesanan->status?->value === 'diterima')
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs text-emerald-700">
              Permintaan disetujui. Lakukan pembayaran di bawah.
            </div>
          @else
            <div class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-xs text-gray-600">Konfirmasi pemesanan belum tersedia atau booking sudah tidak dalam status hold.</div>
          @endif

          @if(in_array($b->status->value,['hold','proses']))
            <form method="post" action="{{ route('user.booking.update',$b) }}" onsubmit="return confirm('Batalkan booking ini?')">
              @csrf @method('PUT')
              <input type="hidden" name="aksi" value="cancel">
              <button class="flex w-full items-center justify-center gap-2 rounded-full border border-rose-200 px-6 py-3 text-sm font-semibold text-rose-600 transition hover:border-rose-300 hover:text-rose-700">Batalkan Booking</button>
            </form>
          @endif
        </div>
      </div>

      <div class="rounded-3xl border border-indigo-100 bg-indigo-50/60 p-6 shadow-inner">
        <h3 class="text-sm font-semibold text-indigo-700">Perlu bantuan?</h3>
        <p class="mt-2 text-xs text-indigo-700/90">Hubungi admin takmir melalui menu kontak atau WA yang tersedia di profil jika memerlukan perpanjangan hold.</p>
      </div>
    </aside>
  </div>
</div>
@endsection
