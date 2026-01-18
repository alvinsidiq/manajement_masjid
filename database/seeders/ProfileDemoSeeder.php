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
                'nama_lengkap' => 'Ahmad User Demo',
                'nik' => '3201123409876543',
                'alamat_lengkap' => 'Jl. Melati No. 12, RT 04/RW 02, Bandung',
                'no_telephone' => '+62 812-1111-2222',
            ]);
            $u->assignRole('user');
        }
    }
}
