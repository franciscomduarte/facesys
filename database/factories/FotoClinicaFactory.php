<?php

namespace Database\Factories;

use App\Models\FotoClinica;
use App\Models\Patient;
use App\Models\Procedimento;
use App\Models\TreatmentSession;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FotoClinicaFactory extends Factory
{
    protected $model = FotoClinica::class;

    public function definition(): array
    {
        $tipo = fake()->randomElement(['antes', 'depois']);
        $uuid = Str::uuid();

        return [
            'patient_id' => Patient::factory(),
            'treatment_session_id' => TreatmentSession::factory(),
            'procedimento_id' => Procedimento::factory(),
            'tipo' => $tipo,
            'caminho_arquivo' => "fotos_clinicas/paciente_1/sessao_1/{$uuid}.jpg",
            'nome_original' => "foto_{$tipo}_" . fake()->word() . '.jpg',
            'mime_type' => 'image/jpeg',
            'tamanho_bytes' => fake()->numberBetween(100000, 5000000),
            'data_registro' => fake()->dateTimeBetween('-6 months', 'now'),
            'profissional_responsavel' => 'Dra. Maria Silva',
            'observacoes' => fake()->optional(0.5)->sentence(),
            'regiao_facial' => fake()->optional(0.4)->randomElement([
                'Testa', 'Glabela', 'Periorbicular E', 'Periorbicular D',
                'Perioral', 'Masseter E', 'Masseter D', 'Mentual',
            ]),
            'ordem' => 0,
        ];
    }
}
