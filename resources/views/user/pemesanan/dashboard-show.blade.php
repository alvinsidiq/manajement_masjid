@extends('layouts.admin', ['pageTitle' => 'Detail Pemesanan'])
@section('content')
@php
  $p = $pemesanan;
  $statusMap = [
    'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'class' => 'bg-amber-100 text-amber-700 border-amber-200'],
    'diterima' => ['label' => 'Disetujui', 'class' => 'bg-emerald-100 text-emerald-700 border-emerald-200'],
    'selesai' => ['label' => 'Selesai', 'class' => 'bg-emerald-100 text-emerald-700 border-emerald-200'],
    'ditolak' => ['label' => 'Ditolak', 'class' => 'bg-rose-100 text-rose-700 border-rose-200'],
    'dibatalkan' => ['label' => 'Dibatalkan', 'class' => 'bg-gray-200 text-gray-700 border-gray-300'],
  ];
  $statusValue = $p->status?->value;
  $statusMeta = $statusMap[$statusValue] ?? ['label' => Str::headline($statusValue ?? 'unknown'), 'class' => 'bg-gray-100 text-gray-700 border-gray-200'];
  $bookingDate = optional($p->booking?->hari_tanggal)?->timezone('Asia/Jakarta');
  $bookingTime = $p->booking?->jam;
  $payment = $p->payment;
  $statusPembayaran = $p->status_pembayaran ?? $payment?->status_pembayaran;
  $statusPembayaranNorm = $statusPembayaran ? strtoupper($statusPembayaran) : null;
  $paid = ($payment?->status?->value === 'paid') || in_array($statusPembayaranNorm, ['PAID', 'SETTLED'], true);
  $showPayment = in_array($statusValue, ['diterima', 'selesai'], true) || $payment;
  $statusPembayaranLabel = $paid
    ? 'Lunas'
    : ($statusPembayaranNorm ? Str::headline(strtolower($statusPembayaranNorm)) : ($payment?->status?->value ? Str::headline($payment->status->value) : 'Belum dibuat'));
@endphp

<div class="space-y-4">
  @if(session('status'))
    <div class="rounded border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">{{ session('status') }}</div>
  @endif
  @if($errors->any())
    <div class="rounded border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700">
      <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="bg-white p-4 rounded-xl shadow">
    <div class="flex flex-wrap items-center justify-between gap-3">
      <div>
        <div class="text-sm text-gray-500">Detail pemesanan</div>
        <div class="text-xl font-semibold">#{{ $p->pemesanan_id }}</div>
      </div>
      <span class="inline-flex items-center px-3 py-1 rounded-full border text-xs font-semibold {{ $statusMeta['class'] }}">
        {{ $statusMeta['label'] }}
      </span>
    </div>
  </div>

  <div class="grid lg:grid-cols-3 gap-4">
    <div class="lg:col-span-2 space-y-4">
      <div class="bg-white p-4 rounded-xl shadow">
        <h3 class="text-sm font-semibold text-gray-700">Ringkasan pemesanan</h3>
        <dl class="mt-3 grid md:grid-cols-2 gap-3 text-sm text-gray-700">
          <div class="flex items-center justify-between gap-2">
            <dt class="text-gray-500">Ruangan</dt>
            <dd class="font-semibold text-gray-900">{{ $p->ruangan?->nama_ruangan ?? '-' }}</dd>
          </div>
          <div class="flex items-center justify-between gap-2">
            <dt class="text-gray-500">Tujuan</dt>
            <dd class="font-semibold text-gray-900">{{ $p->tujuan_pemesanan ?? '-' }}</dd>
          </div>
          <div class="flex items-center justify-between gap-2">
            <dt class="text-gray-500">Tanggal</dt>
            <dd class="font-semibold text-gray-900">{{ $bookingDate ? $bookingDate->translatedFormat('l, d M Y') : '-' }}</dd>
          </div>
          <div class="flex items-center justify-between gap-2">
            <dt class="text-gray-500">Jam</dt>
            <dd class="font-semibold text-gray-900">{{ $bookingTime ? Str::of($bookingTime)->substr(0,5).' WIB' : '-' }}</dd>
          </div>
          <div class="flex items-center justify-between gap-2">
            <dt class="text-gray-500">Jumlah</dt>
            <dd class="font-semibold text-gray-900">Rp {{ number_format($p->ruangan->harga ?? 0, 0, ',', '.') }}</dd>
          </div>
          <div class="flex items-center justify-between gap-2">
            <dt class="text-gray-500">Status pembayaran</dt>
            <dd class="font-semibold text-gray-900">{{ $statusPembayaranLabel }}</dd>
          </div>
          <div class="md:col-span-2">
            <dt class="text-gray-500">Catatan</dt>
            <dd class="mt-1 text-gray-700">{{ $p->catatan ?? '-' }}</dd>
          </div>
        </dl>
      </div>

      @if($showPayment)
        @include('user.booking.payment-card', ['pemesanan' => $p])
      @endif
    </div>

    <aside class="space-y-4">
      <div class="bg-white p-4 rounded-xl shadow">
        <h3 class="text-sm font-semibold text-gray-700">Aksi</h3>
        <div class="mt-3 space-y-2 text-sm">
          <a href="{{ route('user.booking.dashboard') }}" class="flex w-full items-center justify-center rounded border px-3 py-2">Kembali ke daftar</a>
          @if($p->booking)
            <a href="{{ route('user.booking.show', $p->booking) }}" class="flex w-full items-center justify-center rounded border border-indigo-200 px-3 py-2 text-indigo-700">Lihat booking</a>
          @endif
          @if(Route::has('user.pemesanan.cancel.confirm') && $p->isCancellable())
            <a href="{{ route('user.pemesanan.cancel.confirm', $p) }}" class="flex w-full items-center justify-center rounded border border-rose-200 px-3 py-2 text-rose-600">Batalkan pemesanan</a>
          @endif
        </div>
      </div>

      <div class="bg-white p-4 rounded-xl shadow">
        <h3 class="text-sm font-semibold text-gray-700">Informasi</h3>
        <p class="mt-2 text-xs text-gray-600">Hubungi takmir jika perlu bantuan atau perubahan jadwal.</p>
      </div>
    </aside>
  </div>
</div>
@endsection
