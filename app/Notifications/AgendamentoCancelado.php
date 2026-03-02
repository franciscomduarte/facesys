<?php

namespace App\Notifications;

use App\Models\Agendamento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AgendamentoCancelado extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Agendamento $agendamento
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $ag = $this->agendamento;

        $message = (new MailMessage)
            ->subject('Agendamento Cancelado - LC Estetica')
            ->greeting("Ola, {$ag->patient->nome_completo}!")
            ->line('Informamos que seu agendamento foi cancelado.')
            ->line("**Data:** {$ag->data_agendamento->format('d/m/Y')}")
            ->line("**Horario:** {$ag->hora_inicio} - {$ag->hora_fim}");

        if ($ag->motivo_cancelamento) {
            $message->line("**Motivo:** {$ag->motivo_cancelamento}");
        }

        $message->line('Para reagendar, entre em contato conosco.')
            ->salutation('Atenciosamente, LC Estetica');

        return $message;
    }
}
