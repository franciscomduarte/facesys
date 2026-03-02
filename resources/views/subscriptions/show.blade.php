<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('subscriptions.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Subscription: {{ $subscription->empresa->nome_fantasia }}
            </h2>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                {{ $subscription->status === 'ativa' ? 'bg-green-100 text-green-800' : '' }}
                {{ $subscription->status === 'trial' ? 'bg-blue-100 text-blue-800' : '' }}
                {{ $subscription->status === 'inadimplente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                {{ $subscription->status === 'cancelada' ? 'bg-red-100 text-red-800' : '' }}
                {{ $subscription->status === 'expirada' ? 'bg-gray-100 text-gray-800' : '' }}">
                {{ ucfirst($subscription->status) }}
            </span>
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
                    <x-clinic-card title="Dados da Subscription">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Empresa</dt>
                                <dd class="text-sm text-gray-900">{{ $subscription->empresa->nome_fantasia }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Plano</dt>
                                <dd class="text-sm text-gray-900 font-semibold">{{ $subscription->plano->nome }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Valor Atual</dt>
                                <dd class="text-sm text-gray-900">{{ $subscription->valor_atual_formatado }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Periodicidade</dt>
                                <dd class="text-sm text-gray-900">{{ ucfirst($subscription->periodicidade) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Data Inicio</dt>
                                <dd class="text-sm text-gray-900">{{ $subscription->data_inicio->format('d/m/Y') }}</dd>
                            </div>
                            @if($subscription->data_fim)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Data Fim</dt>
                                <dd class="text-sm text-gray-900">{{ $subscription->data_fim->format('d/m/Y') }}</dd>
                            </div>
                            @endif
                            @if($subscription->trial_termina_em)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Trial Termina Em</dt>
                                <dd class="text-sm text-gray-900">{{ $subscription->trial_termina_em->format('d/m/Y') }}</dd>
                            </div>
                            @endif
                            @if($subscription->proxima_cobranca)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Proxima Cobranca</dt>
                                <dd class="text-sm text-gray-900">{{ $subscription->proxima_cobranca->format('d/m/Y') }}</dd>
                            </div>
                            @endif
                            @if($subscription->gateway)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Gateway</dt>
                                <dd class="text-sm text-gray-900">{{ ucfirst($subscription->gateway) }}</dd>
                            </div>
                            @endif
                        </dl>
                    </x-clinic-card>
                </div>

                <div class="space-y-6">
                    <x-clinic-card title="Acoes">
                        <div class="space-y-3">
                            @if($subscription->isAtiva() || $subscription->isTrial() || $subscription->isInadimplente())
                                <div x-data="{ open: false }">
                                    <button @click="open = !open" type="button"
                                            class="w-full text-center px-4 py-2 bg-red-50 text-red-600 rounded-md hover:bg-red-100 text-sm">
                                        Cancelar Subscription
                                    </button>
                                    <div x-show="open" x-transition class="mt-3">
                                        <form method="POST" action="{{ route('subscriptions.cancelar', $subscription) }}">
                                            @csrf
                                            @method('PATCH')
                                            <textarea name="motivo" rows="2" placeholder="Motivo do cancelamento..."
                                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm mb-2"></textarea>
                                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm">
                                                Confirmar Cancelamento
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif

                            @if($subscription->isCancelada() || $subscription->isExpirada())
                                <form method="POST" action="{{ route('subscriptions.reativar', $subscription) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="w-full text-center px-4 py-2 bg-green-50 text-green-600 rounded-md hover:bg-green-100 text-sm">
                                        Reativar Subscription
                                    </button>
                                </form>
                            @endif

                            @if(!$subscription->isCancelada() && !$subscription->isExpirada())
                                <div x-data="{ open: false }">
                                    <button @click="open = !open" type="button"
                                            class="w-full text-center px-4 py-2 bg-indigo-50 text-indigo-600 rounded-md hover:bg-indigo-100 text-sm">
                                        Alterar Plano
                                    </button>
                                    <div x-show="open" x-transition class="mt-3">
                                        <form method="POST" action="{{ route('subscriptions.alterar-plano', $subscription) }}">
                                            @csrf
                                            @method('PATCH')
                                            <select name="plano_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm mb-2">
                                                @foreach(\App\Models\Plano::ativos()->ordenados()->get() as $plano)
                                                    <option value="{{ $plano->id }}" {{ $plano->id == $subscription->plano_id ? 'selected' : '' }}>
                                                        {{ $plano->nome }} — {{ $plano->valor_mensal_formatado }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">
                                                Confirmar Alteracao
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </x-clinic-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
