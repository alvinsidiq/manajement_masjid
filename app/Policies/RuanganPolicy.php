<?php

namespace App\Policies;

use App\Models\Ruangan;
use App\Models\User;

class RuanganPolicy
{
    public function viewAny(User $user): bool { return $user->hasRole('admin'); }
    public function view(User $user, Ruangan $ruangan): bool { return $user->hasRole('admin'); }
    public function create(User $user): bool { return $user->hasRole('admin'); }
    public function update(User $user, Ruangan $ruangan): bool { return $user->hasRole('admin'); }
    public function delete(User $user, Ruangan $ruangan): bool { return $user->hasRole('admin'); }
    public function restore(User $user, Ruangan $ruangan): bool { return $user->hasRole('admin'); }
    public function forceDelete(User $user, Ruangan $ruangan): bool { return false; }
}

