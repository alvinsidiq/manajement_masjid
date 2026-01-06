@csrf
@php($notifikasi = $notifikasi ?? null)
<div class="grid md:grid-cols-2 gap-4">
  <div>
    <label class="block text-sm font-medium">Tujuan User</label>
    <select name="user_id" class="border rounded px-3 py-2 w-full" required>
      @foreach($users as $u)
        <option value="{{ $u->user_id }}" @selected(old('user_id', $notifikasi->user_id ?? '')==$u->user_id)>{{ $u->username }}</option>
      @endforeach
    </select>
    @error('user_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div>
    <label class="block text-sm font-medium">Jenis Referensi</label>
    <select name="jenis_referensi" class="border rounded px-3 py-2 w-full" required>
      @foreach(['umum','pemesanan','payment','kegiatan'] as $x)
        <option value="{{ $x }}" @selected(old('jenis_referensi', $notifikasi->jenis_referensi->value ?? 'umum')==$x)>{{ ucfirst($x) }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block text-sm font-medium">Referensi ID (opsional)</label>
    <input name="referensi_id" type="number" value="{{ old('referensi_id', $notifikasi->referensi_id ?? '') }}" class="border rounded px-3 py-2 w-full">
  </div>
  <div class="md:col-span-2">
    <label class="block text-sm font-medium">Pesan</label>
    <textarea name="pesan" rows="3" class="border rounded px-3 py-2 w-full" required>{{ old('pesan', $notifikasi->pesan ?? '') }}</textarea>
    @error('pesan')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>
</div>
<div class="pt-4">
  <button class="px-3 py-2 rounded bg-blue-600 text-white">Kirim</button>
</div>

