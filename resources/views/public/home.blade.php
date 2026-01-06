@extends('layouts.landing', ['title'=>'Beranda'])
@section('content')
@php
    $heroSchedule = $jadwal->first();
    $heroInfo = $infos->first();
    $statLabels = [
        'jadwal' => 'Agenda terjadwal',
        'informasi' => 'Informasi dipublikasi',
        'kegiatan' => 'Kegiatan aktif',
        'ruangan' => 'Ruangan tersedia',
    ];
    $featureCards = [
        [
            'title' => 'Jadwal',
            'body' => 'Pantau jadwal harian masjid dan pastikan setiap kegiatan terekam rapi.',
            'link' => route('public.jadwal.index'),
            'count' => $stats['jadwal'] ?? 0,
        ],
        [
            'title' => 'Informasi',
            'body' => 'Sampaikan pengumuman, agenda penting, atau kabar terbaru kepada jamaah.',
            'link' => route('public.informasi.index'),
            'count' => $stats['informasi'] ?? 0,
        ],
        [
            'title' => 'Kegiatan',
            'body' => 'Kelola kegiatan rutin hingga acara khusus dengan satu pusat data.',
            'link' => route('public.kegiatan.index'),
            'count' => $stats['kegiatan'] ?? 0,
        ],
        [
            'title' => 'Daftar Ruangan',
            'body' => 'Booking ruangan favorit jamaah secara transparan dan cepat.',
            'link' => route('public.ruangan.index'),
            'count' => $stats['ruangan'] ?? 0,
        ],
    ];
    $jenisLabels = [
        'rutin' => 'Kegiatan Rutin',
        'berkala' => 'Kegiatan Berkala',
        'khusus' => 'Kegiatan Khusus',
    ];
    $featuredKegiatan = $kegiatan->take(3);
    $ruanganPopuler = $ruangan->take(3);
@endphp

