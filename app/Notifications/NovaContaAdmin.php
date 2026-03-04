<?php

namespace App\Notifications;

use App\Models\Empresa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NovaContaAdmin extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Empresa $empresa,
        public string $senhaTemporaria
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Suas credenciais de acesso - ' . config('app.name'))
            ->greeting("Ola, {$notifiable->name}!")
            ->line("Sua conta de administrador para a empresa **{$this->empresa->nome_fantasia}** foi criada.")
            ->line('Seguem suas credenciais de acesso:')
            ->line("**E-mail:** {$notifiable->email}")
            ->line("**Senha temporaria:** {$this->senhaTemporaria}")
            ->action('Acessar o Sistema', url('/login'))
            ->line('Por seguranca, voce sera solicitado a trocar sua senha no primeiro acesso.')
            ->salutation('Atenciosamente, ' . config('app.name'));
    }
}
