<!doctype html>
<html><head><meta charset="utf-8"><style>table{width:100%;border-collapse:collapse}th,td{border:1px solid #ccc;padding:6px;font-size:12px}h2{margin:0 0 10px}</style></head>
<body>
<h2>Laporan Aktivitas Kegiatan</h2>
<table>
  <thead><tr><th>Tanggal</th><th>Jenis</th><th>Nama</th><th>PJ</th></tr></thead>
  <tbody>
  @foreach($rows as $r)
    <tr>
      <td>{{ \Carbon\Carbon::parse($r->date)->format('d/m/Y') }}</td>
      <td>{{ ucfirst($r->jenis_kegiatan) }}</td>
      <td>{{ $r->nama_kegiatan }}</td>
      <td>{{ $r->penanggung_jawab }}</td>
    </tr>
  @endforeach
  </tbody>
</table>
</body></html>

