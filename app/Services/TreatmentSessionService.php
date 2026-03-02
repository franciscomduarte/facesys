<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\TreatmentSession;
use Illuminate\Support\Facades\DB;

class TreatmentSessionService
{
    public function create(Patient $patient, array $sessionData, array $points = [], array $procedimentos = []): TreatmentSession
    {
        return DB::transaction(function () use ($patient, $sessionData, $points, $procedimentos) {
            $session = $patient->treatmentSessions()->create($sessionData);

            foreach ($points as $point) {
                $session->applicationPoints()->create($point);
            }

            $this->syncProcedimentos($session, $procedimentos);

            return $session->load('applicationPoints', 'procedimentos');
        });
    }

    public function update(TreatmentSession $session, array $sessionData, array $points = [], array $procedimentos = []): TreatmentSession
    {
        return DB::transaction(function () use ($session, $sessionData, $points, $procedimentos) {
            $session->update($sessionData);

            // Substituir todos os pontos
            $session->applicationPoints()->delete();

            foreach ($points as $point) {
                $session->applicationPoints()->create($point);
            }

            $this->syncProcedimentos($session, $procedimentos);

            return $session->fresh(['applicationPoints', 'procedimentos']);
        });
    }

    public function delete(TreatmentSession $session): bool
    {
        return $session->delete();
    }

    public function getWithPoints(TreatmentSession $session): TreatmentSession
    {
        return $session->load('applicationPoints', 'patient', 'procedimentos', 'clinicalPhotos.procedimento', 'prescricao.items', 'contrato');
    }

    private function syncProcedimentos(TreatmentSession $session, array $procedimentos): void
    {
        $syncData = [];
        foreach ($procedimentos as $proc) {
            if (!empty($proc['id'])) {
                $syncData[$proc['id']] = [
                    'quantidade' => $proc['quantidade'] ?? null,
                    'observacoes' => $proc['observacoes'] ?? null,
                ];
            }
        }
        $session->procedimentos()->sync($syncData);
    }
}
