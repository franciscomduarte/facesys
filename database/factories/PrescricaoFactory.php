<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Prescricao;
use App\Models\TreatmentSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrescricaoFactory extends Factory
{
    protected $model = Prescricao::class;

    public function definition(): array
    {
        $status = fake()->randomElement(['rascunho', 'emitida', 'emitida', 'emitida']);

        return [
            'patient_id' => Patient::factory(),
            'treatment_session_id' => TreatmentSession::factory(),
            'data_emissao' => $status !== 'rascunho' ? fake()->dateTimeBetween('-3 months', 'now') : null,
            'observacoes_gerais' => fake()->optional(0.4)->sentence(),
            'status' => $status,
            'profissional_responsavel' => 'Dra. Maria Silva',
        ];
    }
}
