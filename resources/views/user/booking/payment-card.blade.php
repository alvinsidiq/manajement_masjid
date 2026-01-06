@php($paid = $pemesanan?->payment?->status?->value === 'paid')
@php($statusValue = optional($pemesanan->payment?->status)->value)
@php($statusLabel = $statusValue === 'paid' ? 'Lunas' : ($statusValue === 'pending' ? 'Menunggu pembayaran' : ($statusValue ?? 'Belum dibuat')))
<div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm space-y-3">
  <div class="flex items-center justify-between">
    <h3 class="text-sm font-semibold text-gray-700">Pembayaran</h3>
    @if($paid)
      <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">Lunas</span>
    @endif
  </div>
  <div class="text-sm text-gray-700">
    <div><span class="text-gray-500">Jumlah:</span> <span class="font-semibold">Rp {{ number_format($pemesanan->ruangan->harga ?? 0,0,',','.') }}</span></div>
    <div><span class="text-gray-500">Status:</span> <span class="font-semibold">{{ $statusLabel }}</span></div>
  </div>

  @if(!$paid)
    <div class="space-y-2">
      <form method="post" action="{{ route('user.pemesanan.pay', $pemesanan) }}">
        @csrf
        <input type="hidden" name="gateway" value="xendit">
        <input type="hidden" name="method" value="wallet_bank">
        <button class="w-full flex items-center justify-center gap-2 rounded-full bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Bayar via Wallet/Bank (Xendit)</button>
      </form>
      <form method="post" action="{{ route('user.pemesanan.pay', $pemesanan) }}">
        @csrf
        <input type="hidden" name="gateway" value="manual">
        <button class="w-full flex items-center justify-center gap-2 rounded-full border border-amber-300 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-800 hover:bg-amber-100">Bayar Tunai (konfirmasi admin)</button>
      </form>
    </div>
  @endif
</div>
