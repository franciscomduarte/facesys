<?php

namespace App\Policies;

use App\Models\Plano;
use App\Models\User;

class PlanoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function view(User $user, Plano $plano): bool
    {
        return $user->isSuperAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function update(User $user, Plano $plano): bool
    {
        return $user->isSuperAdmin();
    }

    public function delete(User $user, Plano $plano): bool
    {
        return $user->isSuperAdmin();
    }

    public function toggleAtivo(User $user, Plano $plano): bool
    {
        return $user->isSuperAdmin();
    }
}
