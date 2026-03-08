<section id="funcionalidades" class="py-20 lg:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-1.5 bg-purple-50 text-purple-600 text-sm font-semibold rounded-full mb-4">Funcionalidades</span>
            <h2 class="text-3xl sm:text-4xl font-bold mb-6">Ferramentas poderosas para sua clínica</h2>
            <p class="text-lg text-gray-600">Cada funcionalidade foi desenvolvida pensando nas necessidades reais de profissionais da estética.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $features = [
                    ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'title' => 'Prontuário digital', 'desc' => 'Registre todo o histórico clínico dos pacientes de forma organizada e acessível.', 'benefit' => 'Acesso rápido ao histórico completo'],
                    ['icon' => 'M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Mapa facial interativo', 'desc' => 'Marque pontos de aplicação de botox e preenchimentos diretamente no mapa facial.', 'benefit' => 'Precisão nos procedimentos'],
                    ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'title' => 'Agenda inteligente', 'desc' => 'Organize atendimentos por profissional com visualização diária e semanal.', 'benefit' => 'Zero conflitos de horário'],
                    ['icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z', 'title' => 'Prescrição digital', 'desc' => 'Gere prescrições profissionais com assinatura digital e envio ao paciente.', 'benefit' => 'Profissionalismo e agilidade'],
                    ['icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'title' => 'Fotos antes/depois', 'desc' => 'Registre e compare a evolução dos tratamentos com upload de fotos organizadas.', 'benefit' => 'Acompanhamento visual dos resultados'],
                    ['icon' => 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z', 'title' => 'Contratos e assinatura', 'desc' => 'Gere contratos a partir de templates e colete assinaturas eletrônicas dos pacientes.', 'benefit' => 'Segurança jurídica garantida'],
                    ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title' => 'Relatórios clínicos', 'desc' => 'Acompanhe indicadores da clínica com relatórios detalhados de atendimentos.', 'benefit' => 'Decisões baseadas em dados'],
                    ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'title' => 'Gestão multiusuário', 'desc' => 'Gerencie múltiplos profissionais com níveis de acesso e permissões personalizadas.', 'benefit' => 'Controle total da equipe'],
                    ['icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'title' => 'Conformidade LGPD', 'desc' => 'Dados dos pacientes protegidos com criptografia e em total conformidade com a LGPD.', 'benefit' => 'Tranquilidade e confiança'],
                ];
            @endphp

            @foreach($features as $feature)
                <div class="group relative bg-white rounded-2xl p-6 border border-gray-100 hover:border-purple-100 hover:shadow-xl hover:shadow-purple-500/5 transition-all duration-300">
                    <div class="w-12 h-12 bg-gradient-to-br from-rose-50 to-purple-50 rounded-xl flex items-center justify-center mb-4 group-hover:from-rose-100 group-hover:to-purple-100 transition-all">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $feature['icon'] }}"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">{{ $feature['title'] }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed mb-3">{{ $feature['desc'] }}</p>
                    <span class="inline-flex items-center text-xs font-medium text-purple-600 bg-purple-50 px-3 py-1 rounded-full">
                        {{ $feature['benefit'] }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</section>
