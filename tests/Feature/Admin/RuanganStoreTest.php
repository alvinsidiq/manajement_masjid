<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('admin can create ruangan with photo', function(){
    Storage::fake('public');
    $admin = User::factory()->create(['role'=>'admin'])->assignRole('admin');
    actingAs($admin);

    $file = UploadedFile::fake()->image('aula.jpg', 640, 480);

    post(route('admin.ruangan.store'), [
        'nama_ruangan' => 'Aula Test',
        'deskripsi' => 'Deskripsi aula',
        'fasilitas' => 'AC, Karpet',
        'status' => 'aktif',
        'foto' => $file,
    ])->assertRedirect(route('admin.ruangan.index'));

    Storage::disk('public')->assertExists('ruangan');
});

