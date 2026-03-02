{{-- Partial compartilhado por create e edit de sessoes --}}
<x-form-section title="Dados da Sessao">
    <div>
        <x-input-label for="data_sessao" value="Data da Sessao *" />
        <x-text-input id="data_sessao" name="data_sessao" type="date"
                      class="mt-1 block w-full"
                      :value="old('data_sessao', isset($session) ? $session->data_sessao?->format('Y-m-d') : date('Y-m-d'))" required />
        <x-input-error :messages="$errors->get('data_sessao')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="procedimento" value="Procedimento Principal *" />
        <x-text-input id="procedimento" name="procedimento" type="text"
                      class="mt-1 block w-full" :value="old('procedimento', $session->procedimento ?? '')"
                      placeholder="Ex: Toxina Botulinica, Preenchimento..." required />
        <x-input-error :messages="$errors->get('procedimento')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="marca_produto" value="Marca do Produto *" />
        <x-text-input id="marca_produto" name="marca_produto" type="text"
                      class="mt-1 block w-full" :value="old('marca_produto', $session->marca_produto ?? '')"
                      placeholder="Ex: Botox, Dysport, Xeomin..." required />
        <x-input-error :messages="$errors->get('marca_produto')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="lote" value="Lote" />
        <x-text-input id="lote" name="lote" type="text"
                      class="mt-1 block w-full" :value="old('lote', $session->lote ?? '')" />
        <x-input-error :messages="$errors->get('lote')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="quantidade_total" value="Quantidade Total (unidades) *" />
        <x-text-input id="quantidade_total" name="quantidade_total" type="number" step="0.5" min="0.5"
                      class="mt-1 block w-full" :value="old('quantidade_total', $session->quantidade_total ?? '')" required />
        <x-input-error :messages="$errors->get('quantidade_total')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="profissional_responsavel" value="Profissional Responsavel *" />
        <x-text-input id="profissional_responsavel" name="profissional_responsavel" type="text"
                      class="mt-1 block w-full" :value="old('profissional_responsavel', $session->profissional_responsavel ?? auth()->user()->name)" required />
        <x-input-error :messages="$errors->get('profissional_responsavel')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="observacoes_sessao" value="Observacoes da Sessao" />
        <textarea id="observacoes_sessao" name="observacoes_sessao" rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('observacoes_sessao', $session->observacoes_sessao ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('observacoes_sessao')" class="mt-2" />
    </div>
</x-form-section>

{{-- Procedimentos Cadastrados --}}
<div class="mb-6">
    <h4 class="text-md font-semibold text-gray-700 border-b border-gray-200 pb-2 mb-4">Procedimentos Realizados</h4>
    <p class="text-sm text-gray-500 mb-3">Selecione os procedimentos cadastrados que serao realizados nesta sessao.</p>
    <x-procedimento-selector
        :procedimentosAtivos="$procedimentosAtivos ?? collect()"
        :selectedProcedimentos="isset($session) ? $session->procedimentos : collect()" />
</div>

{{-- Mapa Facial --}}
<div class="mb-6">
    <h4 class="text-md font-semibold text-gray-700 border-b border-gray-200 pb-2 mb-4">Mapa Facial de Aplicacao</h4>
    <x-facial-map
        :points="isset($session) ? $session->applicationPoints : collect()"
        :editable="true" />
</div>
