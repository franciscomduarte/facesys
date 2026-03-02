<?php

namespace App\Services;

class EmpresaContextService
{
    private ?int $empresaId = null;

    public function setEmpresaId(?int $empresaId): void
    {
        $this->empresaId = $empresaId;
    }

    public function getEmpresaId(): ?int
    {
        return $this->empresaId;
    }

    public function hasContext(): bool
    {
        return $this->empresaId !== null;
    }
}
