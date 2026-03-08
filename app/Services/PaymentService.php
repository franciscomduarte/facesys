<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct(
        private PaymentGatewayInterface $gateway,
        private SubscriptionService $subscriptionService,
    ) {}

    public function createPixPayment(Subscription $subscription): array
    {
        return DB::transaction(function () use ($subscription) {
            $amount = (float) $subscription->valor_atual;

            $gatewayResult = $this->gateway->createPixPayment($subscription, $amount);

            $payment = Payment::create([
                'empresa_id' => $subscription->empresa_id,
                'subscription_id' => $subscription->id,
                'amount' => $amount,
                'method' => 'pix',
                'status' => 'pending',
                'gateway' => config('billing.gateway'),
                'gateway_payment_id' => $gatewayResult['gateway_payment_id'],
                'gateway_data' => $gatewayResult,
            ]);

            Invoice::create([
                'subscription_id' => $subscription->id,
                'payment_id' => $payment->id,
                'amount' => $amount,
                'status' => 'pending',
                'due_date' => now()->addDays(3),
                'gateway_invoice_id' => $gatewayResult['gateway_payment_id'],
                'invoice_url' => $gatewayResult['invoice_url'] ?? null,
            ]);

            return [
                'payment' => $payment,
                'pix_qr_code' => $gatewayResult['pix_qr_code'] ?? null,
                'pix_copy_paste' => $gatewayResult['pix_copy_paste'] ?? null,
                'expiration_date' => $gatewayResult['expiration_date'] ?? null,
            ];
        });
    }

    public function createCardPayment(Subscription $subscription, array $cardData): Payment
    {
        return DB::transaction(function () use ($subscription, $cardData) {
            $amount = (float) $subscription->valor_atual;

            $gatewayResult = $this->gateway->createCardPayment($subscription, $amount, $cardData);

            $payment = Payment::create([
                'empresa_id' => $subscription->empresa_id,
                'subscription_id' => $subscription->id,
                'amount' => $amount,
                'method' => 'credit_card',
                'status' => $gatewayResult['status'] ?? 'pending',
                'gateway' => config('billing.gateway'),
                'gateway_payment_id' => $gatewayResult['gateway_payment_id'],
                'paid_at' => ($gatewayResult['status'] ?? '') === 'paid' ? now() : null,
            ]);

            $invoiceStatus = $payment->isPaid() ? 'paid' : 'pending';

            Invoice::create([
                'subscription_id' => $subscription->id,
                'payment_id' => $payment->id,
                'amount' => $amount,
                'status' => $invoiceStatus,
                'due_date' => now(),
                'paid_at' => $payment->isPaid() ? now() : null,
                'gateway_invoice_id' => $gatewayResult['gateway_payment_id'],
                'invoice_url' => $gatewayResult['invoice_url'] ?? null,
            ]);

            if ($payment->isPaid()) {
                $this->subscriptionService->marcarPaga($subscription);
            }

            return $payment;
        });
    }

    public function handleWebhookPayment(string $gatewayPaymentId, string $event, array $data): void
    {
        $payment = Payment::where('gateway_payment_id', $gatewayPaymentId)->first();

        if (!$payment) {
            Log::warning('Webhook: payment not found', ['gateway_payment_id' => $gatewayPaymentId]);
            return;
        }

        // Idempotencia: nao reprocessar pagamentos ja confirmados
        if ($payment->isPaid() && in_array($event, ['PAYMENT_CONFIRMED', 'PAYMENT_RECEIVED'])) {
            return;
        }

        match ($event) {
            'PAYMENT_CONFIRMED', 'PAYMENT_RECEIVED' => $this->confirmPayment($payment),
            'PAYMENT_OVERDUE' => $this->overduePayment($payment),
            'PAYMENT_REFUNDED', 'PAYMENT_CHARGEBACK' => $this->refundPayment($payment),
            default => Log::info('Webhook: unhandled event', ['event' => $event]),
        };
    }

    private function confirmPayment(Payment $payment): void
    {
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $payment->invoice?->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $this->subscriptionService->marcarPaga($payment->subscription);

        Log::info('Payment confirmed', ['payment_id' => $payment->id]);
    }

    private function overduePayment(Payment $payment): void
    {
        $payment->update(['status' => 'failed']);
        $payment->invoice?->update(['status' => 'overdue']);

        $this->subscriptionService->marcarInadimplente($payment->subscription);

        Log::warning('Payment overdue', ['payment_id' => $payment->id]);
    }

    private function refundPayment(Payment $payment): void
    {
        $payment->update(['status' => 'refunded']);

        Log::info('Payment refunded', ['payment_id' => $payment->id]);
    }
}
