{{-- Partial compartilhado por create e edit de agendamentos --}}
@php
    $initialSelected = isset($agendamento)
        ? $agendamento->procedimentos->map(fn($p) => [
            'id' => $p->id,
            'nome' => $p->nome,
            'categoria' => ucfirst($p->categoria),
            'duracao' => $p->duracao_media_minutos,
            'quantidade' => $p->pivot->quantidade ?? '',
            'observacoes' => $p->pivot->observacoes ?? '',
        ])->values()->toArray()
        : [];

    $available = $procedimentosAtivos->map(fn($p) => [
        'id' => $p->id,
        'nome' => $p->nome,
        'categoria' => ucfirst($p->categoria),
        'duracao' => $p->duracao_media_minutos,
    ])->values()->toArray();
@endphp

<div x-data="agendamentoForm({
    availableProcedimentos: {{ json_encode($available) }},
    initialSelected: {{ json_encode($initialSelected) }},
    horariosUrl: '{{ route('agendamentos.horarios-disponiveis') }}',
    initialData: '{{ old('data_agendamento', isset($agendamento) ? $agendamento->data_agendamento->format('Y-m-d') : '') }}',
    initialProfissional: '{{ old('profissional_id', $agendamento->profissional_id ?? '') }}',
    initialHora: '{{ old('hora_inicio', $agendamento->hora_inicio ?? '') }}',
})">
    <x-form-section title="Dados do Agendamento">
        {{-- Paciente --}}
        <div class="md:col-span-2">
            <x-input-label for="patient_id" value="Paciente *" />
            <select id="patient_id" name="patient_id" x-model="patientId" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Selecione um paciente...</option>
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}"
                        {{ old('patient_id', $agendamento->patient_id ?? '') == $patient->id ? 'selected' : '' }}>
                        {{ $patient->nome_completo }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />
        </div>

        {{-- Profissional --}}
        <div>
            <x-input-label for="profissional_id" value="Profissional *" />
            <select id="profissional_id" name="profissional_id" x-model="profissionalId" required
                    @change="fetchHorarios()"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Selecione...</option>
                @foreach($profissionais as $prof)
                    <option value="{{ $prof->id }}"
                        {{ old('profissional_id', $agendamento->profissional_id ?? '') == $prof->id ? 'selected' : '' }}>
                        {{ $prof->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('profissional_id')" class="mt-2" />
        </div>

        {{-- Data --}}
        <div>
            <x-input-label for="data_agendamento" value="Data *" />
            <x-text-input id="data_agendamento" name="data_agendamento" type="date"
                          class="mt-1 block w-full"
                          x-model="dataAgendamento"
                          @change="fetchHorarios()"
                          required />
            <x-input-error :messages="$errors->get('data_agendamento')" class="mt-2" />
        </div>

        {{-- Tipo de Atendimento --}}
        <div>
            <x-input-label for="tipo_atendimento" value="Tipo de Atendimento" />
            <select id="tipo_atendimento" name="tipo_atendimento"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="procedimento" {{ old('tipo_atendimento', $agendamento->tipo_atendimento ?? 'procedimento') === 'procedimento' ? 'selected' : '' }}>Procedimento</option>
                <option value="consulta" {{ old('tipo_atendimento', $agendamento->tipo_atendimento ?? '') === 'consulta' ? 'selected' : '' }}>Consulta</option>
            </select>
            <x-input-error :messages="$errors->get('tipo_atendimento')" class="mt-2" />
        </div>

        {{-- Observacoes --}}
        <div class="md:col-span-2">
            <x-input-label for="observacoes" value="Observacoes" />
            <textarea id="observacoes" name="observacoes" rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('observacoes', $agendamento->observacoes ?? '') }}</textarea>
            <x-input-error :messages="$errors->get('observacoes')" class="mt-2" />
        </div>
    </x-form-section>

    {{-- Procedimentos --}}
    <div class="mb-6">
        <h4 class="text-md font-semibold text-gray-700 border-b border-gray-200 pb-2 mb-4">Procedimentos *</h4>
        <p class="text-sm text-gray-500 mb-3">Selecione os procedimentos que serao realizados neste agendamento.</p>

        {{-- Seletor --}}
        <div class="flex gap-2 mb-4">
            <select x-model="selectedProcId" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="">Adicionar procedimento...</option>
                <template x-for="proc in availableFiltered" :key="proc.id">
                    <option :value="proc.id" x-text="proc.categoria + ' — ' + proc.nome + ' (' + proc.duracao + ' min)'"></option>
                </template>
            </select>
            <button type="button" @click="addProcedimento()" :disabled="!selectedProcId"
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
                            <span class="text-xs text-gray-500" x-text="proc.duracao + ' min'"></span>
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
        <x-input-error :messages="$errors->get('procedimentos_selecionados')" class="mt-2" />
    </div>

    {{-- Horario (após procedimentos para calcular duracao correta) --}}
    <div class="mb-6">
        <h4 class="text-md font-semibold text-gray-700 border-b border-gray-200 pb-2 mb-4">Horario *</h4>
        <div class="max-w-md">
            <select id="hora_inicio" name="hora_inicio" x-model="horaInicio" required
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :disabled="!horariosDisponiveis.length && !horaInicio">
                <template x-if="selected.length === 0">
                    <option value="">Adicione procedimentos primeiro...</option>
                </template>
                <template x-if="selected.length > 0 && (!dataAgendamento || !profissionalId)">
                    <option value="">Selecione data e profissional...</option>
                </template>
                <template x-if="selected.length > 0 && dataAgendamento && profissionalId && !horariosDisponiveis.length && !loading">
                    <option value="">Nenhum horario disponivel</option>
                </template>
                <template x-if="loading">
                    <option value="">Buscando horarios...</option>
                </template>
                <template x-if="horariosDisponiveis.length > 0 && !loading">
                    <option value="">Selecione um horario...</option>
                </template>
                <template x-for="slot in horariosDisponiveis" :key="slot.hora_inicio">
                    <option :value="slot.hora_inicio" x-text="slot.label" :selected="slot.hora_inicio === horaInicio"></option>
                </template>
                {{-- Manter hora atual em edicao mesmo que nao esteja nos slots --}}
                <template x-if="horaInicio && !horariosDisponiveis.some(s => s.hora_inicio === horaInicio)">
                    <option :value="horaInicio" x-text="horaInicio + ' (atual)'" selected></option>
                </template>
            </select>
            <p class="mt-1 text-xs text-gray-500">
                <span x-show="loading" class="text-indigo-600">Buscando horarios disponiveis...</span>
                <span x-show="!loading && duracaoTotal > 0" x-text="'Duracao estimada: ' + duracaoTotal + ' min'"></span>
            </p>
            <x-input-error :messages="$errors->get('hora_inicio')" class="mt-2" />
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('agendamentoForm', ({ availableProcedimentos = [], initialSelected = [], horariosUrl, initialData, initialProfissional, initialHora }) => ({
        availableProcedimentos,
        selected: initialSelected,
        selectedProcId: '',
        patientId: '',
        profissionalId: initialProfissional,
        dataAgendamento: initialData,
        horaInicio: initialHora,
        horariosDisponiveis: [],
        loading: false,

        get availableFiltered() {
            const selectedIds = this.selected.map(p => p.id);
            return this.availableProcedimentos.filter(p => !selectedIds.includes(p.id));
        },

        get duracaoTotal() {
            return this.selected.reduce((sum, p) => {
                const proc = this.availableProcedimentos.find(ap => ap.id == p.id);
                return sum + (proc ? proc.duracao : 0);
            }, 0);
        },

        addProcedimento() {
            if (!this.selectedProcId) return;
            const proc = this.availableProcedimentos.find(p => p.id == this.selectedProcId);
            if (proc && !this.selected.find(p => p.id == proc.id)) {
                this.selected.push({
                    id: proc.id,
                    nome: proc.nome,
                    categoria: proc.categoria,
                    duracao: proc.duracao,
                    quantidade: '',
                    observacoes: '',
                });
                this.$nextTick(() => this.fetchHorarios());
            }
            this.selectedProcId = '';
        },

        removeProcedimento(index) {
            this.selected.splice(index, 1);
            this.$nextTick(() => this.fetchHorarios());
        },

        async fetchHorarios() {
            if (!this.dataAgendamento || !this.profissionalId || this.duracaoTotal === 0) {
                this.horariosDisponiveis = [];
                return;
            }

            this.loading = true;
            try {
                const params = new URLSearchParams({
                    data: this.dataAgendamento,
                    profissional_id: this.profissionalId,
                    duracao: this.duracaoTotal,
                });
                const response = await fetch(`${horariosUrl}?${params}`);
                if (response.ok) {
                    this.horariosDisponiveis = await response.json();
                }
            } catch (e) {
                console.error('Erro ao buscar horarios:', e);
            } finally {
                this.loading = false;
            }
        },

        init() {
            if (this.dataAgendamento && this.profissionalId && this.duracaoTotal > 0) {
                this.fetchHorarios();
            }
        }
    }));
});
</script>
