<?php

use App\Models\{User, Ruangan, Booking, Pemesanan};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('shows accepted reservation blocks in list view', function(){
    Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

    $tz = 'Asia/Jakarta';
    $user = User::factory()->create();
    $user->assignRole('user');

    $room = Ruangan::factory()->create(['status' => 'aktif']);

    $hari = Carbon::now($tz)->addDays(2)->setTime(10, 0);

    $booking = Booking::factory()->create([
        'user_id' => $user->user_id,
        'ruangan_id' => $room->ruangan_id,
        'hari_tanggal' => $hari->copy()->setTimezone('UTC'),
        'jam' => $hari->format('H:i:s'),
        'status' => 'submitted',
    ]);

    Pemesanan::factory()->create([
        'user_id' => $user->user_id,
        'ruangan_id' => $room->ruangan_id,
        'booking_id' => $booking->booking_id,
        'status' => 'diterima',
        'tujuan_pemesanan' => 'Rapat Komite',
    ]);

    $this->get(route('public.jadwal.index', ['view' => 'list']))
        ->assertOk()
        ->assertSee('Rapat Komite');
});
