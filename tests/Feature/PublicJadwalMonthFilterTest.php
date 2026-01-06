<?php

use App\Models\{Kegiatan, Jadwal, Ruangan};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

it('filters by month correctly', function(){
    $tz = 'Asia/Jakarta';
    $ruangan = Ruangan::factory()->create(['status' => 'aktif']);
    $kegiatan = Kegiatan::factory()->create(['nama_kegiatan' => 'Bulan Ini']);

    $firstStart = Carbon::now($tz)->setTime(8, 0);
    $secondStart = $firstStart->copy()->addMonth();

    Jadwal::factory()->create([
        'kegiatan_id' => $kegiatan->kegiatan_id,
        'ruangan_id' => $ruangan->ruangan_id,
        'tanggal_mulai' => $firstStart->copy()->setTimezone('UTC'),
        'tanggal_selesai' => $firstStart->copy()->addHour()->setTimezone('UTC'),
    ]);

    Jadwal::factory()->create([
        'kegiatan_id' => $kegiatan->kegiatan_id,
        'ruangan_id' => $ruangan->ruangan_id,
        'tanggal_mulai' => $secondStart->copy()->setTimezone('UTC'),
        'tanggal_selesai' => $secondStart->copy()->addHour()->setTimezone('UTC'),
    ]);

    $this->get(route('public.jadwal.index', [
        'month' => $firstStart->month,
        'year' => $firstStart->year,
        'view' => 'list',
    ]))->assertOk()->assertSee('Bulan Ini');

    $this->get(route('public.jadwal.index', [
        'month' => $secondStart->month,
        'year' => $secondStart->year,
        'view' => 'list',
    ]))->assertOk()->assertSee('Bulan Ini');
});
