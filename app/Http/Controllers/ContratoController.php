<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Patient;
use App\Models\TemplateContrato;
use App\Models\TreatmentSession;
use App\Http\Requests\StoreContratoRequest;
use App\Services\ContratoService;
use App\Services\DocumentoAssinavelService;

class ContratoController extends Controller
{
    public function __construct(
        private ContratoService $contratoService,
        private DocumentoAssinavelService $documentoService
    ) {}

    public function create(Patient $patient, TreatmentSession $session)
    {
        if ($session->contrato) {
            return redirect()->route('patients.sessions.contrato.show', [
                $patient, $session, $session->contrato,
            ]);
        }

        $templates = TemplateContrato::ativos()->get();

        return view('contratos.create', compact('patient', 'session', 'templates'));
    }

    public function store(StoreContratoRequest $request, Patient $patient, TreatmentSession $session)
    {
        $validated = $request->validated();
        $template = TemplateContrato::findOrFail($validated['template_contrato_id']);

        $contrato = $this->contratoService->create($session, $template, $validated);

        return redirect()
            ->route('patients.sessions.contrato.show', [$patient, $session, $contrato])
            ->with('success', 'Contrato gerado como rascunho.');
    }

    public function show(Patient $patient, TreatmentSession $session, Contrato $contrato)
    {
        $contrato->load(['templateContrato', 'profissional']);

        $documentoAssinavel = $contrato->documentoAssinavelAtivo()
            ?->with(['assinaturaPaciente', 'assinaturaProfissional'])
            ->first();

        if (!$documentoAssinavel) {
            $documentoAssinavel = $contrato->documentosAssinaveis()
                ->where('status', 'finalizado')
                ->with(['assinaturaPaciente', 'assinaturaProfissional'])
                ->latest()
                ->first();
        }

        return view('contratos.show', compact('patient', 'session', 'contrato', 'documentoAssinavel'));
    }

    public function edit(Patient $patient, TreatmentSession $session, Contrato $contrato)
    {
        if (!$contrato->podeEditar()) {
            return redirect()
                ->route('patients.sessions.contrato.show', [$patient, $session, $contrato])
                ->with('error', 'Este contrato nao pode mais ser editado.');
        }

        return view('contratos.edit', compact('patient', 'session', 'contrato'));
    }

    public function update(StoreContratoRequest $request, Patient $patient, TreatmentSession $session, Contrato $contrato)
    {
        if (!$contrato->podeEditar()) {
            return redirect()
                ->route('patients.sessions.contrato.show', [$patient, $session, $contrato])
                ->with('error', 'Este contrato nao pode mais ser editado.');
        }

        $validated = $request->validated();

        $contrato->update([
            'observacoes' => $validated['observacoes'] ?? null,
            'valor_total' => $validated['valor_total'] ?? $contrato->valor_total,
        ]);

        return redirect()
            ->route('patients.sessions.contrato.show', [$patient, $session, $contrato])
            ->with('success', 'Contrato atualizado com sucesso.');
    }

    public function destroy(Patient $patient, TreatmentSession $session, Contrato $contrato)
    {
        if (!$contrato->podeEditar()) {
            return redirect()
                ->route('patients.sessions.contrato.show', [$patient, $session, $contrato])
                ->with('error', 'Este contrato nao pode ser removido.');
        }

        $this->contratoService->delete($contrato);

        return redirect()
            ->route('patients.sessions.show', [$patient, $session])
            ->with('success', 'Contrato removido com sucesso.');
    }

    public function gerar(Patient $patient, TreatmentSession $session, Contrato $contrato)
    {
        if (!$contrato->isRascunho()) {
            return redirect()
                ->route('patients.sessions.contrato.show', [$patient, $session, $contrato])
                ->with('error', 'Este contrato ja foi gerado.');
        }

        $this->contratoService->gerar($contrato);

        return redirect()
            ->route('patients.sessions.contrato.show', [$patient, $session, $contrato])
            ->with('success', 'Contrato gerado com sucesso. Nao sera mais possivel edita-lo.');
    }

    public function pdf(Patient $patient, TreatmentSession $session, Contrato $contrato)
    {
        $pdf = $this->contratoService->generatePdf($contrato);

        $filename = "contrato_{$contrato->id}_{$patient->nome_completo}.pdf";
        $filename = preg_replace('/[^A-Za-z0-9_\-.]/', '_', $filename);

        return $pdf->stream($filename);
    }

    public function solicitarAssinatura(Patient $patient, TreatmentSession $session, Contrato $contrato)
    {
        if (!$contrato->isGerado()) {
            return back()->with('error', 'O contrato precisa estar gerado para solicitar assinatura.');
        }

        if ($contrato->documentoAssinavelAtivo) {
            return back()->with('error', 'Ja existe uma solicitacao de assinatura ativa para este contrato.');
        }

        $documento = $this->documentoService->createForContrato($contrato, auth()->id());

        return redirect()
            ->route('assinatura.show', $documento)
            ->with('success', 'Solicitacao de assinatura criada. Envie o link ao paciente.');
    }
}