<section class="relative overflow-hidden bg-gradient-to-br from-emerald-50 via-white to-white">
  <div class="absolute inset-0">
    <img src="https://images.unsplash.com/photo-1503696967350-ad187412205d?auto=format&fit=crop&w=1500&q=60" alt="Masjid background" class="w-full h-full object-cover opacity-40">
    <div class="absolute inset-0 bg-gradient-to-br from-white/90 via-white/80 to-emerald-50/70"></div>
    <div class="absolute w-64 h-64 bg-emerald-200/40 rounded-full blur-3xl -right-10 top-6"></div>
    <div class="absolute w-56 h-56 bg-sky-200/40 rounded-full blur-3xl -left-6 bottom-0"></div>
  </div>
  <div class="relative max-w-6xl mx-auto px-4 py-16 grid gap-12 lg:grid-cols-[1.05fr_0.95fr] items-center">
    <div class="space-y-6">
      <p class="inline-flex items-center rounded-full bg-white/80 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.3em] text-emerald-700 shadow">Selalu Terhubung</p>
      <h1 class="text-3xl md:text-5xl font-semibold leading-tight text-slate-900">Halaman <span class="text-emerald-600">home</span> yang merangkum jadwal, informasi, dan layanan Masjid.</h1>
      <p class="text-base md:text-lg text-slate-600">Setiap aktivitas penting ditata dengan paragraf nyaman dibaca, font modern, serta grid yang terasa halus. Nikmati pengalaman menjelajah yang rapi bagi jamaah dan pengurus.</p>
      <div class="flex flex-wrap gap-4">
        <a href="{{ route('public.kegiatan.index') }}" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-6 py-3 text-white text-sm font-semibold shadow-lg shadow-emerald-600/30 hover:bg-emerald-700 transition">
          Jelajahi Kegiatan
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/></svg>
        </a>
        <a href="{{ route('public.ruangan.index') }}" class="inline-flex items-center gap-2 rounded-full border border-emerald-200 px-6 py-3 text-sm font-semibold text-emerald-700 hover:bg-white transition">
          Lihat Ruangan
        </a>
      </div>
      <div class="grid grid-cols-2 gap-4 text-sm text-slate-500">
        <div class="rounded-2xl bg-white/80 p-4 shadow-sm backdrop-blur">
          <p class="text-xs uppercase tracking-widest text-emerald-600">Highlight Jadwal</p>
          <p class="mt-1 font-semibold text-slate-900">{{ $heroSchedule?->kegiatan->nama_kegiatan ?? 'Belum ada jadwal' }}</p>
          <p class="mt-1">{{ $heroSchedule ? $heroSchedule->tanggal_mulai->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i') : 'Jadwal terbaru akan muncul di sini.' }}</p>
        </div>
        <div class="rounded-2xl bg-white/80 p-4 shadow-sm backdrop-blur">
          <p class="text-xs uppercase tracking-widest text-emerald-600">Informasi Terbaru</p>
          <p class="mt-1 font-semibold text-slate-900">{{ $heroInfo->judul ?? 'Belum ada informasi' }}</p>
          <p class="mt-1 line-clamp-3">{{ $heroInfo?->ringkasan ?? str(optional($heroInfo)->isi)->limit(90) }}</p>
        </div>
      </div>
    </div>
    <div class="relative">
      <div class="absolute inset-0 translate-x-8 translate-y-6 rounded-3xl bg-gradient-to-br from-emerald-100 via-white to-white"></div>
      <div class="relative rounded-[32px] border border-white/60 bg-white p-6 shadow-2xl space-y-5">
        <div class="rounded-3xl bg-emerald-600/90 text-white p-6 shadow-lg">
          <p class="text-xs uppercase tracking-widest text-white/70">Pengelolaan Masjid</p>
          <p class="mt-2 text-2xl font-semibold">Data realtime siap dibagikan.</p>
          <p class="mt-3 text-sm text-white/80">Grid dan paragraf yang lembut memastikan setiap informasi tetap fokus dan mudah diikuti.</p>
        </div>
        <div class="rounded-2xl border border-slate-100 p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs uppercase tracking-widest text-slate-500">Ruangan tersedia</p>
              <p class="text-3xl font-semibold text-slate-900">{{ number_format($stats['ruangan'] ?? 0) }}</p>
            </div>
            <a href="{{ route('public.ruangan.index') }}" class="inline-flex items-center gap-2 rounded-full bg-slate-900/90 px-4 py-2 text-xs font-semibold text-white">Daftar</a>
          </div>
          <p class="mt-2 text-sm text-slate-500">Pengaturan grid 3 kolom menghadirkan tampilan smooth pada daftar ruangan.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="max-w-6xl mx-auto px-4 -mt-12 relative z-10">
  <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @foreach($statLabels as $key => $label)
      <div class="rounded-2xl bg-white shadow-sm border border-emerald-50/70 p-5 flex flex-col">
        <span class="text-sm text-slate-500">{{ $label }}</span>
        <span class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($stats[$key] ?? 0) }}</span>
        <span class="mt-auto text-xs uppercase tracking-widest text-emerald-600">Selalu aktif</span>
      </div>
    @endforeach
  </div>
</section>

<section class="max-w-6xl mx-auto px-4 py-12">
  <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">
    @foreach($featureCards as $feature)
      <article class="rounded-3xl bg-white border border-slate-100 p-6 flex flex-col shadow-sm hover:-translate-y-1 transition">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-semibold text-slate-900">{{ $feature['title'] }}</h3>
          <span class="text-xs px-3 py-1 rounded-full bg-emerald-50 text-emerald-700">{{ number_format($feature['count']) }}</span>
        </div>
        <p class="mt-3 text-sm text-slate-600 flex-1">{{ $feature['body'] }}</p>
        <a href="{{ $feature['link'] }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 hover:text-emerald-700">Buka halaman
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/></svg>
        </a>
      </article>
    @endforeach
  </div>
</section>

