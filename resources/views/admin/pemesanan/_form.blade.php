@csrf
@php($pemesanan = $pemesanan ?? null)
<div class="grid md:grid-cols-2 gap-4">
  <div>
    <label class="block text-sm font-medium">Pemesan</label>
    <select name="user_id" class="border rounded px-3 py-2 w-full" required>
      @foreach($users as $u)
        <option value="{{ $u->user_id }}" @selected(old('user_id',$pemesanan->user_id ?? '')==$u->user_id)>{{ $u->username }}</option>
      @endforeach
    </select>
    @error('user_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium">Ruangan</label>
    <select name="ruangan_id" class="border rounded px-3 py-2 w-full" required>
      @foreach($ruangans as $r)
        <option value="{{ $r->ruangan_id }}" @selected(old('ruangan_id',$pemesanan->ruangan_id ?? '')==$r->ruangan_id)>{{ $r->nama_ruangan }}</option>
      @endforeach
    </select>
    @error('ruangan_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium">Booking (opsional)</label>
    <select name="booking_id" class="border rounded px-3 py-2 w-full">
      <option value="">- Tanpa Booking -</option>
      @foreach($bookings as $b)
        <option value="{{ $b->booking_id }}" @selected(old('booking_id',$pemesanan->booking_id ?? '')==$b->booking_id)>#{{ $b->booking_id }} — {{ $b->hari_tanggal->timezone('Asia/Jakarta')->format('d M Y') }} {{ $b->jam }} ({{ ucfirst($b->status->value) }})</option>
      @endforeach
    </select>
    @error('booking_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium">Jadwal (opsional)</label>
    <select name="jadwal_id" class="border rounded px-3 py-2 w-full">
      <option value="">- Tidak terikat -</option>
      @foreach($jadwals as $j)
        <option value="{{ $j->jadwal_id }}" @selected(old('jadwal_id',$pemesanan->jadwal_id ?? '')==$j->jadwal_id)>#{{ $j->jadwal_id }} — {{ $j->tanggal_mulai->timezone('Asia/Jakarta')->format('d M Y H:i') }}</option>
      @endforeach
    </select>
    @error('jadwal_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div class="md:col-span-2">
    <label class="block text-sm font-medium">Tujuan Pemesanan</label>
    <input name="tujuan_pemesanan" class="border rounded px-3 py-2 w-full" value="{{ old('tujuan_pemesanan',$pemesanan->tujuan_pemesanan ?? '') }}" required>
    @error('tujuan_pemesanan')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div class="md:col-span-2">
    <label class="block text-sm font-medium">Catatan (opsional)</label>
    <textarea name="catatan" rows="3" class="border rounded px-3 py-2 w-full">{{ old('catatan',$pemesanan->catatan ?? '') }}</textarea>
  </div>
</div>
<div class="pt-4">
  <button class="px-3 py-2 rounded bg-blue-600 text-white">Simpan</button>
</div>
