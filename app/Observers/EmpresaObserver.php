<?php

namespace App\Observers;

use App\Models\Empresa;
use App\Services\CacheService;

class EmpresaObserver
{
    public function updated(Empresa $empresa): void
    {
        CacheService::clearEmpresa($empresa->id);
        CacheService::clearDashboardAdmin();
    }

    public function created(Empresa $empresa): void
    {
        CacheService::clearDashboardAdmin();
    }

    public function deleted(Empresa $empresa): void
    {
        CacheService::clearEmpresa($empresa->id);
        CacheService::clearDashboardAdmin();
    }
}
