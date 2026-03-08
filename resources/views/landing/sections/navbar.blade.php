<nav x-data="{ open: false }" class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-gradient-to-br from-rose-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-sm">SF</span>
                </div>
                <span class="text-xl font-bold text-gray-900">SkinFlow</span>
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden lg:flex items-center gap-8">
                <a href="{{ route('landing') }}#funcionalidades" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">Funcionalidades</a>
                <a href="{{ route('landing') }}#planos" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">Planos</a>
                <a href="{{ route('landing') }}#depoimentos" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">Depoimentos</a>
                <a href="{{ route('landing.demo') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">Demonstração</a>
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">Entrar</a>
                <a href="{{ route('landing') }}#planos" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-rose-500 to-purple-600 text-white text-sm font-semibold rounded-full hover:shadow-lg hover:shadow-rose-500/25 transition-all duration-300 transform hover:-translate-y-0.5">
                    Começar agora
                </a>
            </div>

            {{-- Mobile Menu Button --}}
            <button @click="open = !open" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="lg:hidden bg-white border-b border-gray-100">
        <div class="px-4 py-4 space-y-3">
            <a href="{{ route('landing') }}#funcionalidades" @click="open = false" class="block text-sm font-medium text-gray-600 hover:text-gray-900 py-2">Funcionalidades</a>
            <a href="{{ route('landing') }}#planos" @click="open = false" class="block text-sm font-medium text-gray-600 hover:text-gray-900 py-2">Planos</a>
            <a href="{{ route('landing') }}#depoimentos" @click="open = false" class="block text-sm font-medium text-gray-600 hover:text-gray-900 py-2">Depoimentos</a>
            <a href="{{ route('landing.demo') }}" class="block text-sm font-medium text-gray-600 hover:text-gray-900 py-2">Demonstração</a>
            <a href="{{ route('login') }}" class="block text-sm font-medium text-gray-600 hover:text-gray-900 py-2">Entrar</a>
            <a href="{{ route('landing') }}#planos" class="block text-center px-5 py-2.5 bg-gradient-to-r from-rose-500 to-purple-600 text-white text-sm font-semibold rounded-full">
                Começar agora
            </a>
        </div>
    </div>
</nav>
