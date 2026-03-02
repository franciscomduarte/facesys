<?php

return [
    'horario_inicio' => env('AGENDA_HORARIO_INICIO', '08:00'),
    'horario_fim' => env('AGENDA_HORARIO_FIM', '18:00'),
    'duracao_slot_minutos' => env('AGENDA_SLOT_DURACAO', 30),
    'enviar_email_confirmacao' => env('AGENDA_EMAIL_CONFIRMACAO', true),
    'enviar_email_cancelamento' => env('AGENDA_EMAIL_CANCELAMENTO', true),
];
