<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class InitSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin','user','bendahara','takmir'] as $r) {
            Role::findOrCreate($r);
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@masjid.local'],
            [
                'username' => 'superadmin',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'is_active'=> true,
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles('admin');

        $bendahara = User::updateOrCreate(
            ['email' => 'bendahara@masjid.local'],
            [
                'username' => 'bendahara',
                'password' => Hash::make('password'),
                'role'     => 'bendahara',
                'is_active'=> true,
                'email_verified_at' => now(),
            ]
        );
        $bendahara->syncRoles('bendahara');

        $takmir = User::updateOrCreate(
            ['email' => 'takmir@masjid.local'],
            [
                'username' => 'takmir',
                'password' => Hash::make('password'),
                'role'     => 'takmir',
                'is_active'=> true,
                'email_verified_at' => now(),
            ]
        );
        $takmir->syncRoles('takmir');

        $user = User::updateOrCreate(
            ['email' => 'user@masjid.local'],
            [
                'username' => 'jamaah',
                'password' => Hash::make('password'),
                'role'     => 'user',
                'is_active'=> true,
                'email_verified_at' => now(),
            ]
        );
        $user->syncRoles('user');
    }
}

