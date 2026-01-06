<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserSetting;

class UserSettingPolicy
{
    public function view(User $user, UserSetting $setting): bool
    {
        return $user->user_id === $setting->user_id || $user->hasRole('admin');
    }

    public function update(User $user, UserSetting $setting): bool
    {
        return $user->user_id === $setting->user_id || $user->hasRole('admin');
    }
}

