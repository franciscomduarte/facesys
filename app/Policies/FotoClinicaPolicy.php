<?php

namespace App\Policies;

use App\Models\FotoClinica;
use App\Models\User;

class FotoClinicaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, FotoClinica $foto): bool
    {
        return $this->belongsToSameEmpresa($user, $foto);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isMedico();
    }

    public function delete(User $user, FotoClinica $foto): bool
    {
        return $this->belongsToSameEmpresa($user, $foto)
            && ($user->isAdmin() || ($user->isMedico() && $foto->created_by === $user->id));
    }

    private function belongsToSameEmpresa(User $user, FotoClinica $model): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->empresa_id === $model->empresa_id;
    }
}
