<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('patients.sessions.show', [$patient, $session]) }}" class="text-gray-500 hover:text-gray-700">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gerar Contrato</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <x-clinic-card title="Informacoes da Sessao">
                <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                    <div>
                        <dt class="font-medium text-gray-500">Paciente</dt>
                        <dd class="text-gray-900">{{ $patient->nome_completo }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Data</dt>
                        <dd class="text-gray-900">{{ $session->data_sessao->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Profissional</dt>
                        <dd class="text-gray-900">{{ $session->profissional_responsavel }}</dd>
                    </div>
                </dl>

                @if($session->procedimentos->count())
                    <div class="mt-4 border-t pt-4">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Procedimentos</h4>
                        <div class="space-y-1">
                            @foreach($session->procedimentos as $proc)
                                <div class="flex justify-between text-sm">
                                    <span>{{ $proc->nome }}</span>
                                    <span class="text-gray-500">
                                        {{ $proc->pivot->quantidade ?? 1 }}x — R$ {{ number_format(($proc->pivot->quantidade ?? 1) * ($proc->valor_padrao ?? 0), 2, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </x-clinic-card>

            <div class="mt-6">
                <x-clinic-card title="Selecionar Template">
                    <form method="POST" action="{{ route('patients.sessions.contrato.store', [$patient, $session]) }}">
                        @csrf

                        <div class="space-y-4">
                            <div>
                                <label for="template_contrato_id" class="block text-sm font-medium text-gray-700">Template de Contrato</label>
                                <select name="template_contrato_id" id="template_contrato_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Selecione um template...</option>
                                    @foreach($templates as $template)
                                        <option value="{{ $template->id }}" {{ old('template_contrato_id') == $template->id ? 'selected' : '' }}>
                                            {{ $template->nome }}
                                            @if($template->descricao) — {{ Str::limit($template->descricao, 50) }} @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('template_contrato_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="valor_total" class="block text-sm font-medium text-gray-700">Valor Total (R$)</label>
                                <input type="number" name="valor_total" id="valor_total" step="0.01" min="0"
                                    value="{{ old('valor_total') }}"
                                    placeholder="Calculado automaticamente se vazio"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <p class="mt-1 text-xs text-gray-400">Deixe em branco para calcular com base nos procedimentos.</p>
                            </div>

                            <div>
                                <label for="observacoes" class="block text-sm font-medium text-gray-700">Observacoes</label>
                                <textarea name="observacoes" id="observacoes" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('observacoes') }}</textarea>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                    Gerar Contrato
                                </button>
                            </div>
                        </div>
                    </form>
                </x-clinic-card>
            </div>
        </div>
    </div>
</x-app-layout>
