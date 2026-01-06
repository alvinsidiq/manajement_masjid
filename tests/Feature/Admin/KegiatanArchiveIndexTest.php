<?php

use App\Models\User;
use App\Models\Arsip;

it('shows archives in index list', function(){
    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    $this->actingAs($admin);

    $a = Arsip::factory()->create(['judul'=>'Surat Penting']);
    $b = Arsip::factory()->create(['judul'=>'Notulensi Rapat']);

    $this->get(route('admin.arsip.index'))
        ->assertOk()
        ->assertSee('Surat Penting')
        ->assertSee('Notulensi Rapat');
});
