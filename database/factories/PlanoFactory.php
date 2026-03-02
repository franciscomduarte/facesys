<?php

namespace Database\Factories;

use App\Models\Plano;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PlanoFactory extends Factory
{
    protected $model = Plano::class;

    public function definition(): array
    {
        $nome = $this->faker->unique()->randomElement(['Basic', 'Standard', 'Premium', 'Ultimate']);
        $valorMensal = $this->faker->randomElement([99.00, 199.00, 299.00, 399.00]);

        return [
            'nome' => $nome,
            'slug' => Str::slug($nome),
            'descricao' => $this->faker->sentence(),
            'valor_mensal' => $valorMensal,
            'valor_anual' => $valorMensal * 10,
            'periodicidade_padrao' => 'mensal',
            'limite_usuarios' => $this->faker->randomElement([3, 5, 10, -1]),
            'limite_pacientes' => $this->faker->randomElement([50, 200, 500, -1]),
            'limite_agendamentos_mes' => $this->faker->randomElement([100, 500, 1000, -1]),
            'funcionalidades' => ['agendamentos' => true, 'prescricoes' => true],
            'trial_dias' => 14,
            'ativo' => true,
            'ordem' => 0,
        ];
    }

    public function inativo(): static
    {
        return $this->state(fn () => ['ativo' => false]);
    }
}
