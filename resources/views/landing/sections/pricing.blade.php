<section id="planos" class="py-20 lg:py-28 bg-white relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-white via-rose-50/30 to-white"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-1.5 bg-green-50 text-green-600 text-sm font-semibold rounded-full mb-4">Planos</span>
            <h2 class="text-3xl sm:text-4xl font-bold mb-6">Escolha o plano ideal para sua clínica</h2>
            <p class="text-lg text-gray-600">Comece hoje mesmo e transforme a gestão da sua clínica. Sem fidelidade, cancele quando quiser.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            {{-- Plano Starter --}}
            <div class="bg-white rounded-2xl p-8 border border-gray-200 hover:shadow-xl transition-all duration-300 flex flex-col">
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Starter</h3>
                    <p class="text-gray-500 text-sm">Ideal para profissionais autônomos e clínicas iniciantes.</p>
                </div>

                <div class="mb-8">
                    <div class="flex items-baseline gap-1">
                        <span class="text-sm text-gray-500">R$</span>
                        <span class="text-5xl font-extrabold text-gray-900">29</span>
                        <span class="text-2xl font-bold text-gray-900">,90</span>
                        <span class="text-gray-500 text-sm">/mês</span>
                    </div>
                </div>

                <ul class="space-y-3 mb-8 flex-1">
                    @php
                        $starterFeatures = [
                            'Prontuário digital',
                            'Agenda de atendimentos',
                            'Cadastro de pacientes',
                            'Fotos antes/depois',
                            'Prescrição digital',
                            'Relatórios básicos',
                        ];
                    @endphp
                    @foreach($starterFeatures as $feature)
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700 text-sm">{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>

                <a href="{{ route('register') }}" class="block w-full text-center px-6 py-3.5 bg-white text-gray-900 font-semibold rounded-full border-2 border-gray-200 hover:border-gray-900 hover:bg-gray-900 hover:text-white transition-all duration-300">
                    Começar agora
                </a>
            </div>

            {{-- Plano Enterprise --}}
            <div class="bg-gradient-to-b from-gray-900 to-gray-800 rounded-2xl p-8 border border-gray-700 hover:shadow-2xl hover:shadow-purple-500/10 transition-all duration-300 relative flex flex-col">
                <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                    <span class="inline-flex items-center px-4 py-1.5 bg-gradient-to-r from-rose-500 to-purple-600 text-white text-xs font-bold rounded-full shadow-lg">
                        Mais popular
                    </span>
                </div>

                <div class="mb-8">
                    <h3 class="text-xl font-bold text-white mb-2">Enterprise</h3>
                    <p class="text-gray-400 text-sm">Para clínicas que buscam gestão completa e profissional.</p>
                </div>

                <div class="mb-8">
                    <div class="flex items-baseline gap-1">
                        <span class="text-sm text-gray-400">R$</span>
                        <span class="text-5xl font-extrabold text-white">99</span>
                        <span class="text-2xl font-bold text-white">,90</span>
                        <span class="text-gray-400 text-sm">/mês</span>
                    </div>
                </div>

                <ul class="space-y-3 mb-8 flex-1">
                    @php
                        $enterpriseFeatures = [
                            'Tudo do plano Starter',
                            'Mapa facial interativo',
                            'Assinatura eletrônica',
                            'Geração de contratos',
                            'Relatórios avançados',
                            'Gestão multiusuário',
                            'Suporte prioritário',
                        ];
                    @endphp
                    @foreach($enterpriseFeatures as $feature)
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-300 text-sm">{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>

                <a href="{{ route('register') }}" class="block w-full text-center px-6 py-3.5 bg-gradient-to-r from-rose-500 to-purple-600 text-white font-semibold rounded-full hover:shadow-lg hover:shadow-rose-500/25 transition-all duration-300 transform hover:-translate-y-0.5">
                    Contratar plano
                </a>
            </div>
        </div>

        <div class="text-center mt-12">
            <div class="inline-flex items-center gap-2 text-sm text-gray-500">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>7 dias de garantia incondicional. Cancele quando quiser, sem burocracia.</span>
            </div>
        </div>
    </div>
</section>
