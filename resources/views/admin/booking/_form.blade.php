@csrf
@php($booking = $booking ?? null)
<div class="grid md:grid-cols-2 gap-4">
  <div>
    <label class="block text-sm font-medium">User</label>
    <select name="user_id" class="border rounded px-3 py-2 w-full" required>
      <option value="">- Pilih User -</option>
      @foreach($users as $u)
        <option value="{{ $u->user_id }}" @selected(old('user_id', $booking->user_id ?? '')==$u->user_id)>{{ $u->username }}</option>
      @endforeach
    </select>
    @error('user_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium">Ruangan</label>
    <select name="ruangan_id" class="border rounded px-3 py-2 w-full" required>
      <option value="">- Pilih Ruangan -</option>
      @foreach($ruangans as $r)
        <option value="{{ $r->ruangan_id }}" @selected(old('ruangan_id', $booking->ruangan_id ?? '')==$r->ruangan_id)>{{ $r->nama_ruangan }}</option>
      @endforeach
    </select>
    @error('ruangan_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium">Hari & Tanggal</label>
    <input type="date" name="hari_tanggal" value="{{ old('hari_tanggal', optional($booking->hari_tanggal ?? null)->timezone('Asia/Jakarta')->format('Y-m-d')) }}" class="border rounded px-3 py-2 w-full" required>
    @error('hari_tanggal')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium">Jam</label>
    <input type="time" name="jam" value="{{ old('jam', $booking->jam ?? '') }}" class="border rounded px-3 py-2 w-full" required>
    @error('jam')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium">Status</label>
    <select name="status" class="border rounded px-3 py-2 w-full">
      @foreach(['hold','expired','submitted','cancelled'] as $s)
        <option value="{{ $s }}" @selected(old('status', $booking->status->value ?? 'hold')==$s)>{{ ucfirst($s) }}</option>
      @endforeach
    </select>
    @error('status')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium">Hold Expired At (opsional)</label>
    <input type="datetime-local" name="hold_expires_at" value="{{ old('hold_expires_at', optional($booking->hold_expires_at ?? null)->timezone('Asia/Jakarta')->format('Y-m-d\TH:i')) }}" class="border rounded px-3 py-2 w-full">
    @error('hold_expires_at')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
</div>
<div class="pt-4">
  <button class="px-3 py-2 rounded bg-blue-600 text-white">Simpan</button>
</div>

