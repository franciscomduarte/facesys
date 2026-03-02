<?php

namespace Database\Seeders;

use App\Models\Agendamento;
use App\Models\Empresa;
use App\Models\Plano;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Patient;
use App\Models\Procedimento;
use App\Models\Prescricao;
use App\Models\PrescricaoItem;
use App\Models\DocumentoAssinavel;
use App\Models\Assinatura;
use App\Models\Contrato;
use App\Models\FotoClinica;
use App\Models\TemplateContrato;
use App\Models\TreatmentSession;
use App\Models\ApplicationPoint;
use App\Services\TemplateParserService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Criar empresas
        $empresaLC = Empresa::create([
            'nome_fantasia' => 'LC Estetica',
            'razao_social' => 'LC Estetica e Bem-Estar LTDA',
            'cnpj' => '12.345.678/0001-90',
            'email' => 'contato@lcestetica.com',
            'telefone' => '(11) 99999-0001',
            'status' => 'ativa',
        ]);

        $empresaBeleza = Empresa::create([
            'nome_fantasia' => 'Clinica Beleza',
            'razao_social' => 'Clinica Beleza e Saude LTDA',
            'cnpj' => '98.765.432/0001-10',
            'email' => 'contato@clinicabeleza.com',
            'telefone' => '(21) 99999-0002',
            'status' => 'ativa',
        ]);

        // Super Admin (sem empresa)
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@sistema.com',
            'role' => 'super_admin',
            'empresa_id' => null,
        ]);

        // Usuarios da LC Estetica
        $adminLC = User::factory()->create([
            'name' => 'Admin LC',
            'email' => 'admin@lcestetica.com',
            'role' => 'admin',
            'empresa_id' => $empresaLC->id,
        ]);

        $medicoLC = User::factory()->create([
            'name' => 'Dra. Maria Silva',
            'email' => 'medico@lcestetica.com',
            'role' => 'medico',
            'empresa_id' => $empresaLC->id,
        ]);

        User::factory()->create([
            'name' => 'Recepcao LC',
            'email' => 'recepcao@lcestetica.com',
            'role' => 'recepcionista',
            'empresa_id' => $empresaLC->id,
        ]);

        // Usuarios da Clinica Beleza
        $adminBeleza = User::factory()->create([
            'name' => 'Admin Beleza',
            'email' => 'admin@clinicabeleza.com',
            'role' => 'admin',
            'empresa_id' => $empresaBeleza->id,
        ]);

        $medicoBeleza = User::factory()->create([
            'name' => 'Dr. Carlos Santos',
            'email' => 'medico@clinicabeleza.com',
            'role' => 'medico',
            'empresa_id' => $empresaBeleza->id,
        ]);

        // Criar planos
        $planoStarter = Plano::create([
            'nome' => 'Starter',
            'slug' => 'starter',
            'descricao' => 'Ideal para clinicas iniciantes com ate 3 profissionais.',
            'valor_mensal' => 99.00,
            'valor_anual' => 990.00,
            'periodicidade_padrao' => 'mensal',
            'limite_usuarios' => 3,
            'limite_pacientes' => 100,
            'limite_agendamentos_mes' => 200,
            'funcionalidades' => [
                'agendamentos' => true,
                'prescricoes' => true,
                'contratos' => false,
                'fotos_clinicas' => false,
                'assinatura_digital' => false,
                'relatorios' => false,
            ],
            'trial_dias' => 14,
            'ativo' => true,
            'ordem' => 1,
        ]);

        $planoProfessional = Plano::create([
            'nome' => 'Professional',
            'slug' => 'professional',
            'descricao' => 'Para clinicas em crescimento com recursos completos.',
            'valor_mensal' => 199.00,
            'valor_anual' => 1990.00,
            'periodicidade_padrao' => 'mensal',
            'limite_usuarios' => 10,
            'limite_pacientes' => 500,
            'limite_agendamentos_mes' => 1000,
            'funcionalidades' => [
                'agendamentos' => true,
                'prescricoes' => true,
                'contratos' => true,
                'fotos_clinicas' => true,
                'assinatura_digital' => true,
                'relatorios' => false,
            ],
            'trial_dias' => 14,
            'ativo' => true,
            'ordem' => 2,
        ]);

        $planoEnterprise = Plano::create([
            'nome' => 'Enterprise',
            'slug' => 'enterprise',
            'descricao' => 'Solucao completa e ilimitada para grandes clinicas.',
            'valor_mensal' => 399.00,
            'valor_anual' => 3990.00,
            'periodicidade_padrao' => 'mensal',
            'limite_usuarios' => -1,
            'limite_pacientes' => -1,
            'limite_agendamentos_mes' => -1,
            'funcionalidades' => [
                'agendamentos' => true,
                'prescricoes' => true,
                'contratos' => true,
                'fotos_clinicas' => true,
                'assinatura_digital' => true,
                'relatorios' => true,
            ],
            'trial_dias' => 14,
            'ativo' => true,
            'ordem' => 3,
        ]);

        // Criar subscriptions
        Subscription::create([
            'empresa_id' => $empresaLC->id,
            'plano_id' => $planoProfessional->id,
            'status' => 'ativa',
            'periodicidade' => 'mensal',
            'data_inicio' => now()->subMonths(3),
            'proxima_cobranca' => now()->addDays(15),
            'valor_atual' => 199.00,
        ]);

        Subscription::create([
            'empresa_id' => $empresaBeleza->id,
            'plano_id' => $planoStarter->id,
            'status' => 'ativa',
            'periodicidade' => 'mensal',
            'data_inicio' => now()->subMonth(),
            'proxima_cobranca' => now()->addDays(20),
            'valor_atual' => 99.00,
        ]);

        // Seed para cada empresa
        $this->seedEmpresaData($empresaLC, $adminLC, $medicoLC);
        $this->seedEmpresaData($empresaBeleza, $adminBeleza, $medicoBeleza);
    }

    private function seedEmpresaData(Empresa $empresa, User $admin, User $medico): void
    {
        // Criar procedimentos
        $procedimentosData = [
            ['nome' => 'Toxina Botulinica', 'categoria' => 'facial', 'descricao_clinica' => 'Aplicacao de toxina botulinica para tratamento de rugas dinamicas e linhas de expressao.', 'indicacoes' => 'Rugas frontais, glabelares, periorbiculares (pes de galinha), linhas peribucais.', 'contraindicacoes' => 'Gestantes, lactantes, doencas neuromusculares, alergia a albumina humana.', 'duracao_media_minutos' => 30, 'valor_padrao' => 1200.00],
            ['nome' => 'Preenchimento Labial', 'categoria' => 'facial', 'descricao_clinica' => 'Preenchimento com acido hialuronico para volumizacao e definicao dos labios.', 'indicacoes' => 'Labios finos, assimetria labial, perda de volume por envelhecimento.', 'contraindicacoes' => 'Gestantes, lactantes, herpes ativa, alergia ao acido hialuronico.', 'duracao_media_minutos' => 45, 'valor_padrao' => 1800.00],
            ['nome' => 'Preenchimento Malar', 'categoria' => 'facial', 'descricao_clinica' => 'Preenchimento da regiao malar com acido hialuronico para restaurar volume.', 'indicacoes' => 'Perda de volume malar, envelhecimento facial, assimetria.', 'contraindicacoes' => 'Gestantes, lactantes, infeccao ativa no local, doencas autoimunes.', 'duracao_media_minutos' => 40, 'valor_padrao' => 2200.00],
            ['nome' => 'Bioestimulador de Colageno', 'categoria' => 'facial', 'descricao_clinica' => 'Aplicacao de bioestimuladores para estimular producao natural de colageno.', 'indicacoes' => 'Flacidez cutanea, perda de sustentacao, envelhecimento.', 'contraindicacoes' => 'Gestantes, lactantes, doencas autoimunes ativas, tendencia a queloides.', 'duracao_media_minutos' => 60, 'valor_padrao' => 2500.00],
            ['nome' => 'Peeling Quimico', 'categoria' => 'facial', 'descricao_clinica' => 'Aplicacao de acidos para renovacao celular e melhora da textura.', 'indicacoes' => 'Melasma, manchas solares, acne, cicatrizes, textura irregular.', 'contraindicacoes' => 'Gestantes, lactantes, pele com lesoes ativas, herpes ativa.', 'duracao_media_minutos' => 30, 'valor_padrao' => 350.00],
            ['nome' => 'Microagulhamento', 'categoria' => 'facial', 'descricao_clinica' => 'Procedimento com microagulhas para estimular producao de colageno.', 'indicacoes' => 'Cicatrizes de acne, rugas finas, flacidez, estrias, poros dilatados.', 'contraindicacoes' => 'Gestantes, lactantes, pele com infeccao ativa, uso de anticoagulantes.', 'duracao_media_minutos' => 45, 'valor_padrao' => 500.00],
            ['nome' => 'Limpeza de Pele Profunda', 'categoria' => 'facial', 'descricao_clinica' => 'Limpeza profunda com extracao de comedoes, esfoliacao e hidratacao.', 'indicacoes' => 'Pele oleosa, comedoes, miliuns, preparo para outros procedimentos.', 'contraindicacoes' => 'Pele com infeccao ativa, rosacea em crise, dermatite de contato.', 'duracao_media_minutos' => 60, 'valor_padrao' => 200.00],
            ['nome' => 'Criolipolise', 'categoria' => 'corporal', 'descricao_clinica' => 'Resfriamento controlado para eliminar celulas de gordura localizada.', 'indicacoes' => 'Gordura localizada em abdomen, flancos, costas, coxas, papada.', 'contraindicacoes' => 'Gestantes, lactantes, crioglobulinemia, urticaria ao frio, marcapasso.', 'duracao_media_minutos' => 60, 'valor_padrao' => 800.00],
            ['nome' => 'Drenagem Linfatica', 'categoria' => 'corporal', 'descricao_clinica' => 'Massagem especializada para estimular o sistema linfatico.', 'indicacoes' => 'Retencao hidrica, pos-operatorio, celulite, edema.', 'contraindicacoes' => 'Trombose venosa profunda, infeccoes ativas, insuficiencia cardiaca.', 'duracao_media_minutos' => 50, 'valor_padrao' => 180.00],
            ['nome' => 'Intradermoterapia Capilar', 'categoria' => 'capilar', 'descricao_clinica' => 'Aplicacao intradermica de substancias ativas no couro cabeludo.', 'indicacoes' => 'Alopecia androgenetica, queda capilar, enfraquecimento dos fios.', 'contraindicacoes' => 'Gestantes, lactantes, infeccao no couro cabeludo, doencas autoimunes.', 'duracao_media_minutos' => 30, 'valor_padrao' => 400.00],
        ];

        $procedimentos = collect();
        foreach ($procedimentosData as $data) {
            $procedimentos->push(Procedimento::create(array_merge($data, [
                'ativo' => true,
                'empresa_id' => $empresa->id,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ])));
        }

        // Criar template padrao de contrato
        $templatePadrao = TemplateContrato::create([
            'nome' => 'Contrato Padrao de Prestacao de Servicos Esteticos',
            'descricao' => 'Template padrao para contratos de procedimentos esteticos com clausulas juridicas.',
            'conteudo_template' => '<h2 style="text-align:center;">CONTRATO DE PRESTACAO DE SERVICOS ESTETICOS</h2>

<p>Pelo presente instrumento particular, de um lado <strong>{{clinica.nome}}</strong>, inscrita no CNPJ sob n. {{clinica.cnpj}}, com sede em {{clinica.endereco}}, doravante denominada <strong>CONTRATADA</strong>, representada pelo(a) profissional <strong>{{profissional.nome}}</strong>; e de outro lado <strong>{{paciente.nome}}</strong>, inscrito(a) no CPF sob n. {{paciente.cpf}}, nascido(a) em {{paciente.data_nascimento}}, residente em {{paciente.endereco}}, telefone {{paciente.telefone}}, doravante denominado(a) <strong>CONTRATANTE</strong>, firmam o presente contrato mediante as clausulas e condicoes seguintes:</p>

<h3>CLAUSULA 1 - DO OBJETO</h3>
<p>O presente contrato tem por objeto a prestacao dos seguintes servicos esteticos, a serem realizados na data de <strong>{{data_atendimento}}</strong>:</p>

{{procedimentos.lista}}

<h3>CLAUSULA 2 - DO VALOR</h3>
<p>O valor total dos servicos ora contratados e de <strong>{{valor_total}}</strong>, a ser pago conforme condicoes acordadas entre as partes.</p>

<h3>CLAUSULA 3 - DAS OBRIGACOES DA CONTRATADA</h3>
<p>A CONTRATADA se compromete a:</p>
<ul>
<li>Realizar os procedimentos descritos na Clausula 1 com zelo, diligencia e dentro dos padroes tecnicos exigidos;</li>
<li>Utilizar materiais e produtos de qualidade, devidamente registrados nos orgaos competentes;</li>
<li>Informar o(a) CONTRATANTE sobre os riscos, beneficios e cuidados pos-procedimento;</li>
<li>Manter sigilo sobre todas as informacoes do(a) CONTRATANTE, conforme a Lei Geral de Protecao de Dados (LGPD).</li>
</ul>

<h3>CLAUSULA 4 - DAS OBRIGACOES DO(A) CONTRATANTE</h3>
<p>O(A) CONTRATANTE se compromete a:</p>
<ul>
<li>Informar sobre seu historico medico, alergias, medicamentos em uso e demais informacoes relevantes;</li>
<li>Seguir rigorosamente as orientacoes pos-procedimento fornecidas pela CONTRATADA;</li>
<li>Comparecer aos retornos agendados, quando aplicavel;</li>
<li>Efetuar o pagamento conforme acordado.</li>
</ul>

<h3>CLAUSULA 5 - DOS RISCOS E CONSENTIMENTO</h3>
<p>O(A) CONTRATANTE declara ter sido informado(a) sobre os possiveis riscos e efeitos colaterais dos procedimentos contratados, incluindo, mas nao se limitando a: edema, hematoma, dor, assimetria, reacoes alergicas e resultados esteticos variaveis. O(A) CONTRATANTE assume os riscos inerentes ao procedimento e consente livremente com a realizacao dos servicos.</p>

<h3>CLAUSULA 6 - DA VIGENCIA</h3>
<p>O presente contrato entra em vigor na data de sua assinatura e permanece valido ate a conclusao dos servicos contratados.</p>

<h3>CLAUSULA 7 - DO FORO</h3>
<p>Fica eleito o foro da comarca onde se situa a CONTRATADA para dirimir quaisquer duvidas ou controversias decorrentes deste contrato.</p>

<p style="margin-top:30px;">E, por estarem de acordo, as partes firmam o presente contrato em duas vias de igual teor.</p>',
            'ativo' => true,
            'empresa_id' => $empresa->id,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        $templateParserService = app(TemplateParserService::class);

        // Criar pacientes com sessoes, pontos e procedimentos
        $patients = Patient::factory(10)->create([
            'empresa_id' => $empresa->id,
            'created_by' => $medico->id,
            'updated_by' => $medico->id,
        ]);

        foreach ($patients as $patient) {
            $sessionCount = fake()->numberBetween(1, 4);

            for ($i = 0; $i < $sessionCount; $i++) {
                $session = TreatmentSession::factory()->create([
                    'patient_id' => $patient->id,
                    'empresa_id' => $empresa->id,
                    'created_by' => $medico->id,
                    'updated_by' => $medico->id,
                ]);

                $pointCount = fake()->numberBetween(3, 8);
                ApplicationPoint::factory($pointCount)->create([
                    'treatment_session_id' => $session->id,
                    'empresa_id' => $empresa->id,
                    'created_by' => $medico->id,
                    'updated_by' => $medico->id,
                ]);

                // Vincular 1-3 procedimentos a cada sessao
                $selectedProcs = $procedimentos->random(fake()->numberBetween(1, 3));
                foreach ($selectedProcs as $proc) {
                    $session->procedimentos()->attach($proc->id, [
                        'quantidade' => fake()->randomFloat(1, 1, 50),
                        'observacoes' => fake()->optional(0.4)->sentence(),
                    ]);
                }

                // Criar 0-4 fotos clinicas por sessao (caminhos ficticios, sem arquivo real)
                $fotoCount = fake()->numberBetween(0, 4);
                $sessionProcs = $selectedProcs->values();
                for ($f = 0; $f < $fotoCount; $f++) {
                    $proc = $sessionProcs->random();
                    $tipo = fake()->randomElement(['antes', 'depois']);
                    $uuid = Str::uuid();
                    FotoClinica::create([
                        'patient_id' => $patient->id,
                        'treatment_session_id' => $session->id,
                        'procedimento_id' => $proc->id,
                        'tipo' => $tipo,
                        'caminho_arquivo' => "fotos_clinicas/paciente_{$patient->id}/sessao_{$session->id}/{$uuid}.jpg",
                        'nome_original' => "foto_{$tipo}_{$f}.jpg",
                        'mime_type' => 'image/jpeg',
                        'tamanho_bytes' => fake()->numberBetween(200000, 4000000),
                        'data_registro' => $session->data_sessao,
                        'profissional_responsavel' => $session->profissional_responsavel,
                        'observacoes' => fake()->optional(0.4)->sentence(),
                        'regiao_facial' => fake()->optional(0.3)->randomElement([
                            'Testa', 'Glabela', 'Periorbicular', 'Perioral', 'Masseter', 'Mentual',
                        ]),
                        'ordem' => $f,
                        'empresa_id' => $empresa->id,
                        'created_by' => $medico->id,
                        'updated_by' => $medico->id,
                    ]);
                }

                // Criar prescricao em ~30% das sessoes
                if (fake()->boolean(30)) {
                    $status = fake()->randomElement(['rascunho', 'emitida', 'emitida', 'emitida']);
                    $prescricao = Prescricao::create([
                        'patient_id' => $patient->id,
                        'treatment_session_id' => $session->id,
                        'data_emissao' => $status !== 'rascunho' ? $session->data_sessao : null,
                        'observacoes_gerais' => fake()->optional(0.4)->sentence(),
                        'status' => $status,
                        'profissional_responsavel' => $session->profissional_responsavel,
                        'empresa_id' => $empresa->id,
                        'created_by' => $medico->id,
                        'updated_by' => $medico->id,
                    ]);

                    $medicamentos = [
                        ['medicamento' => 'Dexametasona Creme 0,1%', 'dosagem' => 'Aplicar fina camada', 'via' => 'Topica', 'frequencia' => '2x ao dia', 'duracao' => '7 dias'],
                        ['medicamento' => 'Cefalexina 500mg', 'dosagem' => '500mg', 'via' => 'Oral', 'frequencia' => '8/8h', 'duracao' => '7 dias'],
                        ['medicamento' => 'Dipirona 500mg', 'dosagem' => '500mg', 'via' => 'Oral', 'frequencia' => '6/6h se dor', 'duracao' => '3 dias'],
                        ['medicamento' => 'Arnica Montana D6', 'dosagem' => '5 globulos', 'via' => 'Sublingual', 'frequencia' => '3x ao dia', 'duracao' => '5 dias'],
                        ['medicamento' => 'Protetor Solar FPS 50', 'dosagem' => 'Aplicar generosamente', 'via' => 'Topica', 'frequencia' => 'A cada 2h', 'duracao' => '30 dias'],
                        ['medicamento' => 'Acido Hialuronico Creme', 'dosagem' => 'Aplicar fina camada', 'via' => 'Topica', 'frequencia' => '2x ao dia', 'duracao' => '15 dias'],
                        ['medicamento' => 'Vitamina C Serum 20%', 'dosagem' => '3-4 gotas', 'via' => 'Topica', 'frequencia' => '1x ao dia (manha)', 'duracao' => '30 dias'],
                        ['medicamento' => 'Ibuprofeno 400mg', 'dosagem' => '400mg', 'via' => 'Oral', 'frequencia' => '8/8h se dor', 'duracao' => '3 dias'],
                    ];

                    $itemCount = fake()->numberBetween(1, 4);
                    $selectedMeds = fake()->randomElements($medicamentos, $itemCount);

                    foreach ($selectedMeds as $ordem => $med) {
                        PrescricaoItem::create([
                            'prescricao_id' => $prescricao->id,
                            'medicamento' => $med['medicamento'],
                            'dosagem' => $med['dosagem'],
                            'via_administracao' => $med['via'],
                            'frequencia' => $med['frequencia'],
                            'duracao' => $med['duracao'],
                            'observacoes' => fake()->optional(0.3)->sentence(),
                            'ordem' => $ordem,
                        ]);
                    }

                    // ~50% das prescricoes emitidas recebem documento assinavel
                    if ($status === 'emitida' && fake()->boolean(50)) {
                        $hashDocumento = hash('sha256', json_encode([
                            'prescricao_id' => $prescricao->id,
                            'patient_id' => $patient->id,
                            'profissional' => $prescricao->profissional_responsavel,
                        ]));

                        $docStatus = fake()->randomElement(['pendente', 'finalizado', 'finalizado', 'finalizado']);

                        $documento = DocumentoAssinavel::create([
                            'tipo_documento' => 'prescricao',
                            'documento_id' => $prescricao->id,
                            'documento_type' => Prescricao::class,
                            'patient_id' => $patient->id,
                            'profissional_id' => $medico->id,
                            'status' => $docStatus,
                            'hash_documento' => $hashDocumento,
                            'token_acesso' => Str::random(64),
                            'token_expira_em' => now()->addHours(48),
                            'empresa_id' => $empresa->id,
                            'created_by' => $medico->id,
                            'updated_by' => $medico->id,
                        ]);

                        if ($docStatus === 'finalizado' || $docStatus === 'assinado_paciente') {
                            Assinatura::create([
                                'documento_assinavel_id' => $documento->id,
                                'tipo_assinatura' => 'paciente',
                                'nome_assinante' => $patient->nome_completo,
                                'documento_assinante' => fake()->numerify('###########'),
                                'ip' => fake()->ipv4(),
                                'user_agent' => fake()->userAgent(),
                                'data_assinatura' => $session->data_sessao->addHours(fake()->numberBetween(1, 24)),
                                'hash_assinatura' => hash('sha256', Str::random(64)),
                            ]);
                        }

                        if ($docStatus === 'finalizado') {
                            Assinatura::create([
                                'documento_assinavel_id' => $documento->id,
                                'tipo_assinatura' => 'profissional',
                                'nome_assinante' => $medico->name,
                                'documento_assinante' => fake()->numerify('###########'),
                                'ip' => fake()->ipv4(),
                                'user_agent' => fake()->userAgent(),
                                'data_assinatura' => $session->data_sessao->addHours(fake()->numberBetween(25, 48)),
                                'hash_assinatura' => hash('sha256', Str::random(64)),
                            ]);

                            $prescricao->update(['status' => 'assinada']);
                        }
                    }
                }

                // Criar contrato em ~40% das sessoes
                if (fake()->boolean(40)) {
                    $session->load('procedimentos');
                    $data = $templateParserService->buildDataFromSession($session);
                    $conteudo = $templateParserService->parse($templatePadrao->conteudo_template, $data);
                    $valorTotal = $templateParserService->calculateValorTotal($session);

                    $contratoStatus = fake()->randomElement(['rascunho', 'gerado', 'gerado', 'gerado', 'assinado']);
                    $hashContrato = hash('sha256', $conteudo . $session->id);

                    $contrato = Contrato::create([
                        'patient_id' => $patient->id,
                        'treatment_session_id' => $session->id,
                        'profissional_id' => $medico->id,
                        'template_contrato_id' => $templatePadrao->id,
                        'status' => $contratoStatus === 'assinado' ? 'gerado' : $contratoStatus,
                        'conteudo_renderizado' => $conteudo,
                        'hash_contrato' => $hashContrato,
                        'valor_total' => $valorTotal,
                        'observacoes' => fake()->optional(0.3)->sentence(),
                        'data_geracao' => $contratoStatus !== 'rascunho' ? $session->data_sessao : null,
                        'empresa_id' => $empresa->id,
                        'created_by' => $medico->id,
                        'updated_by' => $medico->id,
                    ]);

                    // Contratos gerados/assinados recebem documento assinavel
                    if ($contratoStatus === 'gerado' || $contratoStatus === 'assinado') {
                        $docContratoStatus = $contratoStatus === 'assinado' ? 'finalizado' : fake()->randomElement(['pendente', 'assinado_paciente']);

                        $docContrato = DocumentoAssinavel::create([
                            'tipo_documento' => 'contrato',
                            'documento_id' => $contrato->id,
                            'documento_type' => Contrato::class,
                            'patient_id' => $patient->id,
                            'profissional_id' => $medico->id,
                            'status' => $docContratoStatus,
                            'hash_documento' => $hashContrato,
                            'token_acesso' => Str::random(64),
                            'token_expira_em' => now()->addHours(48),
                            'empresa_id' => $empresa->id,
                            'created_by' => $medico->id,
                            'updated_by' => $medico->id,
                        ]);

                        if ($docContratoStatus === 'assinado_paciente' || $docContratoStatus === 'finalizado') {
                            Assinatura::create([
                                'documento_assinavel_id' => $docContrato->id,
                                'tipo_assinatura' => 'paciente',
                                'nome_assinante' => $patient->nome_completo,
                                'documento_assinante' => fake()->numerify('###########'),
                                'ip' => fake()->ipv4(),
                                'user_agent' => fake()->userAgent(),
                                'data_assinatura' => $session->data_sessao->addHours(fake()->numberBetween(1, 24)),
                                'hash_assinatura' => hash('sha256', Str::random(64)),
                            ]);
                        }

                        if ($docContratoStatus === 'finalizado') {
                            Assinatura::create([
                                'documento_assinavel_id' => $docContrato->id,
                                'tipo_assinatura' => 'profissional',
                                'nome_assinante' => $medico->name,
                                'documento_assinante' => fake()->numerify('###########'),
                                'ip' => fake()->ipv4(),
                                'user_agent' => fake()->userAgent(),
                                'data_assinatura' => $session->data_sessao->addHours(fake()->numberBetween(25, 48)),
                                'hash_assinatura' => hash('sha256', Str::random(64)),
                            ]);

                            $contrato->update(['status' => 'assinado']);
                        }
                    }
                }
            }
        }

        // Criar agendamentos (10 por empresa)
        $profissionaisAgenda = [$admin, $medico];
        $horasDisponiveis = ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00'];

        for ($a = 0; $a < 10; $a++) {
            $patient = $patients->random();
            $prof = fake()->randomElement($profissionaisAgenda);
            $selectedProcs = $procedimentos->random(fake()->numberBetween(1, 3));
            $duracaoTotal = $selectedProcs->sum('duracao_media_minutos');
            $horaInicio = fake()->randomElement($horasDisponiveis);
            $horaFim = Carbon::createFromFormat('H:i', $horaInicio)->addMinutes($duracaoTotal)->format('H:i');

            $rand = fake()->numberBetween(1, 100);
            if ($rand <= 40) {
                $status = 'agendado';
                $dataAgendamento = fake()->dateTimeBetween('+1 day', '+30 days')->format('Y-m-d');
            } elseif ($rand <= 65) {
                $status = 'confirmado';
                $dataAgendamento = fake()->dateTimeBetween('+1 day', '+14 days')->format('Y-m-d');
            } elseif ($rand <= 80) {
                $status = 'realizado';
                $dataAgendamento = fake()->dateTimeBetween('-15 days', '-1 day')->format('Y-m-d');
            } elseif ($rand <= 90) {
                $status = 'cancelado';
                $dataAgendamento = fake()->dateTimeBetween('-7 days', '+7 days')->format('Y-m-d');
            } else {
                $status = 'nao_compareceu';
                $dataAgendamento = fake()->dateTimeBetween('-10 days', '-1 day')->format('Y-m-d');
            }

            $agendamento = Agendamento::create([
                'patient_id' => $patient->id,
                'profissional_id' => $prof->id,
                'data_agendamento' => $dataAgendamento,
                'hora_inicio' => $horaInicio,
                'hora_fim' => $horaFim,
                'status' => $status,
                'tipo_atendimento' => fake()->randomElement(['procedimento', 'consulta']),
                'origem' => 'manual',
                'observacoes' => fake()->optional(0.3)->sentence(),
                'motivo_cancelamento' => $status === 'cancelado' ? fake()->sentence() : null,
                'empresa_id' => $empresa->id,
                'created_by' => $prof->id,
                'updated_by' => $prof->id,
            ]);

            foreach ($selectedProcs as $proc) {
                $agendamento->procedimentos()->attach($proc->id, [
                    'quantidade' => fake()->optional(0.5)->randomFloat(1, 1, 50),
                    'observacoes' => fake()->optional(0.3)->sentence(),
                ]);
            }
        }
    }
}
