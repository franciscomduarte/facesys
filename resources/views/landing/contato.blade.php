<x-landing-layout>
    <x-slot:title>Contato - SkinFlow</x-slot:title>

    <section class="pt-32 pb-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl sm:text-5xl font-bold mb-6">Fale conosco</h1>
                <p class="text-lg text-gray-600">Tem alguma dúvida ou quer saber mais sobre o sistema? Entre em contato.</p>
            </div>

            <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-sm">
                <form method="POST" action="#" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome</label>
                            <input type="text" id="name" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-mail</label>
                            <input type="email" id="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Telefone</label>
                        <input type="tel" id="phone" name="phone" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Mensagem</label>
                        <textarea id="message" name="message" rows="4" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all resize-none"></textarea>
                    </div>
                    <button type="submit" class="w-full px-6 py-3.5 bg-gradient-to-r from-rose-500 to-purple-600 text-white font-semibold rounded-full hover:shadow-lg hover:shadow-rose-500/25 transition-all duration-300">
                        Enviar mensagem
                    </button>
                </form>
            </div>
        </div>
    </section>
</x-landing-layout>
