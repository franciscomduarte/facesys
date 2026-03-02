<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Plano;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(
        private SubscriptionService $subscriptionService,
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Subscription::class);

        $query = Subscription::with(['empresa', 'plano']);

        if ($search = $request->input('search')) {
            $query->whereHas('empresa', function ($q) use ($search) {
                $q->where('nome_fantasia', 'ilike', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $subscriptions = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $this->authorize('create', Subscription::class);

        $empresas = Empresa::orderBy('nome_fantasia')->get();
        $planos = Plano::ativos()->ordenados()->get();

        return view('subscriptions.create', compact('empresas', 'planos'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Subscription::class);

        $data = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'plano_id' => 'required|exists:planos,id',
            'periodicidade' => 'required|in:mensal,anual',
        ]);

        $empresa = Empresa::findOrFail($data['empresa_id']);
        $plano = Plano::findOrFail($data['plano_id']);

        $subscription = $this->subscriptionService->criar($empresa, $plano, $data['periodicidade']);

        return redirect()
            ->route('subscriptions.show', $subscription)
            ->with('success', 'Subscription criada com sucesso.');
    }

    public function show(Subscription $subscription)
    {
        $this->authorize('view', $subscription);

        $subscription->load(['empresa', 'plano']);

        return view('subscriptions.show', compact('subscription'));
    }

    public function cancelar(Subscription $subscription, Request $request)
    {
        $this->authorize('cancel', $subscription);

        $motivo = $request->input('motivo');
        $this->subscriptionService->cancelar($subscription, $motivo);

        return redirect()
            ->back()
            ->with('success', 'Subscription cancelada com sucesso.');
    }

    public function reativar(Subscription $subscription)
    {
        $this->authorize('update', $subscription);

        $this->subscriptionService->reativar($subscription);

        return redirect()
            ->back()
            ->with('success', 'Subscription reativada com sucesso.');
    }

    public function alterarPlano(Request $request, Subscription $subscription)
    {
        $this->authorize('update', $subscription);

        $data = $request->validate([
            'plano_id' => 'required|exists:planos,id',
        ]);

        $novoPlano = Plano::findOrFail($data['plano_id']);
        $this->subscriptionService->alterarPlano($subscription, $novoPlano);

        return redirect()
            ->back()
            ->with('success', 'Plano alterado com sucesso.');
    }
}
