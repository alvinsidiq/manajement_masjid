@extends('layouts.admin', ['pageTitle'=>'Pembayaran'])
@section('content')
@if(session('status'))<div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>@endif
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <form method="get" class="grid md:grid-cols-12 gap-3">
    <input type="text" name="q" value="{{ $q }}" placeholder="Cari tujuan pemesanan" class="border rounded px-3 py-2 md:col-span-3">
    <select name="gateway" class="border rounded px-3 py-2 md:col-span-2">
      <option value="">- Semua Gateway -</option>
      @foreach(['manual','midtrans','xendit'] as $g)
        <option value="{{ $g }}" @selected(($gw ?? '')==$g)>{{ ucfirst($g) }}</option>
      @endforeach
    </select>
    <select name="status" class="border rounded px-3 py-2 md:col-span-2">
      <option value="">- Semua Status -</option>
      @foreach(['pending','paid','failed','expired','refunded'] as $s)
        <option value="{{ $s }}" @selected(($st ?? '')==$s)>{{ ucfirst($s) }}</option>
      @endforeach
    </select>
    <input type="date" name="date_from" value="{{ $df }}" class="border rounded px-3 py-2">
    <input type="date" name="date_to" value="{{ $dt }}" class="border rounded px-3 py-2">
  </form>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead><tr class="text-left border-b">
        <th class="py-2 px-3">#</th>
        <th class="py-2 px-3">Pemesanan</th>
        <th class="py-2 px-3">Gateway</th>
        <th class="py-2 px-3">Jumlah</th>
        <th class="py-2 px-3">Status</th>
        <th class="py-2 px-3">Aksi</th>
      </tr></thead>
      <tbody>
        @forelse($items as $p)
        <tr class="border-b">
          <td class="py-2 px-3">{{ $p->payment_id }}</td>
          <td class="py-2 px-3">#{{ $p->pemesanan->pemesanan_id }} — {{ $p->pemesanan->tujuan_pemesanan }}</td>
          <td class="py-2 px-3">{{ ucfirst($p->gateway->value) }}</td>
          <td class="py-2 px-3">{{ number_format($p->amount,2,',','.') }} {{ $p->currency }}</td>
          <td class="py-2 px-3">{{ ucfirst($p->status->value) }}</td>
          <td class="py-2 px-3 flex gap-2">
            <a href="{{ route('bendahara.payment.show',$p) }}" class="px-2 py-1 rounded border">Detail</a>
            @if($p->status->value==='pending')
              <form method="post" action="{{ route('bendahara.payment.markPaid',$p) }}" class="inline">@csrf
                <button class="px-2 py-1 rounded border bg-green-50">Tandai Lunas</button>
              </form>
            @endif
          </td>
        </tr>
        @empty
          <tr><td colspan="6" class="py-6 text-center text-gray-500">Belum ada payment.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $items->links() }}
  <div class="pt-2"><a href="{{ route('bendahara.payment.create') }}" class="px-3 py-2 rounded bg-blue-600 text-white">Tambah Payment</a></div>
</div>
@endsection

