@extends('layouts.landing', ['title'=>'Informasi'])
@section('content')
<div class="max-w-6xl mx-auto px-4 py-10 space-y-8">
  <!-- Jumbotron / Hero -->
  <section class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 to-blue-600 text-white">
    <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(ellipse at top left, rgba(255,255,255,0.4), transparent 55%), radial-gradient(circle at bottom right, rgba(255,255,255,0.25), transparent 55%);"></div>
    <div class="relative px-6 py-10 md:px-10 md:py-14">
      <h1 class="text-2xl md:text-3xl font-bold">Informasi Masjid</h1>
      <p class="mt-2 text-white/90 max-w-2xl">Temukan informasi umum, kontak, serta perkembangan kegiatan di lingkungan masjid kami.</p>
      <div class="mt-6 grid md:grid-cols-3 gap-4">
        <div class="bg-white/15 backdrop-blur rounded-xl p-4">
          <div class="text-sm text-white/80">Alamat</div>
          <div class="font-semibold">Jl. Persaudaraan No. 12, Kecamatan Sukamaju, Kota Example</div>
        </div>
        <div class="bg-white/15 backdrop-blur rounded-xl p-4">
          <div class="text-sm text-white/80">Kontak</div>
          <div class="font-semibold">Telp/WA: 0812-3456-7890 · Email: info@masjid.local</div>
        </div>
        <div class="bg-white/15 backdrop-blur rounded-xl p-4">
          <div class="text-sm text-white/80">Jam Layanan</div>
          <div class="font-semibold">Setiap hari 08.00–20.00 WIB</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Detail Informasi & Sejarah Singkat -->
  <section class="grid md:grid-cols-3 gap-6">
    <div class="md:col-span-2 bg-white rounded-xl border p-5 space-y-3">
      <h2 class="text-lg font-semibold">Detail Informasi Masjid</h2>
      <p class="text-gray-700">Masjid kami berkomitmen menjadi pusat ibadah, pendidikan, dan sosial bagi jamaah serta masyarakat sekitar. Pelayanan meliputi kegiatan harian, kajian rutin, pembinaan remaja, serta program sosial kemasyarakatan.</p>
      <ul class="list-disc pl-5 text-gray-700 space-y-1">
        <li>Kajian rutin pekanan dan bulanan (fiqih, akidah, keluarga).</li>
        <li>Fasilitas: aula serbaguna, perpustakaan kecil, ruang musala wanita, dan tempat wudu bersih.</li>
        <li>Layanan konsultasi keagamaan dan bimbingan ibadah.</li>
      </ul>
      <div class="grid md:grid-cols-2 gap-4 mt-2">
        <div class="rounded-lg bg-gray-50 p-4 border">
          <div class="text-sm text-gray-500">Pengurus</div>
          <div class="font-medium">Takmir, Bendahara, dan tim relawan</div>
        </div>
        <div class="rounded-lg bg-gray-50 p-4 border">
          <div class="text-sm text-gray-500">Media</div>
          <div class="font-medium">Instagram: @masjid.example · FB: Masjid Example</div>
        </div>
      </div>
    </div>
    <div class="bg-white rounded-xl border p-5 space-y-3">
      <h2 class="text-lg font-semibold">Sejarah Singkat</h2>
      <p class="text-gray-700">Didirikan pada tahun 1985, masjid ini awalnya merupakan surau kecil yang dibangun gotong royong. Seiring bertambahnya jamaah, masjid direnovasi pada tahun 2005 dan 2020, melengkapi fasilitas serta memperluas daya tampung.</p>
      <p class="text-gray-700">Kini, masjid berperan aktif dalam kegiatan dakwah, pendidikan, dan sosial, menjadi rumah spiritual bagi beragam lapisan masyarakat.</p>
    </div>
  </section>
  <form method="get" class="grid md:grid-cols-12 gap-3 bg-white p-4 rounded-xl border">
    <input type="text" name="q" value="{{ $q }}" placeholder="Cari judul/konten" class="border rounded px-3 py-2 md:col-span-6">
    <select name="month" class="border rounded px-3 py-2 md:col-span-3">
      <option value="">Bulan</option>
      @for($m=1;$m<=12;$m++)
        <option value="{{ $m }}" @selected(($month ?? null)==$m)>{{ \Carbon\Carbon::create(null, $m, 1)->locale('id')->translatedFormat('F') }}</option>
      @endfor
    </select>
    <select name="year" class="border rounded px-3 py-2 md:col-span-3">
      <option value="">Tahun</option>
      @for($y=date('Y');$y>=date('Y')-5;$y--)
        <option value="{{ $y }}" @selected(($year ?? null)==$y)>{{ $y }}</option>
      @endfor
    </select>
  </form>

  <div class="grid md:grid-cols-3 gap-4">
    @forelse($items as $i)
      <a href="{{ route('public.informasi.show',$i->slug) }}" class="rounded-xl border overflow-hidden bg-white block">
        <div class="p-4">
          <div class="text-sm text-gray-500">{{ $i->published_at?->timezone('Asia/Jakarta')->format('d M Y') }}</div>
          <div class="font-semibold">{{ $i->judul }}</div>
          <p class="text-sm text-gray-600">{{ $i->ringkasan ?? str($i->isi)->limit(120) }}</p>
        </div>
      </a>
    @empty
      <div class="text-gray-500">Tidak ada hasil.</div>
    @endforelse
  </div>

  {{ $items->links() }}
</div>
@endsection
