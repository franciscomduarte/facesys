<section class="relative min-h-screen flex items-center pt-20 overflow-hidden">
    {{-- Background gradient --}}
    <div class="absolute inset-0 bg-gradient-to-br from-rose-50 via-white to-purple-50"></div>
    <div class="absolute top-20 right-0 w-96 h-96 bg-rose-200/30 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 left-0 w-96 h-96 bg-purple-200/30 rounded-full blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
        <div class="text-center max-w-4xl mx-auto">
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-sm border border-gray-100 mb-8">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                <span class="text-sm font-medium text-gray-600">Mais de 150 clínicas já utilizam</span>
            </div>

            {{-- Title --}}
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight mb-6">
                A forma mais inteligente de
                <span class="bg-gradient-to-r from-rose-500 to-purple-600 bg-clip-text text-transparent">gerenciar sua clínica</span>
                de estética
            </h1>

            {{-- Subtitle --}}
            <p class="text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto mb-10 leading-relaxed">
                Controle pacientes, procedimentos, agenda e documentação clínica em um único sistema moderno e seguro. Simplifique sua rotina e foque no que importa: seus pacientes.
            </p>

            {{-- CTAs --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
                <a href="#planos" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-rose-500 to-purple-600 text-white font-semibold rounded-full hover:shadow-xl hover:shadow-rose-500/25 transition-all duration-300 transform hover:-translate-y-0.5 text-base">
                    Começar agora
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                <a href="{{ route('landing.demo') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 bg-white text-gray-700 font-semibold rounded-full border border-gray-200 hover:border-gray-300 hover:shadow-md transition-all duration-300 text-base">
                    Testar demonstração
                </a>
            </div>

            {{-- Trust indicators --}}
            <div class="flex flex-wrap items-center justify-center gap-8 text-sm text-gray-500">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Dados protegidos</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Setup em minutos</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span>Sem fidelidade</span>
                </div>
            </div>
        </div>
    </div>
</section>
