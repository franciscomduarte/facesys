<section id="depoimentos" class="py-20 lg:py-28 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-1.5 bg-rose-50 text-rose-600 text-sm font-semibold rounded-full mb-4">Depoimentos</span>
            <h2 class="text-3xl sm:text-4xl font-bold mb-6">Profissionais que já <span class="bg-gradient-to-r from-rose-500 to-purple-600 bg-clip-text text-transparent">transformaram</span> suas clínicas</h2>
            <p class="text-lg text-gray-600">Veja como o SkinFlow está impactando o dia a dia de clínicas de estética em todo o Brasil.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $testimonials = [
                    [
                        'name' => 'Dra. Ana Silva',
                        'role' => 'Dermatologista',
                        'clinic' => 'Clínica Derma Vida — São Paulo, SP',
                        'text' => 'O SkinFlow mudou completamente a organização da minha clínica. Antes eu perdia 2 horas por dia com papelada. Hoje tenho controle total dos pacientes e procedimentos. A agenda inteligente sozinha já paga o investimento.',
                        'highlight' => 'Economizou 2h por dia',
                        'rating' => 5,
                        'initials' => 'AS',
                        'color' => 'from-rose-400 to-pink-500',
                    ],
                    [
                        'name' => 'Dr. Ricardo Mendes',
                        'role' => 'Médico Esteta',
                        'clinic' => 'Instituto Estética Premium — Brasília, DF',
                        'text' => 'O mapa facial interativo é incrível para marcar pontos de aplicação de toxina e preenchimento. Meus pacientes ficam impressionados com a tecnologia. A gestão de prontuários digitais facilitou muito meu dia a dia.',
                        'highlight' => 'Referência em inovação',
                        'rating' => 5,
                        'initials' => 'RM',
                        'color' => 'from-purple-400 to-indigo-500',
                    ],
                    [
                        'name' => 'Dra. Camila Rocha',
                        'role' => 'Biomédica Esteticista',
                        'clinic' => 'Studio Camila Rocha — Belo Horizonte, MG',
                        'text' => 'Finalmente um sistema pensado para quem trabalha com estética. Os contratos com assinatura eletrônica me deram muito mais segurança jurídica. Já indiquei para 5 colegas e todas adoraram.',
                        'highlight' => 'Segurança jurídica total',
                        'rating' => 5,
                        'initials' => 'CR',
                        'color' => 'from-amber-400 to-orange-500',
                    ],
                ];
            @endphp

            @foreach($testimonials as $testimonial)
                <div class="bg-white rounded-2xl p-8 border border-gray-100 hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="flex gap-1 mb-4">
                        @for($i = 0; $i < $testimonial['rating']; $i++)
                            <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>

                    @if(!empty($testimonial['highlight']))
                        <span class="inline-block self-start px-3 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full mb-3">{{ $testimonial['highlight'] }}</span>
                    @endif

                    <p class="text-gray-600 leading-relaxed mb-6 flex-1">"{{ $testimonial['text'] }}"</p>

                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <div class="w-12 h-12 bg-gradient-to-br {{ $testimonial['color'] }} rounded-full flex items-center justify-center shadow-md">
                            <span class="text-white text-sm font-bold">{{ $testimonial['initials'] }}</span>
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-gray-900">{{ $testimonial['name'] }}</p>
                            <p class="text-xs text-gray-500">{{ $testimonial['role'] }}</p>
                            <p class="text-xs text-gray-400">{{ $testimonial['clinic'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('landing.demo') }}" class="inline-flex items-center text-rose-600 font-semibold hover:text-rose-700 transition-colors">
                Quer ver na prática? Teste a demonstração gratuita
                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>
</section>
