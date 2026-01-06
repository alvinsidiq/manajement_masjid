<?php

use App\Models\{Informasi, User};
use function Pest\Laravel\get; use function Pest\Laravel\actingAs;

it('blocks guest from viewing unpublished information', function(){
    $info = Informasi::factory()->create(['is_published'=>false,'published_at'=>null]);
    get(route('public.informasi.show',$info->slug))->assertNotFound();
});

it('allows admin to preview unpublished information', function(){
    $info = Informasi::factory()->create(['is_published'=>false,'published_at'=>null]);
    $admin = User::factory()->create()->assignRole('admin');
    actingAs($admin);
    get(route('public.informasi.show',$info->slug))->assertOk();
});

