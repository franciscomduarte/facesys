<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Meu Plano</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @if(!$subscription)
                <x-clinic-card>
                    <div class="text-center py-8">
                        <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum plano ativo</h3>
                        <p class="text-gray-500">Sua empresa nao possui um plano ativo. Entre em contato com o suporte para ativar um plano.</p>
                    </div>
                </x-clinic-card>
            @else
                <div class="space-y-6">
                    {{-- Plano atual --}}
                    <x-clinic-card>
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $plano->nome }}</h3>
                                @if($plano->descricao)
                                    <p class="text-gray-500 mt-1">{{ $plano->descricao }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-indigo-600">{{ $subscription->valor_atual_formatado }}</div>
                                <div class="text-sm text-gray-500">/{{ $subscription->periodicidade === 'mensal' ? 'mes' : 'ano' }}</div>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center gap-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $subscription->status === 'ativa' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $subscription->status === 'trial' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $subscription->status === 'inadimplente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $subscription->status === 'cancelada' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $subscription->status === 'expirada' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ ucfirst($subscription->status) }}
                            </span>
                            @if($subscription->isTrial() && $subscription->trial_termina_em)
                                <span class="text-sm text-gray-500">Trial termina em {{ $subscription->trial_termina_em->format('d/m/Y') }}</span>
                            @endif
                            @if($subscription->proxima_cobranca)
                                <span class="text-sm text-gray-500">Proxima cobranca: {{ $subscription->proxima_cobranca->format('d/m/Y') }}</span>
                            @endif
                        </div>
                    </x-clinic-card>

                    {{-- Uso de recursos --}}
                    <x-clinic-card title="Uso de Recursos">
                        <div class="space-y-5">
                            @php
                                $recursoLabels = [
                                    'usuarios' => 'Usuarios',
                                    'pacientes' => 'Pacientes',
                                    'agendamentos_mes' => 'Agendamentos este mes',
                                ];
                            @endphp
                            @foreach($recursoLabels as $key => $label)
                                @if(isset($uso[$key]))
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                                            <span class="text-sm text-gray-500">
                                                {{ $uso[$key]['atual'] }} / {{ $uso[$key]['ilimitado'] ? 'Ilimitado' : $uso[$key]['limite'] }}
                                            </span>
                                        </div>
                                        @if(!$uso[$key]['ilimitado'])
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="h-2.5 rounded-full {{ $uso[$key]['percentual'] >= 90 ? 'bg-red-500' : ($uso[$key]['percentual'] >= 70 ? 'bg-yellow-500' : 'bg-indigo-600') }}"
                                                     style="width: {{ $uso[$key]['percentual'] }}%"></div>
                                            </div>
                                        @else
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-green-500 h-2.5 rounded-full" style="width: 100%"></div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </x-clinic-card>

                    {{-- Funcionalidades --}}
                    <x-clinic-card title="Funcionalidades do Plano">
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
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
                                <div class="flex items-center gap-2 p-2 rounded-md {{ $plano->temFuncionalidade($key) ? 'bg-green-50' : 'bg-gray-50' }}">
                                    @if($plano->temFuncionalidade($key))
                                        <svg class="h-5 w-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    @endif
                                    <span class="text-sm {{ $plano->temFuncionalidade($key) ? 'text-green-800' : 'text-gray-500' }}">{{ $label }}</span>
                                </div>
                            @endforeach
                        </div>
                    </x-clinic-card>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
