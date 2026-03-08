<section class="py-20 lg:py-28 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-1.5 bg-red-50 text-red-600 text-sm font-semibold rounded-full mb-4">Problemas comuns</span>
            <h2 class="text-3xl sm:text-4xl font-bold mb-6">Sua clínica enfrenta algum desses desafios?</h2>
            <p class="text-lg text-gray-600">Se a gestão da clínica não é organizada, o crescimento se torna um problema.</p>
        </div>

        {{-- Problems Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $problems = [
                    ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'title' => 'Agenda desorganizada', 'desc' => 'Horários conflitantes, cancelamentos sem controle e falta de visão geral dos atendimentos.'],
                    ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'title' => 'Prontuários em papel', 'desc' => 'Informações perdidas, dificuldade de consulta e risco de extravio de dados importantes.'],
                    ['icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'title' => 'Sem histórico de pacientes', 'desc' => 'Impossibilidade de acompanhar a evolução dos tratamentos e procedimentos realizados.'],
                    ['icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'title' => 'Fotos antes/depois perdidas', 'desc' => 'Sem registro visual organizado para acompanhar resultados e mostrar evolução ao paciente.'],
                    ['icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'title' => 'Risco jurídico', 'desc' => 'Falta de documentação adequada, contratos e termos de consentimento colocam a clínica em risco.'],
                    ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title' => 'Sem controle de resultados', 'desc' => 'Dificuldade em medir a eficiência dos procedimentos e acompanhar indicadores da clínica.'],
                ];
            @endphp

            @foreach($problems as $problem)
                <div class="bg-white rounded-2xl p-6 border border-gray-100 hover:border-red-100 hover:shadow-lg hover:shadow-red-500/5 transition-all duration-300 group">
                    <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-red-100 transition-colors">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $problem['icon'] }}"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">{{ $problem['title'] }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $problem['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
