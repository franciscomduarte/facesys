<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detalhes da Assinatura</h2>
            <a href="{{ url()->previous() }}" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Voltar</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="rounded-md bg-green-50 p-4">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Status do documento --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Documento</h3>
                    @php
                        $statusColors = [
                            'pendente' => 'bg-yellow-100 text-yellow-800',
                            'assinado_paciente' => 'bg-blue-100 text-blue-800',
                            'assinado_profissional' => 'bg-blue-100 text-blue-800',
                            'finalizado' => 'bg-green-100 text-green-800',
                        ];
                        $statusLabels = [
                            'pendente' => 'Pendente',
                            'assinado_paciente' => 'Assinado pelo Paciente',
                            'assinado_profissional' => 'Assinado pelo Profissional',
                            'finalizado' => 'Finalizado',
                        ];
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$documento->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$documento->status] ?? $documento->status }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Tipo:</span>
                        <span class="font-medium">{{ ucfirst($documento->tipo_documento) }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Paciente:</span>
                        <span class="font-medium">{{ $documento->patient->nome_completo }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Profissional:</span>
                        <span class="font-medium">{{ $documento->profissional->name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Criado em:</span>
                        <span class="font-medium">{{ $documento->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="text-gray-500">Hash do documento:</span>
                        <code class="text-xs bg-gray-100 px-2 py-1 rounded font-mono break-all">{{ $documento->hash_documento }}</code>
                    </div>
                </div>
            </div>

            {{-- Token de acesso (se nao finalizado) --}}
            @if (!$documento->isFinalizado())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-3">Link de Assinatura do Paciente</h3>

                    @if ($documento->tokenExpirado())
                        <div class="rounded-md bg-red-50 p-3 mb-3">
                            <p class="text-sm text-red-700">Token expirado em {{ $documento->token_expira_em->format('d/m/Y H:i') }}</p>
                        </div>
                        <form method="POST" action="{{ route('assinatura.regenerar-token', $documento) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Regenerar Token
                            </button>
                        </form>
                    @else
                        <div class="flex items-center space-x-2" x-data="{ copied: false }">
                            <input type="text" readonly value="{{ route('assinatura.paciente', $documento->token_acesso) }}"
                                class="flex-1 text-sm rounded-md border-gray-300 bg-gray-50" id="link-assinatura">
                            <button type="button"
                                @click="navigator.clipboard.writeText(document.getElementById('link-assinatura').value); copied = true; setTimeout(() => copied = false, 2000)"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <span x-show="!copied">Copiar</span>
                                <span x-show="copied" class="text-green-600">Copiado!</span>
                            </button>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            Expira em: {{ $documento->token_expira_em->format('d/m/Y H:i') }}
                            ({{ $documento->token_expira_em->diffForHumans() }})
                        </p>
                    @endif
                </div>
            @endif

            {{-- Assinaturas --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Assinatura Paciente --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-3">Assinatura do Paciente</h3>
                    @if ($documento->assinaturaPaciente)
                        <div class="space-y-2 text-sm">
                            <div><span class="text-gray-500">Nome:</span> <span class="font-medium">{{ $documento->assinaturaPaciente->nome_assinante }}</span></div>
                            <div><span class="text-gray-500">CPF:</span> <span class="font-medium">***.***.{{ substr($documento->assinaturaPaciente->documento_assinante, 6, 3) }}-**</span></div>
                            <div><span class="text-gray-500">Data:</span> <span class="font-medium">{{ $documento->assinaturaPaciente->data_assinatura->format('d/m/Y H:i') }}</span></div>
                            <div><span class="text-gray-500">IP:</span> <span class="font-medium">{{ $documento->assinaturaPaciente->ip }}</span></div>
                        </div>
                        @if ($documento->assinaturaPaciente->assinatura_imagem)
                            <div class="mt-3 p-2 bg-gray-50 rounded border">
                                <img src="{{ Storage::disk(config('assinatura.signature_disk'))->url($documento->assinaturaPaciente->assinatura_imagem) }}"
                                     alt="Assinatura do paciente" class="max-h-20">
                            </div>
                        @endif
                    @else
                        <p class="text-sm text-gray-400 italic">Aguardando assinatura</p>
                    @endif
                </div>

                {{-- Assinatura Profissional --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-3">Assinatura do Profissional</h3>
                    @if ($documento->assinaturaProfissional)
                        <div class="space-y-2 text-sm">
                            <div><span class="text-gray-500">Nome:</span> <span class="font-medium">{{ $documento->assinaturaProfissional->nome_assinante }}</span></div>
                            <div><span class="text-gray-500">CPF:</span> <span class="font-medium">***.***.{{ substr($documento->assinaturaProfissional->documento_assinante, 6, 3) }}-**</span></div>
                            <div><span class="text-gray-500">Data:</span> <span class="font-medium">{{ $documento->assinaturaProfissional->data_assinatura->format('d/m/Y H:i') }}</span></div>
                            <div><span class="text-gray-500">IP:</span> <span class="font-medium">{{ $documento->assinaturaProfissional->ip }}</span></div>
                        </div>
                        @if ($documento->assinaturaProfissional->assinatura_imagem)
                            <div class="mt-3 p-2 bg-gray-50 rounded border">
                                <img src="{{ Storage::disk(config('assinatura.signature_disk'))->url($documento->assinaturaProfissional->assinatura_imagem) }}"
                                     alt="Assinatura do profissional" class="max-h-20">
                            </div>
                        @endif
                    @else
                        @if ($documento->isAssinadoPaciente())
                            <p class="text-sm text-gray-400 italic mb-3">Aguardando assinatura</p>
                            <a href="{{ route('assinatura.profissional', $documento) }}"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Assinar como Profissional
                            </a>
                        @else
                            <p class="text-sm text-gray-400 italic">Aguardando assinatura do paciente primeiro</p>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Verificacao --}}
            @if ($documento->isFinalizado())
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                    <p class="text-green-800 font-medium mb-1">Documento Assinado e Finalizado</p>
                    <p class="text-sm text-green-600">
                        Verifique a autenticidade em:
                        <a href="{{ route('assinatura.verificar.hash', $documento->hash_documento) }}" class="underline" target="_blank">
                            {{ route('assinatura.verificar.hash', $documento->hash_documento) }}
                        </a>
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
