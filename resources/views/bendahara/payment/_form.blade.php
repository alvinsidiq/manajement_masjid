@csrf
@php($payment = $payment ?? null)
<div class="grid md:grid-cols-2 gap-4">
  <div>
    <label class="block text-sm font-medium">Pemesanan</label>
    <select name="pemesanan_id" class="border rounded px-3 py-2 w-full" required>
      @foreach($pemesanan as $x)
        <option value="{{ $x->pemesanan_id }}" @selected(old('pemesanan_id', $payment?->pemesanan_id ?? '')==$x->pemesanan_id)>#{{ $x->pemesanan_id }} — {{ $x->tujuan_pemesanan }}</option>
      @endforeach
    </select>
    @error('pemesanan_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>

  <div>
    <label class="block text-sm font-medium">Nama Pembayaran</label>
    <input name="method" class="border rounded px-3 py-2 w-full" value="{{ old('method', $payment?->method ?? '') }}" placeholder="Contoh: DP, Pelunasan, Tunai, Transfer">
  </div>

  <div>
    <label class="block text-sm font-medium">Jenis Pembayaran</label>
    <select name="gateway" class="border rounded px-3 py-2 w-full" required>
      @foreach(['manual','midtrans','xendit'] as $g)
        <option value="{{ $g }}" @selected(old('gateway', $payment?->gateway?->value ?? 'manual')==$g)>{{ ucfirst($g) }}</option>
      @endforeach
    </select>
    @error('gateway')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>

  <div>
    <label class="block text-sm font-medium">Nominal</label>
    <input type="number" step="0.01" name="amount" class="border rounded px-3 py-2 w-full" required value="{{ old('amount', $payment?->amount ?? '') }}" placeholder="0.00">
    @error('amount')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
  </div>

  <div>
    <label class="block text-sm font-medium">Tanggal</label>
    <input type="date" name="tanggal" class="border rounded px-3 py-2 w-full" value="{{ old('tanggal', $payment?->paid_at?->timezone('Asia/Jakarta')->format('Y-m-d') ?? '') }}">
  </div>

  <div class="md:col-span-2">
    <label class="block text-sm font-medium">Keterangan</label>
    <textarea name="keterangan" rows="3" class="border rounded px-3 py-2 w-full" placeholder="Tuliskan catatan tambahan jika diperlukan">{{ old('keterangan') }}</textarea>
  </div>

  <input type="hidden" name="currency" value="{{ old('currency', $payment?->currency ?? 'IDR') }}">
</div>
<div class="pt-4">
  <button class="px-3 py-2 rounded bg-blue-600 text-white">Simpan</button>
</div>
