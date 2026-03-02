<?php

namespace App\Notifications;

use App\Models\Agendamento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AgendamentoConfirmado extends Notification implements ShouldQueue
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

        return (new MailMessage)
            ->subject('Agendamento Confirmado - LC Estetica')
            ->greeting("Ola, {$ag->patient->nome_completo}!")
            ->line('Seu agendamento foi confirmado.')
            ->line("**Data:** {$ag->data_agendamento->format('d/m/Y')}")
            ->line("**Horario:** {$ag->hora_inicio} - {$ag->hora_fim}")
            ->line("**Profissional:** {$ag->profissional->name}")
            ->line('Aguardamos voce na data e horario marcados.')
            ->salutation('Atenciosamente, LC Estetica');
    }
}
