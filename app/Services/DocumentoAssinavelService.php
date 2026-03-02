<?php

namespace App\Services;

use App\Models\Contrato;
use App\Models\DocumentoAssinavel;
use App\Models\Prescricao;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DocumentoAssinavelService
{
    public function createForPrescricao(Prescricao $prescricao, int $profissionalId): DocumentoAssinavel
    {
        return DB::transaction(function () use ($prescricao, $profissionalId) {
            return DocumentoAssinavel::create([
                'tipo_documento' => 'prescricao',
                'documento_id' => $prescricao->id,
                'documento_type' => Prescricao::class,
                'patient_id' => $prescricao->patient_id,
                'profissional_id' => $profissionalId,
                'status' => 'pendente',
                'hash_documento' => $this->generateDocumentHash($prescricao),
                'token_acesso' => $this->generateToken(),
                'token_expira_em' => now()->addHours(
                    config('assinatura.token_expiration_hours', 48)
                ),
            ]);
        });
    }

    public function createForContrato(Contrato $contrato, int $profissionalId): DocumentoAssinavel
    {
        return DB::transaction(function () use ($contrato, $profissionalId) {
            return DocumentoAssinavel::create([
                'tipo_documento' => 'contrato',
                'documento_id' => $contrato->id,
                'documento_type' => Contrato::class,
                'patient_id' => $contrato->patient_id,
                'profissional_id' => $profissionalId,
                'status' => 'pendente',
                'hash_documento' => $contrato->hash_contrato,
                'token_acesso' => $this->generateToken(),
                'token_expira_em' => now()->addHours(
                    config('assinatura.token_expiration_hours', 48)
                ),
            ]);
        });
    }

    public function findByToken(string $token): ?DocumentoAssinavel
    {
        return DocumentoAssinavel::where('token_acesso', $token)
            ->with(['patient', 'profissional', 'documento', 'assinaturas'])
            ->first();
    }

    public function regenerateToken(DocumentoAssinavel $documento): DocumentoAssinavel
    {
        $documento->update([
            'token_acesso' => $this->generateToken(),
            'token_expira_em' => now()->addHours(
                config('assinatura.token_expiration_hours', 48)
            ),
        ]);

        return $documento->fresh();
    }

    public function generateToken(): string
    {
        return Str::random(64);
    }

    public function generateDocumentHash(Prescricao $prescricao): string
    {
        $prescricao->load(['patient', 'items']);

        $content = json_encode([
            'prescricao_id' => $prescricao->id,
            'patient_id' => $prescricao->patient_id,
            'patient_cpf' => $prescricao->patient->cpf,
            'profissional' => $prescricao->profissional_responsavel,
            'data_emissao' => $prescricao->data_emissao?->toDateString(),
            'observacoes' => $prescricao->observacoes_gerais,
            'items' => $prescricao->items->map(fn($item) => [
                'medicamento' => $item->medicamento,
                'dosagem' => $item->dosagem,
                'via' => $item->via_administracao,
                'frequencia' => $item->frequencia,
                'duracao' => $item->duracao,
            ])->toArray(),
        ]);

        return hash('sha256', $content);
    }

    public function verificarIntegridade(DocumentoAssinavel $documento): bool
    {
        $doc = $documento->documento;
        if (!$doc) {
            return false;
        }

        if ($documento->documento_type === Prescricao::class) {
            $hashAtual = $this->generateDocumentHash($doc);
            return hash_equals($documento->hash_documento, $hashAtual);
        }

        if ($documento->documento_type === Contrato::class) {
            return hash_equals($documento->hash_documento, $doc->hash_contrato);
        }

        return false;
    }
}
