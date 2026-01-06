@extends('layouts.landing', ['pageTitle'=>'Pemesanan Ruangan'])
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10 space-y-6">
  <div class="rounded-3xl bg-gradient-to-r from-indigo-600 to-sky-500 p-6 text-white shadow-lg">
    <h1 class="text-2xl font-semibold">Konfirmasi Pemesanan</h1>
    <p class="mt-2 text-sm text-white/80">Lengkapi detail pemesanan untuk melanjutkan permintaan ruangan.</p>
  </div>

  @if($errors->any())
    <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
      <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="rounded-3xl border border-gray-100 bg-white shadow-sm">
    <div class="border-b px-6 py-4">
      <h2 class="text-sm font-semibold text-gray-800">Ringkasan Booking</h2>
    </div>
    <div class="grid gap-4 px-6 py-5 md:grid-cols-2 text-sm text-gray-700">
      <div><span class="text-gray-500">Kode Booking:</span> <span class="font-semibold">{{ $booking?->booking_id ?? '-' }}</span></div>
      <div><span class="text-gray-500">Ruangan:</span> <span class="font-semibold">{{ $booking?->ruangan?->nama_ruangan ?? '-' }}</span></div>
      <div><span class="text-gray-500">Tanggal:</span> <span class="font-semibold">{{ $booking?->hari_tanggal?->timezone('Asia/Jakarta')->translatedFormat('l, d M Y') ?? '-' }}</span></div>
      <div><span class="text-gray-500">Jam:</span> <span class="font-semibold">{{ $booking ? Str::of($booking->jam)->substr(0,5).' WIB' : '-' }}</span></div>
      <div class="md:col-span-2"><span class="text-gray-500">Tujuan Booking:</span> <span class="font-semibold">{{ $tujuan ?? session('booking_tujuan_'.$booking?->booking_id) ?? '-' }}</span></div>
    </div>
  </div>

  <form method="post" action="{{ route('user.pemesanan.store') }}" class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm space-y-4">
    @csrf
    <input type="hidden" name="booking_id" value="{{ $booking?->booking_id }}">
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Tujuan Pemesanan</label>
      <input name="tujuan_pemesanan" value="{{ old('tujuan_pemesanan', $tujuan) }}" class="w-full rounded-lg border px-3 py-2" required>
      @error('tujuan_pemesanan')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
      <textarea name="catatan" rows="3" class="w-full rounded-lg border px-3 py-2" placeholder="Tambahkan catatan khusus jika diperlukan.">{{ old('catatan') }}</textarea>
      @error('catatan')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
    </div>
    <div class="flex gap-3">
      <button class="rounded-lg bg-indigo-600 px-4 py-2 text-white font-semibold hover:bg-indigo-500">Kirim Pemesanan</button>
      <a href="{{ route('user.booking.show', $booking) }}" class="rounded-lg border px-4 py-2 text-gray-700 hover:bg-gray-50">Kembali</a>
    </div>
  </form>
</div>
@endsection
