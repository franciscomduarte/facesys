<?php

namespace App\Services\Gateways;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Empresa;
use App\Models\Subscription;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AsaasGateway implements PaymentGatewayInterface
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('billing.asaas.sandbox')
            ? 'https://sandbox.asaas.com/api/v3'
            : 'https://api.asaas.com/v3';
        $this->apiKey = config('billing.asaas.api_key');
    }

    public function createCustomer(Empresa $empresa): string
    {
        $response = $this->request('POST', '/customers', [
            'name' => $empresa->razao_social ?: $empresa->nome_fantasia,
            'cpfCnpj' => preg_replace('/\D/', '', $empresa->cnpj),
            'email' => $empresa->email,
            'phone' => $empresa->telefone,
            'externalReference' => (string) $empresa->id,
        ]);

        return $response['id'];
    }

    public function createSubscription(Subscription $subscription): string
    {
        $plano = $subscription->plano;
        $empresa = $subscription->empresa;

        $customerId = $subscription->gateway_customer_id;
        if (!$customerId) {
            $customerId = $this->createCustomer($empresa);
        }

        $cycle = $subscription->periodicidade === 'anual' ? 'YEARLY' : 'MONTHLY';

        $response = $this->request('POST', '/subscriptions', [
            'customer' => $customerId,
            'billingType' => 'UNDEFINED', // Permite PIX e cartao
            'value' => (float) $subscription->valor_atual,
            'cycle' => $cycle,
            'description' => "SkinFlow - Plano {$plano->nome}",
            'externalReference' => (string) $subscription->id,
        ]);

        return $response['id'];
    }

    public function cancelSubscription(string $gatewaySubscriptionId): bool
    {
        $this->request('DELETE', "/subscriptions/{$gatewaySubscriptionId}");
        return true;
    }

    public function getSubscriptionStatus(string $gatewaySubscriptionId): string
    {
        $response = $this->request('GET', "/subscriptions/{$gatewaySubscriptionId}");

        return match ($response['status'] ?? 'INACTIVE') {
            'ACTIVE' => 'ativa',
            'INACTIVE', 'EXPIRED' => 'expirada',
            default => 'inadimplente',
        };
    }

    public function createPixPayment(Subscription $subscription, float $amount): array
    {
        $customerId = $subscription->gateway_customer_id;

        $response = $this->request('POST', '/payments', [
            'customer' => $customerId,
            'billingType' => 'PIX',
            'value' => $amount,
            'dueDate' => now()->addDays(3)->format('Y-m-d'),
            'description' => "SkinFlow - Plano {$subscription->plano->nome}",
            'externalReference' => (string) $subscription->id,
        ]);

        $paymentId = $response['id'];

        // Buscar QR Code PIX
        $pixData = $this->request('GET', "/payments/{$paymentId}/pixQrCode");

        return [
            'gateway_payment_id' => $paymentId,
            'pix_qr_code' => $pixData['encodedImage'] ?? null,
            'pix_copy_paste' => $pixData['payload'] ?? null,
            'expiration_date' => $pixData['expirationDate'] ?? null,
            'invoice_url' => $response['invoiceUrl'] ?? null,
        ];
    }

    public function createCardPayment(Subscription $subscription, float $amount, array $cardData): array
    {
        $customerId = $subscription->gateway_customer_id;

        $payload = [
            'customer' => $customerId,
            'billingType' => 'CREDIT_CARD',
            'value' => $amount,
            'dueDate' => now()->format('Y-m-d'),
            'description' => "SkinFlow - Plano {$subscription->plano->nome}",
            'externalReference' => (string) $subscription->id,
            'creditCard' => [
                'holderName' => $cardData['holder_name'],
                'number' => $cardData['number'],
                'expiryMonth' => $cardData['expiry_month'],
                'expiryYear' => $cardData['expiry_year'],
                'ccv' => $cardData['ccv'],
            ],
            'creditCardHolderInfo' => [
                'name' => $cardData['holder_name'],
                'cpfCnpj' => preg_replace('/\D/', '', $cardData['cpf_cnpj']),
                'email' => $cardData['email'],
                'phone' => $cardData['phone'] ?? null,
                'postalCode' => $cardData['postal_code'] ?? null,
                'addressNumber' => $cardData['address_number'] ?? null,
            ],
        ];

        $response = $this->request('POST', '/payments', $payload);

        return [
            'gateway_payment_id' => $response['id'],
            'status' => $this->mapPaymentStatus($response['status'] ?? 'PENDING'),
            'invoice_url' => $response['invoiceUrl'] ?? null,
        ];
    }

    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $webhookToken = config('billing.asaas.webhook_token');

        if (!$webhookToken) {
            return true; // Sem token configurado, aceita tudo (dev)
        }

        return $signature === $webhookToken;
    }

    // Helpers

    private function request(string $method, string $endpoint, array $data = []): array
    {
        $http = Http::withHeaders([
            'access_token' => $this->apiKey,
            'Content-Type' => 'application/json',
        ]);

        $url = $this->baseUrl . $endpoint;

        $response = match (strtoupper($method)) {
            'GET' => $http->get($url, $data),
            'POST' => $http->post($url, $data),
            'PUT' => $http->put($url, $data),
            'DELETE' => $http->delete($url),
        };

        if ($response->failed()) {
            Log::error('Asaas API error', [
                'method' => $method,
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            throw new \RuntimeException(
                'Erro na comunicacao com o gateway de pagamento: ' . ($response->json('errors.0.description') ?? $response->body())
            );
        }

        return $response->json();
    }

    private function mapPaymentStatus(string $asaasStatus): string
    {
        return match ($asaasStatus) {
            'CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH' => 'paid',
            'PENDING', 'AWAITING_RISK_ANALYSIS' => 'pending',
            'REFUNDED', 'REFUND_REQUESTED', 'CHARGEBACK_REQUESTED', 'CHARGEBACK_DISPUTE' => 'refunded',
            default => 'failed',
        };
    }
}
