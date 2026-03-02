<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Agenda</h2>
            <a href="{{ route('agendamentos.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                Novo Agendamento
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

            {{-- Navegacao da semana + filtro profissional --}}
            <div class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('agendamentos.index', ['data' => $dataBase->copy()->subWeek()->format('Y-m-d'), 'profissional_id' => $profissionalId]) }}"
                           class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md transition">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>

                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $dataBase->translatedFormat('d M') }} — {{ $dataBase->copy()->addDays(5)->translatedFormat('d M Y') }}
                            </h3>
                            <p class="text-xs text-gray-500">Semana {{ $dataBase->weekOfYear }}</p>
                        </div>

                        <a href="{{ route('agendamentos.index', ['data' => $dataBase->copy()->addWeek()->format('Y-m-d'), 'profissional_id' => $profissionalId]) }}"
                           class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md transition">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        <a href="{{ route('agendamentos.index', ['profissional_id' => $profissionalId]) }}"
                           class="px-3 py-1 text-xs text-indigo-600 border border-indigo-200 rounded-md hover:bg-indigo-50 transition">
                            Hoje
                        </a>
                    </div>

                    {{-- Filtro profissional --}}
                    <form method="GET" action="{{ route('agendamentos.index') }}" class="flex items-center gap-2">
                        <input type="hidden" name="data" value="{{ $dataBase->format('Y-m-d') }}" />
                        <select name="profissional_id" onchange="this.form.submit()"
                                class="text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos os profissionais</option>
                            @foreach($profissionais as $prof)
                                <option value="{{ $prof->id }}" {{ $profissionalId == $prof->id ? 'selected' : '' }}>
                                    {{ $prof->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            {{-- Grid semanal --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                @foreach($dias as $dia)
                    @php
                        $isToday = $dia['data']->isToday();
                    @endphp
                    <div class="bg-white rounded-lg shadow-sm border {{ $isToday ? 'border-indigo-400 ring-1 ring-indigo-200' : 'border-gray-200' }}">
                        {{-- Header do dia --}}
                        <div class="px-3 py-2 border-b {{ $isToday ? 'bg-indigo-50 border-indigo-200' : 'bg-gray-50 border-gray-200' }} rounded-t-lg">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold {{ $isToday ? 'text-indigo-700' : 'text-gray-700' }}">
                                    {{ $dia['label'] }}
                                </span>
                                <span class="text-xs {{ $isToday ? 'text-indigo-500' : 'text-gray-400' }}">
                                    {{ $dia['agendamentos']->count() }}
                                </span>
                            </div>
                        </div>

                        {{-- Agendamentos do dia --}}
                        <div class="p-2 space-y-2 min-h-[120px]">
                            @forelse($dia['agendamentos'] as $ag)
                                @php
                                    $cardColors = [
                                        'agendado' => 'border-l-yellow-400 bg-yellow-50 hover:bg-yellow-100',
                                        'confirmado' => 'border-l-green-400 bg-green-50 hover:bg-green-100',
                                        'cancelado' => 'border-l-red-400 bg-red-50 hover:bg-red-100 opacity-60',
                                        'realizado' => 'border-l-blue-400 bg-blue-50 hover:bg-blue-100',
                                        'nao_compareceu' => 'border-l-gray-400 bg-gray-50 hover:bg-gray-100 opacity-60',
                                    ];
                                @endphp
                                <a href="{{ route('agendamentos.show', $ag) }}"
                                   class="block p-2 rounded border-l-4 {{ $cardColors[$ag->status] ?? '' }} transition cursor-pointer">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-bold text-gray-700">
                                            {{ $ag->hora_inicio }} - {{ $ag->hora_fim }}
                                        </span>
                                    </div>
                                    <p class="text-xs font-medium text-gray-900 truncate">
                                        {{ $ag->patient->nome_completo }}
                                    </p>
                                    <p class="text-xs text-gray-500 truncate">
                                        {{ $ag->procedimentos->pluck('nome')->implode(', ') }}
                                    </p>
                                    @if(!$profissionalId)
                                        <p class="text-xs text-gray-400 truncate mt-0.5">
                                            {{ $ag->profissional->name }}
                                        </p>
                                    @endif
                                </a>
                            @empty
                                <div class="flex items-center justify-center h-20 text-xs text-gray-400">
                                    Sem agendamentos
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Legenda --}}
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex flex-wrap items-center gap-4 text-xs text-gray-600">
                    <span class="font-medium">Legenda:</span>
                    <span class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded bg-yellow-400"></span> Agendado
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded bg-green-400"></span> Confirmado
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded bg-blue-400"></span> Realizado
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded bg-red-400"></span> Cancelado
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded bg-gray-400"></span> Nao compareceu
                    </span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
