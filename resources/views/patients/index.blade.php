<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pacientes</h2>
            <a href="{{ route('patients.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                Novo Paciente
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
                {{-- Busca --}}
                <form method="GET" action="{{ route('patients.index') }}" class="mb-6">
                    <div class="flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Buscar por nome ou CPF..."
                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        <button type="submit"
                                class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                            Buscar
                        </button>
                        @if(request('search'))
                            <a href="{{ route('patients.index') }}"
                               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                Limpar
                            </a>
                        @endif
                    </div>
                </form>

                {{-- Tabela --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CPF</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ultima Sessao</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acoes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($patients as $patient)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('patients.show', $patient) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                            {{ $patient->nome_completo }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $patient->cpf }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $patient->telefone }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($patient->treatmentSessions->count())
                                            {{ $patient->treatmentSessions->sortByDesc('data_sessao')->first()->data_sessao->format('d/m/Y') }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <a href="{{ route('patients.show', $patient) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Ver</a>
                                        <a href="{{ route('patients.edit', $patient) }}" class="text-gray-600 hover:text-gray-900">Editar</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        Nenhum paciente encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $patients->links() }}
                </div>
            </x-clinic-card>
        </div>
    </div>
</x-app-layout>
