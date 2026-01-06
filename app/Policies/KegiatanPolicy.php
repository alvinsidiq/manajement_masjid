<?php

namespace App\Policies;

use App\Models\Kegiatan;
use App\Models\User;

class KegiatanPolicy
{
    public function viewAny(User $user): bool { return $user->hasRole('admin'); }
    public function view(User $user, Kegiatan $kegiatan): bool { return $user->hasRole('admin'); }
    public function create(User $user): bool { return $user->hasRole('admin'); }
    public function update(User $user, Kegiatan $kegiatan): bool { return $user->hasRole('admin'); }
    public function delete(User $user, Kegiatan $kegiatan): bool { return $user->hasRole('admin'); }
    public function restore(User $user, Kegiatan $kegiatan): bool { return $user->hasRole('admin'); }
    public function forceDelete(User $user, Kegiatan $kegiatan): bool { return false; }

    public function archive(User $user, Kegiatan $kegiatan): bool
    {
        return $user->hasRole('admin') && ! $kegiatan->is_archived;
    }

    public function unarchive(User $user, Kegiatan $kegiatan): bool
    {
        return $user->hasRole('admin') && $kegiatan->is_archived;
    }
}
