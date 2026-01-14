 @extends('layouts.landing', ['pageTitle'=>'Status Booking & Pemesanan'])
 @section('content')
<div class="max-w-5xl mx-auto px-4 py-10 space-y-8">
  <!-- Header -->
  <div class="rounded-3xl bg-gradient-to-r from-indigo-600 via-sky-600 to-cyan-500 text-white p-8 shadow-xl">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm uppercase tracking-[0.3em] text-white/80">Timeline</p>
        <h1 class="mt-2 text-3xl font-semibold">Status Booking & Pemesanan</h1>
      </div>
    </div>
  </div>

  <!-- Filter -->
  <div class="bg-white rounded-2xl border p-4 shadow-sm">
    <form method="get" class="grid md:grid-cols-12 gap-3">
      <select name="type" class="border rounded px-3 py-2 md:col-span-2">
        <option value="all" @selected(($type ?? 'all')==='all')>Semua</option>
        <option value="booking" @selected(($type ?? '')==='booking')>Booking</option>
        <option value="pemesanan" @selected(($type ?? '')==='pemesanan')>Pemesanan</option>
      </select>
      <input type="text" name="q" value="{{ $q }}" class="border rounded px-3 py-2 md:col-span-5" placeholder="Cari ruangan/tujuan">
      <input type="date" name="date_from" value="{{ $date_from }}" class="border rounded px-3 py-2 md:col-span-2">
      <input type="date" name="date_to" value="{{ $date_to }}" class="border rounded px-3 py-2 md:col-span-2">
      <button class="px-4 py-2 rounded bg-indigo-600 text-white">Terapkan</button>
    </form>
  </div>

  <!-- List -->
  <div class="space-y-3">
    @forelse($items as $it)
      @php($st = strtolower($it['status']))
      @php($isBooking = $it['kind']==='booking')
      @php($kindColor = $isBooking ? 'bg-indigo-500' : 'bg-emerald-500')
      @php($statusClass = match(true){
        str_contains($st,'hold') => 'bg-amber-100 text-amber-700 border-amber-200',
        str_contains($st,'menunggu') || str_contains($st,'proses') => 'bg-sky-100 text-sky-700 border-sky-200',
        str_contains($st,'diterima') || str_contains($st,'setuju') => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        str_contains($st,'ditolak') || str_contains($st,'tolak') || str_contains($st,'dibatalkan') || str_contains($st,'cancel') => 'bg-rose-100 text-rose-700 border-rose-200',
        str_contains($st,'expired') => 'bg-gray-200 text-gray-700 border-gray-300',
        default => 'bg-gray-100 text-gray-700 border-gray-200',
      })
      <div class="relative overflow-hidden rounded-2xl border bg-white shadow-sm">
        <div class="absolute left-0 top-0 h-full w-1.5 {{ $kindColor }}"></div>
        <div class="p-4 pl-6 flex items-start gap-4">
          <span class="mt-0.5 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium text-white {{ $isBooking ? 'bg-indigo-600' : 'bg-emerald-600' }}">{{ ucfirst($it['kind']) }}</span>
          <div class="flex-1">
            <div class="flex items-center justify-between gap-2">
              <a href="{{ route('user.status.show', $it['kind'].'-'.$it['id']) }}" class="font-semibold text-gray-900 hover:text-indigo-700">{{ $it['title'] }}</a>
              <span class="text-xs px-2 py-0.5 rounded-full border {{ $statusClass }}">{{ Str::headline($it['status']) }}</span>
            </div>
            <div class="mt-1 text-xs text-gray-500">Waktu: {{ $it['when'] ?: '-' }}</div>
            @if($it['note'])<div class="mt-1 text-sm text-gray-700">{{ $it['note'] }}</div>@endif
            <div class="mt-2"><a href="{{ $it['link'] }}" class="inline-flex items-center gap-2 text-xs px-3 py-1.5 rounded-full border border-indigo-200 text-indigo-700 hover:bg-indigo-50">Buka Detail</a></div>
          </div>
        </div>
      </div>
    @empty
      <div class="p-6 text-center text-gray-500">Belum ada status.</div>
    @endforelse
  </div>

  <div>{{ $items->links() }}</div>
</div>
 @endsection
