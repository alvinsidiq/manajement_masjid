<!doctype html>
<html><head><meta charset="utf-8"><style>table{width:100%;border-collapse:collapse}th,td{border:1px solid #ccc;padding:6px;font-size:12px}h2{margin:0 0 10px}</style></head>
<body>
<h2>Laporan Penggunaan Ruangan</h2>
<table>
  <thead><tr><th>Tanggal</th><th>Ruangan</th><th>Jumlah Pemesanan</th></tr></thead>
  <tbody>
  @foreach($rows as $r)
    <tr>
      <td>{{ \Carbon\Carbon::parse($r->date)->format('d/m/Y') }}</td>
      <td>{{ $r->nama_ruangan }}</td>
      <td>{{ $r->total }}</td>
    </tr>
  @endforeach
  </tbody>
</table>
</body></html>

