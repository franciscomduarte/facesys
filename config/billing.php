<?php

return [
    'gateway' => env('BILLING_GATEWAY', 'manual'),
    'trial_dias_padrao' => env('BILLING_TRIAL_DIAS', 14),
    'dias_gracia_inadimplencia' => env('BILLING_GRACIA_DIAS', 7),
];
