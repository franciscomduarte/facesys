<?php

namespace Database\Factories;

use App\Models\Prescricao;
use App\Models\PrescricaoItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrescricaoItemFactory extends Factory
{
    protected $model = PrescricaoItem::class;

    public function definition(): array
    {
        $medicamentos = [
            ['medicamento' => 'Dexametasona Creme 0,1%', 'dosagem' => 'Aplicar fina camada', 'via' => 'Topica', 'frequencia' => '2x ao dia', 'duracao' => '7 dias'],
            ['medicamento' => 'Cefalexina 500mg', 'dosagem' => '500mg', 'via' => 'Oral', 'frequencia' => '8/8h', 'duracao' => '7 dias'],
            ['medicamento' => 'Dipirona 500mg', 'dosagem' => '500mg', 'via' => 'Oral', 'frequencia' => '6/6h se dor', 'duracao' => '3 dias'],
            ['medicamento' => 'Arnica Montana D6', 'dosagem' => '5 globulos', 'via' => 'Sublingual', 'frequencia' => '3x ao dia', 'duracao' => '5 dias'],
            ['medicamento' => 'Protetor Solar FPS 50', 'dosagem' => 'Aplicar generosamente', 'via' => 'Topica', 'frequencia' => 'A cada 2h', 'duracao' => '30 dias'],
            ['medicamento' => 'Acido Hialuronico Creme', 'dosagem' => 'Aplicar fina camada', 'via' => 'Topica', 'frequencia' => '2x ao dia', 'duracao' => '15 dias'],
            ['medicamento' => 'Vitamina C Serum 20%', 'dosagem' => '3-4 gotas', 'via' => 'Topica', 'frequencia' => '1x ao dia (manha)', 'duracao' => '30 dias'],
            ['medicamento' => 'Ibuprofeno 400mg', 'dosagem' => '400mg', 'via' => 'Oral', 'frequencia' => '8/8h se dor', 'duracao' => '3 dias'],
            ['medicamento' => 'Bepantol Derma', 'dosagem' => 'Aplicar fina camada', 'via' => 'Topica', 'frequencia' => '3x ao dia', 'duracao' => '10 dias'],
            ['medicamento' => 'Acido Retinoico 0,025%', 'dosagem' => 'Aplicar fina camada', 'via' => 'Topica', 'frequencia' => '1x ao dia (noite)', 'duracao' => '30 dias'],
        ];

        $med = fake()->randomElement($medicamentos);

        return [
            'prescricao_id' => Prescricao::factory(),
            'medicamento' => $med['medicamento'],
            'dosagem' => $med['dosagem'],
            'via_administracao' => $med['via'],
            'frequencia' => $med['frequencia'],
            'duracao' => $med['duracao'],
            'observacoes' => fake()->optional(0.3)->sentence(),
            'ordem' => 0,
        ];
    }
}
