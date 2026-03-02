<?php

namespace Database\Factories;

use App\Models\Contrato;
use App\Models\Patient;
use App\Models\TemplateContrato;
use App\Models\TreatmentSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ContratoFactory extends Factory
{
    protected $model = Contrato::class;

    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'treatment_session_id' => TreatmentSession::factory(),
            'profissional_id' => User::factory(),
            'template_contrato_id' => TemplateContrato::factory(),
            'status' => 'rascunho',
            'conteudo_renderizado' => '<p>Conteudo do contrato de teste.</p>',
            'hash_contrato' => hash('sha256', Str::random(64)),
            'valor_total' => fake()->randomFloat(2, 200, 5000),
            'observacoes' => fake()->optional()->sentence(),
            'data_geracao' => null,
        ];
    }

    public function gerado(): static
    {
        return $this->state(fn() => [
            'status' => 'gerado',
            'data_geracao' => now(),
        ]);
    }

    public function assinado(): static
    {
        return $this->state(fn() => [
            'status' => 'assinado',
            'data_geracao' => now()->subDays(fake()->numberBetween(1, 10)),
        ]);
    }
}
