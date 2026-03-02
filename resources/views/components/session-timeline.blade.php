@props(['sessions', 'patient'])

<div class="flow-root">
    @forelse($sessions as $session)
        <div class="relative pb-8">
            @if(!$loop->last)
                <span class="absolute left-4 top-8 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
            @endif
            <div class="relative flex items-start space-x-3">
                <div class="relative">
                    <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center ring-8 ring-white">
                        <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <a href="{{ route('patients.sessions.show', [$patient, $session]) }}"
                       class="block hover:bg-gray-50 rounded-lg p-3 -m-3 transition">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-gray-900">{{ $session->procedimento }}</p>
                            <time class="text-xs text-gray-500">{{ $session->data_sessao->format('d/m/Y') }}</time>
                        </div>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ $session->marca_produto }} - {{ $session->quantidade_total }}U
                        </p>
                        <p class="mt-1 text-xs text-gray-500">
                            Dr(a). {{ $session->profissional_responsavel }}
                            @if($session->applicationPoints->count())
                                &middot; {{ $session->applicationPoints->count() }} ponto(s)
                            @endif
                        </p>
                    </a>
                </div>
            </div>
        </div>
    @empty
        <p class="text-sm text-gray-500 text-center py-4">Nenhuma sessao registrada.</p>
    @endforelse
</div>
