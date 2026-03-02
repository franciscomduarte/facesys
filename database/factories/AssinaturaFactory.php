<?php

namespace Database\Factories;

use App\Models\Assinatura;
use App\Models\DocumentoAssinavel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AssinaturaFactory extends Factory
{
    protected $model = Assinatura::class;

    public function definition(): array
    {
        return [
            'documento_assinavel_id' => DocumentoAssinavel::factory(),
            'tipo_assinatura' => 'paciente',
            'nome_assinante' => fake()->name(),
            'documento_assinante' => fake()->numerify('###########'),
            'ip' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'data_assinatura' => fake()->dateTimeBetween('-30 days', 'now'),
            'hash_assinatura' => hash('sha256', Str::random(64)),
            'assinatura_imagem' => null,
        ];
    }

    public function paciente(): static
    {
        return $this->state(fn() => [
            'tipo_assinatura' => 'paciente',
        ]);
    }

    public function profissional(): static
    {
        return $this->state(fn() => [
            'tipo_assinatura' => 'profissional',
        ]);
    }
}
