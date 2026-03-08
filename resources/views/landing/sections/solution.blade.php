<section class="py-20 lg:py-28 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-white to-rose-50/50"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-1.5 bg-green-50 text-green-600 text-sm font-semibold rounded-full mb-4">A solução</span>
            <h2 class="text-3xl sm:text-4xl font-bold mb-6">Tudo que sua clínica precisa em um só lugar</h2>
            <p class="text-lg text-gray-600">O SkinFlow foi desenvolvido especificamente para clínicas de estética. Cada funcionalidade foi pensada para simplificar sua rotina e profissionalizar sua gestão.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $solutions = [
                    ['gradient' => 'from-rose-500 to-pink-500', 'bg' => 'bg-rose-50', 'title' => 'Organização total', 'desc' => 'Centralize todas as informações da sua clínica: pacientes, procedimentos, agenda e documentos em um único lugar.', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                    ['gradient' => 'from-purple-500 to-indigo-500', 'bg' => 'bg-purple-50', 'title' => 'Documentação segura', 'desc' => 'Prontuários digitais, prescrições, contratos com assinatura eletrônica. Tudo armazenado com segurança e em conformidade.', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                    ['gradient' => 'from-amber-500 to-orange-500', 'bg' => 'bg-amber-50', 'title' => 'Gestão inteligente', 'desc' => 'Agenda automatizada, controle por profissional, relatórios clínicos e acompanhamento completo dos resultados.', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                ];
            @endphp

            @foreach($solutions as $solution)
                <div class="bg-white rounded-2xl p-8 border border-gray-100 hover:shadow-xl transition-all duration-300 text-center">
                    <div class="w-16 h-16 {{ $solution['bg'] }} rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $solution['icon'] }}"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">{{ $solution['title'] }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $solution['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
