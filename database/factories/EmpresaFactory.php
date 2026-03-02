<?php

namespace Database\Factories;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmpresaFactory extends Factory
{
    protected $model = Empresa::class;

    public function definition(): array
    {
        return [
            'nome_fantasia' => $this->faker->company(),
            'razao_social' => $this->faker->company() . ' LTDA',
            'cnpj' => $this->faker->numerify('##.###.###/####-##'),
            'email' => $this->faker->companyEmail(),
            'telefone' => $this->faker->numerify('(##) #####-####'),
            'status' => 'ativa',
        ];
    }

    public function inativa(): static
    {
        return $this->state(fn () => ['status' => 'inativa']);
    }

    public function suspensa(): static
    {
        return $this->state(fn () => ['status' => 'suspensa']);
    }
}
