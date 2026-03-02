<?php

namespace Database\Factories;

use App\Models\Agendamento;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgendamentoFactory extends Factory
{
    protected $model = Agendamento::class;

    public function definition(): array
    {
        $hora = $this->faker->randomElement(['08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00']);

        return [
            'patient_id' => Patient::factory(),
            'profissional_id' => User::factory(),
            'data_agendamento' => $this->faker->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'hora_inicio' => $hora,
            'hora_fim' => \Carbon\Carbon::createFromFormat('H:i', $hora)->addMinutes($this->faker->randomElement([30, 60, 90]))->format('H:i'),
            'status' => 'agendado',
            'tipo_atendimento' => $this->faker->randomElement(['procedimento', 'consulta']),
            'origem' => 'manual',
            'observacoes' => $this->faker->optional(0.3)->sentence(),
        ];
    }

    public function confirmado(): static
    {
        return $this->state(fn() => ['status' => 'confirmado']);
    }

    public function cancelado(): static
    {
        return $this->state(fn() => [
            'status' => 'cancelado',
            'motivo_cancelamento' => $this->faker->sentence(),
        ]);
    }

    public function realizado(): static
    {
        return $this->state(fn() => [
            'status' => 'realizado',
            'data_agendamento' => $this->faker->dateTimeBetween('-15 days', '-1 day')->format('Y-m-d'),
        ]);
    }

    public function naoCompareceu(): static
    {
        return $this->state(fn() => [
            'status' => 'nao_compareceu',
            'data_agendamento' => $this->faker->dateTimeBetween('-15 days', '-1 day')->format('Y-m-d'),
        ]);
    }
}
