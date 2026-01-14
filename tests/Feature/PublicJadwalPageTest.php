<?php

use App\Models\{Booking, Jadwal, Kegiatan, Pemesanan, Ruangan, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

it('shows combined jadwal and accepted pemesanan on the public calendar', function(){
    $now = Carbon::create(2025, 5, 10, 9, 0, 0, 'Asia/Jakarta');
    Carbon::setTestNow($now);

    try {
        $ruangan = Ruangan::factory()->create(['status' => 'aktif', 'nama_ruangan' => 'Aula Utama']);
        $kegiatan = Kegiatan::factory()->create(['nama_kegiatan' => 'Kajian Tafsir']);

        $startLocal = $now->copy()->startOfMonth()->addDays(2)->setTime(9, 0);
        Jadwal::factory()->create([
            'kegiatan_id' => $kegiatan->kegiatan_id,
            'ruangan_id' => $ruangan->ruangan_id,
            'tanggal_mulai' => $startLocal->copy()->setTimezone('UTC'),
            'tanggal_selesai' => $startLocal->copy()->addHours(2)->setTimezone('UTC'),
            'status' => 'rutin',
            'catatan' => 'Sesi tafsir pagi',
        ]);

        $user = User::factory()->create();
        $slotLocal = $now->copy()->startOfMonth()->addDays(4)->setTime(14, 0);
        $booking = Booking::factory()->create([
            'user_id' => $user->user_id,
            'ruangan_id' => $ruangan->ruangan_id,
            'hari_tanggal' => $slotLocal->copy()->setTimezone('UTC'),
            'jam' => $slotLocal->format('H:i:s'),
            'status' => 'setuju',
        ]);

        Pemesanan::factory()->create([
            'user_id' => $user->user_id,
            'ruangan_id' => $ruangan->ruangan_id,
            'booking_id' => $booking->booking_id,
            'jadwal_id' => null,
            'status' => 'diterima',
            'tujuan_pemesanan' => 'Booking Kajian Pemuda',
        ]);

        $this->get(route('public.jadwal.index', ['month' => $now->month, 'year' => $now->year]))
            ->assertOk()
            ->assertSee('Kajian Tafsir')
            ->assertSee('Booked: Aula Utama');
    } finally {
        Carbon::setTestNow();
    }
});

it('filters jadwal by requested month and year', function(){
    $now = Carbon::create(2025, 5, 10, 9, 0, 0, 'Asia/Jakarta');
    Carbon::setTestNow($now);

    try {
        $ruangan = Ruangan::factory()->create(['status' => 'aktif']);
        $kegiatanNow = Kegiatan::factory()->create(['nama_kegiatan' => 'Kajian Bulanan']);
        $kegiatanNext = Kegiatan::factory()->create(['nama_kegiatan' => 'Kajian Spesial']);

        $currentStart = $now->copy()->startOfMonth()->addDays(3)->setTime(7, 30);
        Jadwal::factory()->create([
            'kegiatan_id' => $kegiatanNow->kegiatan_id,
            'ruangan_id' => $ruangan->ruangan_id,
            'tanggal_mulai' => $currentStart->copy()->setTimezone('UTC'),
            'tanggal_selesai' => $currentStart->copy()->addHours(1)->setTimezone('UTC'),
            'status' => 'berkala',
        ]);

        $nextMonth = $now->copy()->addMonthNoOverflow();
        $nextStart = $nextMonth->copy()->startOfMonth()->addDays(4)->setTime(10, 0);
        Jadwal::factory()->create([
            'kegiatan_id' => $kegiatanNext->kegiatan_id,
            'ruangan_id' => $ruangan->ruangan_id,
            'tanggal_mulai' => $nextStart->copy()->setTimezone('UTC'),
            'tanggal_selesai' => $nextStart->copy()->addHours(2)->setTimezone('UTC'),
            'status' => 'khusus',
        ]);

        $this->get(route('public.jadwal.index', ['month' => $nextMonth->month, 'year' => $nextMonth->year]))
            ->assertOk()
            ->assertSee('Kajian Spesial')
            ->assertDontSee('Kajian Bulanan');
    } finally {
        Carbon::setTestNow();
    }
});

it('shows empty state when selected month has no events', function(){
    $now = Carbon::create(2025, 5, 10, 9, 0, 0, 'Asia/Jakarta');
    Carbon::setTestNow($now);

    try {
        $target = $now->copy()->addMonths(6);

        $this->get(route('public.jadwal.index', ['month' => $target->month, 'year' => $target->year]))
            ->assertOk()
            ->assertSee('Belum ada jadwal pada bulan ini.');
    } finally {
        Carbon::setTestNow();
    }
});
