@extends('layouts.landing', ['pageTitle'=>'Detail Pemesanan'])
@section('content')
@php($p = $pemesanan)
@php($payment = $p->payment)
@php($statusValue = optional($payment?->status)->value)
@php($gatewayValue = optional($payment?->gateway)->value)
@php($statusPembayaran = $p->status_pembayaran ?? $payment?->status_pembayaran)
@php($statusPembayaranNorm = $statusPembayaran ? strtoupper($statusPembayaran) : null)
@php($paid = $statusValue === 'paid' || in_array($statusPembayaranNorm, ['PAID', 'SETTLED'], true))
@php($isPending = $statusValue === 'pending' || $statusPembayaranNorm === 'PENDING')
@php($isManualPending = $isPending && $gatewayValue === 'manual')
@php($isAwaitingVerification = !$paid && $isPending && ($payment || $statusPembayaranNorm))
@php($statusLabel = $paid ? 'Pembayaran selesai' : ($isAwaitingVerification ? 'Menunggu verifikasi bendahara' : ($isPending ? 'Menunggu pembayaran' : ($statusValue ?? 'Belum dibuat'))))
@php($bookingDate = optional($p->booking?->hari_tanggal)?->timezone('Asia/Jakarta'))
@php($bookingTime = $p->booking?->jam)
<div class="max-w-4xl mx-auto px-4 py-10 space-y-6">
  @if(session('status'))
    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
  @endif
  @if($errors->any())
    <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
      <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
      </ul>
    </div>
  @endif

  <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm space-y-4">
    <div class="flex justify-between items-start">
      <div>
        <p class="text-xs uppercase tracking-wide text-gray-400">Kode Pemesanan</p>
        <h1 class="text-2xl font-semibold text-gray-900">#{{ $p->pemesanan_id }}</h1>
        <p class="text-sm text-gray-600 mt-2">Status: <span class="font-semibold">{{ ucfirst(str_replace('_',' ',$p->status->value)) }}</span></p>
      </div>
      @if($p->booking)
        <a href="{{ route('user.booking.show', $p->booking) }}" class="text-sm text-indigo-600 underline">Lihat Booking</a>
      @endif
    </div>
    <div class="grid md:grid-cols-2 gap-3 text-sm text-gray-800">
      <div><span class="text-gray-500">Ruangan:</span> {{ $p->ruangan->nama_ruangan ?? '-' }}</div>
      <div><span class="text-gray-500">Tujuan:</span> {{ $p->tujuan_pemesanan }}</div>
      <div><span class="text-gray-500">Catatan:</span> {{ $p->catatan ?? '-' }}</div>
      <div><span class="text-gray-500">Jumlah:</span> Rp {{ number_format($p->ruangan->harga ?? 0,0,',','.') }}</div>
    </div>
  </div>

  <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm space-y-3">
    <div class="flex items-center justify-between">
      <h3 class="text-sm font-semibold text-gray-700">Pembayaran</h3>
      @if($paid)
        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">Lunas</span>
      @endif
    </div>
    <div class="text-sm text-gray-700">
      <div><span class="text-gray-500">Jumlah:</span> <span class="font-semibold">Rp {{ number_format($p->ruangan->harga ?? 0,0,',','.') }}</span></div>
      <div><span class="text-gray-500">Status:</span> <span class="font-semibold">{{ $statusLabel }}</span></div>
    </div>

    @if($paid)
      <div class="rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        Pembayaran sudah diterima. Booking dapat digunakan pada
        <span class="font-semibold">
          {{ $bookingDate ? $bookingDate->format('d M Y') : 'tanggal yang dijadwalkan' }}
          @if($bookingTime) pukul {{ $bookingTime }}@endif
        </span>.
      </div>
    @elseif($isAwaitingVerification)
      <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
        Pembayaran Anda sudah dicatat dan sedang menunggu verifikasi bendahara.
      </div>
    @elseif($p->status->value === 'diterima')
      <div class="space-y-2">
        <form method="post" action="{{ route('user.pemesanan.pay', $p) }}">
          @csrf
          <input type="hidden" name="gateway" value="xendit">
          <input type="hidden" name="method" value="wallet_bank">
          <button class="w-full flex items-center justify-center gap-2 rounded-full bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Bayar via Wallet/Bank (Xendit)</button>
        </form>
        <form method="post" action="{{ route('user.pemesanan.pay', $p) }}">
          @csrf
          <input type="hidden" name="gateway" value="manual">
          <button class="w-full flex items-center justify-center gap-2 rounded-full border border-amber-300 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-800 hover:bg-amber-100">Bayar Tunai (konfirmasi admin)</button>
        </form>
      </div>
    @endif
  </div>
</div>
@endsection
