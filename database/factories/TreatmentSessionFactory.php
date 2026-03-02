<?php

namespace Database\Factories;

use App\Models\TreatmentSession;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class TreatmentSessionFactory extends Factory
{
    protected $model = TreatmentSession::class;

    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'data_sessao' => fake()->dateTimeBetween('-1 year', 'now'),
            'procedimento' => fake()->randomElement([
                'Toxina Botulinica', 'Preenchimento Facial', 'Bioestimulador',
            ]),
            'marca_produto' => fake()->randomElement([
                'Botox', 'Dysport', 'Xeomin', 'Jeuveau',
            ]),
            'lote' => fake()->optional(0.7)->bothify('??####'),
            'quantidade_total' => fake()->randomFloat(1, 20, 100),
            'observacoes_sessao' => fake()->optional(0.5)->sentence(),
            'profissional_responsavel' => fake()->randomElement([
                'Dra. Maria Silva', 'Dr. Joao Santos', 'Dra. Ana Costa',
            ]),
        ];
    }
}
