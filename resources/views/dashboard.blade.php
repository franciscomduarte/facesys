<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Cards de resumo --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-100 rounded-lg p-3">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total de Pacientes</p>
                            <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Patient::count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Sessoes Realizadas</p>
                            <p class="text-2xl font-bold text-gray-900">{{ \App\Models\TreatmentSession::count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Sessoes Este Mes</p>
                            <p class="text-2xl font-bold text-gray-900">{{ \App\Models\TreatmentSession::whereMonth('data_sessao', now()->month)->whereYear('data_sessao', now()->year)->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-amber-100 rounded-lg p-3">
                            <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Procedimentos Ativos</p>
                            <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Procedimento::where('ativo', true)->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Acesso rapido --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-clinic-card title="Acesso Rapido">
                    <div class="space-y-3">
                        <a href="{{ route('patients.create') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition">
                            <div class="bg-indigo-100 rounded-lg p-2">
                                <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Novo Paciente</p>
                                <p class="text-sm text-gray-500">Cadastrar um novo paciente</p>
                            </div>
                        </a>
                        <a href="{{ route('patients.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition">
                            <div class="bg-gray-100 rounded-lg p-2">
                                <svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Buscar Paciente</p>
                                <p class="text-sm text-gray-500">Encontrar prontuario existente</p>
                            </div>
                        </a>
                        <a href="{{ route('procedimentos.create') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition">
                            <div class="bg-amber-100 rounded-lg p-2">
                                <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Novo Procedimento</p>
                                <p class="text-sm text-gray-500">Cadastrar procedimento estetico</p>
                            </div>
                        </a>
                    </div>
                </x-clinic-card>

                <x-clinic-card title="Ultimas Sessoes">
                    @php
                        $recentSessions = \App\Models\TreatmentSession::with('patient')
                            ->orderByDesc('data_sessao')
                            ->limit(5)
                            ->get();
                    @endphp
                    @forelse($recentSessions as $session)
                        <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <div>
                                <a href="{{ route('patients.sessions.show', [$session->patient, $session]) }}"
                                   class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                    {{ $session->patient->nome_completo }}
                                </a>
                                <p class="text-xs text-gray-500">{{ $session->procedimento }} - {{ $session->marca_produto }}</p>
                            </div>
                            <span class="text-xs text-gray-400">{{ $session->data_sessao->format('d/m/Y') }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-4">Nenhuma sessao registrada.</p>
                    @endforelse
                </x-clinic-card>
            </div>
        </div>
    </div>
</x-app-layout>
