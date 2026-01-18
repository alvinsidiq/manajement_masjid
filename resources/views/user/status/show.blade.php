@extends('layouts.landing', ['pageTitle'=>'Detail Status'])
@section('content')
@php
  $palette = [
    'hold' => 'bg-amber-100 text-amber-700 border-amber-200',
    'proses' => 'bg-sky-100 text-sky-700 border-sky-200',
    'setuju' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
    'tolak' => 'bg-rose-100 text-rose-700 border-rose-200',
    'diterima' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
    'dibatalkan' => 'bg-rose-100 text-rose-700 border-rose-200',
    'ditolak' => 'bg-rose-100 text-rose-700 border-rose-200',
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
<div class="max-w-3xl mx-auto px-4 py-10 space-y-4">
  @if($kind==='booking')
    @php($st = strtolower($booking->status->value))
    <div class="rounded-2xl border bg-white p-6 shadow-sm space-y-2">
      <div class="flex items-center justify-between">
        <div class="text-lg font-semibold">Booking #{{ $booking->booking_id }}</div>
        <span class="text-xs px-2 py-0.5 rounded-full border {{ $palette[$st] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">{{ $statusLabels[$st] ?? Str::headline($booking->status->value) }}</span>
      </div>
      <div>Ruangan: <span class="font-medium">{{ $booking->ruangan->nama_ruangan ?? '-' }}</span></div>
      <div>Tanggal: {{ $booking->hari_tanggal->timezone('Asia/Jakarta')->format('d M Y') }} &middot; Jam: {{ Str::substr($booking->jam,0,5) }}</div>
      <div class="mt-3"><a href="{{ route('user.booking.show', $booking) }}" class="px-3 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">Buka Halaman Booking</a></div>
    </div>
  @else
    @php($st = strtolower($pemesanan->status))
    <div class="rounded-2xl border bg-white p-6 shadow-sm space-y-2">
      <div class="flex items-center justify-between">
        <div class="text-lg font-semibold">Pemesanan #{{ $pemesanan->pemesanan_id }}</div>
        <span class="text-xs px-2 py-0.5 rounded-full border {{ $palette[$st] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">{{ Str::headline($pemesanan->status) }}</span>
      </div>
      <div>Ruangan: <span class="font-medium">{{ $pemesanan->ruangan->nama_ruangan ?? '-' }}</span></div>
      @if($pemesanan->booking)
        <div>Jadwal: {{ $pemesanan->booking->hari_tanggal->timezone('Asia/Jakarta')->format('d M Y') }} &middot; {{ Str::substr($pemesanan->booking->jam,0,5) }}</div>
      @endif
      <div>Tujuan: {{ $pemesanan->tujuan_pemesanan ?? '-' }}</div>
      <div class="mt-3"><a href="{{ route('user.pemesanan.show', $pemesanan) }}" class="px-3 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">Buka Halaman Pemesanan</a></div>
    </div>
  @endif
  <div><a href="{{ route('user.status.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded border bg-white hover:bg-gray-50">« Kembali ke Timeline</a></div>
</div>
@endsection
