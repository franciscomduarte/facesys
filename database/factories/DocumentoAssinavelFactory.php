<?php

namespace Database\Factories;

use App\Models\DocumentoAssinavel;
use App\Models\Patient;
use App\Models\Prescricao;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DocumentoAssinavelFactory extends Factory
{
    protected $model = DocumentoAssinavel::class;

    public function definition(): array
    {
        return [
            'tipo_documento' => 'prescricao',
            'documento_type' => Prescricao::class,
            'documento_id' => Prescricao::factory(),
            'patient_id' => Patient::factory(),
            'profissional_id' => User::factory(),
            'status' => 'pendente',
            'hash_documento' => hash('sha256', Str::random(64)),
            'token_acesso' => Str::random(64),
            'token_expira_em' => now()->addHours(48),
        ];
    }

    public function finalizado(): static
    {
        return $this->state(fn() => [
            'status' => 'finalizado',
        ]);
    }

    public function assinadoPaciente(): static
    {
        return $this->state(fn() => [
            'status' => 'assinado_paciente',
        ]);
    }
}
