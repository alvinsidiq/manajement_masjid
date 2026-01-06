@csrf
@php($jadwal = $jadwal ?? null)
<div class="grid md:grid-cols-2 gap-4">
  <div>
    <label class="block text-sm font-medium">Kegiatan</label>
    <select name="kegiatan_id" class="border rounded px-3 py-2 w-full" required>
      <option value="">- Pilih Kegiatan -</option>
      @foreach($kegiatans as $k)
        <option value="{{ $k->kegiatan_id }}" @selected(old('kegiatan_id', $jadwal->kegiatan_id ?? '')==$k->kegiatan_id)>{{ $k->nama_kegiatan }}</option>
      @endforeach
    </select>
    @error('kegiatan_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium">Ruangan (opsional)</label>
    <select name="ruangan_id" class="border rounded px-3 py-2 w-full">
      <option value="">- Tanpa Ruangan -</option>
      @foreach($ruangans as $r)
        <option value="{{ $r->ruangan_id }}" @selected(old('ruangan_id', $jadwal->ruangan_id ?? '')==$r->ruangan_id)>{{ $r->nama_ruangan }}</option>
      @endforeach
    </select>
    @error('ruangan_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium">Tanggal Mulai</label>
    <input type="datetime-local" name="tanggal_mulai" value="{{ old('tanggal_mulai', $jadwal?->tanggal_mulai?->timezone('Asia/Jakarta')->format('Y-m-d\TH:i') ?? '') }}" class="border rounded px-3 py-2 w-full" required>
    @error('tanggal_mulai')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium">Tanggal Selesai</label>
    <input type="datetime-local" name="tanggal_selesai" value="{{ old('tanggal_selesai', $jadwal?->tanggal_selesai?->timezone('Asia/Jakarta')->format('Y-m-d\TH:i') ?? '') }}" class="border rounded px-3 py-2 w-full" required>
    @error('tanggal_selesai')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium">Status</label>
    <select name="status" class="border rounded px-3 py-2 w-full" required>
      @foreach(['rutin','berkala','khusus'] as $s)
        <option value="{{ $s }}" @selected(old('status', $jadwal?->status?->value ?? 'khusus')==$s)>{{ ucfirst($s) }}</option>
      @endforeach
    </select>
    @error('status')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div class="md:col-span-2">
    <label class="block text-sm font-medium">Catatan</label>
    <textarea name="catatan" rows="3" class="border rounded px-3 py-2 w-full">{{ old('catatan', $jadwal->catatan ?? '') }}</textarea>
    @error('catatan')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
</div>
<div class="pt-4">
  <button class="px-3 py-2 rounded bg-blue-600 text-white">Simpan</button>
</div>
