<section class="py-20 lg:py-28 bg-gray-900 text-white relative overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-rose-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-1.5 bg-white/10 text-rose-300 text-sm font-semibold rounded-full mb-4">Tecnologia e seguranca</span>
            <h2 class="text-3xl sm:text-4xl font-bold mb-6">Construido com tecnologia de ponta</h2>
            <p class="text-lg text-gray-400">Infraestrutura moderna, segura e escalavel para acompanhar o crescimento da sua clinica.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $techItems = [
                    ['icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4', 'title' => 'Laravel', 'desc' => 'Framework PHP robusto e consolidado, utilizado por milhares de empresas no mundo.'],
                    ['icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'title' => 'Arquitetura SaaS', 'desc' => 'Sistema multi-tenant preparado para atender multiplas clinicas com isolamento de dados.'],
                    ['icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'title' => 'Protecao de dados', 'desc' => 'Criptografia, backups automaticos e politicas de acesso para proteger informacoes sensiveis.'],
                    ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Conformidade LGPD', 'desc' => 'Tratamento de dados pessoais em conformidade com a Lei Geral de Protecao de Dados.'],
                    ['icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'title' => 'Alta disponibilidade', 'desc' => 'Infraestrutura escalavel com uptime garantido para sua clinica nunca ficar parada.'],
                    ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'Performance', 'desc' => 'Sistema otimizado para carregamento rapido e experiencia fluida em qualquer dispositivo.'],
                ];
            @endphp

            @foreach($techItems as $item)
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:border-white/20 hover:bg-white/10 transition-all duration-300">
                    <div class="w-12 h-12 bg-gradient-to-br from-rose-500/20 to-purple-500/20 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">{{ $item['title'] }}</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">{{ $item['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
