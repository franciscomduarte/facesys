<?php

namespace App\Services;

use App\Models\Procedimento;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProcedimentoService
{
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Procedimento::query()
            ->when(isset($filters['search']) && $filters['search'], function ($query) use ($filters) {
                $query->where('nome', 'ilike', "%{$filters['search']}%");
            })
            ->when(isset($filters['categoria']) && $filters['categoria'], function ($query) use ($filters) {
                $query->where('categoria', $filters['categoria']);
            })
            ->when(isset($filters['ativo']), function ($query) use ($filters) {
                $query->where('ativo', $filters['ativo']);
            })
            ->orderBy('nome')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getAtivos(): Collection
    {
        return Procedimento::ativos()->orderBy('nome')->get();
    }

    public function getAtivosByCategoria(): Collection
    {
        return Procedimento::ativos()
            ->orderBy('categoria')
            ->orderBy('nome')
            ->get();
    }

    public function create(array $data): Procedimento
    {
        return Procedimento::create($data);
    }

    public function update(Procedimento $procedimento, array $data): Procedimento
    {
        $procedimento->update($data);
        return $procedimento->fresh();
    }

    public function delete(Procedimento $procedimento): bool
    {
        return $procedimento->delete();
    }

    public function toggleAtivo(Procedimento $procedimento): Procedimento
    {
        $procedimento->update(['ativo' => !$procedimento->ativo]);
        return $procedimento->fresh();
    }
}
