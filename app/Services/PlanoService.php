<?php

namespace App\Services;

use App\Models\Plano;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PlanoService
{
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Plano::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'ilike', "%{$search}%")
                  ->orWhere('descricao', 'ilike', "%{$search}%");
            });
        }

        if (isset($filters['ativo'])) {
            $query->where('ativo', $filters['ativo']);
        }

        return $query->ordenados()->paginate($perPage)->withQueryString();
    }

    public function getAtivos(): Collection
    {
        return Plano::ativos()->ordenados()->get();
    }

    public function create(array $data): Plano
    {
        return Plano::create($data);
    }

    public function update(Plano $plano, array $data): Plano
    {
        $plano->update($data);

        return $plano->fresh();
    }

    public function delete(Plano $plano): bool
    {
        return $plano->delete();
    }

    public function toggleAtivo(Plano $plano): Plano
    {
        $plano->update(['ativo' => !$plano->ativo]);

        return $plano->fresh();
    }
}
