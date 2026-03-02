<?php

namespace App\Services;

use App\Models\Agendamento;
use App\Notifications\AgendamentoCancelado;
use App\Notifications\AgendamentoConfirmado;
use App\Notifications\AgendamentoCriado;
use App\Notifications\AgendamentoRemarcado;
use Illuminate\Support\Facades\Notification;

class NotificacaoAgendamentoService
{
    public function notificarNovo(Agendamento $agendamento): void
    {
        if (!config('agenda.enviar_email_confirmacao')) {
            return;
        }

        $email = $agendamento->patient->email;
        if (!$email) {
            return;
        }

        Notification::route('mail', $email)
            ->notify(new AgendamentoCriado($agendamento));
    }

    public function notificarConfirmacao(Agendamento $agendamento): void
    {
        if (!config('agenda.enviar_email_confirmacao')) {
            return;
        }

        $email = $agendamento->patient->email;
        if (!$email) {
            return;
        }

        Notification::route('mail', $email)
            ->notify(new AgendamentoConfirmado($agendamento));
    }

    public function notificarCancelamento(Agendamento $agendamento): void
    {
        if (!config('agenda.enviar_email_cancelamento')) {
            return;
        }

        $email = $agendamento->patient->email;
        if (!$email) {
            return;
        }

        Notification::route('mail', $email)
            ->notify(new AgendamentoCancelado($agendamento));
    }

    public function notificarRemarcacao(Agendamento $agendamento): void
    {
        if (!config('agenda.enviar_email_confirmacao')) {
            return;
        }

        $email = $agendamento->patient->email;
        if (!$email) {
            return;
        }

        Notification::route('mail', $email)
            ->notify(new AgendamentoRemarcado($agendamento));
    }
}
