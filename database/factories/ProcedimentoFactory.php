<?php

namespace Database\Factories;

use App\Models\Procedimento;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProcedimentoFactory extends Factory
{
    protected $model = Procedimento::class;

    private array $procedimentos = [
        [
            'nome' => 'Toxina Botulinica',
            'categoria' => 'facial',
            'descricao_clinica' => 'Aplicacao de toxina botulinica para tratamento de rugas dinamicas e linhas de expressao.',
            'indicacoes' => 'Rugas frontais, glabelares, periorbiculares (pes de galinha), linhas peribucais.',
            'contraindicacoes' => 'Gestantes, lactantes, doencas neuromusculares, alergia a albumina humana, infeccao no local.',
            'duracao_media_minutos' => 30,
            'valor_padrao' => 1200.00,
        ],
        [
            'nome' => 'Preenchimento Labial',
            'categoria' => 'facial',
            'descricao_clinica' => 'Preenchimento com acido hialuronico para volumizacao e definicao dos labios.',
            'indicacoes' => 'Labios finos, assimetria labial, perda de volume por envelhecimento, definicao de contorno.',
            'contraindicacoes' => 'Gestantes, lactantes, historico de herpes ativa, alergia ao acido hialuronico, doencas autoimunes.',
            'duracao_media_minutos' => 45,
            'valor_padrao' => 1800.00,
        ],
        [
            'nome' => 'Preenchimento Malar',
            'categoria' => 'facial',
            'descricao_clinica' => 'Preenchimento da regiao malar (macas do rosto) com acido hialuronico para restaurar volume.',
            'indicacoes' => 'Perda de volume malar, envelhecimento facial, assimetria, melhora do contorno facial.',
            'contraindicacoes' => 'Gestantes, lactantes, infeccao ativa no local, doencas autoimunes, uso de anticoagulantes.',
            'duracao_media_minutos' => 40,
            'valor_padrao' => 2200.00,
        ],
        [
            'nome' => 'Bioestimulador de Colageno',
            'categoria' => 'facial',
            'descricao_clinica' => 'Aplicacao de bioestimuladores para estimular producao natural de colageno e melhorar a qualidade da pele.',
            'indicacoes' => 'Flacidez cutanea, perda de sustentacao, envelhecimento, melhora da textura da pele.',
            'contraindicacoes' => 'Gestantes, lactantes, doencas autoimunes ativas, infeccao no local, tendencia a queloides.',
            'duracao_media_minutos' => 60,
            'valor_padrao' => 2500.00,
        ],
        [
            'nome' => 'Peeling Quimico',
            'categoria' => 'facial',
            'descricao_clinica' => 'Aplicacao de acidos para renovacao celular e melhora da textura, manchas e cicatrizes.',
            'indicacoes' => 'Melasma, manchas solares, acne, cicatrizes, envelhecimento cutaneo, textura irregular.',
            'contraindicacoes' => 'Gestantes, lactantes, pele com lesoes ativas, herpes ativa, uso recente de isotretinoina, exposicao solar recente.',
            'duracao_media_minutos' => 30,
            'valor_padrao' => 350.00,
        ],
        [
            'nome' => 'Microagulhamento',
            'categoria' => 'facial',
            'descricao_clinica' => 'Procedimento com microagulhas para estimular producao de colageno e facilitar permeacao de ativos.',
            'indicacoes' => 'Cicatrizes de acne, rugas finas, flacidez, estrias, melasma, poros dilatados.',
            'contraindicacoes' => 'Gestantes, lactantes, pele com infeccao ativa, uso de anticoagulantes, queloides.',
            'duracao_media_minutos' => 45,
            'valor_padrao' => 500.00,
        ],
        [
            'nome' => 'Limpeza de Pele Profunda',
            'categoria' => 'facial',
            'descricao_clinica' => 'Limpeza profunda com extracao de comedoes, esfoliacao e hidratacao da pele.',
            'indicacoes' => 'Pele oleosa, comedoes abertos e fechados, miliuns, preparo para outros procedimentos.',
            'contraindicacoes' => 'Pele com infeccao ativa, rosácea em crise, dermatite de contato, queimaduras solares.',
            'duracao_media_minutos' => 60,
            'valor_padrao' => 200.00,
        ],
        [
            'nome' => 'Criolipólise',
            'categoria' => 'corporal',
            'descricao_clinica' => 'Tratamento nao invasivo que utiliza resfriamento controlado para eliminar celulas de gordura localizada.',
            'indicacoes' => 'Gordura localizada em abdomen, flancos, costas, coxas, braccos, papada.',
            'contraindicacoes' => 'Gestantes, lactantes, crioglobulinemia, urticaria ao frio, hernia no local, marcapasso.',
            'duracao_media_minutos' => 60,
            'valor_padrao' => 800.00,
        ],
        [
            'nome' => 'Drenagem Linfatica',
            'categoria' => 'corporal',
            'descricao_clinica' => 'Massagem especializada para estimular o sistema linfatico e reduzir retencao de liquidos.',
            'indicacoes' => 'Retencao hidrica, pos-operatorio, celulite, edema, gestantes (com liberacao medica).',
            'contraindicacoes' => 'Trombose venosa profunda, infeccoes ativas, insuficiencia cardiaca descompensada, cancer ativo.',
            'duracao_media_minutos' => 50,
            'valor_padrao' => 180.00,
        ],
        [
            'nome' => 'Intradermoterapia Capilar',
            'categoria' => 'capilar',
            'descricao_clinica' => 'Aplicacao intradermica de substancias ativas no couro cabeludo para tratamento de alopecia.',
            'indicacoes' => 'Alopecia androgenetica, queda capilar, enfraquecimento dos fios, calvicie inicial.',
            'contraindicacoes' => 'Gestantes, lactantes, infeccao no couro cabeludo, alergia aos componentes, doencas autoimunes.',
            'duracao_media_minutos' => 30,
            'valor_padrao' => 400.00,
        ],
    ];

    public function definition(): array
    {
        $proc = fake()->randomElement($this->procedimentos);

        return [
            'nome' => $proc['nome'],
            'categoria' => $proc['categoria'],
            'descricao_clinica' => $proc['descricao_clinica'],
            'indicacoes' => $proc['indicacoes'],
            'contraindicacoes' => $proc['contraindicacoes'],
            'duracao_media_minutos' => $proc['duracao_media_minutos'],
            'ativo' => true,
            'valor_padrao' => $proc['valor_padrao'],
            'observacoes_internas' => fake()->optional(0.3)->sentence(),
            'termo_padrao' => null,
        ];
    }
}
