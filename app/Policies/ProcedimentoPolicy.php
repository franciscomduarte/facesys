<?php

namespace App\Policies;

use App\Models\Procedimento;
use App\Models\User;

class ProcedimentoPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Procedimento $procedimento): bool
    {
        return $this->belongsToSameEmpresa($user, $procedimento);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isMedico();
    }

    public function update(User $user, Procedimento $procedimento): bool
    {
        return $this->belongsToSameEmpresa($user, $procedimento) && ($user->isAdmin() || $user->isMedico());
    }

    public function delete(User $user, Procedimento $procedimento): bool
    {
        return $this->belongsToSameEmpresa($user, $procedimento) && $user->isAdmin();
    }

    private function belongsToSameEmpresa(User $user, Procedimento $model): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->empresa_id === $model->empresa_id;
    }
}
