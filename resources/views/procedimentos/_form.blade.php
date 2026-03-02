{{-- Partial compartilhado por create e edit de procedimentos --}}
<x-form-section title="Dados do Procedimento">
    <div>
        <x-input-label for="nome" value="Nome do Procedimento *" />
        <x-text-input id="nome" name="nome" type="text"
                      class="mt-1 block w-full" :value="old('nome', $procedimento->nome ?? '')"
                      placeholder="Ex: Toxina Botulinica, Preenchimento Labial..." required />
        <x-input-error :messages="$errors->get('nome')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="categoria" value="Categoria *" />
        <select id="categoria" name="categoria"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            <option value="">Selecione...</option>
            @foreach(['facial' => 'Facial', 'corporal' => 'Corporal', 'capilar' => 'Capilar', 'outro' => 'Outro'] as $val => $label)
                <option value="{{ $val }}" {{ old('categoria', $procedimento->categoria ?? '') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('categoria')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="duracao_media_minutos" value="Duracao Media (minutos) *" />
        <x-text-input id="duracao_media_minutos" name="duracao_media_minutos" type="number" min="1"
                      class="mt-1 block w-full" :value="old('duracao_media_minutos', $procedimento->duracao_media_minutos ?? '')" required />
        <x-input-error :messages="$errors->get('duracao_media_minutos')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="valor_padrao" value="Valor Padrao (R$)" />
        <x-text-input id="valor_padrao" name="valor_padrao" type="number" step="0.01" min="0"
                      class="mt-1 block w-full" :value="old('valor_padrao', $procedimento->valor_padrao ?? '')" />
        <x-input-error :messages="$errors->get('valor_padrao')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="descricao_clinica" value="Descricao Clinica *" />
        <textarea id="descricao_clinica" name="descricao_clinica" rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('descricao_clinica', $procedimento->descricao_clinica ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('descricao_clinica')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="indicacoes" value="Indicacoes *" />
        <textarea id="indicacoes" name="indicacoes" rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('indicacoes', $procedimento->indicacoes ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('indicacoes')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="contraindicacoes" value="Contraindicacoes *" />
        <textarea id="contraindicacoes" name="contraindicacoes" rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('contraindicacoes', $procedimento->contraindicacoes ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('contraindicacoes')" class="mt-2" />
    </div>

    <div class="md:col-span-2 flex items-center gap-2">
        <input type="hidden" name="ativo" value="0" />
        <input type="checkbox" id="ativo" name="ativo" value="1"
               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
               {{ old('ativo', $procedimento->ativo ?? true) ? 'checked' : '' }} />
        <x-input-label for="ativo" value="Procedimento ativo (disponivel para selecao em sessoes)" />
    </div>
</x-form-section>

<x-form-section title="Informacoes Adicionais">
    <div class="md:col-span-2">
        <x-input-label for="observacoes_internas" value="Observacoes Internas" />
        <textarea id="observacoes_internas" name="observacoes_internas" rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('observacoes_internas', $procedimento->observacoes_internas ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('observacoes_internas')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="termo_padrao" value="Termo Padrao (para contrato)" />
        <textarea id="termo_padrao" name="termo_padrao" rows="4"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('termo_padrao', $procedimento->termo_padrao ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('termo_padrao')" class="mt-2" />
    </div>
</x-form-section>
