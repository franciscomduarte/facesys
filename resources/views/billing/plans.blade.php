<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Planos</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-gray-900">Escolha o plano ideal para sua clinica</h3>
                <p class="text-gray-500 mt-2">Todos os planos incluem suporte e atualizacoes</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-{{ $planos->count() }} gap-6">
                @foreach($planos as $plano)
                    <div class="bg-white rounded-lg shadow-sm border {{ $plano->slug === 'enterprise' ? 'border-indigo-500 ring-2 ring-indigo-500' : 'border-gray-200' }} overflow-hidden">
                        @if($plano->slug === 'enterprise')
                            <div class="bg-indigo-500 text-white text-center py-1 text-sm font-medium">
                                Mais popular
                            </div>
                        @endif

                        <div class="p-6">
                            <h4 class="text-xl font-bold text-gray-900">{{ $plano->nome }}</h4>
                            @if($plano->descricao)
                                <p class="text-gray-500 text-sm mt-1">{{ $plano->descricao }}</p>
                            @endif

                            <div class="mt-4">
                                <span class="text-3xl font-bold text-gray-900">{{ $plano->valor_mensal_formatado }}</span>
                                <span class="text-gray-500">/mes</span>
                            </div>

                            @if($plano->valor_anual)
                                <p class="text-sm text-gray-500 mt-1">
                                    ou {{ $plano->valor_anual_formatado }}/ano
                                </p>
                            @endif

                            @if($plano->temTrial())
                                <p class="text-sm text-indigo-600 font-medium mt-2">
                                    {{ $plano->trial_dias }} dias gratis para testar
                                </p>
                            @endif

                            {{-- Limites --}}
                            <div class="mt-6 space-y-3">
                                <div class="flex items-center gap-2">
                                    <svg class="h-5 w-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-700">
                                        {{ $plano->limite_usuarios === -1 ? 'Usuarios ilimitados' : $plano->limite_usuarios . ' usuario(s)' }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="h-5 w-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-700">
                                        {{ $plano->limite_pacientes === -1 ? 'Pacientes ilimitados' : $plano->limite_pacientes . ' pacientes' }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="h-5 w-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-700">
                                        {{ $plano->limite_agendamentos_mes === -1 ? 'Agendamentos ilimitados' : $plano->limite_agendamentos_mes . ' agendamentos/mes' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Funcionalidades --}}
                            @if($plano->funcionalidades)
                                <div class="mt-4 pt-4 border-t border-gray-100 space-y-3">
                                    @php
                                        $featureLabels = [
                                            'agendamentos' => 'Agendamentos',
                                            'prescricoes' => 'Prescricoes',
                                            'contratos' => 'Contratos',
                                            'fotos_clinicas' => 'Fotos Clinicas',
                                            'assinatura_digital' => 'Assinatura Digital',
                                            'relatorios' => 'Relatorios',
                                        ];
                                    @endphp
                                    @foreach($featureLabels as $key => $label)
                                        <div class="flex items-center gap-2">
                                            @if($plano->temFuncionalidade($key))
                                                <svg class="h-5 w-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span class="text-sm text-gray-700">{{ $label }}</span>
                                            @else
                                                <svg class="h-5 w-5 text-gray-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                <span class="text-sm text-gray-400">{{ $label }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-6">
                                <a href="{{ route('billing.checkout', $plano) }}"
                                   class="block w-full text-center px-4 py-2 rounded-lg font-medium transition
                                       {{ $plano->slug === 'enterprise'
                                           ? 'bg-indigo-600 text-white hover:bg-indigo-700'
                                           : 'bg-gray-100 text-gray-900 hover:bg-gray-200' }}">
                                    Assinar {{ $plano->nome }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
