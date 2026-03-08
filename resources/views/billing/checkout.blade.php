<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Checkout - {{ $plano->nome }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Resumo do plano --}}
                <div class="lg:col-span-1">
                    <x-clinic-card title="Resumo">
                        <div class="space-y-3">
                            <h4 class="text-lg font-bold text-gray-900">{{ $plano->nome }}</h4>
                            <div>
                                <span class="text-2xl font-bold text-indigo-600">{{ $plano->valor_mensal_formatado }}</span>
                                <span class="text-gray-500">/mes</span>
                            </div>
                            @if($plano->temTrial())
                                <p class="text-sm text-indigo-600 font-medium">
                                    {{ $plano->trial_dias }} dias gratis
                                </p>
                            @endif
                            <div class="pt-3 border-t border-gray-100 space-y-2 text-sm text-gray-600">
                                <p>{{ $plano->limite_usuarios === -1 ? 'Usuarios ilimitados' : $plano->limite_usuarios . ' usuario(s)' }}</p>
                                <p>{{ $plano->limite_pacientes === -1 ? 'Pacientes ilimitados' : $plano->limite_pacientes . ' pacientes' }}</p>
                                <p>{{ $plano->limite_agendamentos_mes === -1 ? 'Agendamentos ilimitados' : $plano->limite_agendamentos_mes . ' agendamentos/mes' }}</p>
                            </div>
                        </div>
                    </x-clinic-card>
                </div>

                {{-- Forma de pagamento --}}
                <div class="lg:col-span-2" x-data="{ method: 'pix' }">
                    <x-clinic-card title="Forma de Pagamento">
                        {{-- Tabs --}}
                        <div class="flex gap-2 mb-6">
                            <button @click="method = 'pix'"
                                    :class="method === 'pix' ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50'"
                                    class="flex-1 px-4 py-3 border-2 rounded-lg font-medium transition text-center">
                                <svg class="h-5 w-5 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                PIX
                            </button>
                            <button @click="method = 'card'"
                                    :class="method === 'card' ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50'"
                                    class="flex-1 px-4 py-3 border-2 rounded-lg font-medium transition text-center">
                                <svg class="h-5 w-5 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Cartao de Credito
                            </button>
                        </div>

                        {{-- PIX --}}
                        <div x-show="method === 'pix'">
                            <div class="text-center py-4">
                                <p class="text-gray-600 mb-4">Ao clicar no botao abaixo, um QR Code PIX sera gerado para pagamento.</p>
                                <form action="{{ route('billing.pix') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="plano_id" value="{{ $plano->id }}">
                                    <button type="submit"
                                            class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition">
                                        Gerar PIX - {{ $plano->valor_mensal_formatado }}
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Cartao --}}
                        <div x-show="method === 'card'">
                            <form action="{{ route('billing.card') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="plano_id" value="{{ $plano->id }}">

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome no cartao</label>
                                    <input type="text" name="holder_name" required
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="Como aparece no cartao">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Numero do cartao</label>
                                    <input type="text" name="number" required maxlength="19"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="0000 0000 0000 0000">
                                </div>

                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mes</label>
                                        <input type="text" name="expiry_month" required maxlength="2"
                                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               placeholder="MM">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Ano</label>
                                        <input type="text" name="expiry_year" required maxlength="4"
                                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               placeholder="AAAA">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                                        <input type="text" name="ccv" required maxlength="4"
                                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               placeholder="123">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">CPF/CNPJ</label>
                                        <input type="text" name="cpf_cnpj" required
                                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               placeholder="000.000.000-00">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                                        <input type="email" name="email" required value="{{ auth()->user()->email }}"
                                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                                        <input type="text" name="phone"
                                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               placeholder="(00) 00000-0000">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">CEP</label>
                                        <input type="text" name="postal_code"
                                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               placeholder="00000-000">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Numero</label>
                                        <input type="text" name="address_number"
                                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               placeholder="123">
                                    </div>
                                </div>

                                <button type="submit"
                                        class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition mt-2">
                                    Pagar {{ $plano->valor_mensal_formatado }}
                                </button>
                            </form>
                        </div>
                    </x-clinic-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
