<form method="get" class="grid md:grid-cols-12 gap-3 mb-4">
  <input type="text" name="q" value="{{ $f['q'] ?? '' }}" placeholder="Cari ..." class="border rounded px-3 py-2 md:col-span-3">
  <select name="report" class="border rounded px-3 py-2 md:col-span-2">
    <option value="">- Jenis Laporan -</option>
    @foreach([
      'pemesanan' => 'Pemesanan',
      'penggunaan-ruangan' => 'Penggunaan Ruangan',
      'aktivitas-kegiatan' => 'Aktivitas Kegiatan',
    ] as $key => $label)
      <option value="{{ $key }}" @selected(($f['report'] ?? 'pemesanan')===$key)>{{ $label }}</option>
    @endforeach
  </select>
  <input type="number" name="ruangan_id" value="{{ $f['ruangan_id'] ?? '' }}" placeholder="Ruangan ID" class="border rounded px-3 py-2 md:col-span-2">
  <input type="number" name="user_id" value="{{ $f['user_id'] ?? '' }}" placeholder="User ID" class="border rounded px-3 py-2 md:col-span-2">
  <input type="date" name="date_from" value="{{ $f['date_from'] ?? '' }}" class="border rounded px-3 py-2 md:col-span-1">
  <input type="date" name="date_to" value="{{ $f['date_to'] ?? '' }}" class="border rounded px-3 py-2 md:col-span-1">
  <div class="flex gap-2 md:col-span-2">
    <a href="{{ route('admin.reports.index', array_merge($f,['format'=>'pdf'])) }}" class="px-3 py-2 rounded bg-red-600 text-white">PDF</a>
    <a href="{{ route('admin.reports.index', array_merge($f,['format'=>'excel'])) }}" class="px-3 py-2 rounded bg-green-600 text-white">Excel</a>
  </div>
</form>
