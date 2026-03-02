{{-- Partial compartilhado por create e edit de templates de contrato --}}
<x-form-section title="Dados do Template">
    <div class="md:col-span-2">
        <x-input-label for="nome" value="Nome do Template *" />
        <x-text-input id="nome" name="nome" type="text"
                      class="mt-1 block w-full" :value="old('nome', $template->nome ?? '')"
                      placeholder="Ex: Contrato Padrao de Estetica Facial" required />
        <x-input-error :messages="$errors->get('nome')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="descricao" value="Descricao" />
        <textarea id="descricao" name="descricao" rows="2"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  placeholder="Breve descricao do template">{{ old('descricao', $template->descricao ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('descricao')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="conteudo_template" value="Conteudo do Template (HTML com placeholders) *" />
        <textarea id="conteudo_template" name="conteudo_template" rows="20"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono text-sm"
                  required>{{ old('conteudo_template', $template->conteudo_template ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('conteudo_template')" class="mt-2" />
    </div>

    <div class="md:col-span-2 flex items-center gap-2">
        <input type="hidden" name="ativo" value="0" />
        <input type="checkbox" id="ativo" name="ativo" value="1"
               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
               {{ old('ativo', $template->ativo ?? true) ? 'checked' : '' }} />
        <x-input-label for="ativo" value="Template ativo (disponivel para selecao ao gerar contratos)" />
    </div>
</x-form-section>

<x-form-section title="Placeholders Disponiveis">
    <div class="md:col-span-2">
        <div class="bg-gray-50 rounded-lg p-4 text-sm">
            <p class="text-gray-600 mb-3">Use os placeholders abaixo no conteudo do template. Eles serao substituidos automaticamente pelos dados reais ao gerar o contrato.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div>
                    <p class="font-medium text-gray-700 mb-1">Paciente</p>
                    <ul class="space-y-0.5 text-gray-500 text-xs">
                        <li><code class="bg-gray-200 px-1 rounded">@{{paciente.nome}}</code> — Nome completo</li>
                        <li><code class="bg-gray-200 px-1 rounded">@{{paciente.cpf}}</code> — CPF</li>
                        <li><code class="bg-gray-200 px-1 rounded">@{{paciente.data_nascimento}}</code> — Data nascimento</li>
                        <li><code class="bg-gray-200 px-1 rounded">@{{paciente.endereco}}</code> — Endereco</li>
                        <li><code class="bg-gray-200 px-1 rounded">@{{paciente.telefone}}</code> — Telefone</li>
                    </ul>
                </div>
                <div>
                    <p class="font-medium text-gray-700 mb-1">Profissional</p>
                    <ul class="space-y-0.5 text-gray-500 text-xs">
                        <li><code class="bg-gray-200 px-1 rounded">@{{profissional.nome}}</code> — Nome do profissional</li>
                    </ul>
                </div>
                <div>
                    <p class="font-medium text-gray-700 mb-1">Procedimentos</p>
                    <ul class="space-y-0.5 text-gray-500 text-xs">
                        <li><code class="bg-gray-200 px-1 rounded">@{{procedimentos.lista}}</code> — Tabela HTML</li>
                        <li><code class="bg-gray-200 px-1 rounded">@{{procedimentos.lista_texto}}</code> — Lista texto</li>
                        <li><code class="bg-gray-200 px-1 rounded">@{{valor_total}}</code> — Valor total</li>
                    </ul>
                </div>
                <div>
                    <p class="font-medium text-gray-700 mb-1">Outros</p>
                    <ul class="space-y-0.5 text-gray-500 text-xs">
                        <li><code class="bg-gray-200 px-1 rounded">@{{data_atendimento}}</code> — Data da sessao</li>
                        <li><code class="bg-gray-200 px-1 rounded">@{{clinica.nome}}</code> — Nome da clinica</li>
                        <li><code class="bg-gray-200 px-1 rounded">@{{clinica.cnpj}}</code> — CNPJ</li>
                        <li><code class="bg-gray-200 px-1 rounded">@{{clinica.endereco}}</code> — Endereco clinica</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-form-section>
