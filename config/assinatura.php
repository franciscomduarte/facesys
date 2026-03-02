<?php

return [
    'token_expiration_hours' => env('ASSINATURA_TOKEN_EXPIRATION', 48),
    'signature_disk' => env('ASSINATURA_DISK', 'local'),
    'signature_directory' => 'assinaturas',
];
