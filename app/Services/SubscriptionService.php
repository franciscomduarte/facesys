<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Empresa;
use App\Models\Plano;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    public function __construct(
        private PaymentGatewayInterface $gateway,
    ) {}

    public function getByEmpresa(Empresa $empresa): ?Subscription
    {
        return $empresa->subscription;
    }

    public function criar(Empresa $empresa, Plano $plano, string $periodicidade = 'mensal'): Subscription
    {
        return DB::transaction(function () use ($empresa, $plano, $periodicidade) {
            // Cancelar subscription anterior se existir
            $existente = $empresa->subscription;
            if ($existente) {
                $existente->update([
                    'status' => 'cancelada',
                    'data_fim' => now(),
                ]);
            }

            $valor = $periodicidade === 'anual' && $plano->valor_anual
                ? $plano->valor_anual
                : $plano->valor_mensal;

            $data = [
                'empresa_id' => $empresa->id,
                'plano_id' => $plano->id,
                'periodicidade' => $periodicidade,
                'valor_atual' => $valor,
                'data_inicio' => now()->toDateString(),
                'gateway' => config('billing.gateway'),
            ];

            if ($plano->temTrial()) {
                $data['status'] = 'trial';
                $data['trial_termina_em'] = now()->addDays($plano->trial_dias)->toDateString();
                $data['proxima_cobranca'] = now()->addDays($plano->trial_dias)->toDateString();
            } else {
                $data['status'] = 'ativa';
                $data['proxima_cobranca'] = $periodicidade === 'anual'
                    ? now()->addYear()->toDateString()
                    : now()->addMonth()->toDateString();
            }

            $subscription = Subscription::create($data);

            // Criar no gateway
            $gatewayCustomerId = $this->gateway->createCustomer($empresa);
            $gatewaySubId = $this->gateway->createSubscription($subscription);

            $subscription->update([
                'gateway_customer_id' => $gatewayCustomerId,
                'gateway_subscription_id' => $gatewaySubId,
            ]);

            return $subscription->refresh()->load(['plano', 'empresa']);
        });
    }

    public function cancelar(Subscription $subscription, ?string $motivo = null): Subscription
    {
        if ($subscription->gateway_subscription_id) {
            $this->gateway->cancelSubscription($subscription->gateway_subscription_id);
        }

        $subscription->update([
            'status' => 'cancelada',
            'data_fim' => now()->toDateString(),
            'observacoes' => $motivo,
        ]);

        return $subscription->refresh();
    }

    public function reativar(Subscription $subscription): Subscription
    {
        $subscription->update([
            'status' => 'ativa',
            'data_fim' => null,
            'proxima_cobranca' => $subscription->periodicidade === 'anual'
                ? now()->addYear()->toDateString()
                : now()->addMonth()->toDateString(),
        ]);

        return $subscription->refresh();
    }

    public function alterarPlano(Subscription $subscription, Plano $novoPlano): Subscription
    {
        $valor = $subscription->periodicidade === 'anual' && $novoPlano->valor_anual
            ? $novoPlano->valor_anual
            : $novoPlano->valor_mensal;

        $subscription->update([
            'plano_id' => $novoPlano->id,
            'valor_atual' => $valor,
        ]);

        return $subscription->refresh()->load(['plano']);
    }

    public function marcarInadimplente(Subscription $subscription): Subscription
    {
        $subscription->update(['status' => 'inadimplente']);

        return $subscription->refresh();
    }

    public function marcarPaga(Subscription $subscription): Subscription
    {
        $subscription->update([
            'status' => 'ativa',
            'proxima_cobranca' => $subscription->periodicidade === 'anual'
                ? now()->addYear()->toDateString()
                : now()->addMonth()->toDateString(),
        ]);

        return $subscription->refresh();
    }

    public function verificarLimite(Empresa $empresa, string $recurso): bool
    {
        $subscription = $this->getByEmpresa($empresa);
        if (!$subscription || !$subscription->plano) {
            return false;
        }

        $campo = "limite_{$recurso}";
        $limite = $subscription->plano->{$campo} ?? 0;

        if ($limite === -1) {
            return true;
        }

        $atual = $this->contarRecurso($empresa, $recurso);

        return $atual < $limite;
    }

    public function contarRecurso(Empresa $empresa, string $recurso): int
    {
        return match ($recurso) {
            'usuarios' => $empresa->users()->count(),
            'pacientes' => $empresa->patients()->count(),
            'agendamentos_mes' => $empresa->agendamentos()
                ->whereMonth('data_agendamento', now()->month)
                ->whereYear('data_agendamento', now()->year)
                ->count(),
            default => 0,
        };
    }
}
