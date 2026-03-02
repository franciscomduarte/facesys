<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;

class PatientPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Patient $patient): bool
    {
        return $this->belongsToSameEmpresa($user, $patient);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Patient $patient): bool
    {
        return $this->belongsToSameEmpresa($user, $patient) && ($user->isAdmin() || $user->isMedico());
    }

    public function delete(User $user, Patient $patient): bool
    {
        return $this->belongsToSameEmpresa($user, $patient) && $user->isAdmin();
    }

    private function belongsToSameEmpresa(User $user, Patient $model): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->empresa_id === $model->empresa_id;
    }
}
