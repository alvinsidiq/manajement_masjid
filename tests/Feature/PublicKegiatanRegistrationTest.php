<?php

use App\Models\Kegiatan;
use App\Models\KegiatanPendaftaran;

it('allows visitor to register for approved kegiatan', function(){
    $kegiatan = Kegiatan::factory()->create([
        'approval_status' => 'approved',
        'is_archived' => false,
    ]);

    $response = $this->post(route('public.kegiatan.daftar', $kegiatan), [
        'nama' => 'Rahmat Hidayat',
        'email' => 'rahmat@example.test',
        'no_telephone' => '+628123456789',
        'catatan' => 'Ingin ikut dengan keluarga.',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('kegiatan_pendaftaran', [
        'kegiatan_id' => $kegiatan->getKey(),
        'email' => 'rahmat@example.test',
    ]);
});

it('blocks registration for non-approved kegiatan', function(){
    $kegiatan = Kegiatan::factory()->create([
        'approval_status' => 'pending',
    ]);

    $this->post(route('public.kegiatan.daftar', $kegiatan), [
        'nama' => 'Tester',
        'email' => 'tester@example.test',
        'no_telephone' => '+628123456789',
    ])->assertNotFound();

    $this->assertDatabaseMissing('kegiatan_pendaftaran', [
        'email' => 'tester@example.test',
        'kegiatan_id' => $kegiatan->getKey(),
    ]);
});
