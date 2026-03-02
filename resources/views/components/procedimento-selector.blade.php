@props([
    'procedimentosAtivos' => collect(),
    'selectedProcedimentos' => collect(),
])

@php
    // Preparar dados dos procedimentos ja selecionados (para edicao)
    $initialSelected = $selectedProcedimentos->map(function ($proc) {
        return [
            'id' => $proc->id,
            'nome' => $proc->nome,
            'categoria' => $proc->categoria,
            'quantidade' => $proc->pivot->quantidade ?? '',
            'observacoes' => $proc->pivot->observacoes ?? '',
        ];
    })->values()->toArray();

    // Lista de todos os procedimentos ativos para o dropdown
    $available = $procedimentosAtivos->map(function ($proc) {
        return [
            'id' => $proc->id,
            'nome' => $proc->nome,
            'categoria' => ucfirst($proc->categoria),
            'duracao' => $proc->duracao_media_minutos,
        ];
    })->values()->toArray();
@endphp

<div x-data="procedimentoSelector({
    availableProcedimentos: {{ json_encode($available) }},
    initialSelected: {{ json_encode($initialSelected) }},
})">
    {{-- Seletor --}}
    <div class="flex gap-2 mb-4">
        <select x-model="selectedId" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            <option value="">Adicionar procedimento...</option>
            <template x-for="proc in availableFiltered" :key="proc.id">
                <option :value="proc.id" x-text="proc.categoria + ' — ' + proc.nome + ' (' + proc.duracao + ' min)'"></option>
            </template>
        </select>
        <button type="button" @click="addProcedimento()" :disabled="!selectedId"
                class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
            Adicionar
        </button>
    </div>

    {{-- Lista de procedimentos selecionados --}}
    <div class="space-y-3">
        <template x-for="(proc, index) in selected" :key="'proc-' + index">
            <div class="p-3 bg-gray-50 rounded-lg border">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-sm text-gray-900" x-text="proc.nome"></span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800"
                              x-text="proc.categoria"></span>
                    </div>
                    <button type="button" @click="removeProcedimento(index)"
                            class="text-red-500 hover:text-red-700 text-sm">
                        Remover
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-600">Quantidade/Unidades</label>
                        <input type="number" step="0.5" min="0" x-model="proc.quantidade"
                               class="mt-1 block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Ex: 50" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600">Observacoes</label>
                        <input type="text" x-model="proc.observacoes"
                               class="mt-1 block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Observacoes especificas..." />
                    </div>
                </div>

                {{-- Hidden inputs --}}
                <input type="hidden" :name="'procedimentos_selecionados['+index+'][id]'" :value="proc.id" />
                <input type="hidden" :name="'procedimentos_selecionados['+index+'][quantidade]'" :value="proc.quantidade || ''" />
                <input type="hidden" :name="'procedimentos_selecionados['+index+'][observacoes]'" :value="proc.observacoes || ''" />
            </div>
        </template>
    </div>

    <div x-show="selected.length === 0" class="text-sm text-gray-400 py-2">
        Nenhum procedimento selecionado.
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('procedimentoSelector', ({ availableProcedimentos = [], initialSelected = [] }) => ({
        availableProcedimentos,
        selected: initialSelected,
        selectedId: '',

        get availableFiltered() {
            const selectedIds = this.selected.map(p => p.id);
            return this.availableProcedimentos.filter(p => !selectedIds.includes(p.id));
        },

        addProcedimento() {
            if (!this.selectedId) return;
            const proc = this.availableProcedimentos.find(p => p.id == this.selectedId);
            if (proc && !this.selected.find(p => p.id == proc.id)) {
                this.selected.push({
                    id: proc.id,
                    nome: proc.nome,
                    categoria: proc.categoria,
                    quantidade: '',
                    observacoes: '',
                });
            }
            this.selectedId = '';
        },

        removeProcedimento(index) {
            this.selected.splice(index, 1);
        },
    }));
});
</script>
