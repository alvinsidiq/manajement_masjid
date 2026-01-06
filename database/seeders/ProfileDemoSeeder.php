<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user demo jika belum ada
        if (!User::where('email','user@masjid.test')->exists()) {
            $u = User::factory()->create([
                'username' => 'userdemo',
                'email' => 'user@masjid.test',
                'password' => Hash::make('password'),
                'no_telephone' => '+62 812-1111-2222',
            ]);
            $u->assignRole('user');
        }
    }
}