<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserSetting;

class UserSettingSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        foreach ($users as $u) {
            UserSetting::firstOrCreate(
                ['user_id' => $u->getKey()],
                ['dark_mode' => false, 'preferred_landing' => $u->hasRole('user') ? 'home' : 'dashboard']
            );
        }
    }
}

