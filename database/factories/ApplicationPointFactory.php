<?php

namespace Database\Factories;

use App\Models\ApplicationPoint;
use App\Models\TreatmentSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationPointFactory extends Factory
{
    protected $model = ApplicationPoint::class;

    public function definition(): array
    {
        $regions = [
            'Frontalis' => ['x' => [25, 75], 'y' => [10, 25]],
            'Procerus' => ['x' => [45, 55], 'y' => [28, 33]],
            'Corrugador Esquerdo' => ['x' => [32, 42], 'y' => [27, 33]],
            'Corrugador Direito' => ['x' => [58, 68], 'y' => [27, 33]],
            'Orbicularis Oculi E' => ['x' => [20, 36], 'y' => [32, 40]],
            'Orbicularis Oculi D' => ['x' => [64, 80], 'y' => [32, 40]],
            'Orbicularis Oris' => ['x' => [40, 60], 'y' => [58, 68]],
            'Masseter E' => ['x' => [15, 30], 'y' => [50, 68]],
            'Masseter D' => ['x' => [70, 85], 'y' => [50, 68]],
            'Mentual' => ['x' => [42, 58], 'y' => [72, 80]],
        ];

        $region = fake()->randomElement(array_keys($regions));
        $bounds = $regions[$region];

        return [
            'treatment_session_id' => TreatmentSession::factory(),
            'regiao_musculo' => $region,
            'unidades_aplicadas' => fake()->randomFloat(1, 2, 15),
            'observacoes' => fake()->optional(0.3)->sentence(),
            'coord_x' => fake()->randomFloat(4, $bounds['x'][0], $bounds['x'][1]),
            'coord_y' => fake()->randomFloat(4, $bounds['y'][0], $bounds['y'][1]),
        ];
    }
}
