<?php

namespace App\Policies;

use App\Enums\StatusPemesanan;
use App\Models\Pemesanan;
use App\Models\User;

class PemesananPolicy
{
    public function viewAny(User $user): bool { return $user->hasAnyRole(['admin','takmir','bendahara']); }
    public function view(User $user, Pemesanan $p): bool { return $user->hasAnyRole(['admin','takmir','bendahara']); }
    public function create(User $user): bool { return $user->hasAnyRole(['admin','takmir']); }
    public function update(User $user, Pemesanan $p): bool { return $user->hasAnyRole(['admin','takmir']); }
    public function delete(User $user, Pemesanan $p): bool { return $user->hasRole('admin'); }
    public function restore(User $user, Pemesanan $p): bool { return $user->hasRole('admin'); }
    public function forceDelete(User $user, Pemesanan $p): bool { return false; }

    public function approve(User $user, Pemesanan $p): bool
    {
        return $user->hasRole('takmir') && $p->status === StatusPemesanan::MENUNGGU;
    }

    public function reject(User $user, Pemesanan $p): bool
    {
        return $user->hasRole('takmir') && $p->status === StatusPemesanan::MENUNGGU;
    }

    public function cancel(User $user, Pemesanan $p): bool
    {
        return $user->hasAnyRole(['admin','takmir']) || $p->user_id === $user->user_id;
    }

    public function complete(User $user, Pemesanan $p): bool
    {
        return $user->hasAnyRole(['admin','takmir']) && $p->status === StatusPemesanan::DITERIMA;
    }
}
