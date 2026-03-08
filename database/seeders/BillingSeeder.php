<?php

namespace Database\Seeders;

use App\Models\FeatureFlag;
use App\Models\Module;
use App\Models\Plano;
use Illuminate\Database\Seeder;

class BillingSeeder extends Seeder
{
    public function run(): void
    {
        // Modules
        $modules = [
            ['name' => 'Agenda', 'slug' => 'agenda', 'description' => 'Agendamento de consultas e procedimentos'],
            ['name' => 'Prontuario', 'slug' => 'prontuario', 'description' => 'Prontuario eletronico e sessoes de atendimento'],
            ['name' => 'Financeiro', 'slug' => 'financeiro', 'description' => 'Controle financeiro e faturamento'],
            ['name' => 'Relatorios', 'slug' => 'relatorios', 'description' => 'Relatorios gerenciais e analiticos'],
            ['name' => 'Automacoes', 'slug' => 'automacoes', 'description' => 'Lembretes automaticos e workflows'],
            ['name' => 'Marketing', 'slug' => 'marketing', 'description' => 'Campanhas e comunicacao com pacientes'],
        ];

        $createdModules = [];
        foreach ($modules as $data) {
            $createdModules[$data['slug']] = Module::create($data);
        }

        // Feature flags
        $features = [
            ['name' => 'Acesso a API', 'slug' => 'api_access', 'description' => 'Acesso a API REST para integracoes'],
            ['name' => 'Relatorios Avancados', 'slug' => 'advanced_reports', 'description' => 'Relatorios detalhados com exportacao'],
            ['name' => 'Automacao WhatsApp', 'slug' => 'whatsapp_automation', 'description' => 'Envio automatico de mensagens via WhatsApp'],
        ];

        $createdFeatures = [];
        foreach ($features as $data) {
            $createdFeatures[$data['slug']] = FeatureFlag::create($data);
        }

        // Associate modules and features to plans
        $planos = Plano::all()->keyBy('slug');

        // Starter: agenda + prontuario
        if ($starter = $planos->get('starter')) {
            $starter->modules()->attach([
                $createdModules['agenda']->id,
                $createdModules['prontuario']->id,
            ]);
            $starter->featureFlags()->attach([
                $createdFeatures['api_access']->id => ['enabled' => false],
                $createdFeatures['advanced_reports']->id => ['enabled' => false],
                $createdFeatures['whatsapp_automation']->id => ['enabled' => false],
            ]);
        }

        // Professional: agenda + prontuario + financeiro + relatorios
        if ($professional = $planos->get('professional')) {
            $professional->modules()->attach([
                $createdModules['agenda']->id,
                $createdModules['prontuario']->id,
                $createdModules['financeiro']->id,
                $createdModules['relatorios']->id,
            ]);
            $professional->featureFlags()->attach([
                $createdFeatures['api_access']->id => ['enabled' => false],
                $createdFeatures['advanced_reports']->id => ['enabled' => true],
                $createdFeatures['whatsapp_automation']->id => ['enabled' => false],
            ]);
        }

        // Enterprise: all modules + all features
        if ($enterprise = $planos->get('enterprise')) {
            $enterprise->modules()->attach(
                collect($createdModules)->pluck('id')->toArray()
            );
            $enterprise->featureFlags()->attach([
                $createdFeatures['api_access']->id => ['enabled' => true],
                $createdFeatures['advanced_reports']->id => ['enabled' => true],
                $createdFeatures['whatsapp_automation']->id => ['enabled' => true],
            ]);
        }
    }
}
