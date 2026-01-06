<?php

namespace App\Policies;

use App\Models\{Payment, User};

class PaymentPolicy
{
    public function viewAny(User $u): bool { return $u->hasAnyRole(['admin','bendahara']); }
    public function view(User $u, Payment $p): bool { return $u->hasAnyRole(['admin','bendahara']); }
    public function create(User $u): bool { return $u->hasAnyRole(['admin','bendahara']); }
    public function update(User $u, Payment $p): bool { return $u->hasAnyRole(['admin','bendahara']); }
    public function delete(User $u, Payment $p): bool { return $u->hasRole('admin'); }

    public function markPaid(User $u, Payment $p): bool { return $u->hasAnyRole(['admin','bendahara']) && $p->status->value==='pending'; }
    public function refund(User $u, Payment $p): bool { return $u->hasAnyRole(['admin','bendahara']) && in_array($p->status->value,['paid']); }
}

