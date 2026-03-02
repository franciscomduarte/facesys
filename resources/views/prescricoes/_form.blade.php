<div x-data="prescricaoForm()" class="space-y-6">
    {{-- Dados da Prescricao --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="sm:col-span-2">
            <label for="profissional_responsavel" class="block text-sm font-medium text-gray-700">Profissional Responsavel *</label>
            <input type="text" name="profissional_responsavel" id="profissional_responsavel"
                   value="{{ old('profissional_responsavel', $prescricao->profissional_responsavel ?? auth()->user()->name) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                   required>
            @error('profissional_responsavel')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="observacoes_gerais" class="block text-sm font-medium text-gray-700">Observacoes Gerais</label>
            <textarea name="observacoes_gerais" id="observacoes_gerais" rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('observacoes_gerais', $prescricao->observacoes_gerais ?? '') }}</textarea>
            @error('observacoes_gerais')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Medicamentos --}}
    <div class="border-t border-gray-200 pt-6">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-base font-semibold text-gray-800">Medicamentos</h4>
            <button type="button" @click="addItem()"
                    class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Adicionar
            </button>
        </div>

        @error('items')
            <p class="mb-4 text-sm text-red-600">{{ $message }}</p>
        @enderror

        <div class="space-y-4">
            <template x-for="(item, index) in items" :key="index">
                <div class="bg-gray-50 rounded-lg p-4 relative">
                    <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                            class="absolute top-2 right-2 text-red-400 hover:text-red-600 transition">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        <div class="sm:col-span-2 lg:col-span-3">
                            <label class="block text-sm font-medium text-gray-700">Medicamento *</label>
                            <input type="text" x-model="item.medicamento"
                                   :name="'items[' + index + '][medicamento]'"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dosagem *</label>
                            <input type="text" x-model="item.dosagem"
                                   :name="'items[' + index + '][dosagem]'"
                                   placeholder="Ex: 500mg"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Via *</label>
                            <select x-model="item.via_administracao"
                                    :name="'items[' + index + '][via_administracao]'"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    required>
                                <option value="">Selecione...</option>
                                <option value="Oral">Oral</option>
                                <option value="Topica">Topica</option>
                                <option value="Injetavel">Injetavel</option>
                                <option value="Sublingual">Sublingual</option>
                                <option value="Nasal">Nasal</option>
                                <option value="Ocular">Ocular</option>
                                <option value="Outra">Outra</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Frequencia *</label>
                            <input type="text" x-model="item.frequencia"
                                   :name="'items[' + index + '][frequencia]'"
                                   placeholder="Ex: 2x ao dia"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Duracao *</label>
                            <input type="text" x-model="item.duracao"
                                   :name="'items[' + index + '][duracao]'"
                                   placeholder="Ex: 7 dias"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   required>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Observacoes</label>
                            <input type="text" x-model="item.observacoes"
                                   :name="'items[' + index + '][observacoes]'"
                                   placeholder="Instrucoes adicionais..."
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div x-show="items.length === 0" class="text-center py-8 text-gray-400">
            <p>Nenhum medicamento adicionado. Clique em "Adicionar" para comecar.</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
function prescricaoForm() {
    const existingItems = @json(old('items', isset($prescricao) && $prescricao->items ? $prescricao->items->map(fn($i) => [
        'medicamento' => $i->medicamento,
        'dosagem' => $i->dosagem,
        'via_administracao' => $i->via_administracao,
        'frequencia' => $i->frequencia,
        'duracao' => $i->duracao,
        'observacoes' => $i->observacoes ?? '',
    ])->toArray() : []));

    return {
        items: existingItems.length > 0 ? existingItems : [{ medicamento: '', dosagem: '', via_administracao: '', frequencia: '', duracao: '', observacoes: '' }],
        addItem() {
            this.items.push({ medicamento: '', dosagem: '', via_administracao: '', frequencia: '', duracao: '', observacoes: '' });
        },
        removeItem(index) {
            this.items.splice(index, 1);
        }
    };
}
</script>
@endpush
