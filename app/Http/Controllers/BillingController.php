<?php

namespace App\Http\Controllers;

use App\Models\Plano;
use App\Services\PaymentService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    public function __construct(
        private SubscriptionService $subscriptionService,
        private PaymentService $paymentService,
    ) {}

    /**
     * Pagina "Meu Plano" - billing dashboard
     */
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
            foreach (['usuarios', 'pacientes', 'agendamentos_mes'] as $recurso) {
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

        $payments = $subscription?->payments()->latest()->limit(10)->get() ?? collect();
        $planos = Plano::ativos()->ordenados()->get();

        return view('billing.show', compact('subscription', 'plano', 'uso', 'payments', 'planos'));
    }

    /**
     * Pagina de planos publicos
     */
    public function plans()
    {
        $planos = Plano::ativos()->ordenados()->get();

        return view('billing.plans', compact('planos'));
    }

    /**
     * Pagina de checkout
     */
    public function checkout(Plano $plano)
    {
        $user = Auth::user();
        $empresa = $user->empresa;

        if (!$empresa) {
            return redirect()->route('dashboard')->with('error', 'Sua conta nao esta vinculada a uma empresa.');
        }

        return view('billing.checkout', compact('plano', 'empresa'));
    }

    /**
     * Processar pagamento via PIX
     */
    public function createPixPayment(Request $request)
    {
        $request->validate(['plano_id' => 'required|exists:planos,id']);

        $user = Auth::user();
        $empresa = $user->empresa;
        $plano = Plano::findOrFail($request->plano_id);

        // Criar ou atualizar subscription
        $subscription = $this->subscriptionService->getByEmpresa($empresa);
        if (!$subscription || $subscription->isCancelada() || $subscription->isExpirada()) {
            $subscription = $this->subscriptionService->criar($empresa, $plano);
        } elseif ($subscription->plano_id !== $plano->id) {
            $subscription = $this->subscriptionService->alterarPlano($subscription, $plano);
        }

        $result = $this->paymentService->createPixPayment($subscription);

        return view('billing.pix', [
            'payment' => $result['payment'],
            'pixQrCode' => $result['pix_qr_code'],
            'pixCopyPaste' => $result['pix_copy_paste'],
            'expirationDate' => $result['expiration_date'],
            'plano' => $plano,
        ]);
    }

    /**
     * Processar pagamento via cartao de credito
     */
    public function createCardPayment(Request $request)
    {
        $request->validate([
            'plano_id' => 'required|exists:planos,id',
            'holder_name' => 'required|string|max:100',
            'number' => 'required|string|min:13|max:19',
            'expiry_month' => 'required|string|size:2',
            'expiry_year' => 'required|string|size:4',
            'ccv' => 'required|string|min:3|max:4',
            'cpf_cnpj' => 'required|string',
            'email' => 'required|email',
        ]);

        $user = Auth::user();
        $empresa = $user->empresa;
        $plano = Plano::findOrFail($request->plano_id);

        $subscription = $this->subscriptionService->getByEmpresa($empresa);
        if (!$subscription || $subscription->isCancelada() || $subscription->isExpirada()) {
            $subscription = $this->subscriptionService->criar($empresa, $plano);
        } elseif ($subscription->plano_id !== $plano->id) {
            $subscription = $this->subscriptionService->alterarPlano($subscription, $plano);
        }

        try {
            $payment = $this->paymentService->createCardPayment($subscription, $request->only([
                'holder_name', 'number', 'expiry_month', 'expiry_year', 'ccv',
                'cpf_cnpj', 'email', 'phone', 'postal_code', 'address_number',
            ]));

            if ($payment->isPaid()) {
                return redirect()->route('billing.show')->with('success', 'Pagamento confirmado! Seu plano foi ativado.');
            }

            return redirect()->route('billing.show')->with('info', 'Pagamento em processamento.');
        } catch (\RuntimeException $e) {
            return back()->with('error', 'Erro no pagamento: ' . $e->getMessage());
        }
    }

    /**
     * Cancelar assinatura
     */
    public function cancelSubscription(Request $request)
    {
        $user = Auth::user();
        $empresa = $user->empresa;
        $subscription = $this->subscriptionService->getByEmpresa($empresa);

        if (!$subscription) {
            return back()->with('error', 'Nenhuma assinatura encontrada.');
        }

        $this->subscriptionService->cancelar($subscription, $request->input('motivo'));

        return redirect()->route('billing.show')->with('success', 'Assinatura cancelada. Acesso permanece ate o fim do periodo pago.');
    }

    /**
     * Trocar de plano
     */
    public function changePlan(Request $request)
    {
        $request->validate(['plano_id' => 'required|exists:planos,id']);

        $user = Auth::user();
        $empresa = $user->empresa;
        $subscription = $this->subscriptionService->getByEmpresa($empresa);
        $novoPlano = Plano::findOrFail($request->plano_id);

        if (!$subscription) {
            return redirect()->route('billing.checkout', $novoPlano);
        }

        $this->subscriptionService->alterarPlano($subscription, $novoPlano);

        return redirect()->route('billing.show')->with('success', "Plano alterado para {$novoPlano->nome}.");
    }
}
