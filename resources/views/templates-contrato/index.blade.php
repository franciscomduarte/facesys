<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Templates de Contrato</h2>
            <a href="{{ route('templates-contrato.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                Novo Template
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

            <x-clinic-card>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descricao</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Contratos</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acoes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($templates as $template)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('templates-contrato.show', $template) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                            {{ $template->nome }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ Str::limit($template->descricao, 60) ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                        {{ $template->contratos_count ?? $template->contratos()->count() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <form method="POST" action="{{ route('templates-contrato.toggle-ativo', $template) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium cursor-pointer
                                                {{ $template->ativo ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                                {{ $template->ativo ? 'Ativo' : 'Inativo' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <a href="{{ route('templates-contrato.show', $template) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Ver</a>
                                        <a href="{{ route('templates-contrato.edit', $template) }}" class="text-gray-600 hover:text-gray-900">Editar</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        Nenhum template de contrato encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-clinic-card>
        </div>
    </div>
</x-app-layout>
