<form method="get" class="grid md:grid-cols-12 gap-3 mb-4 items-end">
  <div class="md:col-span-3">
    <label class="block text-sm font-medium mb-1">Pencarian</label>
    <input type="text" name="q" value="{{ $f['q'] ?? '' }}" placeholder="Cari pemesan, ruangan, atau kegiatan" class="border rounded px-3 py-2 w-full">
  </div>
  <div class="md:col-span-2">
    <label class="block text-sm font-medium mb-1">Jenis Laporan</label>
    <select name="report" class="border rounded px-3 py-2 w-full">
      <option value="">- Jenis Laporan -</option>
      @foreach([
        'pemesanan' => 'Pemesanan',
        'penggunaan-ruangan' => 'Penggunaan Ruangan',
        'aktivitas-kegiatan' => 'Aktivitas Kegiatan',
      ] as $key => $label)
        <option value="{{ $key }}" @selected(($f['report'] ?? 'pemesanan')===$key)>{{ $label }}</option>
      @endforeach
    </select>
  </div>
  <div class="md:col-span-2">
    <label class="block text-sm font-medium mb-1">Tanggal Mulai</label>
    <input type="date" name="date_from" value="{{ $f['date_from'] ?? '' }}" class="border rounded px-3 py-2 w-full">
  </div>
  <div class="md:col-span-2">
    <label class="block text-sm font-medium mb-1">Tanggal Berakhir</label>
    <input type="date" name="date_to" value="{{ $f['date_to'] ?? '' }}" class="border rounded px-3 py-2 w-full">
  </div>
  <div class="flex flex-wrap gap-2 md:col-span-3">
    <button type="submit" class="px-3 py-2 rounded bg-slate-900 text-white">Cari</button>
    <a href="{{ route('admin.reports.index', array_merge($f,['format'=>'pdf'])) }}" class="px-3 py-2 rounded bg-red-600 text-white">PDF</a>
    <a href="{{ route('admin.reports.index', array_merge($f,['format'=>'excel'])) }}" class="px-3 py-2 rounded bg-green-600 text-white">Excel</a>
  </div>
</form>
