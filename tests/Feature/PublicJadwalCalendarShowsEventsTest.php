<?php

use App\Models\{Kegiatan, Jadwal, Ruangan};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

it('shows jadwal events on the calendar', function(){
    $tz = 'Asia/Jakarta';
    $now = Carbon::now($tz);

    $ruangan = Ruangan::factory()->create(['status' => 'aktif']);
    $kegiatan = Kegiatan::factory()->create(['nama_kegiatan' => 'Uji Kalender']);

    Jadwal::factory()->create([
        'kegiatan_id' => $kegiatan->kegiatan_id,
        'ruangan_id' => $ruangan->ruangan_id,
        'tanggal_mulai' => $now->copy()->setTime(18, 0)->setTimezone('UTC'),
        'tanggal_selesai' => $now->copy()->setTime(19, 0)->setTimezone('UTC'),
        'status' => 'khusus',
    ]);

    $this->get(route('public.jadwal.index', [
        'month' => $now->month,
        'year' => $now->year,
        'view' => 'calendar',
    ]))->assertOk()->assertSee('Uji Kalender');
});
