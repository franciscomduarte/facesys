<x-assinatura-layout>
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-1">Assinatura de Documento</h2>
        <p class="text-sm text-gray-500 mb-6">Leia atentamente o conteudo abaixo antes de assinar.</p>

        {{-- Dados do paciente --}}
        <div class="border-b border-gray-200 pb-4 mb-4">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Paciente</h3>
            <p class="text-gray-800 font-medium">{{ $documento->patient->nome_completo }}</p>
        </div>

        {{-- Conteudo do documento --}}
        @if ($documento->tipo_documento === 'prescricao')
            <div class="border-b border-gray-200 pb-4 mb-4">
                <h3 class="text-sm font-medium text-gray-500 mb-3">Prescricao Medica</h3>
                <div class="text-sm text-gray-600 mb-2">
                    <span class="font-medium">Profissional:</span> {{ $documento->documento->profissional_responsavel }}
                </div>
                <div class="text-sm text-gray-600 mb-3">
                    <span class="font-medium">Data de emissao:</span> {{ $documento->documento->data_emissao?->format('d/m/Y') }}
                </div>

                @if ($documento->documento->observacoes_gerais)
                    <div class="text-sm text-gray-600 mb-3">
                        <span class="font-medium">Observacoes:</span> {{ $documento->documento->observacoes_gerais }}
                    </div>
                @endif

                <h4 class="text-sm font-medium text-gray-700 mb-2">Medicamentos</h4>
                <div class="space-y-2">
                    @foreach ($documento->documento->items as $item)
                        <div class="bg-gray-50 rounded p-3 text-sm">
                            <p class="font-medium text-gray-800">{{ $item->medicamento }}</p>
                            <div class="text-gray-600 mt-1 grid grid-cols-2 gap-1">
                                <span>Dosagem: {{ $item->dosagem }}</span>
                                <span>Via: {{ $item->via_administracao }}</span>
                                <span>Frequencia: {{ $item->frequencia }}</span>
                                @if ($item->duracao)
                                    <span>Duracao: {{ $item->duracao }}</span>
                                @endif
                            </div>
                            @if ($item->observacoes)
                                <p class="text-gray-500 mt-1">{{ $item->observacoes }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Formulario de assinatura --}}
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Assinar Documento</h3>

        <form method="POST" action="{{ route('assinatura.paciente.store', $documento->token_acesso) }}">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="nome_assinante" class="block text-sm font-medium text-gray-700">Nome completo</label>
                    <input type="text" name="nome_assinante" id="nome_assinante" value="{{ old('nome_assinante', $documento->patient->nome_completo) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    @error('nome_assinante')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="documento_assinante" class="block text-sm font-medium text-gray-700">CPF (apenas numeros)</label>
                    <input type="text" name="documento_assinante" id="documento_assinante" value="{{ old('documento_assinante') }}"
                        maxlength="11" inputmode="numeric" pattern="\d{11}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    @error('documento_assinante')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <x-signature-pad name="assinatura_imagem_base64" label="Desenhe sua assinatura abaixo" />
                @error('assinatura_imagem_base64')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div class="flex items-start">
                    <input type="checkbox" name="consentimento" id="consentimento" value="1"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mt-1">
                    <label for="consentimento" class="ml-2 text-sm text-gray-600">
                        Declaro que li e concordo com o conteudo deste documento. Estou ciente de que esta assinatura
                        eletronica tem validade juridica e que meus dados serao tratados conforme a LGPD.
                    </label>
                </div>
                @error('consentimento')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror

                <button type="submit"
                    class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Assinar Documento
                </button>
            </div>
        </form>
    </div>
</x-assinatura-layout>
