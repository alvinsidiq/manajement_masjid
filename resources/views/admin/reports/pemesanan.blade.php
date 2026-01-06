@extends('layouts.admin', ['pageTitle'=>'Laporan Pemesanan'])
@section('content')
@include('admin.reports._filter', ['f'=>$f])
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead><tr class="border-b text-left">
        <th class="py-2 px-3">Tanggal</th>
        <th class="py-2 px-3">Pemesan</th>
        <th class="py-2 px-3">Email</th>
        <th class="py-2 px-3">Ruangan</th>
        <th class="py-2 px-3">Tujuan</th>
        <th class="py-2 px-3">Status</th>
      </tr></thead>
      <tbody>
        @foreach($rows as $p)
        <tr class="border-b">
          <td class="py-2 px-3">{{ $p->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
          <td class="py-2 px-3">{{ $p->user->username }}</td>
          <td class="py-2 px-3">{{ $p->user->email }}</td>
          <td class="py-2 px-3">{{ $p->ruangan->nama_ruangan }}</td>
          <td class="py-2 px-3">{{ $p->tujuan_pemesanan }}</td>
          <td class="py-2 px-3">{{ str($p->status->value)->replace('_',' ')->title() }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  {{ $rows->links() }}
</div>
@endsection

