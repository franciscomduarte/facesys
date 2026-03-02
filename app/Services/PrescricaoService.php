<?php

namespace App\Services;

use App\Models\Prescricao;
use App\Models\TreatmentSession;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class PrescricaoService
{
    public function create(TreatmentSession $session, array $data, array $items): Prescricao
    {
        return DB::transaction(function () use ($session, $data, $items) {
            $prescricao = Prescricao::create([
                'patient_id' => $session->patient_id,
                'treatment_session_id' => $session->id,
                'status' => 'rascunho',
                'profissional_responsavel' => $data['profissional_responsavel'],
                'observacoes_gerais' => $data['observacoes_gerais'] ?? null,
            ]);

            foreach ($items as $index => $item) {
                $prescricao->items()->create([
                    'medicamento' => $item['medicamento'],
                    'dosagem' => $item['dosagem'],
                    'via_administracao' => $item['via_administracao'],
                    'frequencia' => $item['frequencia'],
                    'duracao' => $item['duracao'],
                    'observacoes' => $item['observacoes'] ?? null,
                    'ordem' => $index,
                ]);
            }

            return $prescricao->load('items');
        });
    }

    public function update(Prescricao $prescricao, array $data, array $items): Prescricao
    {
        return DB::transaction(function () use ($prescricao, $data, $items) {
            $prescricao->update([
                'profissional_responsavel' => $data['profissional_responsavel'],
                'observacoes_gerais' => $data['observacoes_gerais'] ?? null,
            ]);

            // Replace all items
            $prescricao->items()->delete();

            foreach ($items as $index => $item) {
                $prescricao->items()->create([
                    'medicamento' => $item['medicamento'],
                    'dosagem' => $item['dosagem'],
                    'via_administracao' => $item['via_administracao'],
                    'frequencia' => $item['frequencia'],
                    'duracao' => $item['duracao'],
                    'observacoes' => $item['observacoes'] ?? null,
                    'ordem' => $index,
                ]);
            }

            return $prescricao->fresh('items');
        });
    }

    public function emitir(Prescricao $prescricao): Prescricao
    {
        $prescricao->update([
            'status' => 'emitida',
            'data_emissao' => now()->toDateString(),
        ]);

        return $prescricao->fresh();
    }

    public function delete(Prescricao $prescricao): bool
    {
        return $prescricao->delete();
    }

    public function generatePdf(Prescricao $prescricao)
    {
        $prescricao->load(['patient', 'treatmentSession', 'items']);

        $documentoAssinavel = $prescricao->documentosAssinaveis()
            ->where('status', 'finalizado')
            ->with(['assinaturaPaciente', 'assinaturaProfissional'])
            ->latest()
            ->first();

        return Pdf::loadView('prescricoes.pdf', [
            'prescricao' => $prescricao,
            'documentoAssinavel' => $documentoAssinavel,
        ])->setPaper('a4');
    }
}
