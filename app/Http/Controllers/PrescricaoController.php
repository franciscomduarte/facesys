<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Prescricao;
use App\Models\TreatmentSession;
use App\Services\PrescricaoService;
use App\Http\Requests\StorePrescricaoRequest;
use App\Http\Requests\UpdatePrescricaoRequest;

class PrescricaoController extends Controller
{
    public function __construct(
        private PrescricaoService $prescricaoService
    ) {}

    public function create(Patient $patient, TreatmentSession $session)
    {
        // Se ja tem prescricao, redirecionar para ela
        if ($session->prescricao) {
            return redirect()->route('patients.sessions.prescricao.show', [
                $patient, $session, $session->prescricao,
            ]);
        }

        return view('prescricoes.create', compact('patient', 'session'));
    }

    public function store(StorePrescricaoRequest $request, Patient $patient, TreatmentSession $session)
    {
        $validated = $request->validated();

        $prescricao = $this->prescricaoService->create(
            $session,
            $validated,
            $validated['items']
        );

        return redirect()
            ->route('patients.sessions.prescricao.show', [$patient, $session, $prescricao])
            ->with('success', 'Prescricao salva como rascunho.');
    }

    public function show(Patient $patient, TreatmentSession $session, Prescricao $prescricao)
    {
        $prescricao->load('items');

        $documentoAssinavel = $prescricao->documentoAssinavelAtivo()
            ?->with(['assinaturaPaciente', 'assinaturaProfissional'])
            ->first();

        if (!$documentoAssinavel) {
            $documentoAssinavel = $prescricao->documentosAssinaveis()
                ->where('status', 'finalizado')
                ->with(['assinaturaPaciente', 'assinaturaProfissional'])
                ->latest()
                ->first();
        }

        return view('prescricoes.show', compact('patient', 'session', 'prescricao', 'documentoAssinavel'));
    }

    public function edit(Patient $patient, TreatmentSession $session, Prescricao $prescricao)
    {
        if (!$prescricao->podeEditar()) {
            return redirect()
                ->route('patients.sessions.prescricao.show', [$patient, $session, $prescricao])
                ->with('error', 'Esta prescricao nao pode mais ser editada.');
        }

        $prescricao->load('items');

        return view('prescricoes.edit', compact('patient', 'session', 'prescricao'));
    }

    public function update(UpdatePrescricaoRequest $request, Patient $patient, TreatmentSession $session, Prescricao $prescricao)
    {
        $validated = $request->validated();

        $this->prescricaoService->update($prescricao, $validated, $validated['items']);

        return redirect()
            ->route('patients.sessions.prescricao.show', [$patient, $session, $prescricao])
            ->with('success', 'Prescricao atualizada com sucesso.');
    }

    public function destroy(Patient $patient, TreatmentSession $session, Prescricao $prescricao)
    {
        if (!$prescricao->podeEditar()) {
            return redirect()
                ->route('patients.sessions.prescricao.show', [$patient, $session, $prescricao])
                ->with('error', 'Esta prescricao nao pode ser removida.');
        }

        $this->prescricaoService->delete($prescricao);

        return redirect()
            ->route('patients.sessions.show', [$patient, $session])
            ->with('success', 'Prescricao removida com sucesso.');
    }

    public function emitir(Patient $patient, TreatmentSession $session, Prescricao $prescricao)
    {
        if (!$prescricao->podeEditar()) {
            return redirect()
                ->route('patients.sessions.prescricao.show', [$patient, $session, $prescricao])
                ->with('error', 'Esta prescricao ja foi emitida.');
        }

        $this->prescricaoService->emitir($prescricao);

        return redirect()
            ->route('patients.sessions.prescricao.show', [$patient, $session, $prescricao])
            ->with('success', 'Prescricao emitida com sucesso. Nao sera mais possivel edita-la.');
    }

    public function pdf(Patient $patient, TreatmentSession $session, Prescricao $prescricao)
    {
        $pdf = $this->prescricaoService->generatePdf($prescricao);

        $filename = "prescricao_{$prescricao->id}_{$patient->nome_completo}.pdf";
        $filename = preg_replace('/[^A-Za-z0-9_\-.]/', '_', $filename);

        return $pdf->stream($filename);
    }
}