<section class="max-w-6xl mx-auto px-4 py-12 grid gap-10 lg:grid-cols-2 items-center">
  <div class="space-y-5">
    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-600">Tentang masjid</p>
    <h2 class="text-3xl font-semibold text-slate-900 leading-tight">Pelayanan jamaah lebih tertata dengan paragraf yang menenangkan mata.</h2>
    <p class="text-base text-slate-600"> {{ config('app.name') }} menjadi ruang kolaborasi antara takmir, bendahara, dan jamaah. Grid layout yang smooth membuat daftar informasi, jadwal, dan ruangan mudah ditelusuri tanpa terasa padat.</p>
    <div class="grid gap-4 sm:grid-cols-2">
      <div class="rounded-2xl border border-slate-100 p-4">
        <p class="text-sm font-semibold text-slate-900">Kolaborasi cepat</p>
        <p class="text-sm text-slate-500 mt-1">Setiap pembaruan otomatis tampil di halaman depan.</p>
      </div>
      <div class="rounded-2xl border border-slate-100 p-4">
        <p class="text-sm font-semibold text-slate-900">Responsif & halus</p>
        <p class="text-sm text-slate-500 mt-1">Grid tiga kolom untuk kegiatan dan ruangan memberikan ritme visual nyaman.</p>
      </div>
    </div>
    <div class="flex gap-3">
      <a href="{{ route('public.informasi.index') }}" class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-6 py-3 text-white text-sm font-semibold">Pelajari Informasi</a>
      <a href="{{ route('public.jadwal.index') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-800">Lihat Jadwal</a>
    </div>
  </div>
  <div class="relative">
    <div class="absolute inset-0 -z-10 rounded-[30px] bg-gradient-to-br from-white via-emerald-50 to-white blur-lg"></div>
    <div class="rounded-[30px] border border-white/70 bg-white p-6 shadow-xl space-y-5">
      <div class="flex items-center gap-4">
        <div class="h-12 w-12 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-semibold">JM</div>
        <div>
          <p class="font-semibold text-slate-900">Jamaah Masjid</p>
          <p class="text-sm text-slate-500">Testimoni singkat</p>
        </div>
      </div>
      <p class="text-slate-600 leading-relaxed">“Halaman home terasa ringan namun informatif. Paragrafnya tertata rapi, font lebih modern, dan gridnya halus untuk memantau kegiatan mingguan.”</p>
    </div>
  </div>
</section>

