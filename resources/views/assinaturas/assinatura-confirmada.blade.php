<x-assinatura-layout>
    <div class="bg-white rounded-lg shadow-sm p-8 text-center">
        <div class="mx-auto w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Assinatura Registrada!</h2>
        <p class="text-gray-600 mb-6">
            Sua assinatura foi registrada com sucesso. O profissional responsavel sera notificado para finalizar o documento.
        </p>

        <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-600 max-w-md mx-auto">
            <p class="font-medium text-gray-700 mb-1">Informacoes do registro:</p>
            <p>Data: {{ now()->format('d/m/Y H:i') }}</p>
            <p>Documento: {{ ucfirst($documento->tipo_documento) }}</p>
            <p class="mt-2 text-xs text-gray-400">Hash: {{ $documento->hash_documento }}</p>
        </div>

        <p class="text-sm text-gray-400 mt-6">Voce pode fechar esta pagina com seguranca.</p>
    </div>
</x-assinatura-layout>
