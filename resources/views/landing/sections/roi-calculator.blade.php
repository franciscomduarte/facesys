<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="inline-block px-4 py-1.5 bg-green-50 text-green-600 text-sm font-semibold rounded-full mb-4">Calculadora de economia</span>
                <h2 class="text-3xl sm:text-4xl font-bold mb-4">Descubra quanto tempo você <span class="bg-gradient-to-r from-rose-500 to-purple-600 bg-clip-text text-transparent">economiza por mês</span></h2>
                <p class="text-lg text-gray-600 mb-8">Profissionais que usam o SkinFlow economizam em média 15 horas por mês em tarefas administrativas. Calcule sua economia.</p>

                <div class="space-y-4 text-gray-600">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Elimine retrabalho com prontuários manuais</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Reduza faltas com lembretes automáticos</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Ganhe mais tempo para atender pacientes</span>
                    </div>
                </div>
            </div>

            <div x-data="{
                pacientes: 50,
                minutosProntuario: 15,
                minutosAgenda: 30,
                get horasProntuarioMes() {
                    return this.pacientes * this.minutosProntuario * 4 / 60;
                },
                get horasAgendaMes() {
                    return this.minutosAgenda * 22 / 60;
                },
                get horasMes() {
                    return Math.round(this.horasProntuarioMes + this.horasAgendaMes);
                },
                get horasEconomizadas() {
                    return Math.round(this.horasMes * 0.7);
                },
                get valorEconomizado() {
                    return (this.horasEconomizadas * 80).toLocaleString('pt-BR');
                }
            }" class="bg-gradient-to-b from-gray-900 to-gray-800 rounded-2xl p-8 text-white">
                <h3 class="text-xl font-bold mb-6 text-center">Calculadora de Economia</h3>

                <div class="space-y-6">
                    <div>
                        <div class="flex justify-between mb-2">
                            <label class="text-sm text-gray-300">Pacientes por semana</label>
                            <span class="text-sm font-semibold text-rose-400" x-text="pacientes"></span>
                        </div>
                        <input type="range" min="10" max="200" step="5" x-model="pacientes" class="w-full h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer accent-rose-500">
                    </div>

                    <div>
                        <div class="flex justify-between mb-2">
                            <label class="text-sm text-gray-300">Min. por prontuário (manual)</label>
                            <span class="text-sm font-semibold text-rose-400" x-text="minutosProntuario + ' min'"></span>
                        </div>
                        <input type="range" min="5" max="30" step="1" x-model="minutosProntuario" class="w-full h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer accent-rose-500">
                    </div>

                    <div>
                        <div class="flex justify-between mb-2">
                            <label class="text-sm text-gray-300">Min. por dia organizando agenda</label>
                            <span class="text-sm font-semibold text-rose-400" x-text="minutosAgenda + ' min'"></span>
                        </div>
                        <input type="range" min="10" max="60" step="5" x-model="minutosAgenda" class="w-full h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer accent-rose-500">
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-700">
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="text-center">
                            <p class="text-3xl font-bold text-white" x-text="horasMes + 'h'"></p>
                            <p class="text-xs text-gray-400 mt-1">Horas gastas/mês</p>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-bold bg-gradient-to-r from-green-400 to-emerald-400 bg-clip-text text-transparent" x-text="horasEconomizadas + 'h'"></p>
                            <p class="text-xs text-gray-400 mt-1">Horas economizadas</p>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-green-500/20 to-emerald-500/20 rounded-xl p-4 text-center">
                        <p class="text-sm text-green-300 mb-1">Economia estimada por mês</p>
                        <p class="text-3xl font-bold text-green-400">R$ <span x-text="valorEconomizado"></span></p>
                        <p class="text-xs text-gray-400 mt-1">Baseado em R$ 80/hora de trabalho</p>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <a href="#planos" class="inline-flex items-center justify-center w-full px-6 py-3.5 bg-gradient-to-r from-rose-500 to-purple-600 text-white font-semibold rounded-full hover:shadow-lg hover:shadow-rose-500/25 transition-all duration-300">
                        Começar a economizar agora
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
