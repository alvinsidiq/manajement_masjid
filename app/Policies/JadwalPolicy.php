<?php

namespace App\Policies;

use App\Models\Jadwal;
use App\Models\User;

class JadwalPolicy
{
    public function viewAny(User $user): bool { return $user->hasRole('admin'); }
    public function view(User $user, Jadwal $jadwal): bool { return $user->hasRole('admin'); }
    public function create(User $user): bool { return $user->hasRole('admin'); }
    public function update(User $user, Jadwal $jadwal): bool { return $user->hasRole('admin'); }
    public function delete(User $user, Jadwal $jadwal): bool { return $user->hasRole('admin'); }
    public function restore(User $user, Jadwal $jadwal): bool { return $user->hasRole('admin'); }
    public function forceDelete(User $user, Jadwal $jadwal): bool { return false; }
}

