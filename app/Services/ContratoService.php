<?php

namespace App\Services;

use App\Models\Contrato;
use App\Models\TemplateContrato;
use App\Models\TreatmentSession;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ContratoService
{
    public function __construct(
        private TemplateParserService $parserService,
        private DocumentoAssinavelService $documentoService
    ) {}

    public function create(TreatmentSession $session, TemplateContrato $template, array $extras = []): Contrato
    {
        return DB::transaction(function () use ($session, $template, $extras) {
            $data = $this->parserService->buildDataFromSession($session);
            $conteudoRenderizado = $this->parserService->parse($template->conteudo_template, $data);

            $valorTotal = $extras['valor_total'] ?? $this->parserService->calculateValorTotal($session);

            $contrato = Contrato::create([
                'patient_id' => $session->patient_id,
                'treatment_session_id' => $session->id,
                'profissional_id' => auth()->id(),
                'template_contrato_id' => $template->id,
                'status' => 'rascunho',
                'conteudo_renderizado' => $conteudoRenderizado,
                'hash_contrato' => $this->generateContractHash($conteudoRenderizado, $session),
                'valor_total' => $valorTotal,
                'observacoes' => $extras['observacoes'] ?? null,
            ]);

            return $contrato;
        });
    }

    public function gerar(Contrato $contrato): Contrato
    {
        $contrato->update([
            'status' => 'gerado',
            'data_geracao' => now(),
        ]);

        return $contrato->fresh();
    }

    public function delete(Contrato $contrato): bool
    {
        return $contrato->delete();
    }

    public function generatePdf(Contrato $contrato)
    {
        $contrato->load(['patient', 'treatmentSession', 'profissional', 'templateContrato']);

        $documentoAssinavel = $contrato->documentosAssinaveis()
            ->where('status', 'finalizado')
            ->with(['assinaturaPaciente', 'assinaturaProfissional'])
            ->latest()
            ->first();

        return Pdf::loadView('contratos.pdf', [
            'contrato' => $contrato,
            'documentoAssinavel' => $documentoAssinavel,
        ])->setPaper('a4');
    }

    public function generateContractHash(string $conteudo, TreatmentSession $session): string
    {
        $content = json_encode([
            'conteudo' => $conteudo,
            'patient_id' => $session->patient_id,
            'session_id' => $session->id,
            'profissional' => $session->profissional_responsavel,
        ]);

        return hash('sha256', $content);
    }
}