<section class="max-w-6xl mx-auto px-4 py-12">
  <div class="flex items-center justify-between mb-6">
    <div>
      <p class="text-xs uppercase tracking-[0.3em] text-emerald-600">Agenda & informasi</p>
      <h2 class="text-3xl font-semibold text-slate-900">Aktivitas terbaru untuk jamaah.</h2>
    </div>
    <div class="flex gap-3 text-sm font-semibold">
      <a href="{{ route('public.jadwal.index') }}" class="text-emerald-600 hover:text-emerald-700">Semua jadwal</a>
      <a href="{{ route('public.informasi.index') }}" class="text-emerald-600 hover:text-emerald-700">Semua informasi</a>
    </div>
  </div>
  <div class="grid gap-8 lg:grid-cols-3">
    <div class="lg:col-span-2 rounded-3xl bg-white border border-slate-100 shadow-sm p-6">
      <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.3em] text-emerald-600">
        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Jadwal Mendatang
      </div>
      <ul class="mt-6 space-y-5">
        @forelse($jadwal as $j)
          <li class="flex gap-4">
            <div class="flex flex-col items-center text-sm text-slate-500">
              <span class="text-xl font-semibold text-slate-900">{{ $j->tanggal_mulai->timezone('Asia/Jakarta')->format('d') }}</span>
              <span>{{ $j->tanggal_mulai->timezone('Asia/Jakarta')->format('M') }}</span>
              <span class="mt-1 px-3 py-0.5 rounded-full bg-emerald-50 text-emerald-700">{{ $j->tanggal_mulai->timezone('Asia/Jakarta')->format('H:i') }}</span>
            </div>
            <div class="flex-1 rounded-2xl border border-slate-100 p-4 bg-slate-50">
              <h3 class="text-lg font-semibold text-slate-900">{{ $j->kegiatan->nama_kegiatan ?? 'Kegiatan Masjid' }}</h3>
              <p class="text-sm text-slate-600 mt-1">{{ $j->kegiatan->deskripsi ? str($j->kegiatan->deskripsi)->limit(120) : 'Detail kegiatan akan diinformasikan.' }}</p>
            </div>
          </li>
        @empty
          <li class="text-slate-500">Belum ada jadwal terkonfirmasi.</li>
        @endforelse
      </ul>
    </div>
    <div class="space-y-6">
      <div class="rounded-3xl bg-white border border-slate-100 shadow-sm p-6">
        <div class="flex items-center justify-between">
          <h3 class="font-semibold text-slate-900">Informasi Terbaru</h3>
          <a href="{{ route('public.informasi.index') }}" class="text-xs font-semibold text-emerald-600">Selengkapnya</a>
        </div>
        <div class="mt-4 space-y-4">
          @forelse($infos as $i)
            <a href="{{ route('public.informasi.show',$i->slug) }}" class="block rounded-2xl border border-slate-100 p-4 hover:border-emerald-200 hover:bg-emerald-50/40 transition">
              <div class="text-xs text-slate-500">{{ $i->published_at?->timezone('Asia/Jakarta')->translatedFormat('d M Y') }}</div>
              <div class="font-semibold text-slate-900">{{ $i->judul }}</div>
              <p class="text-sm text-slate-600 mt-1">{{ $i->ringkasan ?? str($i->isi)->limit(80) }}</p>
            </a>
          @empty
            <p class="text-sm text-slate-500">Belum ada informasi terbaru.</p>
          @endforelse
        </div>
      </div>
      <div class="rounded-3xl bg-slate-900 text-white p-6 shadow">
        <h3 class="font-semibold text-white/80 text-xs uppercase tracking-[0.4em]">Butuh bantuan?</h3>
        <p class="mt-3 text-lg font-semibold">Takmir siap membantu.</p>
        <p class="text-sm text-white/80 mt-2">Gunakan form booking ruangan atau hubungi kami jika membutuhkan dukungan acara.</p>
        <a href="{{ route('public.ruangan.index') }}" class="mt-4 inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm font-semibold hover:bg-white/20 transition">
          Hubungi sekarang
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/></svg>
        </a>
      </div>
    </div>
  </div>
</section>

<section class="bg-slate-50 py-12">
  <div class="max-w-6xl mx-auto px-4">
    <div class="flex items-center justify-between mb-6">
      <div>
        <p class="text-xs uppercase tracking-[0.3em] text-emerald-600">Sorotan kegiatan</p>
        <h2 class="text-3xl font-semibold text-slate-900">Aktivitas unggulan minggu ini.</h2>
      </div>
      <a href="{{ route('public.kegiatan.index') }}" class="text-sm font-semibold text-emerald-600">Semua kegiatan</a>
    </div>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      @forelse($featuredKegiatan as $k)
        <article class="rounded-3xl bg-white border border-slate-100 p-6 shadow-sm flex flex-col">
          <div class="flex items-center justify-between text-xs uppercase tracking-[0.4em] text-slate-500">
            <span>{{ $jenisLabels[$k->jenis_kegiatan?->value ?? ''] ?? 'Kegiatan' }}</span>
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px]">Aktif</span>
          </div>
          <h3 class="mt-3 text-xl font-semibold text-slate-900">{{ $k->nama_kegiatan }}</h3>
          <p class="mt-2 text-sm text-slate-600">{{ $k->deskripsi ? str($k->deskripsi)->limit(180) : 'Kegiatan rutin jamaah masjid.' }}</p>
          <dl class="mt-4 space-y-1 text-sm text-slate-600">
            <div class="flex justify-between">
              <dt>Penanggung jawab</dt>
              <dd class="font-medium text-slate-900">{{ $k->penanggung_jawab ?? '-' }}</dd>
            </div>
            <div class="flex justify-between">
              <dt>Kontak</dt>
              <dd class="font-medium text-slate-900">{{ $k->no_telephone ?? '-' }}</dd>
            </div>
          </dl>
          <a href="{{ route('public.kegiatan.show', $k) }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 hover:text-emerald-700">Detail kegiatan
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/></svg>
          </a>
        </article>
      @empty
        <p class="text-slate-500">Belum ada kegiatan aktif.</p>
      @endforelse
    </div>
  </div>
