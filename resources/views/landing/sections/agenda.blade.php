<section class="py-20 lg:py-28 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            {{-- Content --}}
            <div>
                <span class="inline-block px-4 py-1.5 bg-blue-50 text-blue-600 text-sm font-semibold rounded-full mb-4">Agenda inteligente</span>
                <h2 class="text-3xl sm:text-4xl font-bold mb-6">Nunca mais perca um agendamento</h2>
                <p class="text-lg text-gray-600 mb-8">Gerencie todos os atendimentos da sua clínica com uma agenda visual, intuitiva e integrada ao prontuário de cada paciente.</p>

                <div class="space-y-4">
                    @php
                        $agendaFeatures = [
                            'Organização total por profissional',
                            'Visualização diária e semanal',
                            'Confirmação e cancelamento com um clique',
                            'Controle de status: agendado, confirmado, atendido',
                        ];
                    @endphp

                    @foreach($agendaFeatures as $item)
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-gray-700">{{ $item }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Visual --}}
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 lg:p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-semibold text-gray-900">Março 2026</h3>
                    <div class="flex gap-2">
                        <span class="px-3 py-1 bg-rose-50 text-rose-600 text-xs font-medium rounded-full">Diário</span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">Semanal</span>
                    </div>
                </div>

                <div class="space-y-3">
                    @php
                        $appointments = [
                            ['time' => '08:00', 'patient' => 'Maria Silva', 'proc' => 'Botox - Testa', 'status' => 'confirmado', 'color' => 'green'],
                            ['time' => '09:30', 'patient' => 'Ana Costa', 'proc' => 'Preenchimento Labial', 'status' => 'agendado', 'color' => 'blue'],
                            ['time' => '11:00', 'patient' => 'Júlia Santos', 'proc' => 'Limpeza de Pele', 'status' => 'confirmado', 'color' => 'green'],
                            ['time' => '14:00', 'patient' => 'Carla Mendes', 'proc' => 'Harmonização Facial', 'status' => 'agendado', 'color' => 'blue'],
                            ['time' => '15:30', 'patient' => 'Patrícia Lima', 'proc' => 'Peeling Químico', 'status' => 'confirmado', 'color' => 'green'],
                        ];
                    @endphp

                    @foreach($appointments as $apt)
                        <div class="flex items-center gap-4 p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                            <span class="text-sm font-mono font-medium text-gray-500 w-12">{{ $apt['time'] }}</span>
                            <div class="w-1 h-8 rounded-full {{ $apt['color'] === 'green' ? 'bg-green-400' : 'bg-blue-400' }}"></div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-sm text-gray-900 truncate">{{ $apt['patient'] }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $apt['proc'] }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $apt['color'] === 'green' ? 'bg-green-50 text-green-600' : 'bg-blue-50 text-blue-600' }}">
                                {{ $apt['status'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
