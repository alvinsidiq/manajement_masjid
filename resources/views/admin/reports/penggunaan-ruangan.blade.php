@extends('layouts.admin', ['pageTitle'=>'Laporan Penggunaan Ruangan'])
@section('content')
@include('admin.reports._filter', ['f'=>$f])
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead><tr class="border-b text-left">
        <th class="py-2 px-3">Tanggal</th>
        <th class="py-2 px-3">Ruangan</th>
        <th class="py-2 px-3">Jumlah Pemesanan</th>
      </tr></thead>
      <tbody>
        @foreach($rows as $r)
        <tr class="border-b">
          <td class="py-2 px-3">{{ \Carbon\Carbon::parse($r->date)->format('d M Y') }}</td>
          <td class="py-2 px-3">{{ $r->nama_ruangan }}</td>
          <td class="py-2 px-3">{{ $r->total }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

