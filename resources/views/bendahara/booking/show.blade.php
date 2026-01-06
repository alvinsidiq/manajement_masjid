@extends('layouts.admin', ['pageTitle'=>'Detail Booking'])
@section('content')
@php($payment = $booking->pemesanan?->payment)
@php($isPaid = ($payment?->status?->value === 'paid'))
<div class="bg-white p-4 rounded-xl shadow">
  <form class="grid md:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium mb-1">Nama</label>
      <input type="text" class="w-full border rounded px-3 py-2" value="{{ $booking->user->username }}" disabled>
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Ruangan</label>
      <input type="text" class="w-full border rounded px-3 py-2" value="{{ $booking->ruangan->nama_ruangan }}" disabled>
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Tanggal</label>
      <input type="text" class="w-full border rounded px-3 py-2" value="{{ $booking->hari_tanggal?->timezone('Asia/Jakarta')->format('d M Y') }} {{ $booking->jam }}" disabled>
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Status Pembayaran</label>
      <select class="w-full border rounded px-3 py-2 bg-gray-50" disabled>
        <option value="belum" @selected(!$isPaid)>Belum Bayar</option>
        <option value="lunas" @selected($isPaid)>Lunas</option>
      </select>
    </div>

    @if($payment)
      <div>
        <label class="block text-sm font-medium mb-1">Nominal</label>
        <input type="text" class="w-full border rounded px-3 py-2" value="{{ number_format($payment->amount,2,',','.') }} {{ $payment->currency }}" disabled>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Gateway</label>
        <input type="text" class="w-full border rounded px-3 py-2" value="{{ ucfirst($payment->gateway->value) }}" disabled>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Dibuat</label>
        <input type="text" class="w-full border rounded px-3 py-2" value="{{ $payment->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}" disabled>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Dibayar</label>
        <input type="text" class="w-full border rounded px-3 py-2" value="{{ $payment->paid_at?->timezone('Asia/Jakarta')->format('d M Y H:i') ?? '' }}" disabled>
      </div>
    @endif

    <div class="md:col-span-2 flex gap-2 pt-2">
      @if($payment)
        <a href="{{ route('bendahara.payment.show',$payment) }}" class="px-3 py-2 rounded bg-indigo-600 text-white">Lihat Transaksi</a>
      @endif
      <a href="{{ route('bendahara.booking.index') }}" class="px-3 py-2 rounded bg-gray-200">Kembali</a>
    </div>
  </form>
</div>
@endsection
