@extends('layouts.admin', ['pageTitle'=>'Detail Payment'])
@section('content')
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <div class="grid md:grid-cols-2 gap-4">
    <div>
      <div class="text-xl font-semibold">Payment #{{ $p->payment_id }}</div>
      <div class="text-sm text-gray-600">Pemesanan: #{{ $p->pemesanan->pemesanan_id }} — {{ $p->pemesanan->tujuan_pemesanan }}</div>
      <div class="mt-2"><span class="text-gray-500">Gateway:</span> {{ ucfirst($p->gateway->value) }}</div>
      <div><span class="text-gray-500">Metode:</span> {{ $p->method ?? '-' }}</div>
      <div><span class="text-gray-500">Jumlah:</span> {{ number_format($p->amount,2,',','.') }} {{ $p->currency }}</div>
      <div><span class="text-gray-500">Status:</span> {{ ucfirst($p->status->value) }}</div>
      <div><span class="text-gray-500">Dibayar:</span> {{ optional($p->paid_at)->timezone('Asia/Jakarta')->format('d M Y H:i') ?? '-' }}</div>
      @if($p->status_pembayaran)
      <div><span class="text-gray-500">Status Gateway:</span> {{ $p->status_pembayaran }}</div>
      @endif
      @if($p->xendit_transaction_id)
      <div><span class="text-gray-500">Xendit ID:</span> {{ $p->xendit_transaction_id }}</div>
      @endif
      @if($p->snap_url_or_qris)
      <div class="mt-2"><a class="px-3 py-2 rounded bg-indigo-600 text-white" href="{{ $p->snap_url_or_qris }}" target="_blank">Buka Link Pembayaran</a></div>
      @endif
    </div>
    <div>
      <div class="font-medium mb-1">Aksi</div>
      @if($p->status->value==='pending')
      <form method="post" action="{{ route('bendahara.payment.markPaid',$p) }}">@csrf
        <button class="px-3 py-2 rounded bg-green-600 text-white">Tandai Lunas</button>
      </form>
      <div class="text-xs text-gray-500 mt-2">Atau panggil callback simulasi:
        <code>POST {{ route('callback.payment',['gateway'=>$p->gateway->value,'externalRef'=>$p->external_ref]) }}?status=paid</code>
      </div>
      @endif
    </div>
  </div>
  <div class="pt-3"><a href="{{ route('bendahara.payment.index') }}" class="px-3 py-2 rounded bg-gray-200">Kembali</a></div>
</div>
@endsection
