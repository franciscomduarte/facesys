<?php

namespace App\Http\Controllers;

use App\Models\Plano;
use App\Services\PlanoService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanoController extends Controller
{
    public function __construct(
        private PlanoService $planoService,
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Plano::class);

        $planos = $this->planoService->list(
            filters: $request->only('search', 'ativo'),
            perPage: 15
        );

        return view('planos.index', compact('planos'));
    }

    public function create()
    {
        $this->authorize('create', Plano::class);

        return view('planos.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Plano::class);

        $data = $request->validate([
            'nome' => 'required|string|max:100',
            'descricao' => 'nullable|string',
            'valor_mensal' => 'required|numeric|min:0',
            'valor_anual' => 'nullable|numeric|min:0',
            'periodicidade_padrao' => 'required|in:mensal,anual',
            'limite_usuarios' => 'required|integer|min:-1',
            'limite_pacientes' => 'required|integer|min:-1',
            'limite_agendamentos_mes' => 'required|integer|min:-1',
            'trial_dias' => 'required|integer|min:0',
            'ordem' => 'required|integer|min:0',
        ]);

        $data['slug'] = Str::slug($data['nome']);
        $data['ativo'] = $request->boolean('ativo', true);
        $data['funcionalidades'] = $this->parseFuncionalidades($request);

        $plano = $this->planoService->create($data);

        return redirect()
            ->route('planos.show', $plano)
            ->with('success', 'Plano cadastrado com sucesso.');
    }

    public function show(Plano $plano)
    {
        $this->authorize('view', $plano);

        $plano->loadCount('subscriptions');

        return view('planos.show', compact('plano'));
    }

    public function edit(Plano $plano)
    {
        $this->authorize('update', $plano);

        return view('planos.edit', compact('plano'));
    }

    public function update(Request $request, Plano $plano)
    {
        $this->authorize('update', $plano);

        $data = $request->validate([
            'nome' => 'required|string|max:100',
            'descricao' => 'nullable|string',
            'valor_mensal' => 'required|numeric|min:0',
            'valor_anual' => 'nullable|numeric|min:0',
            'periodicidade_padrao' => 'required|in:mensal,anual',
            'limite_usuarios' => 'required|integer|min:-1',
            'limite_pacientes' => 'required|integer|min:-1',
            'limite_agendamentos_mes' => 'required|integer|min:-1',
            'trial_dias' => 'required|integer|min:0',
            'ordem' => 'required|integer|min:0',
        ]);

        $data['slug'] = Str::slug($data['nome']);
        $data['ativo'] = $request->boolean('ativo', true);
        $data['funcionalidades'] = $this->parseFuncionalidades($request);

        $this->planoService->update($plano, $data);

        return redirect()
            ->route('planos.show', $plano)
            ->with('success', 'Plano atualizado com sucesso.');
    }

    public function destroy(Plano $plano)
    {
        $this->authorize('delete', $plano);

        $this->planoService->delete($plano);

        return redirect()
            ->route('planos.index')
            ->with('success', 'Plano removido com sucesso.');
    }

    public function toggleAtivo(Plano $plano)
    {
        $this->authorize('toggleAtivo', $plano);

        $this->planoService->toggleAtivo($plano);
        $status = $plano->fresh()->ativo ? 'ativado' : 'desativado';

        return redirect()
            ->back()
            ->with('success', "Plano {$status} com sucesso.");
    }

    private function parseFuncionalidades(Request $request): array
    {
        $features = ['agendamentos', 'prescricoes', 'contratos', 'fotos_clinicas', 'assinatura_digital', 'relatorios'];
        $result = [];

        foreach ($features as $feature) {
            $result[$feature] = $request->boolean("func_{$feature}");
        }

        return $result;
    }
}
