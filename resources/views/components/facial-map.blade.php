@props([
    'points' => [],
    'editable' => false,
])

<div
    x-data="facialMap({
        initialPoints: {{ json_encode($points instanceof \Illuminate\Support\Collection ? $points->toArray() : $points) }},
        editable: {{ $editable ? 'true' : 'false' }},
    })"
    class="relative"
>
    {{-- Instrucao --}}
    @if($editable)
    <p class="text-sm text-gray-500 mb-2">Clique no rosto para marcar pontos de aplicacao.</p>
    @endif

    <div class="flex flex-col lg:flex-row gap-6">
        {{-- SVG Face --}}
        <div class="flex-1">
            <svg
                viewBox="0 0 400 500"
                class="w-full max-w-md mx-auto border rounded-lg bg-white {{ $editable ? 'cursor-crosshair' : '' }}"
                @if($editable) @click="handleSvgClick($event)" @endif
                x-ref="svgFace"
            >
                {{-- Contorno do rosto --}}
                <ellipse cx="200" cy="230" rx="145" ry="195" fill="#fef3c7" fill-opacity="0.3" stroke="#d1d5db" stroke-width="2" />

                {{-- Cabelo/Testa --}}
                <path d="M55,230 Q55,50 200,35 Q345,50 345,230" fill="none" stroke="#d1d5db" stroke-width="1.5" />

                {{-- Frontalis (testa) --}}
                <path d="M80,80 Q200,45 320,80 L310,140 Q200,115 90,140 Z"
                      fill="rgba(191,219,254,0.25)" stroke="#93c5fd" stroke-width="1" stroke-dasharray="4,4" />
                <text x="200" y="105" text-anchor="middle" class="text-[10px] fill-blue-400 pointer-events-none select-none">Frontalis</text>

                {{-- Glabela --}}
                <rect x="172" y="130" width="56" height="38" rx="8"
                      fill="rgba(253,230,138,0.25)" stroke="#fbbf24" stroke-width="1" stroke-dasharray="4,4" />
                <text x="200" y="154" text-anchor="middle" class="text-[10px] fill-yellow-500 pointer-events-none select-none">Glabela</text>

                {{-- Sobrancelha esquerda --}}
                <path d="M90,155 Q130,135 170,150" fill="none" stroke="#9ca3af" stroke-width="2" />
                {{-- Sobrancelha direita --}}
                <path d="M230,150 Q270,135 310,155" fill="none" stroke="#9ca3af" stroke-width="2" />

                {{-- Olho esquerdo --}}
                <ellipse cx="140" cy="185" rx="35" ry="14" fill="white" stroke="#9ca3af" stroke-width="1.5" />
                <circle cx="140" cy="185" r="8" fill="#6b7280" />
                <circle cx="142" cy="183" r="3" fill="white" />

                {{-- Olho direito --}}
                <ellipse cx="260" cy="185" rx="35" ry="14" fill="white" stroke="#9ca3af" stroke-width="1.5" />
                <circle cx="260" cy="185" r="8" fill="#6b7280" />
                <circle cx="262" cy="183" r="3" fill="white" />

                {{-- Periorbital E --}}
                <ellipse cx="130" cy="185" rx="50" ry="30"
                         fill="rgba(167,243,208,0.15)" stroke="#6ee7b7" stroke-width="1" stroke-dasharray="4,4" />
                <text x="85" y="190" text-anchor="middle" class="text-[9px] fill-green-400 pointer-events-none select-none">Periorbital E</text>

                {{-- Periorbital D --}}
                <ellipse cx="270" cy="185" rx="50" ry="30"
                         fill="rgba(167,243,208,0.15)" stroke="#6ee7b7" stroke-width="1" stroke-dasharray="4,4" />
                <text x="315" y="190" text-anchor="middle" class="text-[9px] fill-green-400 pointer-events-none select-none">Periorbital D</text>

                {{-- Nariz --}}
                <path d="M192,200 L186,270 Q200,282 214,270 L208,200" fill="none" stroke="#d1d5db" stroke-width="1.5" />

                {{-- Perioral --}}
                <ellipse cx="200" cy="320" rx="48" ry="28"
                         fill="rgba(252,165,165,0.15)" stroke="#fca5a5" stroke-width="1" stroke-dasharray="4,4" />
                <text x="200" y="348" text-anchor="middle" class="text-[9px] fill-red-400 pointer-events-none select-none">Perioral</text>

                {{-- Boca --}}
                <path d="M170,315 Q185,305 200,308 Q215,305 230,315" fill="none" stroke="#f87171" stroke-width="1.5" />
                <path d="M170,315 Q185,330 200,332 Q215,330 230,315" fill="none" stroke="#f87171" stroke-width="1.5" />

                {{-- Masseter E --}}
                <ellipse cx="95" cy="310" rx="32" ry="50"
                         fill="rgba(196,181,253,0.15)" stroke="#a78bfa" stroke-width="1" stroke-dasharray="4,4" />
                <text x="95" y="313" text-anchor="middle" class="text-[9px] fill-purple-400 pointer-events-none select-none">Masseter E</text>

                {{-- Masseter D --}}
                <ellipse cx="305" cy="310" rx="32" ry="50"
                         fill="rgba(196,181,253,0.15)" stroke="#a78bfa" stroke-width="1" stroke-dasharray="4,4" />
                <text x="305" y="313" text-anchor="middle" class="text-[9px] fill-purple-400 pointer-events-none select-none">Masseter D</text>

                {{-- Mentual (queixo) --}}
                <ellipse cx="200" cy="380" rx="38" ry="22"
                         fill="rgba(253,186,116,0.15)" stroke="#fdba74" stroke-width="1" stroke-dasharray="4,4" />
                <text x="200" y="383" text-anchor="middle" class="text-[9px] fill-orange-400 pointer-events-none select-none">Mentual</text>

                {{-- Orelha esquerda --}}
                <ellipse cx="52" cy="220" rx="12" ry="30" fill="none" stroke="#d1d5db" stroke-width="1.5" />
                {{-- Orelha direita --}}
                <ellipse cx="348" cy="220" rx="12" ry="30" fill="none" stroke="#d1d5db" stroke-width="1.5" />

                {{-- Pontos de aplicacao --}}
                <template x-for="(point, index) in points" :key="index">
                    <g class="cursor-pointer" @click.stop="selectPoint(index)">
                        <circle
                            :cx="point.coord_x * 4"
                            :cy="point.coord_y * 5"
                            r="10"
                            :fill="selectedIndex === index ? '#ef4444' : '#3b82f6'"
                            fill-opacity="0.85"
                            stroke="white"
                            stroke-width="2.5"
                        />
                        <text
                            :x="point.coord_x * 4"
                            :y="(point.coord_y * 5) - 14"
                            text-anchor="middle"
                            class="text-[10px] fill-gray-800 font-bold pointer-events-none select-none"
                            x-text="point.unidades_aplicadas + 'U'"
                        />
                    </g>
                </template>
            </svg>
        </div>

        {{-- Painel lateral: lista de pontos --}}
        <div class="lg:w-72">
            {{-- Formulario de ponto --}}
            <div x-show="showPointForm" x-transition class="p-4 bg-gray-50 rounded-lg border mb-4">
                <h4 class="font-semibold text-gray-700 mb-3"
                    x-text="editingExisting ? 'Editar Ponto' : 'Novo Ponto de Aplicacao'">
                </h4>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Regiao/Musculo *</label>
                        <select x-model="currentPoint.regiao_musculo"
                                class="mt-1 block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecione...</option>
                            <option value="Frontalis">Frontalis (Testa)</option>
                            <option value="Procerus">Procerus (Glabela)</option>
                            <option value="Corrugador Esquerdo">Corrugador Esquerdo</option>
                            <option value="Corrugador Direito">Corrugador Direito</option>
                            <option value="Orbicularis Oculi E">Orbicularis Oculi Esquerdo</option>
                            <option value="Orbicularis Oculi D">Orbicularis Oculi Direito</option>
                            <option value="Orbicularis Oris">Orbicularis Oris (Perioral)</option>
                            <option value="Masseter E">Masseter Esquerdo</option>
                            <option value="Masseter D">Masseter Direito</option>
                            <option value="Mentual">Mentual (Queixo)</option>
                            <option value="Outro">Outro</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Unidades Aplicadas *</label>
                        <input type="number" step="0.5" min="0.5"
                               x-model="currentPoint.unidades_aplicadas"
                               class="mt-1 block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Observacoes</label>
                        <textarea x-model="currentPoint.observacoes" rows="2"
                                  class="mt-1 block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                </div>
                <div class="mt-4 flex gap-2">
                    <button @click="savePoint()" type="button"
                            class="px-3 py-1.5 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">
                        <span x-text="editingExisting ? 'Atualizar' : 'Adicionar'"></span>
                    </button>
                    <button x-show="editingExisting" @click="removePoint()" type="button"
                            class="px-3 py-1.5 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">
                        Remover
                    </button>
                    <button @click="cancelPoint()" type="button"
                            class="px-3 py-1.5 bg-gray-300 text-gray-700 text-sm rounded-md hover:bg-gray-400">
                        Cancelar
                    </button>
                </div>
            </div>

            {{-- Lista de pontos --}}
            <div x-show="points.length > 0">
                <h4 class="font-semibold text-gray-700 mb-2 text-sm">
                    Pontos Marcados (<span x-text="points.length"></span>)
                    — Total: <span x-text="totalUnits().toFixed(1)"></span>U
                </h4>
                <div class="space-y-2 max-h-80 overflow-y-auto">
                    <template x-for="(point, index) in points" :key="'list-' + index">
                        <div class="flex items-center justify-between p-2 bg-white rounded border text-sm"
                             :class="selectedIndex === index ? 'ring-2 ring-indigo-500 bg-indigo-50' : ''">
                            <div>
                                <span class="font-medium" x-text="point.regiao_musculo"></span>
                                <span class="text-gray-500 ml-1" x-text="point.unidades_aplicadas + 'U'"></span>
                            </div>
                            <div class="flex gap-1" x-show="editable">
                                <button type="button" @click="editPoint(index)"
                                        class="text-indigo-600 hover:text-indigo-800 text-xs">Editar</button>
                                <button type="button" @click="removePointAt(index)"
                                        class="text-red-600 hover:text-red-800 text-xs">X</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            <div x-show="points.length === 0 && !showPointForm">
                <p class="text-sm text-gray-400">Nenhum ponto marcado.</p>
            </div>
        </div>
    </div>

    {{-- Hidden inputs para submeter pontos com o formulario --}}
    <template x-for="(point, index) in points" :key="'input-' + index">
        <div>
            <input type="hidden" :name="'pontos['+index+'][regiao_musculo]'" :value="point.regiao_musculo" />
            <input type="hidden" :name="'pontos['+index+'][unidades_aplicadas]'" :value="point.unidades_aplicadas" />
            <input type="hidden" :name="'pontos['+index+'][observacoes]'" :value="point.observacoes || ''" />
            <input type="hidden" :name="'pontos['+index+'][coord_x]'" :value="point.coord_x" />
            <input type="hidden" :name="'pontos['+index+'][coord_y]'" :value="point.coord_y" />
        </div>
    </template>
</div>
