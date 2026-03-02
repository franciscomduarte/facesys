<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Empresas</h2>
            <a href="{{ route('empresas.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                Nova Empresa
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
                <form method="GET" action="{{ route('empresas.index') }}" class="mb-6">
                    <div class="flex flex-wrap gap-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Buscar por nome, razao social ou CNPJ..."
                               class="flex-1 min-w-[200px] rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos status</option>
                            @foreach(['ativa' => 'Ativa', 'inativa' => 'Inativa', 'suspensa' => 'Suspensa'] as $val => $label)
                                <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Filtrar</button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('empresas.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Limpar</a>
                        @endif
                    </div>
                </form>

                {{-- Tabela --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CNPJ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contato</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acoes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($empresas as $empresa)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('empresas.show', $empresa) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                            {{ $empresa->nome_fantasia }}
                                        </a>
                                        @if($empresa->razao_social)
                                            <p class="text-xs text-gray-500">{{ $empresa->razao_social }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $empresa->cnpj ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($empresa->email)
                                            <div>{{ $empresa->email }}</div>
                                        @endif
                                        @if($empresa->telefone)
                                            <div>{{ $empresa->telefone }}</div>
                                        @endif
                                        @if(!$empresa->email && !$empresa->telefone)
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <form method="POST" action="{{ route('empresas.toggle-status', $empresa) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium cursor-pointer
                                                {{ $empresa->status === 'ativa' ? 'bg-green-100 text-green-800 hover:bg-green-200' : '' }}
                                                {{ $empresa->status === 'inativa' ? 'bg-gray-100 text-gray-800 hover:bg-gray-200' : '' }}
                                                {{ $empresa->status === 'suspensa' ? 'bg-red-100 text-red-800 hover:bg-red-200' : '' }}">
                                                {{ ucfirst($empresa->status) }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <a href="{{ route('empresas.show', $empresa) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Ver</a>
                                        <a href="{{ route('empresas.edit', $empresa) }}" class="text-gray-600 hover:text-gray-900">Editar</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        Nenhuma empresa encontrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $empresas->links() }}
                </div>
            </x-clinic-card>
        </div>
    </div>
</x-app-layout>
