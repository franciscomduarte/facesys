<?php

namespace App\Policies;

use App\Models\Empresa;
use App\Models\User;

class EmpresaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function view(User $user, Empresa $empresa): bool
    {
        return $user->isSuperAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function update(User $user, Empresa $empresa): bool
    {
        return $user->isSuperAdmin();
    }

    public function delete(User $user, Empresa $empresa): bool
    {
        return $user->isSuperAdmin();
    }

    public function toggleStatus(User $user, Empresa $empresa): bool
    {
        return $user->isSuperAdmin();
    }
}
