<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('exports kegiatan to pdf', function(){
    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    actingAs($admin);
    get(route('admin.reports.index',['report'=>'aktivitas-kegiatan','format'=>'pdf']))->assertOk();
});

