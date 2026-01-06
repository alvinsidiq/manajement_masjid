<?php

use App\Models\User;

it('admin can create kegiatan', function(){
    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    $this->actingAs($admin);

    $this->post(route('admin.kegiatan.store'), [
        'nama_kegiatan' => 'Seminar Parenting Islami',
        'deskripsi' => 'Seminar untuk orang tua muslim.',
        'jenis_kegiatan' => 'khusus',
        'penanggung_jawab' => 'Panitia Pendidikan',
        'no_telephone' => '+628555111222',
    ])->assertRedirect(route('admin.kegiatan.index'));
});
