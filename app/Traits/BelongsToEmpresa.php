<?php

namespace App\Traits;

use App\Models\Empresa;
use App\Scopes\EmpresaScope;
use App\Services\EmpresaContextService;

trait BelongsToEmpresa
{
    public static function bootBelongsToEmpresa(): void
    {
        static::addGlobalScope(new EmpresaScope);

        static::creating(function ($model) {
            if (!$model->empresa_id) {
                $model->empresa_id = app(EmpresaContextService::class)->getEmpresaId();
            }
        });
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
