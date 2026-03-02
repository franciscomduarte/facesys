<?php

namespace Database\Factories;

use App\Models\TemplateContrato;
use Illuminate\Database\Eloquent\Factories\Factory;

class TemplateContratoFactory extends Factory
{
    protected $model = TemplateContrato::class;

    public function definition(): array
    {
        return [
            'nome' => fake()->sentence(3),
            'descricao' => fake()->optional()->sentence(),
            'conteudo_template' => '<p>Template de contrato de teste.</p>',
            'ativo' => true,
        ];
    }

    public function inativo(): static
    {
        return $this->state(fn() => [
            'ativo' => false,
        ]);
    }
}
