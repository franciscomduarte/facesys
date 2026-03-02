<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('subscriptions.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nova Subscription</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('subscriptions.store') }}">
                @csrf

                <x-form-section title="Vincular Empresa a Plano">
                    <div>
                        <x-input-label for="empresa_id" value="Empresa *" />
                        <select id="empresa_id" name="empresa_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Selecione...</option>
                            @foreach($empresas as $empresa)
                                <option value="{{ $empresa->id }}" {{ old('empresa_id') == $empresa->id ? 'selected' : '' }}>
                                    {{ $empresa->nome_fantasia }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('empresa_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="plano_id" value="Plano *" />
                        <select id="plano_id" name="plano_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Selecione...</option>
                            @foreach($planos as $plano)
                                <option value="{{ $plano->id }}" {{ old('plano_id') == $plano->id ? 'selected' : '' }}>
                                    {{ $plano->nome }} — {{ $plano->valor_mensal_formatado }}/mes
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('plano_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="periodicidade" value="Periodicidade *" />
                        <select id="periodicidade" name="periodicidade"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="mensal" {{ old('periodicidade') == 'mensal' ? 'selected' : '' }}>Mensal</option>
                            <option value="anual" {{ old('periodicidade') == 'anual' ? 'selected' : '' }}>Anual</option>
                        </select>
                        <x-input-error :messages="$errors->get('periodicidade')" class="mt-2" />
                    </div>
                </x-form-section>

                <div class="flex items-center justify-end gap-4 mt-6">
                    <a href="{{ route('subscriptions.index') }}" class="text-gray-600 hover:text-gray-800">Cancelar</a>
                    <x-primary-button>Criar Subscription</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
