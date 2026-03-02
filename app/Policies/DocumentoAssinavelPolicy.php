<?php

namespace App\Policies;

use App\Models\DocumentoAssinavel;
use App\Models\User;

class DocumentoAssinavelPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, DocumentoAssinavel $documento): bool
    {
        return $this->belongsToSameEmpresa($user, $documento);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isMedico();
    }

    public function assinarProfissional(User $user, DocumentoAssinavel $documento): bool
    {
        return $this->belongsToSameEmpresa($user, $documento)
            && ($user->isAdmin() || $user->isMedico())
            && $documento->podeAssinarProfissional();
    }

    public function regenerateToken(User $user, DocumentoAssinavel $documento): bool
    {
        return $this->belongsToSameEmpresa($user, $documento)
            && ($user->isAdmin() || $user->isMedico())
            && ($documento->isPendente() || $documento->tokenExpirado());
    }

    private function belongsToSameEmpresa(User $user, DocumentoAssinavel $model): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->empresa_id === $model->empresa_id;
    }
}
