@extends('layouts.admin', ['pageTitle'=>'Lihat Booking'])
@section('content')
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <form method="get" class="grid md:grid-cols-6 gap-3">
    <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama/ruangan" class="border rounded px-3 py-2 md:col-span-3">
  </form>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead>
        <tr class="text-left border-b">
          <th class="py-2 px-3">Nama</th>
          <th class="py-2 px-3">Ruangan</th>
          <th class="py-2 px-3">Tanggal</th>
          <th class="py-2 px-3">Status Pembayaran</th>
          <th class="py-2 px-3">Aksi</th>
        </tr>
      </thead>
      <tbody>
      @forelse($items as $b)
        <tr class="border-b">
          <td class="py-2 px-3">{{ $b->user->username }}</td>
          <td class="py-2 px-3">{{ $b->ruangan->nama_ruangan }}</td>
          <td class="py-2 px-3">{{ $b->hari_tanggal?->timezone('Asia/Jakarta')->format('d M Y') }} {{ $b->jam }}</td>
          <td class="py-2 px-3">
            @php($status = $b->pemesanan?->payment?->status->value ?? '-')
            <span class="px-2 py-1 rounded text-xs @class([
              'bg-gray-200 text-gray-800' => $status==='-',
              'bg-yellow-100 text-yellow-800' => $status==='pending',
              'bg-green-100 text-green-800' => $status==='paid',
              'bg-red-100 text-red-800' => in_array($status,['failed','expired','refunded']),
            ])">{{ $status==='-' ? '-' : ucfirst($status) }}</span>
          </td>
          <td class="py-2 px-3">
            <a href="{{ route('bendahara.booking.show',$b) }}" class="px-2 py-1 rounded bg-white border">Lihat</a>
          </td>
        </tr>
      @empty
        <tr><td colspan="5" class="py-6 text-center text-gray-500">Belum ada data.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>

  {{ $items->links() }}
</div>
@endsection

