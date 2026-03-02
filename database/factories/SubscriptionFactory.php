<?php

namespace Database\Factories;

use App\Models\Empresa;
use App\Models\Plano;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        return [
            'empresa_id' => Empresa::factory(),
            'plano_id' => Plano::factory(),
            'status' => 'ativa',
            'periodicidade' => 'mensal',
            'data_inicio' => now(),
            'proxima_cobranca' => now()->addMonth(),
            'valor_atual' => 199.00,
        ];
    }

    public function trial(): static
    {
        return $this->state(fn () => [
            'status' => 'trial',
            'trial_termina_em' => now()->addDays(14),
            'proxima_cobranca' => null,
        ]);
    }

    public function inadimplente(): static
    {
        return $this->state(fn () => ['status' => 'inadimplente']);
    }

    public function cancelada(): static
    {
        return $this->state(fn () => [
            'status' => 'cancelada',
            'data_fim' => now(),
            'proxima_cobranca' => null,
        ]);
    }
}
