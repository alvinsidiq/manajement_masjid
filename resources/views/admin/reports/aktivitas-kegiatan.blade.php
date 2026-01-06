@extends('layouts.admin', ['pageTitle'=>'Laporan Aktivitas Kegiatan'])
@section('content')
@include('admin.reports._filter', ['f'=>$f])
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead><tr class="border-b text-left">
        <th class="py-2 px-3">Tanggal</th>
        <th class="py-2 px-3">Jenis</th>
        <th class="py-2 px-3">Nama Kegiatan</th>
        <th class="py-2 px-3">Penanggung Jawab</th>
      </tr></thead>
      <tbody>
        @foreach($rows as $r)
        <tr class="border-b">
          <td class="py-2 px-3">{{ \Carbon\Carbon::parse($r->date)->format('d M Y') }}</td>
          <td class="py-2 px-3">{{ ucfirst($r->jenis_kegiatan) }}</td>
          <td class="py-2 px-3">{{ $r->nama_kegiatan }}</td>
          <td class="py-2 px-3">{{ $r->penanggung_jawab }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

