<section id="faq" class="py-24 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 bg-purple-50 text-purple-600 text-sm font-semibold rounded-full mb-4">Tire suas dúvidas</span>
            <h2 class="text-3xl sm:text-4xl font-bold mb-4">Perguntas frequentes</h2>
            <p class="text-lg text-gray-600">Tudo o que você precisa saber antes de começar.</p>
        </div>

        <div class="space-y-4" x-data="{ open: null }">
            @php
                $faqs = [
                    [
                        'question' => 'Meus dados e dos meus pacientes estão seguros?',
                        'answer' => 'Sim. O SkinFlow utiliza criptografia de ponta a ponta (SSL/TLS), armazenamento com criptografia em repouso, backups automáticos diários e isolamento completo de dados entre clínicas (multi-tenant). Estamos em total conformidade com a LGPD e seguimos as melhores práticas de segurança do mercado.',
                    ],
                    [
                        'question' => 'Preciso instalar algum programa no computador?',
                        'answer' => 'Não. O SkinFlow é 100% online (SaaS) e funciona diretamente no navegador. Basta acessar pelo computador, tablet ou celular — sem instalações, sem atualizações manuais. Seus dados ficam sempre sincronizados.',
                    ],
                    [
                        'question' => 'Posso cancelar a qualquer momento?',
                        'answer' => 'Sim, sem fidelidade. Você pode cancelar sua assinatura quando quiser, diretamente pelo painel. Após o cancelamento, seu acesso continua até o final do período pago e você tem 90 dias para exportar todos os seus dados.',
                    ],
                    [
                        'question' => 'O sistema funciona no celular?',
                        'answer' => 'Sim. O SkinFlow é totalmente responsivo e funciona perfeitamente em smartphones e tablets. Você pode consultar prontuários, verificar a agenda e registrar atendimentos de qualquer dispositivo com acesso à internet.',
                    ],
                    [
                        'question' => 'Posso ter mais de um profissional na mesma conta?',
                        'answer' => 'Sim! O plano Enterprise permite múltiplos profissionais na mesma clínica, cada um com seu login individual e permissões específicas. Você controla quem acessa o quê, com total segurança.',
                    ],
                    [
                        'question' => 'Como funciona o período de teste?',
                        'answer' => 'Você pode experimentar o SkinFlow através do nosso ambiente de demonstração gratuito, sem necessidade de cadastro ou cartão de crédito. Assim você conhece todas as funcionalidades antes de assinar.',
                    ],
                    [
                        'question' => 'O sistema gera documentos com validade jurídica?',
                        'answer' => 'Sim. As prescrições e contratos gerados pelo SkinFlow utilizam assinatura eletrônica com validade jurídica, conforme a Medida Provisória nº 2.200-2/2001 e a Lei nº 14.063/2020. Cada documento registra data, hora e identificação do signatário.',
                    ],
                ];
            @endphp

            @foreach($faqs as $index => $faq)
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden transition-all duration-300" :class="open === {{ $index }} ? 'shadow-md border-rose-200 bg-rose-50/30' : 'hover:border-gray-300'">
                    <button
                        @click="open = open === {{ $index }} ? null : {{ $index }}"
                        class="w-full flex items-center justify-between px-6 py-5 text-left"
                    >
                        <span class="font-semibold text-gray-900 pr-4">{{ $faq['question'] }}</span>
                        <svg class="w-5 h-5 text-gray-500 flex-shrink-0 transition-transform duration-300" :class="open === {{ $index }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open === {{ $index }}" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2">
                        <div class="px-6 pb-5 text-gray-600 leading-relaxed">
                            {{ $faq['answer'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-12 bg-gray-50 rounded-2xl p-8 border border-gray-200">
            <p class="text-gray-600 mb-4">Ainda tem dúvidas? Fale diretamente conosco.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('landing.contato') }}" class="inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-full hover:border-gray-400 transition-all">
                    Enviar mensagem
                </a>
                <a href="https://wa.me/5561998652709?text={{ urlencode('Olá! Tenho uma dúvida sobre o SkinFlow.') }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center px-6 py-3 bg-green-500 text-white font-semibold rounded-full hover:bg-green-600 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.611.611l4.458-1.495A11.96 11.96 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.325 0-4.47-.744-6.227-2.01a.5.5 0 00-.394-.082l-3.163 1.06 1.06-3.163a.5.5 0 00-.082-.394A9.96 9.96 0 012 12C2 6.486 6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/></svg>
                    WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>
