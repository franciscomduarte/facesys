<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Meu Plano</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('info'))
                <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg">
                    {{ session('info') }}
                </div>
            @endif

            @if(!$subscription)
                <x-clinic-card>
                    <div class="text-center py-8">
                        <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum plano ativo</h3>
                        <p class="text-gray-500 mb-4">Sua empresa nao possui um plano ativo.</p>
                        <a href="{{ route('billing.plans') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition">
                            Ver planos disponiveis
                        </a>
                    </div>
                </x-clinic-card>
            @else
                <div class="space-y-6">
                    {{-- Plano atual --}}
                    <x-clinic-card>
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $plano->nome }}</h3>
                                @if($plano->descricao)
                                    <p class="text-gray-500 mt-1">{{ $plano->descricao }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-indigo-600">{{ $subscription->valor_atual_formatado }}</div>
                                <div class="text-sm text-gray-500">/{{ $subscription->periodicidade === 'mensal' ? 'mes' : 'ano' }}</div>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center gap-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $subscription->status === 'ativa' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $subscription->status === 'trial' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $subscription->status === 'inadimplente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $subscription->status === 'cancelada' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $subscription->status === 'expirada' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ ucfirst($subscription->status) }}
                            </span>
                            @if($subscription->isTrial() && $subscription->trial_termina_em)
                                <span class="text-sm text-gray-500">Trial termina em {{ $subscription->trial_termina_em->format('d/m/Y') }}</span>
                            @endif
                            @if($subscription->proxima_cobranca)
                                <span class="text-sm text-gray-500">Proxima cobranca: {{ $subscription->proxima_cobranca->format('d/m/Y') }}</span>
                            @endif
                        </div>
                    </x-clinic-card>

                    {{-- Uso de recursos --}}
                    <x-clinic-card title="Uso de Recursos">
                        <div class="space-y-5">
                            @php
                                $recursoLabels = [
                                    'usuarios' => 'Usuarios',
                                    'pacientes' => 'Pacientes',
                                    'agendamentos_mes' => 'Agendamentos este mes',
                                ];
                            @endphp
                            @foreach($recursoLabels as $key => $label)
                                @if(isset($uso[$key]))
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                                            <span class="text-sm text-gray-500">
                                                {{ $uso[$key]['atual'] }} / {{ $uso[$key]['ilimitado'] ? 'Ilimitado' : $uso[$key]['limite'] }}
                                            </span>
                                        </div>
                                        @if(!$uso[$key]['ilimitado'])
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="h-2.5 rounded-full {{ $uso[$key]['percentual'] >= 90 ? 'bg-red-500' : ($uso[$key]['percentual'] >= 70 ? 'bg-yellow-500' : 'bg-indigo-600') }}"
                                                     style="width: {{ $uso[$key]['percentual'] }}%"></div>
                                            </div>
                                        @else
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-green-500 h-2.5 rounded-full" style="width: 100%"></div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </x-clinic-card>

                    {{-- Funcionalidades --}}
                    <x-clinic-card title="Funcionalidades do Plano">
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @php
                                $featureLabels = [
                                    'agendamentos' => 'Agendamentos',
                                    'prescricoes' => 'Prescricoes',
                                    'contratos' => 'Contratos',
                                    'fotos_clinicas' => 'Fotos Clinicas',
                                    'assinatura_digital' => 'Assinatura Digital',
                                    'relatorios' => 'Relatorios',
                                ];
                            @endphp
                            @foreach($featureLabels as $key => $label)
                                <div class="flex items-center gap-2 p-2 rounded-md {{ $plano->temFuncionalidade($key) ? 'bg-green-50' : 'bg-gray-50' }}">
                                    @if($plano->temFuncionalidade($key))
                                        <svg class="h-5 w-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    @endif
                                    <span class="text-sm {{ $plano->temFuncionalidade($key) ? 'text-green-800' : 'text-gray-500' }}">{{ $label }}</span>
                                </div>
                            @endforeach
                        </div>
                    </x-clinic-card>

                    {{-- Historico de pagamentos --}}
                    @if($payments->isNotEmpty())
                        <x-clinic-card title="Historico de Pagamentos">
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="text-left text-gray-500 border-b">
                                            <th class="pb-2 font-medium">Data</th>
                                            <th class="pb-2 font-medium">Valor</th>
                                            <th class="pb-2 font-medium">Metodo</th>
                                            <th class="pb-2 font-medium">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($payments as $payment)
                                            <tr>
                                                <td class="py-2 text-gray-700">{{ $payment->created_at->format('d/m/Y') }}</td>
                                                <td class="py-2 text-gray-700">{{ $payment->amount_formatado }}</td>
                                                <td class="py-2 text-gray-700">{{ $payment->method === 'pix' ? 'PIX' : 'Cartao' }}</td>
                                                <td class="py-2">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                        {{ $payment->isPaid() ? 'bg-green-100 text-green-800' : '' }}
                                                        {{ $payment->isPending() ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                        {{ $payment->isFailed() ? 'bg-red-100 text-red-800' : '' }}
                                                        {{ $payment->isRefunded() ? 'bg-gray-100 text-gray-800' : '' }}">
                                                        {{ ucfirst($payment->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </x-clinic-card>
                    @endif

                    {{-- Acoes --}}
                    <x-clinic-card title="Gerenciar Plano">
                        <div class="flex flex-wrap gap-3">
                            @if($planos->where('id', '!=', $plano->id)->isNotEmpty())
                                <a href="{{ route('billing.plans') }}"
                                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
                                    Trocar plano
                                </a>
                            @endif

                            @if($subscription->isAtiva() || $subscription->isTrial())
                                <div x-data="{ showCancel: false }">
                                    <button @click="showCancel = true"
                                            class="inline-flex items-center px-4 py-2 bg-white border border-red-300 text-red-700 rounded-lg text-sm font-medium hover:bg-red-50 transition">
                                        Cancelar assinatura
                                    </button>

                                    <div x-show="showCancel" x-cloak class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                        <p class="text-sm text-red-700 mb-3">Tem certeza? Voce perdera acesso ao final do periodo pago.</p>
                                        <form action="{{ route('billing.cancel') }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('DELETE')
                                            <input type="text" name="motivo" placeholder="Motivo (opcional)"
                                                   class="flex-1 rounded-lg border-gray-300 text-sm">
                                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700">
                                                Confirmar cancelamento
                                            </button>
                                            <button type="button" @click="showCancel = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm">
                                                Voltar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </x-clinic-card>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
