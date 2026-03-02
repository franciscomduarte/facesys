<?php

namespace App\Http\Controllers;

use App\Models\Procedimento;
use App\Services\ProcedimentoService;
use App\Http\Requests\StoreProcedimentoRequest;
use App\Http\Requests\UpdateProcedimentoRequest;
use Illuminate\Http\Request;

class ProcedimentoController extends Controller
{
    public function __construct(
        private ProcedimentoService $procedimentoService
    ) {}

    public function index(Request $request)
    {
        $procedimentos = $this->procedimentoService->list(
            filters: $request->only('search', 'categoria', 'ativo'),
            perPage: 15
        );

        return view('procedimentos.index', compact('procedimentos'));
    }

    public function create()
    {
        return view('procedimentos.create');
    }

    public function store(StoreProcedimentoRequest $request)
    {
        $data = $request->validated();
        $data['ativo'] = $request->boolean('ativo', true);

        $procedimento = $this->procedimentoService->create($data);

        return redirect()
            ->route('procedimentos.show', $procedimento)
            ->with('success', 'Procedimento cadastrado com sucesso.');
    }

    public function show(Procedimento $procedimento)
    {
        $procedimento->load(['treatmentSessions' => function ($query) {
            $query->with('patient')->orderByDesc('data_sessao')->limit(10);
        }]);

        return view('procedimentos.show', compact('procedimento'));
    }

    public function edit(Procedimento $procedimento)
    {
        return view('procedimentos.edit', compact('procedimento'));
    }

    public function update(UpdateProcedimentoRequest $request, Procedimento $procedimento)
    {
        $data = $request->validated();
        $data['ativo'] = $request->boolean('ativo', true);

        $this->procedimentoService->update($procedimento, $data);

        return redirect()
            ->route('procedimentos.show', $procedimento)
            ->with('success', 'Procedimento atualizado com sucesso.');
    }

    public function destroy(Procedimento $procedimento)
    {
        $this->procedimentoService->delete($procedimento);

        return redirect()
            ->route('procedimentos.index')
            ->with('success', 'Procedimento removido com sucesso.');
    }

    public function toggleAtivo(Procedimento $procedimento)
    {
        $this->procedimentoService->toggleAtivo($procedimento);
        $status = $procedimento->fresh()->ativo ? 'ativado' : 'desativado';

        return redirect()
            ->back()
            ->with('success', "Procedimento {$status} com sucesso.");
    }
}
