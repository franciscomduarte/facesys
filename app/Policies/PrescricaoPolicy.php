<?php

namespace App\Policies;

use App\Models\Prescricao;
use App\Models\User;

class PrescricaoPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Prescricao $prescricao): bool
    {
        return $this->belongsToSameEmpresa($user, $prescricao);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isMedico();
    }

    public function update(User $user, Prescricao $prescricao): bool
    {
        return $this->belongsToSameEmpresa($user, $prescricao)
            && ($user->isAdmin() || $user->isMedico()) && $prescricao->podeEditar();
    }

    public function delete(User $user, Prescricao $prescricao): bool
    {
        return $this->belongsToSameEmpresa($user, $prescricao)
            && ($user->isAdmin() || $user->isMedico()) && $prescricao->podeEditar();
    }

    public function emitir(User $user, Prescricao $prescricao): bool
    {
        return $this->belongsToSameEmpresa($user, $prescricao)
            && ($user->isAdmin() || $user->isMedico()) && $prescricao->podeEditar();
    }

    public function downloadPdf(User $user, Prescricao $prescricao): bool
    {
        return $this->belongsToSameEmpresa($user, $prescricao);
    }

    private function belongsToSameEmpresa(User $user, Prescricao $model): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->empresa_id === $model->empresa_id;
    }
}
