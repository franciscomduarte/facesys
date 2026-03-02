<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition(): array
    {
        $sexo = fake()->randomElement(['masculino', 'feminino']);

        return [
            'nome_completo' => fake('pt_BR')->name($sexo === 'feminino' ? 'female' : 'male'),
            'data_nascimento' => fake()->dateTimeBetween('-70 years', '-18 years'),
            'sexo' => $sexo,
            'cpf' => sprintf('%03d.%03d.%03d-%02d', fake()->numberBetween(100, 999), fake()->numberBetween(0, 999), fake()->numberBetween(0, 999), fake()->numberBetween(0, 99)),
            'telefone' => sprintf('(%02d) %05d-%04d', fake()->numberBetween(11, 99), fake()->numberBetween(90000, 99999), fake()->numberBetween(0, 9999)),
            'email' => fake()->unique()->safeEmail(),
            'endereco' => fake('pt_BR')->address(),
            'profissao' => fake()->randomElement([
                'Empresario(a)', 'Professor(a)', 'Medico(a)', 'Advogado(a)',
                'Engenheiro(a)', 'Autonomo(a)', 'Aposentado(a)', 'Estudante',
                'Dentista', 'Farmaceutico(a)', 'Psicologo(a)', 'Designer',
            ]),
            'observacoes_gerais' => fake()->optional(0.3)->sentence(),
            'historico_medico' => fake()->optional(0.5)->paragraph(),
            'medicamentos_continuo' => fake()->optional(0.3)->randomElement([
                'Levotiroxina 50mcg', 'Losartana 50mg', 'Metformina 850mg',
                'Anticoncepcional', 'Nenhum',
            ]),
            'alergias' => fake()->optional(0.2)->randomElement([
                'Dipirona', 'Penicilina', 'Latex', 'Nenhuma conhecida', 'AAS',
            ]),
            'doencas_preexistentes' => fake()->optional(0.3)->randomElement([
                'Hipertensao', 'Diabetes Tipo 2', 'Nenhuma', 'Hipotireoidismo', 'Asma',
            ]),
            'contraindicacoes_esteticas' => fake()->optional(0.2)->sentence(),
            'observacoes_medicas' => fake()->optional(0.3)->paragraph(),
        ];
    }
}
