@extends('layouts.admin', ['pageTitle'=>'Batalkan Booking'])
@section('content')
<div class="grid lg:grid-cols-3 gap-4">
  <div class="lg:col-span-2 bg-white p-4 rounded-xl shadow">
    <div>
      <div class="text-sm text-gray-500">Pembatalan booking</div>
      <div class="text-lg font-semibold">Konfirmasi pembatalan</div>
    </div>
    <p class="mt-2 text-sm text-gray-600">Tulis alasan pembatalan agar dapat dicatat oleh sistem.</p>

    <form method="post" action="{{ route('user.booking.cancel', $booking) }}" class="mt-4 space-y-4">
      @csrf
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Alasan pembatalan</label>
        <textarea name="reason" rows="3" class="w-full rounded border px-3 py-2" required>{{ old('reason') }}</textarea>
        @error('reason')<div class="text-sm text-rose-600 mt-1">{{ $message }}</div>@enderror
      </div>
      <div class="flex gap-2">
        <button class="rounded bg-rose-600 px-4 py-2 text-sm font-semibold text-white" onclick="return confirm('Yakin batalkan booking ini?')">Batalkan booking</button>
        <a href="{{ route('user.booking.show', $booking) }}" class="rounded border px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Kembali</a>
      </div>
    </form>
  </div>

  <aside class="space-y-4">
    <div class="bg-white p-4 rounded-xl shadow">
      <h3 class="text-sm font-semibold text-gray-700">Detail booking</h3>
      <dl class="mt-3 space-y-2 text-sm text-gray-700">
        <div class="flex items-center justify-between gap-2">
          <dt class="text-gray-500">Kode booking</dt>
          <dd class="font-semibold text-gray-900">#{{ $booking->booking_id }}</dd>
        </div>
        <div class="flex items-center justify-between gap-2">
          <dt class="text-gray-500">Ruangan</dt>
          <dd class="font-semibold text-gray-900">{{ $booking->ruangan->nama_ruangan ?? '-' }}</dd>
        </div>
        <div class="flex items-center justify-between gap-2">
          <dt class="text-gray-500">Tanggal</dt>
          <dd class="font-semibold text-gray-900">{{ $booking->hari_tanggal->timezone('Asia/Jakarta')->translatedFormat('d M Y') }}</dd>
        </div>
        <div class="flex items-center justify-between gap-2">
          <dt class="text-gray-500">Jam</dt>
          <dd class="font-semibold text-gray-900">{{ \Illuminate\Support\Str::substr($booking->jam, 0, 5) }} WIB</dd>
        </div>
      </dl>
    </div>

    <div class="bg-white p-4 rounded-xl shadow">
      <h3 class="text-sm font-semibold text-gray-700">Catatan</h3>
      <p class="mt-2 text-xs text-gray-600">Pembatalan akan melepaskan slot booking. Anda dapat melakukan booking ulang jika masih dibutuhkan.</p>
    </div>
  </aside>
</div>
@endsection
