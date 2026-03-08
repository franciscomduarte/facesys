<footer class="bg-gray-900 text-gray-400 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            {{-- Brand --}}
            <div class="md:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-rose-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">SF</span>
                    </div>
                    <span class="text-xl font-bold text-white">SkinFlow</span>
                </div>
                <p class="text-sm leading-relaxed max-w-md">
                    Sistema completo para gestão de clínicas de estética. Prontuário digital, agenda inteligente, prescrições e muito mais.
                </p>
            </div>

            {{-- Links --}}
            <div>
                <h4 class="text-white font-semibold mb-4">Produto</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#funcionalidades" class="hover:text-white transition-colors">Funcionalidades</a></li>
                    <li><a href="#planos" class="hover:text-white transition-colors">Planos</a></li>
                    <li><a href="#depoimentos" class="hover:text-white transition-colors">Depoimentos</a></li>
                    <li><a href="{{ route('landing.demo') }}" class="hover:text-white transition-colors">Demonstração</a></li>
                </ul>
            </div>

            {{-- Links 2 --}}
            <div>
                <h4 class="text-white font-semibold mb-4">Acesso</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Entrar</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Criar conta</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm">&copy; {{ date('Y') }} SkinFlow. Todos os direitos reservados.</p>
            <div class="flex items-center gap-6 text-sm">
                <a href="{{ route('landing.termos') }}" class="hover:text-white transition-colors">Termos de uso</a>
                <a href="{{ route('landing.privacidade') }}" class="hover:text-white transition-colors">Política de privacidade</a>
            </div>
        </div>
    </div>
</footer>
