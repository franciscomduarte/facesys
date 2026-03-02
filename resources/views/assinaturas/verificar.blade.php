<x-assinatura-layout>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-1">Verificar Documento</h2>
        <p class="text-sm text-gray-500 mb-6">Insira o hash do documento para verificar sua autenticidade.</p>

        <form method="GET" action="{{ isset($hash) ? route('assinatura.verificar.hash', $hash) : '#' }}" id="verificar-form">
            <div class="flex space-x-2" x-data="{ hashValue: '{{ $hash ?? '' }}' }">
                <input type="text" x-model="hashValue" name="hash" placeholder="Hash do documento (SHA-256)"
                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm font-mono"
                    required>
                <button type="button"
                    @click="if (hashValue) window.location.href = '{{ url('verificar-documento') }}/' + hashValue"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Verificar
                </button>
            </div>
        </form>
    </div>

    @isset($hash)
        <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
            @if ($documento)
                @if ($integridadeOk)
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-green-800">Documento Verificado</h3>
                            <p class="text-sm text-green-600">A integridade do documento foi confirmada.</p>
                        </div>
                    </div>
                @else
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-red-800">Falha na Verificacao</h3>
                            <p class="text-sm text-red-600">O documento pode ter sido alterado apos a assinatura.</p>
                        </div>
                    </div>
                @endif

                <div class="border-t border-gray-200 pt-4 mt-4 space-y-3 text-sm">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-500">Tipo:</span>
                            <span class="font-medium">{{ ucfirst($documento->tipo_documento) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Status:</span>
                            <span class="font-medium">
                                @if ($documento->isFinalizado())
                                    Finalizado
                                @else
                                    {{ ucfirst(str_replace('_', ' ', $documento->status)) }}
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-500">Paciente:</span>
                            <span class="font-medium">{{ $documento->patient->nome_completo }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Profissional:</span>
                            <span class="font-medium">{{ $documento->profissional->name }}</span>
                        </div>
                    </div>

                    @if ($documento->assinaturas->count() > 0)
                        <div class="border-t border-gray-200 pt-3 mt-3">
                            <h4 class="font-medium text-gray-700 mb-2">Assinaturas</h4>
                            @foreach ($documento->assinaturas as $assinatura)
                                <div class="bg-gray-50 rounded p-3 mb-2">
                                    <div class="flex justify-between">
                                        <span class="font-medium">{{ $assinatura->nome_assinante }} ({{ ucfirst($assinatura->tipo_assinatura) }})</span>
                                        <span class="text-gray-500">{{ $assinatura->data_assinatura->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="text-gray-500 mt-1">CPF: ***.***.{{ substr($assinatura->documento_assinante, 6, 3) }}-**</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-gray-800">Documento Nao Encontrado</h3>
                        <p class="text-sm text-gray-500">Nenhum documento encontrado com este hash.</p>
                    </div>
                </div>
            @endif
        </div>
    @endisset
</x-assinatura-layout>
