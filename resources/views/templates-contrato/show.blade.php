<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('templates-contrato.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $template->nome }}</h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    {{ $template->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $template->ativo ? 'Ativo' : 'Inativo' }}
                </span>
            </div>
            <a href="{{ route('templates-contrato.edit', $template) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                Editar
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <x-clinic-card title="Detalhes">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nome</dt>
                        <dd class="text-sm text-gray-900">{{ $template->nome }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Contratos Gerados</dt>
                        <dd class="text-sm text-gray-900">{{ $template->contratos()->count() }}</dd>
                    </div>
                    @if($template->descricao)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Descricao</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ $template->descricao }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                        <dd class="text-sm text-gray-900">{{ $template->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Atualizado em</dt>
                        <dd class="text-sm text-gray-900">{{ $template->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </x-clinic-card>

            <div class="mt-6">
                <x-clinic-card title="Preview do Conteudo">
                    <div class="prose prose-sm max-w-none text-gray-700 bg-gray-50 rounded-lg p-6 border">
                        {!! $template->conteudo_template !!}
                    </div>
                </x-clinic-card>
            </div>

            <div class="mt-6">
                <x-clinic-card title="Codigo Fonte">
                    <pre class="bg-gray-900 text-gray-100 rounded-lg p-4 text-xs overflow-x-auto"><code>{{ $template->conteudo_template }}</code></pre>
                </x-clinic-card>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <x-confirm-modal
                    :action="route('templates-contrato.destroy', $template)"
                    title="Remover Template"
                    message="Tem certeza que deseja remover este template? Contratos ja gerados nao serao afetados."
                    confirmText="Remover">
                    <x-slot name="trigger">
                        <button type="button" class="px-4 py-2 bg-red-50 text-red-600 rounded-md hover:bg-red-100 text-sm">
                            Remover Template
                        </button>
                    </x-slot>
                </x-confirm-modal>
            </div>
        </div>
    </div>
</x-app-layout>
