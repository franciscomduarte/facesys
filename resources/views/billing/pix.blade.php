<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pagamento PIX</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
            <x-clinic-card>
                <div class="text-center space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $plano->nome }}</h3>
                        <p class="text-2xl font-bold text-indigo-600 mt-1">{{ $plano->valor_mensal_formatado }}</p>
                    </div>

                    @if($pixQrCode)
                        <div>
                            <p class="text-sm text-gray-600 mb-3">Escaneie o QR Code com o app do seu banco:</p>
                            <div class="inline-block p-4 bg-white border border-gray-200 rounded-lg">
                                <img src="data:image/png;base64,{{ $pixQrCode }}" alt="QR Code PIX" class="w-48 h-48 mx-auto">
                            </div>
                        </div>
                    @endif

                    @if($pixCopyPaste)
                        <div x-data="{ copied: false }">
                            <p class="text-sm text-gray-600 mb-2">Ou copie o codigo PIX:</p>
                            <div class="flex gap-2">
                                <input type="text" value="{{ $pixCopyPaste }}" readonly
                                       class="flex-1 rounded-lg border-gray-300 bg-gray-50 text-sm" id="pix-code">
                                <button @click="navigator.clipboard.writeText('{{ $pixCopyPaste }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
                                    <span x-show="!copied">Copiar</span>
                                    <span x-show="copied" x-cloak>Copiado!</span>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if($expirationDate)
                        <p class="text-sm text-gray-500">
                            Valido ate: {{ \Carbon\Carbon::parse($expirationDate)->format('d/m/Y H:i') }}
                        </p>
                    @endif

                    <div class="pt-4 border-t border-gray-100">
                        <div class="flex items-center justify-center gap-2 text-sm text-yellow-700 bg-yellow-50 rounded-lg p-3">
                            <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Apos o pagamento, seu plano sera ativado automaticamente em ate 2 minutos.</span>
                        </div>
                    </div>

                    <a href="{{ route('billing.show') }}" class="inline-block text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                        Voltar para Meu Plano
                    </a>
                </div>
            </x-clinic-card>
        </div>
    </div>
</x-app-layout>
