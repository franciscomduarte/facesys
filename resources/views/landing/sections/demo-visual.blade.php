<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 bg-purple-50 text-purple-600 text-sm font-semibold rounded-full mb-4">Conheça o sistema</span>
            <h2 class="text-3xl sm:text-4xl font-bold mb-4">Uma visão do <span class="bg-gradient-to-r from-rose-500 to-purple-600 bg-clip-text text-transparent">painel completo</span></h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Interface moderna e intuitiva para gerenciar toda sua clínica em um só lugar.</p>
        </div>

        {{-- Dashboard mockup --}}
        <div class="relative max-w-5xl mx-auto">
            <div class="bg-gradient-to-b from-gray-900 to-gray-800 rounded-2xl p-4 shadow-2xl">
                {{-- Browser bar --}}
                <div class="flex items-center gap-2 mb-4 px-2">
                    <div class="flex gap-1.5">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    </div>
                    <div class="flex-1 bg-gray-700 rounded-lg px-4 py-1.5 ml-3">
                        <span class="text-gray-400 text-xs font-mono">app.skinflow.com/dashboard</span>
                    </div>
                </div>

                {{-- Dashboard content --}}
                <div class="bg-gray-100 rounded-xl overflow-hidden">
                    <div class="flex min-h-[400px]">
                        {{-- Sidebar --}}
                        <div class="hidden sm:block w-56 bg-white border-r border-gray-200 p-4">
                            <div class="flex items-center gap-2 mb-8">
                                <div class="w-8 h-8 bg-gradient-to-br from-rose-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">SF</span>
                                </div>
                                <span class="font-bold text-sm text-gray-900">SkinFlow</span>
                            </div>
                            @php
                                $menuItems = [
                                    ['Dashboard', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', true],
                                    ['Pacientes', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', false],
                                    ['Agenda', 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', false],
                                    ['Prontuários', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', false],
                                    ['Prescrições', 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z', false],
                                ];
                            @endphp
                            <nav class="space-y-1">
                                @foreach($menuItems as [$label, $icon, $active])
                                    <div class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ $active ? 'bg-rose-50 text-rose-600 font-semibold' : 'text-gray-600' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $icon }}"/></svg>
                                        {{ $label }}
                                    </div>
                                @endforeach
                            </nav>
                        </div>

                        {{-- Main content --}}
                        <div class="flex-1 p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Dashboard</h3>
                                    <p class="text-sm text-gray-500">Visão geral da sua clínica</p>
                                </div>
                                <div class="hidden sm:flex items-center gap-2">
                                    <div class="w-8 h-8 bg-gradient-to-br from-rose-400 to-pink-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">AS</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Stats cards --}}
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
                                @php
                                    $stats = [
                                        ['Pacientes', '247', '+12%', 'text-blue-600', 'bg-blue-50'],
                                        ['Hoje', '8', 'agendados', 'text-green-600', 'bg-green-50'],
                                        ['Semana', '34', 'atendimentos', 'text-purple-600', 'bg-purple-50'],
                                        ['Receita', 'R$ 12.4k', 'este mês', 'text-rose-600', 'bg-rose-50'],
                                    ];
                                @endphp
                                @foreach($stats as [$label, $value, $sub, $color, $bg])
                                    <div class="bg-white rounded-xl p-3 border border-gray-200">
                                        <p class="text-xs text-gray-500 mb-1">{{ $label }}</p>
                                        <p class="text-lg font-bold {{ $color }}">{{ $value }}</p>
                                        <p class="text-xs text-gray-400">{{ $sub }}</p>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Appointments preview --}}
                            <div class="bg-white rounded-xl border border-gray-200 p-4">
                                <p class="text-sm font-semibold text-gray-900 mb-3">Próximos atendimentos</p>
                                <div class="space-y-2">
                                    @php
                                        $appointments = [
                                            ['09:00', 'Maria Santos', 'Botox — Testa', 'bg-rose-100 text-rose-700'],
                                            ['10:30', 'João Oliveira', 'Preenchimento labial', 'bg-purple-100 text-purple-700'],
                                            ['14:00', 'Ana Costa', 'Harmonização facial', 'bg-blue-100 text-blue-700'],
                                        ];
                                    @endphp
                                    @foreach($appointments as [$time, $patient, $procedure, $badge])
                                        <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                                            <div class="flex items-center gap-3">
                                                <span class="text-xs font-mono text-gray-500 w-10">{{ $time }}</span>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $patient }}</p>
                                                    <p class="text-xs text-gray-500">{{ $procedure }}</p>
                                                </div>
                                            </div>
                                            <span class="text-xs px-2 py-0.5 rounded-full {{ $badge }}">Confirmado</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Decorative blur --}}
            <div class="absolute -bottom-6 left-1/2 -translate-x-1/2 w-3/4 h-12 bg-gradient-to-r from-rose-500/20 to-purple-600/20 blur-2xl rounded-full"></div>
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('landing.demo') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-rose-500 to-purple-600 text-white font-semibold rounded-full hover:shadow-lg hover:shadow-rose-500/25 transition-all duration-300 transform hover:-translate-y-0.5">
                Testar demonstração gratuita
                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>
</section>
