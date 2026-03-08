<section class="py-20 lg:py-28 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-rose-500 to-purple-600"></div>
    <div class="absolute inset-0">
        <div class="absolute top-0 left-0 w-96 h-96 bg-white/10 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/10 rounded-full translate-x-1/2 translate-y-1/2"></div>
    </div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-6">
            Leve sua clínica para o próximo nível
        </h2>
        <p class="text-lg sm:text-xl text-white/80 max-w-2xl mx-auto mb-10">
            Comece hoje mesmo com uma gestão moderna e profissional. Junte-se a centenas de clínicas que já transformaram seus atendimentos.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="#planos" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 bg-white text-gray-900 font-semibold rounded-full hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 text-base">
                Começar agora
                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
            <a href="{{ route('landing.demo') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 bg-white/10 text-white font-semibold rounded-full border border-white/30 hover:bg-white/20 transition-all duration-300 text-base">
                Testar demonstração
            </a>
        </div>

        <p class="mt-8 text-sm text-white/60">
            Oferta especial para novas clínicas neste mês. Aproveite!
        </p>
    </div>
</section>
