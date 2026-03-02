<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('patients.sessions.contrato.show', [$patient, $session, $contrato]) }}" class="text-gray-500 hover:text-gray-700">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Contrato #{{ $contrato->id }}</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-clinic-card title="Editar Observacoes e Valor">
                <form method="POST" action="{{ route('patients.sessions.contrato.update', [$patient, $session, $contrato]) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="template_contrato_id" value="{{ $contrato->template_contrato_id }}">

                    <div class="space-y-4">
                        <div>
                            <label for="valor_total" class="block text-sm font-medium text-gray-700">Valor Total (R$)</label>
                            <input type="number" name="valor_total" id="valor_total" step="0.01" min="0"
                                value="{{ old('valor_total', $contrato->valor_total) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="observacoes" class="block text-sm font-medium text-gray-700">Observacoes</label>
                            <textarea name="observacoes" id="observacoes" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('observacoes', $contrato->observacoes) }}</textarea>
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('patients.sessions.contrato.show', [$patient, $session, $contrato]) }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                Salvar Alteracoes
                            </button>
                        </div>
                    </div>
                </form>
            </x-clinic-card>
        </div>
    </div>
</x-app-layout>
