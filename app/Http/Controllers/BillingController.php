<?php

namespace App\Http\Controllers;

use App\Services\SubscriptionService;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    public function __construct(
        private SubscriptionService $subscriptionService,
    ) {}

    public function show()
    {
        $user = Auth::user();
        $empresa = $user->empresa;

        if (!$empresa) {
            return redirect()->route('dashboard')->with('error', 'Sua conta nao esta vinculada a uma empresa.');
        }

        $subscription = $this->subscriptionService->getByEmpresa($empresa);
        $plano = $subscription?->plano;

        $uso = [];
        if ($subscription && $plano) {
            $recursos = ['usuarios', 'pacientes', 'agendamentos_mes'];
            foreach ($recursos as $recurso) {
                $campo = "limite_{$recurso}";
                $limite = $plano->{$campo};
                $atual = $this->subscriptionService->contarRecurso($empresa, $recurso);

                $uso[$recurso] = [
                    'atual' => $atual,
                    'limite' => $limite,
                    'ilimitado' => $limite === -1,
                    'percentual' => $limite > 0 ? min(100, round(($atual / $limite) * 100)) : 0,
                ];
            }
        }

        return view('billing.show', compact('subscription', 'plano', 'uso'));
    }
}
