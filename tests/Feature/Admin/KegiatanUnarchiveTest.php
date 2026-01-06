<?php

use App\Models\User;
use App\Models\Kegiatan;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('admin can unarchive kegiatan', function(){
    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    $k = Kegiatan::factory()->create(['is_archived'=>true,'archived_at'=>now()]);

    actingAs($admin);
    post(route('admin.kegiatan.unarchive',$k))->assertRedirect();

    $k->refresh();
    expect($k->is_archived)->toBeFalse();
});

