<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('procedimentos.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $procedimento->nome }}</h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    {{ $procedimento->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $procedimento->ativo ? 'Ativo' : 'Inativo' }}
                </span>
            </div>
            <a href="{{ route('procedimentos.edit', $procedimento) }}"
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
                    <x-clinic-card title="Informacoes do Procedimento">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Categoria</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $procedimento->categoria === 'facial' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $procedimento->categoria === 'corporal' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $procedimento->categoria === 'capilar' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $procedimento->categoria === 'outro' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ ucfirst($procedimento->categoria) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Duracao Media</dt>
                                <dd class="text-sm text-gray-900">{{ $procedimento->duracao_media_minutos }} minutos</dd>
                            </div>
                            @if($procedimento->valor_padrao)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Valor Padrao</dt>
                                <dd class="text-sm text-gray-900">R$ {{ number_format($procedimento->valor_padrao, 2, ',', '.') }}</dd>
                            </div>
                            @endif
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Descricao Clinica</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $procedimento->descricao_clinica }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Indicacoes</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $procedimento->indicacoes }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Contraindicacoes</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $procedimento->contraindicacoes }}</dd>
                            </div>
                            @if($procedimento->observacoes_internas)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Observacoes Internas</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $procedimento->observacoes_internas }}</dd>
                            </div>
                            @endif
                            @if($procedimento->termo_padrao)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Termo Padrao</dt>
                                <dd class="text-sm text-gray-900 mt-1 whitespace-pre-line">{{ $procedimento->termo_padrao }}</dd>
                            </div>
                            @endif
                        </dl>
                    </x-clinic-card>
                </div>

                <div class="space-y-6">
                    <x-clinic-card title="Ultimas Sessoes">
                        @forelse($procedimento->treatmentSessions as $session)
                            <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                                <div>
                                    <a href="{{ route('patients.sessions.show', [$session->patient, $session]) }}"
                                       class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                        {{ $session->patient->nome_completo }}
                                    </a>
                                    @if($session->pivot->quantidade)
                                        <p class="text-xs text-gray-500">{{ $session->pivot->quantidade }}U</p>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-400">{{ $session->data_sessao->format('d/m/Y') }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 text-center py-4">Nenhuma sessao com este procedimento.</p>
                        @endforelse
                    </x-clinic-card>

                    <x-confirm-modal
                        :action="route('procedimentos.destroy', $procedimento)"
                        title="Remover Procedimento"
                        message="Tem certeza que deseja remover este procedimento? Sessoes existentes nao serao afetadas."
                        confirmText="Remover">
                        <x-slot name="trigger">
                            <button type="button" class="w-full text-center px-4 py-2 bg-red-50 text-red-600 rounded-md hover:bg-red-100 text-sm">
                                Remover Procedimento
                            </button>
                        </x-slot>
                    </x-confirm-modal>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
