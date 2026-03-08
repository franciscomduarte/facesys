<?php

namespace App\Http\Controllers;

use App\Contracts\PaymentGatewayInterface;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private PaymentGatewayInterface $gateway,
    ) {}

    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('asaas-access-token', '');

        // Verificar assinatura do webhook
        if (!$this->gateway->verifyWebhookSignature($payload, $signature)) {
            Log::warning('Webhook: invalid signature');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $event = $request->input('event');
        $paymentData = $request->input('payment', []);
        $gatewayPaymentId = $paymentData['id'] ?? null;

        if (!$event || !$gatewayPaymentId) {
            return response()->json(['error' => 'Missing data'], 400);
        }

        Log::info('Webhook received', [
            'event' => $event,
            'payment_id' => $gatewayPaymentId,
        ]);

        $this->paymentService->handleWebhookPayment($gatewayPaymentId, $event, $paymentData);

        return response()->json(['status' => 'ok']);
    }
}
