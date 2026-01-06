<!doctype html>
<html><head><meta charset="utf-8"><style>table{width:100%;border-collapse:collapse}th,td{border:1px solid #ccc;padding:6px;font-size:12px}h2{margin:0 0 10px}</style></head>
<body>
<h2>Laporan Pemesanan</h2>
<table>
  <thead><tr><th>Tanggal</th><th>Pemesan</th><th>Email</th><th>Ruangan</th><th>Tujuan</th><th>Status</th></tr></thead>
  <tbody>
  @foreach($rows as $p)
    <tr>
      <td>{{ $p->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}</td>
      <td>{{ $p->user->username }}</td>
      <td>{{ $p->user->email }}</td>
      <td>{{ $p->ruangan->nama_ruangan }}</td>
      <td>{{ $p->tujuan_pemesanan }}</td>
      <td>{{ str($p->status->value)->replace('_',' ')->title() }}</td>
    </tr>
  @endforeach
  </tbody>
</table>
</body></html>

