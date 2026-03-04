<?php

namespace App\Services;

use App\Models\Patient;
use Illuminate\Pagination\LengthAwarePaginator;

class PatientService
{
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Patient::query()
            ->select('id', 'empresa_id', 'nome_completo', 'cpf', 'telefone', 'email', 'created_at')
            ->with(['treatmentSessions' => function ($query) {
                $query->select('id', 'patient_id', 'data_sessao')
                    ->orderByDesc('data_sessao');
            }])
            ->when(isset($filters['search']) && $filters['search'], function ($query) use ($filters) {
                $search = $filters['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('nome_completo', 'ilike', "%{$search}%")
                      ->orWhere('cpf', 'like', "%{$search}%");
                });
            })
            ->orderBy('nome_completo')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): Patient
    {
        return Patient::create($data);
    }

    public function update(Patient $patient, array $data): Patient
    {
        $patient->update($data);
        return $patient->refresh();
    }

    public function delete(Patient $patient): bool
    {
        return $patient->delete();
    }

    public function findWithSessions(Patient $patient): Patient
    {
        return $patient->load(['treatmentSessions' => function ($query) {
            $query->orderByDesc('data_sessao');
        }, 'treatmentSessions.applicationPoints', 'clinicalPhotos.procedimento', 'prescricoes' => function ($query) {
            $query->orderByDesc('created_at')->limit(5);
        }, 'prescricoes.treatmentSession', 'contratos' => function ($query) {
            $query->orderByDesc('created_at')->limit(5);
        }, 'contratos.treatmentSession', 'agendamentos' => function ($query) {
            $query->where('data_agendamento', '>=', now()->toDateString())
                  ->whereIn('status', ['agendado', 'confirmado'])
                  ->orderBy('data_agendamento')
                  ->orderBy('hora_inicio')
                  ->limit(5)
                  ->with('procedimentos');
        }]);
    }
}
