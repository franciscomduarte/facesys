{{-- Partial compartilhado por create e edit de empresas --}}
<x-form-section title="Dados da Empresa">
    <div>
        <x-input-label for="nome_fantasia" value="Nome Fantasia *" />
        <x-text-input id="nome_fantasia" name="nome_fantasia" type="text"
                      class="mt-1 block w-full" :value="old('nome_fantasia', $empresa->nome_fantasia ?? '')"
                      placeholder="Ex: LC Estetica" required />
        <x-input-error :messages="$errors->get('nome_fantasia')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="razao_social" value="Razao Social" />
        <x-text-input id="razao_social" name="razao_social" type="text"
                      class="mt-1 block w-full" :value="old('razao_social', $empresa->razao_social ?? '')" />
        <x-input-error :messages="$errors->get('razao_social')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="cnpj" value="CNPJ" />
        <x-text-input id="cnpj" name="cnpj" type="text"
                      class="mt-1 block w-full" :value="old('cnpj', $empresa->cnpj ?? '')"
                      placeholder="00.000.000/0000-00" />
        <x-input-error :messages="$errors->get('cnpj')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="email" value="E-mail" />
        <x-text-input id="email" name="email" type="email"
                      class="mt-1 block w-full" :value="old('email', $empresa->email ?? '')" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="telefone" value="Telefone" />
        <x-text-input id="telefone" name="telefone" type="text"
                      class="mt-1 block w-full" :value="old('telefone', $empresa->telefone ?? '')"
                      placeholder="(00) 00000-0000" />
        <x-input-error :messages="$errors->get('telefone')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="status" value="Status *" />
        <select id="status" name="status"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @foreach(['ativa' => 'Ativa', 'inativa' => 'Inativa', 'suspensa' => 'Suspensa'] as $val => $label)
                <option value="{{ $val }}" {{ old('status', $empresa->status ?? 'ativa') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>
</x-form-section>
