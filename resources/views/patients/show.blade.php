<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('patients.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $patient->nome_completo }}</h2>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('patients.sessions.create', $patient) }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                    Nova Sessao
                </a>
                <a href="{{ route('patients.edit', $patient) }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    Editar
                </a>
            </div>
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
                {{-- Dados do Paciente --}}
                <div class="lg:col-span-2 space-y-6">
                    <x-clinic-card title="Dados Pessoais">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Data de Nascimento</dt>
                                <dd class="text-sm text-gray-900">{{ $patient->data_nascimento->format('d/m/Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Sexo</dt>
                                <dd class="text-sm text-gray-900 capitalize">{{ $patient->sexo }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">CPF</dt>
                                <dd class="text-sm text-gray-900">{{ $patient->cpf }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Telefone</dt>
                                <dd class="text-sm text-gray-900">{{ $patient->telefone }}</dd>
                            </div>
                            @if($patient->email)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">E-mail</dt>
                                <dd class="text-sm text-gray-900">{{ $patient->email }}</dd>
                            </div>
                            @endif
                            @if($patient->profissao)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Profissao</dt>
                                <dd class="text-sm text-gray-900">{{ $patient->profissao }}</dd>
                            </div>
                            @endif
                            @if($patient->endereco)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Endereco</dt>
                                <dd class="text-sm text-gray-900">{{ $patient->endereco }}</dd>
                            </div>
                            @endif
                            @if($patient->observacoes_gerais)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Observacoes Gerais</dt>
                                <dd class="text-sm text-gray-900">{{ $patient->observacoes_gerais }}</dd>
                            </div>
                            @endif
                        </dl>
                    </x-clinic-card>

                    <x-clinic-card title="Dados Clinicos">
                        <dl class="grid grid-cols-1 gap-4">
                            @foreach([
                                'historico_medico' => 'Historico Medico',
                                'medicamentos_continuo' => 'Medicamentos de Uso Continuo',
                                'alergias' => 'Alergias',
                                'doencas_preexistentes' => 'Doencas Pre-existentes',
                                'contraindicacoes_esteticas' => 'Contraindicacoes Esteticas',
                                'observacoes_medicas' => 'Observacoes Medicas',
                            ] as $field => $label)
                                @if($patient->$field)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ $label }}</dt>
                                    <dd class="text-sm text-gray-900 mt-1">{{ $patient->$field }}</dd>
                                </div>
                                @endif
                            @endforeach

                            @if(!$patient->historico_medico && !$patient->medicamentos_continuo && !$patient->alergias && !$patient->doencas_preexistentes && !$patient->contraindicacoes_esteticas && !$patient->observacoes_medicas)
                                <p class="text-sm text-gray-400">Nenhum dado clinico registrado.</p>
                            @endif
                        </dl>
                    </x-clinic-card>
                </div>

                {{-- Timeline de Sessoes --}}
                <div>
                    <x-clinic-card title="Historico de Sessoes">
                        <x-session-timeline :sessions="$patient->treatmentSessions" :patient="$patient" />
                    </x-clinic-card>

                    {{-- Fotos Clinicas do Paciente --}}
                    @if($patient->clinicalPhotos->count())
                    <div class="mt-6">
                        <x-clinic-card title="Fotos Clinicas">
                            <x-photo-gallery :photos="$patient->clinicalPhotos" :editable="false" />
                        </x-clinic-card>
                    </div>
                    @endif

                    {{-- Prescricoes Recentes --}}
                    @if($patient->prescricoes->count())
                    <div class="mt-6">
                        <x-clinic-card title="Prescricoes Recentes">
                            <div class="divide-y divide-gray-100">
                                @foreach($patient->prescricoes as $prescricao)
                                    <div class="py-2 flex items-center justify-between">
                                        <div>
                                            <a href="{{ route('patients.sessions.prescricao.show', [$patient, $prescricao->treatmentSession, $prescricao]) }}"
                                               class="text-sm text-indigo-600 hover:text-indigo-900">
                                                Prescricao #{{ $prescricao->id }}
                                            </a>
                                            <span class="ml-2 text-xs text-gray-500">
                                                {{ $prescricao->created_at->format('d/m/Y') }}
                                            </span>
                                            @if($prescricao->isRascunho())
                                                <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Rascunho</span>
                                            @elseif($prescricao->isEmitida())
                                                <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Emitida</span>
                                            @endif
                                        </div>
                                        @if(!$prescricao->isRascunho())
                                            <a href="{{ route('patients.sessions.prescricao.pdf', [$patient, $prescricao->treatmentSession, $prescricao]) }}"
                                               target="_blank" class="text-gray-400 hover:text-gray-600">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </x-clinic-card>
                    </div>
                    @endif

                    {{-- Proximos Agendamentos --}}
                    @if($patient->agendamentos->count())
                    <div class="mt-6">
                        <x-clinic-card title="Proximos Agendamentos">
                            <div class="divide-y divide-gray-100">
                                @foreach($patient->agendamentos as $agendamento)
                                    @php
                                        $statusBadge = match($agendamento->status) {
                                            'agendado' => 'bg-yellow-100 text-yellow-800',
                                            'confirmado' => 'bg-green-100 text-green-800',
                                            default => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                    <div class="py-2">
                                        <a href="{{ route('agendamentos.show', $agendamento) }}"
                                           class="text-sm text-indigo-600 hover:text-indigo-900">
                                            {{ $agendamento->data_agendamento->format('d/m/Y') }} as {{ $agendamento->hora_inicio }}
                                        </a>
                                        <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $statusBadge }}">
                                            {{ ucfirst($agendamento->status) }}
                                        </span>
                                        <p class="text-xs text-gray-500 truncate">
                                            {{ $agendamento->procedimentos->pluck('nome')->implode(', ') }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </x-clinic-card>
                    </div>
                    @endif

                    {{-- Contratos Recentes --}}
                    @if($patient->contratos->count())
                    <div class="mt-6">
                        <x-clinic-card title="Contratos Recentes">
                            <div class="divide-y divide-gray-100">
                                @foreach($patient->contratos as $contrato)
                                    <div class="py-2 flex items-center justify-between">
                                        <div>
                                            <a href="{{ route('patients.sessions.contrato.show', [$patient, $contrato->treatmentSession, $contrato]) }}"
                                               class="text-sm text-indigo-600 hover:text-indigo-900">
                                                Contrato #{{ $contrato->id }}
                                            </a>
                                            <span class="ml-2 text-xs text-gray-500">
                                                {{ $contrato->created_at->format('d/m/Y') }}
                                            </span>
                                            @if($contrato->isRascunho())
                                                <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Rascunho</span>
                                            @elseif($contrato->isGerado())
                                                <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Gerado</span>
                                            @elseif($contrato->isAssinado())
                                                <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Assinado</span>
                                            @endif
                                        </div>
                                        @if(!$contrato->isRascunho())
                                            <a href="{{ route('patients.sessions.contrato.pdf', [$patient, $contrato->treatmentSession, $contrato]) }}"
                                               target="_blank" class="text-gray-400 hover:text-gray-600">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </x-clinic-card>
                    </div>
                    @endif

                    {{-- Acoes --}}
                    <div class="mt-6">
                        <x-confirm-modal
                            :action="route('patients.destroy', $patient)"
                            title="Remover Paciente"
                            message="Tem certeza que deseja remover este paciente? Esta acao pode ser revertida."
                            confirmText="Remover">
                            <x-slot name="trigger">
                                <button type="button" class="w-full text-center px-4 py-2 bg-red-50 text-red-600 rounded-md hover:bg-red-100 text-sm">
                                    Remover Paciente
                                </button>
                            </x-slot>
                        </x-confirm-modal>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
