@extends('layouts.admin', ['pageTitle'=>'Lanjutkan Pemesanan'])
@section('content')
@php
  $holdEnds = $booking?->hold_expires_at?->timezone('Asia/Jakarta');
@endphp

<div class="grid lg:grid-cols-3 gap-4">
  <div class="lg:col-span-2 bg-white p-4 rounded-xl shadow">
    <div>
      <div class="text-sm text-gray-500">Pemesanan</div>
      <div class="text-lg font-semibold">Lengkapi formulir pemesanan</div>
    </div>

    @if($errors->any())
      <div class="mt-4 rounded border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700">
        <ul class="list-disc list-inside space-y-1">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="post" action="{{ route('user.pemesanan.store') }}" class="mt-4 space-y-4">
      @csrf
      <input type="hidden" name="booking_id" value="{{ $booking?->booking_id }}">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tujuan pemesanan</label>
        <input name="tujuan_pemesanan" value="{{ old('tujuan_pemesanan', $tujuan) }}" class="w-full rounded border px-3 py-2" required>
        @error('tujuan_pemesanan')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
        <textarea name="catatan" rows="3" class="w-full rounded border px-3 py-2" placeholder="Tambahkan catatan khusus jika diperlukan.">{{ old('catatan') }}</textarea>
        @error('catatan')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
      </div>
      <div class="flex gap-2">
        <button class="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Kirim pemesanan</button>
        <a href="{{ route('user.booking.show', $booking) }}" class="rounded border px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Kembali</a>
      </div>
    </form>
  </div>

  <aside class="space-y-4">
    <div class="bg-white p-4 rounded-xl shadow">
      <h3 class="text-sm font-semibold text-gray-700">Ringkasan booking</h3>
      <dl class="mt-3 space-y-2 text-sm text-gray-700">
        <div class="flex items-center justify-between gap-2">
          <dt class="text-gray-500">Kode booking</dt>
          <dd class="font-semibold text-gray-900">{{ $booking?->booking_id ?? '-' }}</dd>
        </div>
        <div class="flex items-center justify-between gap-2">
          <dt class="text-gray-500">Ruangan</dt>
          <dd class="font-semibold text-gray-900">{{ $booking?->ruangan?->nama_ruangan ?? '-' }}</dd>
        </div>
        <div class="flex items-center justify-between gap-2">
          <dt class="text-gray-500">Tanggal</dt>
          <dd class="font-semibold text-gray-900">{{ $booking?->hari_tanggal?->timezone('Asia/Jakarta')->translatedFormat('l, d M Y') ?? '-' }}</dd>
        </div>
        <div class="flex items-center justify-between gap-2">
          <dt class="text-gray-500">Jam</dt>
          <dd class="font-semibold text-gray-900">{{ $booking ? Str::of($booking->jam)->substr(0,5).' WIB' : '-' }}</dd>
        </div>
        <div class="flex items-center justify-between gap-2">
          <dt class="text-gray-500">Tujuan awal</dt>
          <dd class="font-semibold text-gray-900">{{ $tujuan ?? session('booking_tujuan_'.$booking?->booking_id) ?? '-' }}</dd>
        </div>
      </dl>

      @if($holdEnds)
        <div class="mt-3 rounded border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-700">
          Booking ditahan sampai {{ $holdEnds->format('d M Y H:i') }}. Lengkapi pemesanan sebelum waktu berakhir.
        </div>
      @endif
    </div>

    <div class="bg-white p-4 rounded-xl shadow">
      <h3 class="text-sm font-semibold text-gray-700">Butuh bantuan?</h3>
      <p class="mt-2 text-xs text-gray-600">Hubungi takmir jika ada kendala saat melengkapi pemesanan.</p>
    </div>
  </aside>
</div>
@endsection
