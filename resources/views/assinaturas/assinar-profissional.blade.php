<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Assinar como Profissional</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Info do documento --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-3">Documento</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Paciente:</span>
                        <span class="font-medium text-gray-800">{{ $documento->patient->nome_completo }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Tipo:</span>
                        <span class="font-medium text-gray-800">{{ ucfirst($documento->tipo_documento) }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Status:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Aguardando assinatura profissional
                        </span>
                    </div>
                </div>
            </div>

            {{-- Assinatura do paciente (ja realizada) --}}
            @if ($documento->assinaturaPaciente)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-3">Assinatura do Paciente (realizada)</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Nome:</span>
                            <span class="font-medium">{{ $documento->assinaturaPaciente->nome_assinante }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">CPF:</span>
                            <span class="font-medium">***.***.{{ substr($documento->assinaturaPaciente->documento_assinante, 6, 3) }}-**</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Data:</span>
                            <span class="font-medium">{{ $documento->assinaturaPaciente->data_assinatura->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                    @if ($documento->assinaturaPaciente->assinatura_imagem)
                        <div class="mt-3 p-2 bg-gray-50 rounded border max-w-xs">
                            <img src="{{ Storage::disk(config('assinatura.signature_disk'))->url($documento->assinaturaPaciente->assinatura_imagem) }}"
                                 alt="Assinatura do paciente" loading="lazy" decoding="async" class="max-h-24">
                        </div>
                    @endif
                </div>
            @endif

            {{-- Formulario de assinatura profissional --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Sua Assinatura</h3>

                <form method="POST" action="{{ route('assinatura.profissional.store', $documento) }}">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label for="nome_assinante" class="block text-sm font-medium text-gray-700">Nome completo</label>
                            <input type="text" name="nome_assinante" id="nome_assinante"
                                value="{{ old('nome_assinante', auth()->user()->name) }}"
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

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('assinatura.show', $documento) }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Assinar e Finalizar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