</section>

<section class="max-w-6xl mx-auto px-4 py-12">
  <div class="flex items-center justify-between mb-6">
    <div>
      <p class="text-xs uppercase tracking-[0.3em] text-emerald-600">Daftar ruangan</p>
      <h2 class="text-3xl font-semibold text-slate-900">Pilihan ruangan populer.</h2>
      <p class="text-sm text-slate-500">Grid tiga kolom membantu jamaah melihat kapasitas dan fasilitas secara halus.</p>
    </div>
    <a href="{{ route('public.ruangan.index') }}" class="text-sm font-semibold text-emerald-600">Semua ruangan</a>
  </div>
  <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @forelse($ruanganPopuler as $r)
      @php
        $facilities = $r->fasilitas;
        if (is_array($facilities)) {
            $facilities = collect($facilities)->filter()->join(', ');
        }
      @endphp
      <div class="rounded-3xl border border-slate-100 bg-white shadow-sm p-6 flex flex-col">
        <div class="flex items-center justify-between">
          <h3 class="text-xl font-semibold text-slate-900">{{ $r->nama_ruangan }}</h3>
          <span class="text-xs px-3 py-1 rounded-full {{ ($r->status ?? '') === 'tersedia' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
            {{ ucfirst($r->status ?? 'tersedia') }}
          </span>
        </div>
        <p class="mt-2 text-sm text-slate-600">{{ $r->keterangan ? str($r->keterangan)->limit(160) : 'Ruangan serbaguna yang siap digunakan.' }}</p>
        <div class="mt-4 flex flex-wrap gap-2 text-xs text-slate-500">
          <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-slate-50">
            Kapasitas {{ $r->kapasitas ?? '-' }}
          </span>
          <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-slate-50">
            Fasilitas {{ $facilities ? str($facilities)->limit(40) : '-' }}
          </span>
        </div>
        <div class="mt-auto pt-4 flex justify-between items-center">
          <a href="{{ route('public.ruangan.show', $r->ruangan_id) }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">Detail ruangan</a>
          <a href="{{ route('user.booking.index') }}" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-4 py-2 text-white text-sm font-semibold shadow hover:bg-emerald-700">Booking
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/></svg>
          </a>
        </div>
      </div>
    @empty
      <p class="text-slate-500">Belum ada ruangan yang ditampilkan.</p>
    @endforelse
  </div>
</section>

<section class="max-w-6xl mx-auto px-4 pb-16">
  <div class="rounded-[32px] bg-gradient-to-br from-emerald-100 via-white to-white border border-white/60 p-8 md:p-12 shadow-lg flex flex-col md:flex-row md:items-center md:justify-between gap-8">
    <div>
      <p class="text-xs uppercase tracking-[0.3em] text-emerald-600">Ayo mulai</p>
      <h2 class="mt-2 text-3xl font-semibold text-slate-900">Kelola jadwal, informasi, dan ruangan dengan tampilan yang halus.</h2>
      <p class="mt-3 text-slate-600">Halaman home kini hadir dengan paragraf nyaman serta grid layout yang konsisten sehingga jamaah lebih mudah mencari kebutuhan.</p>
    </div>
    <div class="flex flex-col sm:flex-row gap-3">
      <a href="{{ route('public.informasi.index') }}" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-6 py-3 text-white text-sm font-semibold">Mulai jelajah</a>
      <a href="{{ route('public.ruangan.index') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-900">Booking ruangan</a>
    </div>
  </div>
</section>
@endsection
