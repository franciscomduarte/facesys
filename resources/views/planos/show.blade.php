<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('planos.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $plano->nome }}</h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    {{ $plano->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $plano->ativo ? 'Ativo' : 'Inativo' }}
                </span>
            </div>
            <a href="{{ route('planos.edit', $plano) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                Editar
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <x-clinic-card title="Dados do Plano">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nome</dt>
                                <dd class="text-sm text-gray-900">{{ $plano->nome }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Slug</dt>
                                <dd class="text-sm text-gray-900">{{ $plano->slug }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Valor Mensal</dt>
                                <dd class="text-sm text-gray-900 font-semibold">{{ $plano->valor_mensal_formatado }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Valor Anual</dt>
                                <dd class="text-sm text-gray-900">{{ $plano->valor_anual_formatado ?? '-' }}</dd>
                            </div>
                            @if($plano->descricao)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Descricao</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $plano->descricao }}</dd>
                            </div>
                            @endif
                        </dl>
                    </x-clinic-card>

                    <x-clinic-card title="Limites">
                        <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Usuarios</dt>
                                <dd class="text-sm text-gray-900 font-semibold">{{ $plano->limite_usuarios === -1 ? 'Ilimitado' : $plano->limite_usuarios }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Pacientes</dt>
                                <dd class="text-sm text-gray-900 font-semibold">{{ $plano->limite_pacientes === -1 ? 'Ilimitado' : $plano->limite_pacientes }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Agendamentos/Mes</dt>
                                <dd class="text-sm text-gray-900 font-semibold">{{ $plano->limite_agendamentos_mes === -1 ? 'Ilimitado' : $plano->limite_agendamentos_mes }}</dd>
                            </div>
                        </dl>
                    </x-clinic-card>

                    <x-clinic-card title="Funcionalidades">
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
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
                                        <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    @endif
                                    <span class="text-sm text-gray-700">{{ $label }}</span>
                                </div>
                            @endforeach
                        </div>
                    </x-clinic-card>
                </div>

                <div class="space-y-6">
                    <x-clinic-card title="Estatisticas">
                        <dl class="space-y-3">
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-500">Subscriptions ativas</dt>
                                <dd class="text-sm font-semibold text-gray-900">{{ $plano->subscriptions_count }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-500">Trial</dt>
                                <dd class="text-sm font-semibold text-gray-900">{{ $plano->trial_dias }} dias</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-500">Periodicidade Padrao</dt>
                                <dd class="text-sm font-semibold text-gray-900">{{ ucfirst($plano->periodicidade_padrao) }}</dd>
                            </div>
                        </dl>
                    </x-clinic-card>

                    <x-confirm-modal
                        :action="route('planos.destroy', $plano)"
                        title="Remover Plano"
                        message="Tem certeza que deseja remover este plano? Subscriptions existentes nao serao afetadas."
                        confirmText="Remover">
                        <x-slot name="trigger">
                            <button type="button" class="w-full text-center px-4 py-2 bg-red-50 text-red-600 rounded-md hover:bg-red-100 text-sm">
                                Remover Plano
                            </button>
                        </x-slot>
                    </x-confirm-modal>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
