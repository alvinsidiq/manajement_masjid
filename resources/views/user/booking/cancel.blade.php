 @extends('layouts.landing', ['pageTitle'=>'Batalkan Booking'])
 @section('content')
<div class="max-w-xl mx-auto px-4 py-10">
  <div class="bg-white dark:bg-gray-800 rounded-xl border p-4 space-y-3">
    <div class="text-lg font-semibold">Batalkan Booking #{{ $booking->booking_id }}</div>
    <div>Ruangan: <span class="font-medium">{{ $booking->ruangan->nama_ruangan ?? '-' }}</span></div>
    <div>Tanggal: {{ $booking->hari_tanggal->timezone('Asia/Jakarta')->format('d M Y') }} &middot; Jam: {{ \Illuminate\Support\Str::substr($booking->jam,0,5) }}</div>
    <form method="post" action="{{ route('user.booking.cancel',$booking) }}" class="space-y-3">
      @csrf
      <label class="block text-sm font-medium">Alasan Pembatalan</label>
      <textarea name="reason" rows="3" class="w-full border rounded px-3 py-2" required>{{ old('reason') }}</textarea>
      @error('reason')<div class="text-sm text-rose-600">{{ $message }}</div> @enderror
      <div class="flex gap-2">
        <button class="px-4 py-2 rounded bg-rose-600 text-white" onclick="return confirm('Yakin batalkan booking ini?')">Batalkan Booking</button>
        <a href="{{ route('user.booking.show',$booking) }}" class="px-4 py-2 rounded border">Kembali</a>
      </div>
    </form>
  </div>
</div>
 @endsection
