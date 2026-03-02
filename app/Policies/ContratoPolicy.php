<?php

namespace App\Policies;

use App\Models\Contrato;
use App\Models\User;

class ContratoPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Contrato $contrato): bool
    {
        return $this->belongsToSameEmpresa($user, $contrato);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isMedico();
    }

    public function update(User $user, Contrato $contrato): bool
    {
        return $this->belongsToSameEmpresa($user, $contrato)
            && ($user->isAdmin() || $user->isMedico()) && $contrato->podeEditar();
    }

    public function delete(User $user, Contrato $contrato): bool
    {
        return $this->belongsToSameEmpresa($user, $contrato)
            && ($user->isAdmin() || $user->isMedico()) && $contrato->podeEditar();
    }

    public function gerar(User $user, Contrato $contrato): bool
    {
        return $this->belongsToSameEmpresa($user, $contrato)
            && ($user->isAdmin() || $user->isMedico()) && $contrato->isRascunho();
    }

    public function downloadPdf(User $user, Contrato $contrato): bool
    {
        return $this->belongsToSameEmpresa($user, $contrato);
    }

    private function belongsToSameEmpresa(User $user, Contrato $model): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->empresa_id === $model->empresa_id;
    }
}
