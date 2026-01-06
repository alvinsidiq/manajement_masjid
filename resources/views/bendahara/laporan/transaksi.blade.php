@extends('layouts.admin', ['pageTitle'=>'Laporan Transaksi'])
@section('content')
@if(session('status'))<div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>@endif
<div class="space-y-4">
  <div class="grid md:grid-cols-3 gap-4">
    <div class="bg-white p-4 rounded-xl shadow">
      <div class="text-sm text-gray-500">Total Transaksi</div>
      <div class="mt-2 text-2xl font-semibold">{{ number_format($summary['total'] ?? 0) }}</div>
    </div>
    <div class="bg-white p-4 rounded-xl shadow">
      <div class="text-sm text-gray-500">Total Dibayar</div>
      <div class="mt-2 text-2xl font-semibold">{{ number_format($summary['paid_count'] ?? 0) }}</div>
    </div>
    <div class="bg-white p-4 rounded-xl shadow">
      <div class="text-sm text-gray-500">Nominal Dibayar</div>
      <div class="mt-2 text-2xl font-semibold">{{ number_format($summary['paid_amount'] ?? 0,2,',','.') }} IDR</div>
    </div>
  </div>

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
      <div class="flex gap-2 md:col-span-2">
        <select name="sort" class="border rounded px-3 py-2">
          @foreach(['created_at'=>'Dibuat','paid_at'=>'Dibayar','amount'=>'Nominal'] as $k=>$v)
            <option value="{{ $k }}" @selected(($sort ?? 'created_at')===$k)>{{ $v }}</option>
          @endforeach
        </select>
        <select name="dir" class="border rounded px-3 py-2">
          <option value="asc" @selected(($dir ?? 'desc')==='asc')>Asc</option>
          <option value="desc" @selected(($dir ?? 'desc')==='desc')>Desc</option>
        </select>
      </div>
    </form>

    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead><tr class="text-left border-b">
          <th class="py-2 px-3">#</th>
          <th class="py-2 px-3">Pemesanan</th>
          <th class="py-2 px-3">Gateway</th>
          <th class="py-2 px-3">Nominal</th>
          <th class="py-2 px-3">Status</th>
          <th class="py-2 px-3">Dibuat</th>
          <th class="py-2 px-3">Dibayar</th>
        </tr></thead>
        <tbody>
          @forelse($items as $p)
          <tr class="border-b">
            <td class="py-2 px-3">{{ $p->payment_id }}</td>
            <td class="py-2 px-3">#{{ $p->pemesanan->pemesanan_id }} — {{ $p->pemesanan->tujuan_pemesanan }}</td>
            <td class="py-2 px-3">{{ ucfirst($p->gateway->value) }}</td>
            <td class="py-2 px-3">{{ number_format($p->amount,2,',','.') }} {{ $p->currency }}</td>
            <td class="py-2 px-3">{{ ucfirst($p->status->value) }}</td>
            <td class="py-2 px-3">{{ $p->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
            <td class="py-2 px-3">{{ optional($p->paid_at)->timezone('Asia/Jakarta')->format('d M Y H:i') ?? '-' }}</td>
          </tr>
          @empty
            <tr><td colspan="7" class="py-6 text-center text-gray-500">Belum ada data.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{ $items->links() }}
  </div>
</div>
@endsection

