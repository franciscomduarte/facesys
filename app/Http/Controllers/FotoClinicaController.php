<?php

namespace App\Http\Controllers;

use App\Models\FotoClinica;
use App\Models\Patient;
use App\Models\TreatmentSession;
use App\Services\FotoClinicaService;
use App\Http\Requests\StoreFotoClinicaRequest;

class FotoClinicaController extends Controller
{
    public function __construct(
        private FotoClinicaService $fotoService
    ) {}

    public function store(StoreFotoClinicaRequest $request, Patient $patient, TreatmentSession $session)
    {
        $validated = $request->validated();
        $files = $validated['fotos'];
        unset($validated['fotos']);

        $this->fotoService->uploadMultiple($session, $files, $validated);

        return redirect()
            ->route('patients.sessions.show', [$patient, $session])
            ->with('success', 'Foto(s) clinica(s) enviada(s) com sucesso.');
    }

    public function destroy(Patient $patient, TreatmentSession $session, FotoClinica $foto)
    {
        $this->fotoService->delete($foto);

        return redirect()
            ->route('patients.sessions.show', [$patient, $session])
            ->with('success', 'Foto clinica removida com sucesso.');
    }
}
