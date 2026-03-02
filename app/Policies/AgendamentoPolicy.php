<?php

namespace App\Policies;

use App\Models\Agendamento;
use App\Models\User;

class AgendamentoPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Agendamento $agendamento): bool
    {
        return $this->belongsToSameEmpresa($user, $agendamento);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Agendamento $agendamento): bool
    {
        return $this->belongsToSameEmpresa($user, $agendamento) && $agendamento->podeEditar();
    }

    public function confirmar(User $user, Agendamento $agendamento): bool
    {
        return $this->belongsToSameEmpresa($user, $agendamento)
            && ($user->isAdmin() || $user->isMedico()) && $agendamento->isAgendado();
    }

    public function cancelar(User $user, Agendamento $agendamento): bool
    {
        return $this->belongsToSameEmpresa($user, $agendamento)
            && ($user->isAdmin() || $user->isMedico()) && $agendamento->podeCancelar();
    }

    public function marcarRealizado(User $user, Agendamento $agendamento): bool
    {
        return $this->belongsToSameEmpresa($user, $agendamento)
            && ($user->isAdmin() || $user->isMedico()) && $agendamento->isConfirmado();
    }

    public function marcarNaoCompareceu(User $user, Agendamento $agendamento): bool
    {
        return $this->belongsToSameEmpresa($user, $agendamento)
            && ($user->isAdmin() || $user->isMedico()) && $agendamento->isConfirmado();
    }

    public function delete(User $user, Agendamento $agendamento): bool
    {
        return $this->belongsToSameEmpresa($user, $agendamento) && $user->isAdmin();
    }

    private function belongsToSameEmpresa(User $user, Agendamento $model): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->empresa_id === $model->empresa_id;
    }
}
