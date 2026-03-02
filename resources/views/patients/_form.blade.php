{{-- Partial compartilhado por create e edit --}}
<x-form-section title="Dados Pessoais">
    <div>
        <x-input-label for="nome_completo" value="Nome Completo *" />
        <x-text-input id="nome_completo" name="nome_completo" type="text"
                      class="mt-1 block w-full" :value="old('nome_completo', $patient->nome_completo ?? '')" required />
        <x-input-error :messages="$errors->get('nome_completo')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="data_nascimento" value="Data de Nascimento *" />
        <x-text-input id="data_nascimento" name="data_nascimento" type="date"
                      class="mt-1 block w-full" :value="old('data_nascimento', isset($patient) ? $patient->data_nascimento?->format('Y-m-d') : '')" required />
        <x-input-error :messages="$errors->get('data_nascimento')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="sexo" value="Sexo *" />
        <select id="sexo" name="sexo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            <option value="">Selecione...</option>
            <option value="masculino" {{ old('sexo', $patient->sexo ?? '') == 'masculino' ? 'selected' : '' }}>Masculino</option>
            <option value="feminino" {{ old('sexo', $patient->sexo ?? '') == 'feminino' ? 'selected' : '' }}>Feminino</option>
            <option value="outro" {{ old('sexo', $patient->sexo ?? '') == 'outro' ? 'selected' : '' }}>Outro</option>
        </select>
        <x-input-error :messages="$errors->get('sexo')" class="mt-2" />
    </div>

    <div x-data="{ cpf: '{{ old('cpf', $patient->cpf ?? '') }}' }">
        <x-input-label for="cpf" value="CPF *" />
        <x-text-input id="cpf" name="cpf" type="text"
                      class="mt-1 block w-full" x-model="cpf" maxlength="14" placeholder="000.000.000-00"
                      x-on:input="
                          let v = cpf.replace(/\D/g, '');
                          if (v.length > 11) v = v.slice(0, 11);
                          if (v.length > 9) v = v.slice(0,3)+'.'+v.slice(3,6)+'.'+v.slice(6,9)+'-'+v.slice(9);
                          else if (v.length > 6) v = v.slice(0,3)+'.'+v.slice(3,6)+'.'+v.slice(6);
                          else if (v.length > 3) v = v.slice(0,3)+'.'+v.slice(3);
                          cpf = v;
                      " required />
        <x-input-error :messages="$errors->get('cpf')" class="mt-2" />
    </div>

    <div x-data="{ phone: '{{ old('telefone', $patient->telefone ?? '') }}' }">
        <x-input-label for="telefone" value="Telefone *" />
        <x-text-input id="telefone" name="telefone" type="text"
                      class="mt-1 block w-full" x-model="phone" maxlength="15" placeholder="(00) 00000-0000"
                      x-on:input="
                          let v = phone.replace(/\D/g, '');
                          if (v.length > 11) v = v.slice(0, 11);
                          if (v.length > 6) v = '('+v.slice(0,2)+') '+v.slice(2,7)+'-'+v.slice(7);
                          else if (v.length > 2) v = '('+v.slice(0,2)+') '+v.slice(2);
                          phone = v;
                      " required />
        <x-input-error :messages="$errors->get('telefone')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="email" value="E-mail" />
        <x-text-input id="email" name="email" type="email"
                      class="mt-1 block w-full" :value="old('email', $patient->email ?? '')" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="endereco" value="Endereco" />
        <x-text-input id="endereco" name="endereco" type="text"
                      class="mt-1 block w-full" :value="old('endereco', $patient->endereco ?? '')" />
        <x-input-error :messages="$errors->get('endereco')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="profissao" value="Profissao" />
        <x-text-input id="profissao" name="profissao" type="text"
                      class="mt-1 block w-full" :value="old('profissao', $patient->profissao ?? '')" />
        <x-input-error :messages="$errors->get('profissao')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="observacoes_gerais" value="Observacoes Gerais" />
        <textarea id="observacoes_gerais" name="observacoes_gerais" rows="2"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('observacoes_gerais', $patient->observacoes_gerais ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('observacoes_gerais')" class="mt-2" />
    </div>
</x-form-section>

<x-form-section title="Dados Clinicos">
    <div class="md:col-span-2">
        <x-input-label for="historico_medico" value="Historico Medico Relevante" />
        <textarea id="historico_medico" name="historico_medico" rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('historico_medico', $patient->historico_medico ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('historico_medico')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="medicamentos_continuo" value="Medicamentos de Uso Continuo" />
        <textarea id="medicamentos_continuo" name="medicamentos_continuo" rows="2"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('medicamentos_continuo', $patient->medicamentos_continuo ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('medicamentos_continuo')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="alergias" value="Alergias" />
        <textarea id="alergias" name="alergias" rows="2"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('alergias', $patient->alergias ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('alergias')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="doencas_preexistentes" value="Doencas Pre-existentes" />
        <textarea id="doencas_preexistentes" name="doencas_preexistentes" rows="2"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('doencas_preexistentes', $patient->doencas_preexistentes ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('doencas_preexistentes')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="contraindicacoes_esteticas" value="Contraindicacoes Esteticas" />
        <textarea id="contraindicacoes_esteticas" name="contraindicacoes_esteticas" rows="2"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('contraindicacoes_esteticas', $patient->contraindicacoes_esteticas ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('contraindicacoes_esteticas')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="observacoes_medicas" value="Observacoes Medicas" />
        <textarea id="observacoes_medicas" name="observacoes_medicas" rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('observacoes_medicas', $patient->observacoes_medicas ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('observacoes_medicas')" class="mt-2" />
    </div>
</x-form-section>
