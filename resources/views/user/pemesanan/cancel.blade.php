 @extends('layouts.landing', ['pageTitle'=>'Batalkan Pemesanan'])
 @section('content')
<div class="max-w-xl mx-auto px-4 py-10">
  <div class="bg-white dark:bg-gray-800 rounded-xl border p-4 space-y-3">
    <div class="text-lg font-semibold">Batalkan Pemesanan #{{ $pemesanan->pemesanan_id }}</div>
    <div>Ruangan: <span class="font-medium">{{ $pemesanan->ruangan->nama_ruangan ?? '-' }}</span></div>
    <div>Status saat ini: <span class="px-2 py-0.5 rounded-full border">{{ ucfirst($pemesanan->status) }}</span></div>
    <form method="post" action="{{ route('user.pemesanan.cancel',$pemesanan) }}" class="space-y-3">
      @csrf
      <label class="block text-sm font-medium">Alasan Pembatalan</label>
      <textarea name="reason" rows="3" class="w-full border rounded px-3 py-2" required>{{ old('reason') }}</textarea>
      @error('reason')<div class="text-sm text-rose-600">{{ $message }}</div> @enderror
      <div class="flex gap-2">
        <button class="px-4 py-2 rounded bg-rose-600 text-white" onclick="return confirm('Yakin batalkan pemesanan ini?')">Batalkan Pemesanan</button>
        <a href="{{ route('user.pemesanan.show',$pemesanan) }}" class="px-4 py-2 rounded border">Kembali</a>
      </div>
    </form>
  </div>
</div>
 @endsection
