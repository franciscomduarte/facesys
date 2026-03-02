<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Procedimento;
use App\Models\TreatmentSession;
use App\Services\TreatmentSessionService;
use App\Http\Requests\StoreTreatmentSessionRequest;
use App\Http\Requests\UpdateTreatmentSessionRequest;

class TreatmentSessionController extends Controller
{
    public function __construct(
        private TreatmentSessionService $sessionService
    ) {}

    public function create(Patient $patient)
    {
        $procedimentosAtivos = Procedimento::ativos()->orderBy('categoria')->orderBy('nome')->get();

        return view('sessions.create', compact('patient', 'procedimentosAtivos'));
    }

    public function store(StoreTreatmentSessionRequest $request, Patient $patient)
    {
        $validated = $request->validated();
        $points = $validated['pontos'] ?? [];
        $procedimentos = $validated['procedimentos_selecionados'] ?? [];
        unset($validated['pontos'], $validated['procedimentos_selecionados']);

        $session = $this->sessionService->create($patient, $validated, $points, $procedimentos);

        return redirect()
            ->route('patients.sessions.show', [$patient, $session])
            ->with('success', 'Sessao registrada com sucesso.');
    }

    public function show(Patient $patient, TreatmentSession $session)
    {
        $session = $this->sessionService->getWithPoints($session);

        return view('sessions.show', compact('patient', 'session'));
    }

    public function edit(Patient $patient, TreatmentSession $session)
    {
        $session->load('applicationPoints', 'procedimentos');
        $procedimentosAtivos = Procedimento::ativos()->orderBy('categoria')->orderBy('nome')->get();

        return view('sessions.edit', compact('patient', 'session', 'procedimentosAtivos'));
    }

    public function update(UpdateTreatmentSessionRequest $request, Patient $patient, TreatmentSession $session)
    {
        $validated = $request->validated();
        $points = $validated['pontos'] ?? [];
        $procedimentos = $validated['procedimentos_selecionados'] ?? [];
        unset($validated['pontos'], $validated['procedimentos_selecionados']);

        $this->sessionService->update($session, $validated, $points, $procedimentos);

        return redirect()
            ->route('patients.sessions.show', [$patient, $session])
            ->with('success', 'Sessao atualizada com sucesso.');
    }

    public function destroy(Patient $patient, TreatmentSession $session)
    {
        $this->sessionService->delete($session);

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'Sessao removida com sucesso.');
    }
}
