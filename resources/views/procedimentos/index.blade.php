<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Procedimentos</h2>
            <a href="{{ route('procedimentos.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                Novo Procedimento
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
                {{-- Filtros --}}
                <form method="GET" action="{{ route('procedimentos.index') }}" class="mb-6">
                    <div class="flex flex-wrap gap-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Buscar por nome..."
                               class="flex-1 min-w-[200px] rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        <select name="categoria" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todas categorias</option>
                            @foreach(['facial' => 'Facial', 'corporal' => 'Corporal', 'capilar' => 'Capilar', 'outro' => 'Outro'] as $val => $label)
                                <option value="{{ $val }}" {{ request('categoria') == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Filtrar</button>
                        @if(request()->hasAny(['search', 'categoria']))
                            <a href="{{ route('procedimentos.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Limpar</a>
                        @endif
                    </div>
                </form>

                {{-- Tabela --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duracao</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acoes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($procedimentos as $proc)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('procedimentos.show', $proc) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                            {{ $proc->nome }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $proc->categoria === 'facial' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $proc->categoria === 'corporal' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $proc->categoria === 'capilar' ? 'bg-purple-100 text-purple-800' : '' }}
                                            {{ $proc->categoria === 'outro' ? 'bg-gray-100 text-gray-800' : '' }}">
                                            {{ ucfirst($proc->categoria) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $proc->duracao_media_minutos }} min</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $proc->valor_padrao ? 'R$ ' . number_format($proc->valor_padrao, 2, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <form method="POST" action="{{ route('procedimentos.toggle-ativo', $proc) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium cursor-pointer
                                                {{ $proc->ativo ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                                {{ $proc->ativo ? 'Ativo' : 'Inativo' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <a href="{{ route('procedimentos.show', $proc) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Ver</a>
                                        <a href="{{ route('procedimentos.edit', $proc) }}" class="text-gray-600 hover:text-gray-900">Editar</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        Nenhum procedimento encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $procedimentos->links() }}
                </div>
            </x-clinic-card>
        </div>
    </div>
</x-app-layout>
