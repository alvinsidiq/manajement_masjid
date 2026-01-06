<?php

namespace App\Policies;

use App\Models\{Booking, User};

class BookingPolicy
{
    public function viewAny(User $u): bool { return $u->exists; }
    public function view(User $u, Booking $b): bool { return $b->user_id === $u->user_id; }
    public function create(User $u): bool { return $u->hasAnyRole(['user','admin','takmir','bendahara']); }
    public function update(User $u, Booking $b): bool { return $b->user_id === $u->user_id && in_array($b->status->value,['hold','submitted']); }
    public function delete(User $u, Booking $b): bool { return $b->user_id === $u->user_id && in_array($b->status->value,['hold','submitted']); }
}