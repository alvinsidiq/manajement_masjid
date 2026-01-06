<?php

namespace App\Policies;

use App\Models\{Notifikasi, User};

class NotifikasiPolicy
{
    public function viewAny(User $u): bool { return $u->hasAnyRole(['admin','bendahara','takmir']); }
    public function view(User $u, Notifikasi $n): bool { return $u->hasAnyRole(['admin','bendahara','takmir']) || $n->user_id === $u->user_id; }
    public function create(User $u): bool { return $u->hasAnyRole(['admin','takmir']); }
    public function update(User $u, Notifikasi $n): bool { return $u->hasRole('admin'); }
    public function delete(User $u, Notifikasi $n): bool { return $u->hasRole('admin'); }
    public function resend(User $u, Notifikasi $n): bool { return $u->hasAnyRole(['admin','takmir']); }
}

