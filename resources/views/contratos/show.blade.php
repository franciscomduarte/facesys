<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('patients.sessions.show', [$patient, $session]) }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Contrato #{{ $contrato->id }}</h2>
                @if($contrato->isRascunho())
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Rascunho</span>
                @elseif($contrato->isGerado())
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Gerado</span>
                @elseif($contrato->isAssinado())
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Assinado</span>
                @endif
            </div>
            <div class="flex gap-2">
                @if($contrato->podeEditar())
                    <a href="{{ route('patients.sessions.contrato.edit', [$patient, $session, $contrato]) }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                        Editar
                    </a>
                @endif
                @if(!$contrato->isRascunho())
                    <a href="{{ route('patients.sessions.contrato.pdf', [$patient, $session, $contrato]) }}"
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        PDF
                    </a>
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

            {{-- Dados do Contrato --}}
            <x-clinic-card title="Dados do Contrato">
                <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Paciente</dt>
                        <dd class="text-sm text-gray-900">
                            <a href="{{ route('patients.show', $patient) }}" class="text-indigo-600 hover:text-indigo-900">{{ $patient->nome_completo }}</a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Sessao</dt>
                        <dd class="text-sm text-gray-900">{{ $session->data_sessao->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Profissional</dt>
                        <dd class="text-sm text-gray-900">{{ $contrato->profissional->name ?? $session->profissional_responsavel }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Template</dt>
                        <dd class="text-sm text-gray-900">{{ $contrato->templateContrato->nome ?? 'N/A' }}</dd>
                    </div>
                    @if($contrato->valor_total)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Valor Total</dt>
                        <dd class="text-sm text-gray-900 font-semibold">R$ {{ number_format($contrato->valor_total, 2, ',', '.') }}</dd>
                    </div>
                    @endif
                    @if($contrato->data_geracao)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Data de Geracao</dt>
                        <dd class="text-sm text-gray-900">{{ $contrato->data_geracao->format('d/m/Y H:i') }}</dd>
                    </div>
                    @endif
                    @if($contrato->observacoes)
                    <div class="sm:col-span-2 lg:col-span-3">
                        <dt class="text-sm font-medium text-gray-500">Observacoes</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ $contrato->observacoes }}</dd>
                    </div>
                    @endif
                </dl>
            </x-clinic-card>

            {{-- Conteudo do Contrato --}}
            <div class="mt-6">
                <x-clinic-card title="Conteudo do Contrato">
                    <div class="prose prose-sm max-w-none text-gray-700">
                        {!! $contrato->conteudo_renderizado !!}
                    </div>
                </x-clinic-card>
            </div>

            {{-- Assinatura Digital --}}
            @if(!$contrato->isRascunho())
                <div class="mt-6">
                    <x-clinic-card title="Assinatura Digital">
                        @if($contrato->isGerado() && !$documentoAssinavel)
                            <p class="text-sm text-gray-500 mb-3">O contrato esta gerado. Solicite a assinatura para que o paciente e o profissional assinem digitalmente.</p>
                            <form method="POST" action="{{ route('patients.sessions.contrato.solicitar-assinatura', [$patient, $session, $contrato]) }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                    Solicitar Assinatura
                                </button>
                            </form>
                        @elseif($documentoAssinavel)
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <span class="text-sm text-gray-500">Status:</span>
                                    @if($documentoAssinavel->isPendente())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Aguardando Paciente</span>
                                    @elseif($documentoAssinavel->isAssinadoPaciente())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Aguardando Profissional</span>
                                    @elseif($documentoAssinavel->isFinalizado())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Assinado</span>
                                    @endif
                                </div>

                                @if($documentoAssinavel->isPendente())
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-1">Link para o paciente:</label>
                                        @if($documentoAssinavel->tokenExpirado())
                                            <p class="text-sm text-red-600 mb-2">Token expirado em {{ $documentoAssinavel->token_expira_em->format('d/m/Y H:i') }}</p>
                                            <form method="POST" action="{{ route('assinatura.regenerar-token', $documentoAssinavel) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-800 underline">Regenerar token</button>
                                            </form>
                                        @else
                                            <div class="flex items-center gap-2" x-data="{ copied: false }">
                                                <input type="text" readonly value="{{ route('assinatura.paciente', $documentoAssinavel->token_acesso) }}"
                                                    class="flex-1 text-xs rounded-md border-gray-300 bg-gray-50" id="link-assinatura-contrato">
                                                <button type="button"
                                                    @click="navigator.clipboard.writeText(document.getElementById('link-assinatura-contrato').value); copied = true; setTimeout(() => copied = false, 2000)"
                                                    class="text-sm text-indigo-600 hover:text-indigo-800">
                                                    <span x-show="!copied">Copiar</span>
                                                    <span x-show="copied" class="text-green-600">Copiado!</span>
                                                </button>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-400">Expira em {{ $documentoAssinavel->token_expira_em->format('d/m/Y H:i') }}</p>
                                        @endif
                                    </div>
                                @endif

                                @if($documentoAssinavel->isAssinadoPaciente())
                                    <a href="{{ route('assinatura.profissional', $documentoAssinavel) }}"
                                       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                        Assinar como Profissional
                                    </a>
                                @endif

                                @if($documentoAssinavel->isFinalizado())
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                        <p class="text-sm text-green-800 font-medium">Documento assinado digitalmente</p>
                                        <p class="text-xs text-green-600 mt-1">
                                            <a href="{{ route('assinatura.verificar.hash', $documentoAssinavel->hash_documento) }}" class="underline" target="_blank">Verificar autenticidade</a>
                                        </p>
                                    </div>
                                @endif

                                <a href="{{ route('assinatura.show', $documentoAssinavel) }}" class="text-sm text-indigo-600 hover:text-indigo-800 underline">
                                    Ver detalhes da assinatura
                                </a>
                            </div>
                        @endif
                    </x-clinic-card>
                </div>
            @endif

            {{-- Acoes --}}
            <div class="mt-6 flex items-center justify-between">
                <div>
                    @if($contrato->podeEditar())
                        <x-confirm-modal
                            :action="route('patients.sessions.contrato.destroy', [$patient, $session, $contrato])"
                            title="Remover Contrato"
                            message="Tem certeza que deseja remover este contrato?"
                            confirmText="Remover">
                            <x-slot name="trigger">
                                <button type="button" class="px-4 py-2 bg-red-50 text-red-600 rounded-md hover:bg-red-100 text-sm">
                                    Remover Contrato
                                </button>
                            </x-slot>
                        </x-confirm-modal>
                    @endif
                </div>
                <div>
                    @if($contrato->isRascunho())
                        <x-confirm-modal
                            :action="route('patients.sessions.contrato.gerar', [$patient, $session, $contrato])"
                            method="PATCH"
                            title="Gerar Contrato"
                            message="Apos gerar, o contrato nao podera mais ser editado. Deseja continuar?"
                            confirmText="Gerar">
                            <x-slot name="trigger">
                                <button type="button" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium">
                                    Gerar Contrato
                                </button>
                            </x-slot>
                        </x-confirm-modal>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
