<x-landing-layout>
    <x-slot:title>Demonstração - SkinFlow</x-slot:title>

    <section class="pt-32 pb-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-block px-4 py-1.5 bg-purple-50 text-purple-600 text-sm font-semibold rounded-full mb-4">Acesso gratuito</span>
                <h1 class="text-4xl sm:text-5xl font-bold mb-6">Teste o SkinFlow agora</h1>
                <p class="text-lg text-gray-600">Explore todas as funcionalidades do sistema com nosso ambiente de demonstração. Sem compromisso, sem cadastro.</p>
            </div>

            {{-- Demo credentials card --}}
            <div class="bg-gradient-to-b from-gray-900 to-gray-800 rounded-2xl p-8 mb-8 text-white">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-rose-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold mb-2">Credenciais de acesso</h2>
                    <p class="text-gray-400 text-sm">Use os dados abaixo para entrar no sistema demonstrativo</p>
                </div>

                <div class="space-y-4 max-w-sm mx-auto">
                    <div class="bg-white/10 rounded-xl p-4">
                        <label class="block text-xs text-gray-400 uppercase tracking-wider mb-1">E-mail</label>
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-lg">demo@skinflow.com</span>
                            <button onclick="navigator.clipboard.writeText('demo@skinflow.com')" class="p-1.5 hover:bg-white/10 rounded-lg transition-colors" title="Copiar">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="bg-white/10 rounded-xl p-4">
                        <label class="block text-xs text-gray-400 uppercase tracking-wider mb-1">Senha</label>
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-lg">password</span>
                            <button onclick="navigator.clipboard.writeText('password')" class="p-1.5 hover:bg-white/10 rounded-lg transition-colors" title="Copiar">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-rose-500 to-purple-600 text-white font-semibold rounded-full hover:shadow-lg hover:shadow-rose-500/25 transition-all duration-300 transform hover:-translate-y-0.5 text-base">
                        Acessar demonstração
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            </div>

            {{-- Info --}}
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-8">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-sm text-amber-800 font-medium">Ambiente de demonstração</p>
                        <p class="text-sm text-amber-700 mt-1">Os dados neste ambiente são fictícios e podem ser redefinidos periodicamente. Não insira informações reais de pacientes.</p>
                    </div>
                </div>
            </div>

            {{-- What you can test --}}
            <div class="bg-white rounded-2xl p-8 border border-gray-200">
                <h3 class="text-lg font-bold mb-6">O que você pode testar</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @php
                        $demoFeatures = [
                            'Cadastrar e gerenciar pacientes',
                            'Criar sessões de atendimento',
                            'Utilizar o prontuário digital',
                            'Testar o mapa facial interativo',
                            'Agendar atendimentos',
                            'Gerar prescrições digitais',
                            'Criar contratos com templates',
                            'Visualizar relatórios',
                        ];
                    @endphp
                    @foreach($demoFeatures as $feat)
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700 text-sm">{{ $feat }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</x-landing-layout>
