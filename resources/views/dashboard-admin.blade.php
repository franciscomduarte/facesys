<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard SaaS</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Cards de metricas --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-100 rounded-lg p-3">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Empresas Ativas</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $empresasAtivas }}</p>
                            <p class="text-xs text-gray-400">de {{ $empresasTotal }} total</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Subscriptions Ativas</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $subscriptionsAtivas }}</p>
                            @if(($subscriptionsPorStatus['inadimplente'] ?? 0) > 0)
                                <p class="text-xs text-yellow-600">{{ $subscriptionsPorStatus['inadimplente'] }} inadimplente(s)</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-emerald-100 rounded-lg p-3">
                            <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">MRR</p>
                            <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($mrr, 2, ',', '.') }}</p>
                            <p class="text-xs text-gray-400">receita mensal recorrente</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Trials Ativos</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $trialsAtivos }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Resumo de status --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <x-clinic-card title="Empresas por Status">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Ativas</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $empresasAtivas }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Suspensas</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">{{ $empresasSuspensas }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Inativas</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $empresasInativas }}</span>
                        </div>
                    </div>
                </x-clinic-card>

                <x-clinic-card title="Subscriptions por Status">
                    <div class="space-y-3">
                        @foreach(['ativa' => ['Ativas', 'bg-green-100 text-green-800'], 'trial' => ['Trial', 'bg-blue-100 text-blue-800'], 'inadimplente' => ['Inadimplentes', 'bg-yellow-100 text-yellow-800'], 'cancelada' => ['Canceladas', 'bg-red-100 text-red-800'], 'expirada' => ['Expiradas', 'bg-gray-100 text-gray-800']] as $status => [$label, $classes])
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ $label }}</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">{{ $subscriptionsPorStatus[$status] ?? 0 }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-clinic-card>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Ultimas empresas --}}
                <x-clinic-card title="Ultimas Empresas Cadastradas">
                    @forelse($ultimasEmpresas as $empresa)
                        <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <div>
                                <a href="{{ route('empresas.show', $empresa) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                    {{ $empresa->nome_fantasia }}
                                </a>
                                <p class="text-xs text-gray-500">
                                    {{ $empresa->subscription?->plano?->nome ?? 'Sem plano' }}
                                    &middot;
                                    <span class="{{ $empresa->status === 'ativa' ? 'text-green-600' : ($empresa->status === 'suspensa' ? 'text-yellow-600' : 'text-gray-400') }}">
                                        {{ ucfirst($empresa->status) }}
                                    </span>
                                </p>
                            </div>
                            <span class="text-xs text-gray-400">{{ $empresa->created_at->format('d/m/Y') }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-4">Nenhuma empresa cadastrada.</p>
                    @endforelse
                </x-clinic-card>

                {{-- Atencao necessaria --}}
                <x-clinic-card title="Atencao Necessaria">
                    @forelse($atencaoNecessaria as $sub)
                        <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <div>
                                <a href="{{ route('subscriptions.show', $sub) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                    {{ $sub->empresa->nome_fantasia }}
                                </a>
                                <p class="text-xs text-gray-500">
                                    {{ $sub->plano->nome }}
                                    &middot;
                                    <span class="{{ $sub->status === 'inadimplente' ? 'text-yellow-600 font-medium' : 'text-blue-600' }}">
                                        {{ ucfirst($sub->status) }}
                                    </span>
                                </p>
                            </div>
                            <div class="text-right">
                                @if($sub->status === 'trial' && $sub->trial_termina_em)
                                    <span class="text-xs text-blue-600">Trial expira {{ $sub->trial_termina_em->format('d/m') }}</span>
                                @elseif($sub->proxima_cobranca)
                                    <span class="text-xs text-gray-500">Cobranca {{ $sub->proxima_cobranca->format('d/m') }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-green-500 text-center py-4">Tudo em ordem! Nenhum alerta no momento.</p>
                    @endforelse
                </x-clinic-card>
            </div>
        </div>
    </div>
</x-app-layout>
