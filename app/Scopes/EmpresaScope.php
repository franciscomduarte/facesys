<?php

namespace App\Scopes;

use App\Services\EmpresaContextService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class EmpresaScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $empresaId = app(EmpresaContextService::class)->getEmpresaId();

        if ($empresaId) {
            $builder->where($model->getTable() . '.empresa_id', $empresaId);
        }
    }
}
