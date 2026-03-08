<?php

return [
    'gateway' => env('BILLING_GATEWAY', 'manual'),
    'trial_dias_padrao' => env('BILLING_TRIAL_DIAS', 14),
    'dias_gracia_inadimplencia' => env('BILLING_GRACIA_DIAS', 7),

    'asaas' => [
        'api_key' => env('ASAAS_API_KEY'),
        'webhook_token' => env('ASAAS_WEBHOOK_TOKEN'),
        'sandbox' => env('ASAAS_SANDBOX', true),
    ],
];
