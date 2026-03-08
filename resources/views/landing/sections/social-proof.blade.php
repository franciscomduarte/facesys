<section class="py-20 lg:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-1.5 bg-amber-50 text-amber-600 text-sm font-semibold rounded-full mb-4">Números que comprovam</span>
            <h2 class="text-3xl sm:text-4xl font-bold mb-6">Confiado por clínicas em todo o Brasil</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 max-w-4xl mx-auto">
            @php
                $stats = [
                    ['number' => '150+', 'label' => 'Clínicas ativas', 'desc' => 'utilizando o sistema diariamente'],
                    ['number' => '12.000+', 'label' => 'Pacientes cadastrados', 'desc' => 'com prontuário digital completo'],
                    ['number' => '45.000+', 'label' => 'Atendimentos registrados', 'desc' => 'com documentação completa'],
                ];
            @endphp

            @foreach($stats as $stat)
                <div class="text-center p-8 rounded-2xl bg-gradient-to-b from-gray-50 to-white border border-gray-100">
                    <div class="text-4xl sm:text-5xl font-extrabold bg-gradient-to-r from-rose-500 to-purple-600 bg-clip-text text-transparent mb-2">
                        {{ $stat['number'] }}
                    </div>
                    <div class="text-lg font-semibold text-gray-900 mb-1">{{ $stat['label'] }}</div>
                    <div class="text-sm text-gray-500">{{ $stat['desc'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>
