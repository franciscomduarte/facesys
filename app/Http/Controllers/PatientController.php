<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Services\PatientService;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function __construct(
        private PatientService $patientService
    ) {}

    public function index(Request $request)
    {
        $patients = $this->patientService->list(
            filters: $request->only('search'),
            perPage: 15
        );

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(StorePatientRequest $request)
    {
        $patient = $this->patientService->create($request->validated());

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'Paciente cadastrado com sucesso.');
    }

    public function show(Patient $patient)
    {
        $patient = $this->patientService->findWithSessions($patient);

        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        $this->patientService->update($patient, $request->validated());

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'Paciente atualizado com sucesso.');
    }

    public function destroy(Patient $patient)
    {
        $this->patientService->delete($patient);

        return redirect()
            ->route('patients.index')
            ->with('success', 'Paciente removido com sucesso.');
    }
}
