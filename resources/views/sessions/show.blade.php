<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('patients.show', $patient) }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Sessao — {{ $session->data_sessao->format('d/m/Y') }}
                </h2>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('patients.sessions.edit', [$patient, $session]) }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    Editar
                </a>
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

            {{-- Dados da Sessao --}}
            <x-clinic-card title="Detalhes da Sessao">
                <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Paciente</dt>
                        <dd class="text-sm text-gray-900">
                            <a href="{{ route('patients.show', $patient) }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ $patient->nome_completo }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Data</dt>
                        <dd class="text-sm text-gray-900">{{ $session->data_sessao->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Procedimento</dt>
                        <dd class="text-sm text-gray-900">{{ $session->procedimento }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Marca do Produto</dt>
                        <dd class="text-sm text-gray-900">{{ $session->marca_produto }}</dd>
                    </div>
                    @if($session->lote)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Lote</dt>
                        <dd class="text-sm text-gray-900">{{ $session->lote }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Quantidade Total</dt>
                        <dd class="text-sm text-gray-900">{{ $session->quantidade_total }}U</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Profissional</dt>
                        <dd class="text-sm text-gray-900">{{ $session->profissional_responsavel }}</dd>
                    </div>
                    @if($session->observacoes_sessao)
                    <div class="sm:col-span-2 lg:col-span-3">
                        <dt class="text-sm font-medium text-gray-500">Observacoes</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ $session->observacoes_sessao }}</dd>
                    </div>
                    @endif
                </dl>
            </x-clinic-card>

            {{-- Procedimentos Realizados --}}
            @if($session->procedimentos->count())
            <div class="mt-6">
                <x-clinic-card title="Procedimentos Realizados">
                    <div class="divide-y divide-gray-100">
                        @foreach($session->procedimentos as $proc)
                            <div class="py-3 flex items-center justify-between">
                                <div>
                                    <a href="{{ route('procedimentos.show', $proc) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                        {{ $proc->nome }}
                                    </a>
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ $proc->categoria === 'facial' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $proc->categoria === 'corporal' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $proc->categoria === 'capilar' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $proc->categoria === 'outro' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ ucfirst($proc->categoria) }}
                                    </span>
                                    @if($proc->pivot->observacoes)
                                        <p class="text-xs text-gray-500 mt-1">{{ $proc->pivot->observacoes }}</p>
                                    @endif
                                </div>
                                @if($proc->pivot->quantidade)
                                    <span class="text-sm font-semibold text-gray-700">{{ $proc->pivot->quantidade }}U</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </x-clinic-card>
            </div>
            @endif

            {{-- Mapa Facial (read-only) --}}
            <div class="mt-6">
                <x-clinic-card title="Mapa Facial de Aplicacao">
                    <x-facial-map :points="$session->applicationPoints" :editable="false" />
                </x-clinic-card>
            </div>

            {{-- Fotos Clinicas --}}
            <div class="mt-6">
                <x-clinic-card title="Fotos Clinicas">
                    <x-photo-gallery
                        :photos="$session->clinicalPhotos"
                        :editable="true"
                        :patient="$patient"
                        :session="$session" />
                    <x-photo-upload
                        :session="$session"
                        :patient="$patient"
                        :procedimentos="$session->procedimentos" />
                </x-clinic-card>
            </div>

            {{-- Prescricao --}}
            <div class="mt-6">
                <x-clinic-card title="Prescricao">
                    @if($session->prescricao)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                @if($session->prescricao->isRascunho())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Rascunho
                                    </span>
                                @elseif($session->prescricao->isEmitida())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Emitida
                                    </span>
                                @elseif($session->prescricao->isAssinada())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Assinada
                                    </span>
                                @endif
                                <span class="text-sm text-gray-600">
                                    {{ $session->prescricao->items->count() }} medicamento(s)
                                </span>
                                @if($session->prescricao->data_emissao)
                                    <span class="text-sm text-gray-500">
                                        — Emitida em {{ $session->prescricao->data_emissao->format('d/m/Y') }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('patients.sessions.prescricao.show', [$patient, $session, $session->prescricao]) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-md hover:bg-indigo-100 text-sm font-medium transition">
                                    Ver Detalhes
                                </a>
                                @if(!$session->prescricao->isRascunho())
                                    <a href="{{ route('patients.sessions.prescricao.pdf', [$patient, $session, $session->prescricao]) }}"
                                       target="_blank"
                                       class="inline-flex items-center px-3 py-1.5 bg-gray-50 text-gray-700 rounded-md hover:bg-gray-100 text-sm font-medium transition">
                                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        PDF
                                    </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-400 mb-3">Nenhuma prescricao registrada para esta sessao.</p>
                            <a href="{{ route('patients.sessions.prescricao.create', [$patient, $session]) }}"
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Criar Prescricao
                            </a>
                        </div>
                    @endif
                </x-clinic-card>
            </div>

            {{-- Contrato --}}
            <div class="mt-6">
                <x-clinic-card title="Contrato">
                    @if($session->contrato)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                @if($session->contrato->isRascunho())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Rascunho
                                    </span>
                                @elseif($session->contrato->isGerado())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Gerado
                                    </span>
                                @elseif($session->contrato->isAssinado())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Assinado
                                    </span>
                                @endif
                                <span class="text-sm text-gray-600">
                                    Contrato #{{ $session->contrato->id }}
                                </span>
                                @if($session->contrato->valor_total)
                                    <span class="text-sm text-gray-500">
                                        — R$ {{ number_format($session->contrato->valor_total, 2, ',', '.') }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('patients.sessions.contrato.show', [$patient, $session, $session->contrato]) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-md hover:bg-indigo-100 text-sm font-medium transition">
                                    Ver Detalhes
                                </a>
                                @if(!$session->contrato->isRascunho())
                                    <a href="{{ route('patients.sessions.contrato.pdf', [$patient, $session, $session->contrato]) }}"
                                       target="_blank"
                                       class="inline-flex items-center px-3 py-1.5 bg-gray-50 text-gray-700 rounded-md hover:bg-gray-100 text-sm font-medium transition">
                                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        PDF
                                    </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-400 mb-3">Nenhum contrato gerado para esta sessao.</p>
                            <a href="{{ route('patients.sessions.contrato.create', [$patient, $session]) }}"
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Gerar Contrato
                            </a>
                        </div>
                    @endif
                </x-clinic-card>
            </div>

            {{-- Acoes --}}
            <div class="mt-6 flex justify-end">
                <x-confirm-modal
                    :action="route('patients.sessions.destroy', [$patient, $session])"
                    title="Remover Sessao"
                    message="Tem certeza que deseja remover esta sessao e todos os seus pontos de aplicacao?"
                    confirmText="Remover">
                    <x-slot name="trigger">
                        <button type="button" class="px-4 py-2 bg-red-50 text-red-600 rounded-md hover:bg-red-100 text-sm">
                            Remover Sessao
                        </button>
                    </x-slot>
                </x-confirm-modal>
            </div>
        </div>
    </div>
</x-app-layout>
