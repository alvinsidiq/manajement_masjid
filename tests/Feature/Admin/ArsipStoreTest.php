<?php

use App\Models\User;
use App\Models\Arsip;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('admin can upload arsip dokumen', function(){
    Storage::fake('public');
    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    $this->actingAs($admin);

    $response = $this->post(route('admin.arsip.store'), [
        'judul' => 'Surat Keputusan',
        'deskripsi' => 'SK pengurus terbaru',
        'dokumen' => UploadedFile::fake()->create('surat-keputusan.pdf', 120, 'application/pdf'),
    ]);

    $response->assertRedirect(route('admin.arsip.index'));

    $arsip = Arsip::first();
    expect($arsip)->not()->toBeNull();
    Storage::disk('public')->assertExists($arsip->dokumen);
});
