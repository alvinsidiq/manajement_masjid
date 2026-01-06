<?php

namespace App\Policies;

use App\Models\{Informasi, User};

class InformasiPolicy
{
    public function viewAny(?User $user): bool { return true; }

    public function view(?User $user, Informasi $info): bool
    {
        if ($info->is_published) return true;
        return $user?->hasAnyRole(['admin','takmir']) ?? false;
    }

    public function create(User $user): bool { return $user->hasAnyRole(['admin','takmir']); }
    public function update(User $user, Informasi $info): bool { return $user->hasAnyRole(['admin','takmir']); }
    public function delete(User $user, Informasi $info): bool { return $user->hasRole('admin'); }
}

