<x-landing-layout>
    <x-slot:title>Planos - SkinFlow</x-slot:title>

    <section class="pt-32 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h1 class="text-4xl sm:text-5xl font-bold mb-6">Planos e preços</h1>
                <p class="text-lg text-gray-600">Escolha o plano ideal para sua clínica. Sem fidelidade, cancele quando quiser.</p>
            </div>
        </div>
    </section>

    @include('landing.sections.pricing')
    @include('landing.sections.cta')
</x-landing-layout>
