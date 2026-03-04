<?php

namespace App\Http\Controllers;

use App\Models\DocumentoAssinavel;
use App\Models\Patient;
use App\Models\Prescricao;
use App\Models\TreatmentSession;
use App\Http\Requests\AssinaturaPacienteRequest;
use App\Http\Requests\AssinaturaProfissionalRequest;
use App\Services\AssinaturaService;
use App\Services\DocumentoAssinavelService;

class AssinaturaController extends Controller
{
    public function __construct(
        private DocumentoAssinavelService $documentoService,
        private AssinaturaService $assinaturaService
    ) {}

    /**
     * POST auth — cria DocumentoAssinavel para prescricao emitida
     */
    public function solicitarAssinatura(Patient $patient, TreatmentSession $session, Prescricao $prescricao)
    {
        if (!$prescricao->isEmitida()) {
            return back()->with('error', 'A prescricao precisa estar emitida para solicitar assinatura.');
        }

        if ($prescricao->documentoAssinavelAtivo) {
            return back()->with('error', 'Ja existe uma solicitacao de assinatura ativa para esta prescricao.');
        }

        $documento = $this->documentoService->createForPrescricao(
            $prescricao,
            auth()->id()
        );

        return redirect()
            ->route('assinatura.show', $documento)
            ->with('success', 'Solicitacao de assinatura criada. Envie o link ao paciente.');
    }

    /**
     * GET publico — pagina de assinatura do paciente
     */
    public function assinarPaciente(string $token)
    {
        $documento = $this->documentoService->findByToken($token);

        if (!$documento) {
            abort(404);
        }

        if ($documento->tokenExpirado()) {
            return view('assinaturas.token-expirado');
        }

        if (!$documento->isPendente()) {
            return view('assinaturas.ja-assinado');
        }

        $documento->load(['documento']);

        if ($documento->tipo_documento === 'prescricao') {
            $documento->documento->load('items');
        }

        return view('assinaturas.assinar', compact('documento'));
    }

    /**
     * POST publico — registra assinatura do paciente
     */
    public function storeAssinaturaPaciente(AssinaturaPacienteRequest $request, string $token)
    {
        $documento = $this->documentoService->findByToken($token);

        if (!$documento || !$documento->podeAssinarPaciente()) {
            return back()->with('error', 'Este documento nao pode ser assinado no momento.');
        }

        $this->assinaturaService->registrarAssinaturaPaciente(
            $documento,
            $request->validated(),
            $request
        );

        return view('assinaturas.assinatura-confirmada', compact('documento'));
    }

    /**
     * GET auth — pagina de assinatura do profissional
     */
    public function assinarProfissional(DocumentoAssinavel $documento)
    {
        $this->authorize('assinarProfissional', $documento);

        $documento->load(['patient', 'profissional', 'documento', 'assinaturaPaciente']);

        return view('assinaturas.assinar-profissional', compact('documento'));
    }

    /**
     * POST auth — registra assinatura do profissional
     */
    public function storeAssinaturaProfissional(AssinaturaProfissionalRequest $request, DocumentoAssinavel $documento)
    {
        $this->authorize('assinarProfissional', $documento);

        $this->assinaturaService->registrarAssinaturaProfissional(
            $documento,
            $request->validated(),
            $request
        );

        return redirect()
            ->route('assinatura.show', $documento)
            ->with('success', 'Documento assinado com sucesso. O documento esta finalizado.');
    }

    /**
     * GET auth — detalhes do documento e assinaturas
     */
    public function show(DocumentoAssinavel $documento)
    {
        $this->authorize('view', $documento);

        $documento->load(['patient', 'profissional', 'documento', 'assinaturaPaciente', 'assinaturaProfissional']);

        return view('assinaturas.show', compact('documento'));
    }

    /**
     * GET publico — formulario de verificacao
     */
    public function verificar()
    {
        return view('assinaturas.verificar');
    }

    /**
     * GET publico — resultado da verificacao
     */
    public function verificarHash(string $hash)
    {
        $documento = DocumentoAssinavel::where('hash_documento', $hash)
            ->with(['patient', 'profissional', 'assinaturas'])
            ->first();

        $integridadeOk = false;
        if ($documento) {
            $integridadeOk = $this->documentoService->verificarIntegridade($documento);
        }

        return view('assinaturas.verificar', compact('hash', 'documento', 'integridadeOk'));
    }

    /**
     * PATCH auth — regenerar token
     */
    public function regenerarToken(DocumentoAssinavel $documento)
    {
        $this->authorize('regenerateToken', $documento);

        $this->documentoService->regenerateToken($documento);

        return back()->with('success', 'Token regenerado com sucesso. Envie o novo link ao paciente.');
    }
}
