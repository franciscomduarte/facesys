<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('agendamentos.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Agendamento — {{ $agendamento->data_agendamento->format('d/m/Y') }}
                </h2>
                @php
                    $statusColors = [
                        'agendado' => 'bg-yellow-100 text-yellow-800',
                        'confirmado' => 'bg-green-100 text-green-800',
                        'cancelado' => 'bg-red-100 text-red-800',
                        'realizado' => 'bg-blue-100 text-blue-800',
                        'nao_compareceu' => 'bg-gray-100 text-gray-800',
                    ];
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$agendamento->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst(str_replace('_', ' ', $agendamento->status)) }}
                </span>
            </div>
            <div class="flex gap-2">
                @if($agendamento->podeEditar())
                    @can('update', $agendamento)
                        <a href="{{ route('agendamentos.edit', $agendamento) }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                            Editar
                        </a>
                    @endcan
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Coluna principal --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Dados do Agendamento --}}
                    <x-clinic-card title="Detalhes do Agendamento">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Paciente</dt>
                                <dd class="text-sm text-gray-900">
                                    <a href="{{ route('patients.show', $agendamento->patient) }}" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $agendamento->patient->nome_completo }}
                                    </a>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Profissional</dt>
                                <dd class="text-sm text-gray-900">{{ $agendamento->profissional->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Data</dt>
                                <dd class="text-sm text-gray-900">{{ $agendamento->data_agendamento->format('d/m/Y') }} ({{ $agendamento->data_agendamento->translatedFormat('l') }})</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Horario</dt>
                                <dd class="text-sm text-gray-900">{{ $agendamento->hora_inicio }} - {{ $agendamento->hora_fim }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                                <dd class="text-sm text-gray-900">{{ ucfirst($agendamento->tipo_atendimento) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Origem</dt>
                                <dd class="text-sm text-gray-900">{{ ucfirst($agendamento->origem) }}</dd>
                            </div>
                        </dl>

                        @if($agendamento->observacoes)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <dt class="text-sm font-medium text-gray-500 mb-1">Observacoes</dt>
                                <dd class="text-sm text-gray-900">{{ $agendamento->observacoes }}</dd>
                            </div>
                        @endif

                        @if($agendamento->motivo_cancelamento)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <dt class="text-sm font-medium text-gray-500 mb-1">Motivo do Cancelamento</dt>
                                <dd class="text-sm text-red-700">{{ $agendamento->motivo_cancelamento }}</dd>
                            </div>
                        @endif
                    </x-clinic-card>

                    {{-- Procedimentos --}}
                    <x-clinic-card title="Procedimentos">
                        <div class="space-y-3">
                            @foreach($agendamento->procedimentos as $proc)
                                <div class="p-3 bg-gray-50 rounded-lg border">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-sm text-gray-900">{{ $proc->nome }}</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                {{ ucfirst($proc->categoria) }}
                                            </span>
                                            <span class="text-xs text-gray-500">{{ $proc->duracao_media_minutos }} min</span>
                                        </div>
                                    </div>
                                    @if($proc->pivot->quantidade || $proc->pivot->observacoes)
                                        <div class="mt-2 flex gap-4 text-xs text-gray-600">
                                            @if($proc->pivot->quantidade)
                                                <span>Qtd: {{ $proc->pivot->quantidade }}</span>
                                            @endif
                                            @if($proc->pivot->observacoes)
                                                <span>{{ $proc->pivot->observacoes }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 text-sm text-gray-500">
                            Duracao estimada total: <strong>{{ $agendamento->duracao_estimada }} min</strong>
                        </div>
                    </x-clinic-card>

                    {{-- Sessao Vinculada --}}
                    @if($agendamento->treatmentSession)
                        <x-clinic-card title="Sessao Vinculada">
                            <a href="{{ route('patients.sessions.show', [$agendamento->patient, $agendamento->treatmentSession]) }}"
                               class="text-indigo-600 hover:text-indigo-900">
                                Sessao de {{ $agendamento->treatmentSession->data_sessao->format('d/m/Y') }}
                                — {{ $agendamento->treatmentSession->procedimento }}
                            </a>
                        </x-clinic-card>
                    @endif
                </div>

                {{-- Sidebar - Acoes --}}
                <div class="space-y-6">
                    {{-- Acoes rapidas --}}
                    <x-clinic-card title="Acoes">
                        <div class="space-y-3">
                            @if($agendamento->isAgendado())
                                @can('confirmar', $agendamento)
                                    <form method="POST" action="{{ route('agendamentos.confirmar', $agendamento) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="w-full px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-md hover:bg-green-700 transition">
                                            Confirmar Agendamento
                                        </button>
                                    </form>
                                @endcan
                            @endif

                            @if($agendamento->podeCancelar())
                                @can('cancelar', $agendamento)
                                    <div x-data="{ showCancelar: false }">
                                        <button @click="showCancelar = !showCancelar" type="button"
                                                class="w-full px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-md hover:bg-red-700 transition">
                                            Cancelar Agendamento
                                        </button>
                                        <form x-show="showCancelar" x-cloak method="POST" action="{{ route('agendamentos.cancelar', $agendamento) }}" class="mt-3">
                                            @csrf
                                            @method('PATCH')
                                            <textarea name="motivo_cancelamento" rows="2" placeholder="Motivo do cancelamento (opcional)..."
                                                      class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                            <button type="submit"
                                                    class="mt-2 w-full px-4 py-2 bg-red-500 text-white text-xs font-semibold rounded-md hover:bg-red-600 transition">
                                                Confirmar Cancelamento
                                            </button>
                                        </form>
                                    </div>
                                @endcan
                            @endif

                            @if($agendamento->isConfirmado())
                                @can('marcarNaoCompareceu', $agendamento)
                                    <form method="POST" action="{{ route('agendamentos.nao-compareceu', $agendamento) }}"
                                          onsubmit="return confirm('Marcar como nao compareceu?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="w-full px-4 py-2 bg-gray-600 text-white text-sm font-semibold rounded-md hover:bg-gray-700 transition">
                                            Nao Compareceu
                                        </button>
                                    </form>
                                @endcan
                            @endif

                            @if($agendamento->podeEditar())
                                @can('update', $agendamento)
                                    <a href="{{ route('agendamentos.edit', $agendamento) }}"
                                       class="block w-full px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-700 transition text-center">
                                        Editar / Remarcar
                                    </a>
                                @endcan
                            @endif

                            @can('delete', $agendamento)
                                <form method="POST" action="{{ route('agendamentos.destroy', $agendamento) }}"
                                      onsubmit="return confirm('Tem certeza que deseja remover este agendamento?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full px-4 py-2 bg-white border border-red-300 text-red-600 text-sm font-semibold rounded-md hover:bg-red-50 transition">
                                        Remover Agendamento
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </x-clinic-card>

                    {{-- Info --}}
                    <x-clinic-card title="Informacoes">
                        <dl class="space-y-3 text-sm">
                            <div>
                                <dt class="text-gray-500">Criado em</dt>
                                <dd class="text-gray-900">{{ $agendamento->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            @if($agendamento->updated_at->ne($agendamento->created_at))
                                <div>
                                    <dt class="text-gray-500">Atualizado em</dt>
                                    <dd class="text-gray-900">{{ $agendamento->updated_at->format('d/m/Y H:i') }}</dd>
                                </div>
                            @endif
                        </dl>
                    </x-clinic-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
