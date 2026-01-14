@extends('layouts.admin', ['pageTitle'=>'Booking Hold'])
@section('content')
@if(session('status'))<div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>@endif
@php
  $statusOptions = [
    'hold' => 'Hold',
    'proses' => 'Proses',
    'setuju' => 'Setuju',
    'tolak' => 'Tolak',
    'expired' => 'Expired',
    'cancelled' => 'Dibatalkan',
  ];
  $statusClasses = [
    'hold' => 'bg-amber-100 text-amber-800',
    'proses' => 'bg-sky-100 text-sky-800',
    'setuju' => 'bg-emerald-100 text-emerald-800',
    'tolak' => 'bg-rose-100 text-rose-800',
    'expired' => 'bg-gray-200 text-gray-700',
    'cancelled' => 'bg-gray-200 text-gray-700',
  ];
@endphp
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <form method="get" class="grid md:grid-cols-12 gap-3">
    <input type="text" name="q" value="{{ $q }}" placeholder="Cari user/ruangan" class="border rounded px-3 py-2 md:col-span-3">
    <select name="status" class="border rounded px-3 py-2 md:col-span-2">
      <option value="">- Semua Status -</option>
      @foreach($statusOptions as $s => $label)
        <option value="{{ $s }}" @selected(($st ?? '')===$s)>{{ $label }}</option>
      @endforeach
    </select>
    <select name="ruangan_id" class="border rounded px-3 py-2 md:col-span-2">
      <option value="">- Ruangan -</option>
      @foreach($ruangans as $r)
        <option value="{{ $r->ruangan_id }}" @selected(($rid ?? '')==$r->ruangan_id)>{{ $r->nama_ruangan }}</option>
      @endforeach
    </select>
    <select name="user_id" class="border rounded px-3 py-2 md:col-span-2">
      <option value="">- Pemesan -</option>
      @foreach($users as $u)
        <option value="{{ $u->user_id }}" @selected(($uid ?? '')==$u->user_id)>{{ $u->username }}</option>
      @endforeach
    </select>
    <input type="date" name="date_from" value="{{ $df }}" class="border rounded px-3 py-2">
    <input type="date" name="date_to" value="{{ $dt }}" class="border rounded px-3 py-2">
  </form>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead><tr class="text-left border-b">
        <th class="py-2 px-3">#</th>
        <th class="py-2 px-3">Waktu</th>
        <th class="py-2 px-3">User</th>
        <th class="py-2 px-3">Ruangan</th>
        <th class="py-2 px-3">Status</th>
        <th class="py-2 px-3">Expire</th>
        <th class="py-2 px-3">Aksi</th>
      </tr></thead>
      <tbody>
        @forelse($items as $b)
        <tr class="border-b">
          <td class="py-2 px-3">{{ $b->booking_id }}</td>
          <td class="py-2 px-3">{{ $b->hari_tanggal->timezone('Asia/Jakarta')->format('d M Y') }} {{ $b->jam }}</td>
          <td class="py-2 px-3">{{ $b->user->username }}</td>
          <td class="py-2 px-3">{{ $b->ruangan->nama_ruangan }}</td>
          @php($statusValue = $b->status->value)
          <td class="py-2 px-3">
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $statusClasses[$statusValue] ?? 'bg-gray-100 text-gray-700' }}">
              {{ $statusOptions[$statusValue] ?? ucfirst($statusValue) }}
            </span>
          </td>
          <td class="py-2 px-3">{{ optional($b->hold_expires_at)->timezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
          <td class="py-2 px-3">
            <a href="{{ route('admin.booking.show',$b) }}" class="px-2 py-1 rounded border">Detail</a>
            <a href="{{ route('admin.booking.edit',$b) }}" class="px-2 py-1 rounded border">Ubah</a>
            <form method="post" action="{{ route('admin.booking.destroy',$b) }}" onsubmit="return confirm('Hapus booking?');" class="inline">
              @csrf @method('DELETE')
              <button class="px-2 py-1 rounded border bg-red-50">Hapus</button>
            </form>
          </td>
        </tr>
        @empty
          <tr><td colspan="7" class="py-6 text-center text-gray-500">Belum ada booking.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $items->links() }}
  <div class="pt-2"><a href="{{ route('admin.booking.create') }}" class="px-3 py-2 rounded bg-blue-600 text-white">Tambah Booking</a></div>
</div>
@endsection
