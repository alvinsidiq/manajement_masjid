<?php

use App\Models\User;
use App\Models\Kegiatan;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('admin can archive kegiatan with reason', function(){
    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    $k = Kegiatan::factory()->create(['is_archived'=>false]);

    actingAs($admin);
    post(route('admin.kegiatan.archive',$k), ['archive_reason'=>'Selesai.'])->assertRedirect();

    $k->refresh();
    expect($k->is_archived)->toBeTrue();
    expect($k->archive_reason)->toBe('Selesai.');
});

