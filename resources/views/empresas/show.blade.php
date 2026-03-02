<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('empresas.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $empresa->nome_fantasia }}</h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    {{ $empresa->status === 'ativa' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $empresa->status === 'inativa' ? 'bg-gray-100 text-gray-800' : '' }}
                    {{ $empresa->status === 'suspensa' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ ucfirst($empresa->status) }}
                </span>
            </div>
            <a href="{{ route('empresas.edit', $empresa) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                Editar
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <x-clinic-card title="Dados da Empresa">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nome Fantasia</dt>
                                <dd class="text-sm text-gray-900">{{ $empresa->nome_fantasia }}</dd>
                            </div>
                            @if($empresa->razao_social)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Razao Social</dt>
                                <dd class="text-sm text-gray-900">{{ $empresa->razao_social }}</dd>
                            </div>
                            @endif
                            @if($empresa->cnpj)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">CNPJ</dt>
                                <dd class="text-sm text-gray-900">{{ $empresa->cnpj }}</dd>
                            </div>
                            @endif
                            @if($empresa->email)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">E-mail</dt>
                                <dd class="text-sm text-gray-900">{{ $empresa->email }}</dd>
                            </div>
                            @endif
                            @if($empresa->telefone)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Telefone</dt>
                                <dd class="text-sm text-gray-900">{{ $empresa->telefone }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Criada em</dt>
                                <dd class="text-sm text-gray-900">{{ $empresa->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </x-clinic-card>
                </div>

                <div class="space-y-6">
                    <x-clinic-card title="Estatisticas">
                        <dl class="space-y-3">
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-500">Usuarios</dt>
                                <dd class="text-sm font-semibold text-gray-900">{{ $empresa->users_count }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-500">Pacientes</dt>
                                <dd class="text-sm font-semibold text-gray-900">{{ $empresa->patients_count }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-500">Procedimentos</dt>
                                <dd class="text-sm font-semibold text-gray-900">{{ $empresa->procedimentos_count }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-500">Agendamentos</dt>
                                <dd class="text-sm font-semibold text-gray-900">{{ $empresa->agendamentos_count }}</dd>
                            </div>
                        </dl>
                    </x-clinic-card>

                    <x-confirm-modal
                        :action="route('empresas.destroy', $empresa)"
                        title="Remover Empresa"
                        message="Tem certeza que deseja remover esta empresa? Todos os dados associados serao mantidos mas a empresa ficara inativa."
                        confirmText="Remover">
                        <x-slot name="trigger">
                            <button type="button" class="w-full text-center px-4 py-2 bg-red-50 text-red-600 rounded-md hover:bg-red-100 text-sm">
                                Remover Empresa
                            </button>
                        </x-slot>
                    </x-confirm-modal>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
