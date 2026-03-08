<x-landing-layout>
    <x-slot:title>SkinFlow - Sistema para Clínicas de Estética</x-slot:title>

    {{-- 1. Atenção: capturar interesse imediato --}}
    @include('landing.sections.hero')

    {{-- 2. Problema: identificação com a dor --}}
    @include('landing.sections.problems')

    {{-- 3. Solução: apresentar o SkinFlow --}}
    @include('landing.sections.solution')

    {{-- 4. Como funciona: reduzir barreira de entrada --}}
    @include('landing.sections.how-it-works')

    {{-- 5. Funcionalidades: detalhamento do valor --}}
    @include('landing.sections.features')

    {{-- 6. Demo visual: mostrar o sistema real --}}
    @include('landing.sections.demo-visual')

    {{-- 7. Agenda: funcionalidade em destaque --}}
    @include('landing.sections.agenda')

    {{-- 8. CTA intermediário: capturar quem já está convencido --}}
    @include('landing.sections.mid-cta')

    {{-- 9. Calculadora ROI: justificar o investimento --}}
    @include('landing.sections.roi-calculator')

    {{-- 10. Depoimentos: validação de terceiros --}}
    @include('landing.sections.testimonials')

    {{-- 12. Planos: apresentar oferta --}}
    @include('landing.sections.pricing')

    {{-- 13. FAQ: eliminar objeções finais --}}
    @include('landing.sections.faq')

    {{-- 14. CTA final: última chamada para ação --}}
    @include('landing.sections.cta')
</x-landing-layout>
