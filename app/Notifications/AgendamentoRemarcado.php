<?php

namespace App\Notifications;

use App\Models\Agendamento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AgendamentoRemarcado extends Notification implements ShouldQueue
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
        $procedimentos = $ag->procedimentos->pluck('nome')->implode(', ');

        return (new MailMessage)
            ->subject('Agendamento Remarcado - LC Estetica')
            ->greeting("Ola, {$ag->patient->nome_completo}!")
            ->line('Seu agendamento foi remarcado para uma nova data/horario.')
            ->line("**Nova Data:** {$ag->data_agendamento->format('d/m/Y')}")
            ->line("**Novo Horario:** {$ag->hora_inicio} - {$ag->hora_fim}")
            ->line("**Profissional:** {$ag->profissional->name}")
            ->line("**Procedimentos:** {$procedimentos}")
            ->line('Em caso de duvidas, entre em contato conosco.')
            ->salutation('Atenciosamente, LC Estetica');
    }
}
