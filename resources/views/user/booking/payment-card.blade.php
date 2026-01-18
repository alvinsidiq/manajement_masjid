@php($payment = $pemesanan?->payment)
@php($statusValue = optional($payment?->status)->value)
@php($gatewayValue = optional($payment?->gateway)->value)
@php($statusPembayaran = $pemesanan?->status_pembayaran ?? $payment?->status_pembayaran)
@php($statusPembayaranNorm = $statusPembayaran ? strtoupper($statusPembayaran) : null)
@php($paid = $statusValue === 'paid' || in_array($statusPembayaranNorm, ['PAID', 'SETTLED'], true))
@php($isPending = $statusValue === 'pending' || $statusPembayaranNorm === 'PENDING')
@php($isManualPending = $isPending && $gatewayValue === 'manual')
@php($isAwaitingVerification = !$paid && $isPending && ($payment || $statusPembayaranNorm))
@php($statusLabel = $paid ? 'Lunas' : ($isAwaitingVerification ? 'Menunggu verifikasi bendahara' : ($isPending ? 'Menunggu pembayaran' : ($statusValue ?? 'Belum dibuat'))))
@php($bookingDate = optional($pemesanan?->booking?->hari_tanggal)?->timezone('Asia/Jakarta'))
@php($bookingTime = $pemesanan?->booking?->jam)
<div class="rounded-xl border border-gray-100 bg-white p-4 shadow space-y-3">
  <div class="flex items-center justify-between">
    <h3 class="text-sm font-semibold text-gray-700">Pembayaran</h3>
    @if($paid)
      <span class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">Lunas</span>
    @endif
  </div>
  <div class="text-sm text-gray-700">
    <div><span class="text-gray-500">Jumlah:</span> <span class="font-semibold">Rp {{ number_format($pemesanan->ruangan->harga ?? 0, 0, ',', '.') }}</span></div>
    <div><span class="text-gray-500">Status:</span> <span class="font-semibold">{{ $statusLabel }}</span></div>
  </div>

  @if($paid)
    <div class="rounded border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700">
      Pembayaran sukses. Ruangan dapat digunakan pada
      <span class="font-semibold">
        {{ $bookingDate ? $bookingDate->format('d M Y') : 'jadwal yang sudah ditentukan' }}
        @if($bookingDate && $bookingTime) pukul {{ Str::of($bookingTime)->substr(0,5) }} WIB @endif
      </span>.
    </div>
  @elseif($isAwaitingVerification)
    <div class="rounded border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-700">
      Pembayaran Anda sudah dicatat. Menunggu verifikasi bendahara.
    </div>
  @elseif(!$paid)
    <div class="space-y-2">
      <form method="post" action="{{ route('user.pemesanan.pay', $pemesanan) }}">
        @csrf
        <input type="hidden" name="gateway" value="xendit">
        <input type="hidden" name="method" value="wallet_bank">
        <button class="w-full flex items-center justify-center gap-2 rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Bayar via Wallet/Bank (Xendit)</button>
      </form>
      <form method="post" action="{{ route('user.pemesanan.pay', $pemesanan) }}">
        @csrf
        <input type="hidden" name="gateway" value="manual">
        <button class="w-full flex items-center justify-center gap-2 rounded border border-amber-300 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-800 hover:bg-amber-100">Bayar Tunai (konfirmasi admin)</button>
      </form>
    </div>
  @endif
</div>
