{{-- Partial compartilhado por create e edit de planos --}}
<x-form-section title="Dados do Plano">
    <div>
        <x-input-label for="nome" value="Nome do Plano *" />
        <x-text-input id="nome" name="nome" type="text"
                      class="mt-1 block w-full" :value="old('nome', $plano->nome ?? '')"
                      placeholder="Ex: Starter, Professional, Enterprise" required />
        <x-input-error :messages="$errors->get('nome')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="valor_mensal" value="Valor Mensal (R$) *" />
        <x-text-input id="valor_mensal" name="valor_mensal" type="number" step="0.01" min="0"
                      class="mt-1 block w-full" :value="old('valor_mensal', $plano->valor_mensal ?? '')" required />
        <x-input-error :messages="$errors->get('valor_mensal')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="valor_anual" value="Valor Anual (R$)" />
        <x-text-input id="valor_anual" name="valor_anual" type="number" step="0.01" min="0"
                      class="mt-1 block w-full" :value="old('valor_anual', $plano->valor_anual ?? '')" />
        <x-input-error :messages="$errors->get('valor_anual')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="periodicidade_padrao" value="Periodicidade Padrao *" />
        <select id="periodicidade_padrao" name="periodicidade_padrao"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @foreach(['mensal' => 'Mensal', 'anual' => 'Anual'] as $val => $label)
                <option value="{{ $val }}" {{ old('periodicidade_padrao', $plano->periodicidade_padrao ?? 'mensal') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('periodicidade_padrao')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="descricao" value="Descricao" />
        <textarea id="descricao" name="descricao" rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('descricao', $plano->descricao ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('descricao')" class="mt-2" />
    </div>
</x-form-section>

<x-form-section title="Limites">
    <div>
        <x-input-label for="limite_usuarios" value="Limite de Usuarios *" />
        <x-text-input id="limite_usuarios" name="limite_usuarios" type="number" min="-1"
                      class="mt-1 block w-full" :value="old('limite_usuarios', $plano->limite_usuarios ?? -1)" required />
        <p class="text-xs text-gray-500 mt-1">Use -1 para ilimitado</p>
        <x-input-error :messages="$errors->get('limite_usuarios')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="limite_pacientes" value="Limite de Pacientes *" />
        <x-text-input id="limite_pacientes" name="limite_pacientes" type="number" min="-1"
                      class="mt-1 block w-full" :value="old('limite_pacientes', $plano->limite_pacientes ?? -1)" required />
        <p class="text-xs text-gray-500 mt-1">Use -1 para ilimitado</p>
        <x-input-error :messages="$errors->get('limite_pacientes')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="limite_agendamentos_mes" value="Limite de Agendamentos/Mes *" />
        <x-text-input id="limite_agendamentos_mes" name="limite_agendamentos_mes" type="number" min="-1"
                      class="mt-1 block w-full" :value="old('limite_agendamentos_mes', $plano->limite_agendamentos_mes ?? -1)" required />
        <p class="text-xs text-gray-500 mt-1">Use -1 para ilimitado</p>
        <x-input-error :messages="$errors->get('limite_agendamentos_mes')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="trial_dias" value="Dias de Trial *" />
        <x-text-input id="trial_dias" name="trial_dias" type="number" min="0"
                      class="mt-1 block w-full" :value="old('trial_dias', $plano->trial_dias ?? 0)" required />
        <x-input-error :messages="$errors->get('trial_dias')" class="mt-2" />
    </div>
</x-form-section>

@php
    $funcionalidades = old('funcionalidades', $plano->funcionalidades ?? []);
    $features = [
        'agendamentos' => 'Agendamentos',
        'prescricoes' => 'Prescricoes',
        'contratos' => 'Contratos',
        'fotos_clinicas' => 'Fotos Clinicas',
        'assinatura_digital' => 'Assinatura Digital',
        'relatorios' => 'Relatorios',
    ];
@endphp

<x-form-section title="Funcionalidades">
    <div class="md:col-span-2 space-y-3">
        @foreach($features as $key => $label)
            <label class="flex items-center gap-2">
                <input type="checkbox" name="func_{{ $key }}" value="1"
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                       {{ old("func_{$key}", $funcionalidades[$key] ?? false) ? 'checked' : '' }} />
                <span class="text-sm text-gray-700">{{ $label }}</span>
            </label>
        @endforeach
    </div>
</x-form-section>

<x-form-section title="Configuracoes">
    <div>
        <x-input-label for="ordem" value="Ordem de Exibicao *" />
        <x-text-input id="ordem" name="ordem" type="number" min="0"
                      class="mt-1 block w-full" :value="old('ordem', $plano->ordem ?? 0)" required />
        <x-input-error :messages="$errors->get('ordem')" class="mt-2" />
    </div>

    <div class="flex items-center gap-2 pt-6">
        <input type="hidden" name="ativo" value="0" />
        <input type="checkbox" id="ativo" name="ativo" value="1"
               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
               {{ old('ativo', $plano->ativo ?? true) ? 'checked' : '' }} />
        <x-input-label for="ativo" value="Plano ativo (disponivel para novas subscriptions)" />
    </div>
</x-form-section>
