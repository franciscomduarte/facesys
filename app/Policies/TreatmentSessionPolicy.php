<?php

namespace App\Policies;

use App\Models\TreatmentSession;
use App\Models\User;

class TreatmentSessionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, TreatmentSession $session): bool
    {
        return $this->belongsToSameEmpresa($user, $session);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isMedico();
    }

    public function update(User $user, TreatmentSession $session): bool
    {
        return $this->belongsToSameEmpresa($user, $session) && ($user->isAdmin() || $user->isMedico());
    }

    public function delete(User $user, TreatmentSession $session): bool
    {
        return $this->belongsToSameEmpresa($user, $session) && ($user->isAdmin() || $user->isMedico());
    }

    private function belongsToSameEmpresa(User $user, TreatmentSession $model): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->empresa_id === $model->empresa_id;
    }
}
