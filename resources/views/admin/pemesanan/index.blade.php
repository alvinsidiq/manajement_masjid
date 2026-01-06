@extends('layouts.admin', ['pageTitle'=>'Konfirmasi Pemesanan Ruangan'])
@section('content')
@if(session('status'))
  <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>
@endif
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <form method="get" class="grid md:grid-cols-12 gap-3">
    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari tujuan/username/ruangan" class="border rounded px-3 py-2 md:col-span-3">

    <select name="status" class="border rounded px-3 py-2 md:col-span-2">
      <option value="">- Semua Status -</option>
      @foreach(['menunggu_verifikasi','diterima','ditolak','dibatalkan','selesai'] as $s)
        <option value="{{ $s }}" @selected(($status ?? '')===$s)>{{ str($s)->replace('_',' ')->title() }}</option>
      @endforeach
    </select>

    <input type="number" name="ruangan_id" value="{{ $ruangan_id ?? '' }}" placeholder="Ruangan ID" class="border rounded px-3 py-2 md:col-span-2">
    <input type="number" name="user_id" value="{{ $user_id ?? '' }}" placeholder="User ID" class="border rounded px-3 py-2 md:col-span-2">

    <input type="date" name="date_from" value="{{ $date_from ?? '' }}" class="border rounded px-3 py-2 md:col-span-1" title="Dari tanggal">
    <input type="date" name="date_to" value="{{ $date_to ?? '' }}" class="border rounded px-3 py-2 md:col-span-1" title="Sampai tanggal">

    <div class="flex gap-2 md:col-span-2">
      <select name="sort" class="border rounded px-3 py-2">
        @foreach(['created_at'=>'Dibuat','status'=>'Status','ruangan_id'=>'Ruangan','user_id'=>'Pemesan'] as $k=>$v)
          <option value="{{ $k }}" @selected(($sort ?? 'created_at')===$k)>{{ $v }}</option>
        @endforeach
      </select>
      <select name="dir" class="border rounded px-3 py-2">
        <option value="asc" @selected(($dir ?? 'desc')==='asc')>Asc</option>
        <option value="desc" @selected(($dir ?? 'desc')==='desc')>Desc</option>
      </select>
    </div>
  </form>

  <div class="text-sm text-gray-600">Total: {{ $items->total() }}</div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead>
        <tr class="text-left border-b">
          <th class="py-2 px-3">#</th>
          <th class="py-2 px-3">Dibuat</th>
          <th class="py-2 px-3">Pemesan</th>
          <th class="py-2 px-3">Ruangan</th>
          <th class="py-2 px-3">Tujuan</th>
          <th class="py-2 px-3">Status</th>
          <th class="py-2 px-3">Aksi</th>
        </tr>
      </thead>
      <tbody>
      @forelse($items as $p)
        <tr class="border-b">
          <td class="py-2 px-3">{{ $p->pemesanan_id }}</td>
          <td class="py-2 px-3">{{ $p->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
          <td class="py-2 px-3">{{ $p->user->username }}<div class="text-xs text-gray-500">{{ $p->user->email }}</div></td>
          <td class="py-2 px-3">{{ $p->ruangan->nama_ruangan }}</td>
          <td class="py-2 px-3">{{ $p->tujuan_pemesanan }}</td>
          <td class="py-2 px-3">
            <span class="px-2 py-1 rounded text-xs @class([
              'bg-yellow-100 text-yellow-800' => $p->status->value==='menunggu_verifikasi',
              'bg-green-100 text-green-800' => $p->status->value==='diterima',
              'bg-red-100 text-red-800' => $p->status->value==='ditolak',
              'bg-gray-200 text-gray-800' => in_array($p->status->value,['dibatalkan','selesai']),
            ])">{{ str($p->status->value)->replace('_',' ')->title() }}</span>
          </td>
          <td class="py-2 px-3">
            <a href="{{ route('admin.pemesanan.show',$p) }}" class="px-2 py-1 rounded bg-white border">Detail</a>
          </td>
        </tr>
      @empty
        <tr><td colspan="7" class="py-6 text-center text-gray-500">Tidak ada data.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>

  {{ $items->links() }}
  
</div>
@endsection
