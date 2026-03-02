<?php

namespace App\Policies;

use App\Models\TemplateContrato;
use App\Models\User;

class TemplateContratoPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, TemplateContrato $template): bool
    {
        return $this->belongsToSameEmpresa($user, $template);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, TemplateContrato $template): bool
    {
        return $this->belongsToSameEmpresa($user, $template) && $user->isAdmin();
    }

    public function delete(User $user, TemplateContrato $template): bool
    {
        return $this->belongsToSameEmpresa($user, $template) && $user->isAdmin();
    }

    private function belongsToSameEmpresa(User $user, TemplateContrato $model): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->empresa_id === $model->empresa_id;
    }
}
