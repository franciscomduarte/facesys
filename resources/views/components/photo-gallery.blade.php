@props(['photos', 'editable' => false, 'patient' => null, 'session' => null])

@php
    $grouped = $photos->groupBy('procedimento_id');
@endphp

<div x-data="{ lightbox: false, lightboxSrc: '', lightboxCaption: '' }">
    @forelse($grouped as $procId => $procPhotos)
        @php
            $procedimento = $procPhotos->first()->procedimento;
            $antes = $procPhotos->where('tipo', 'antes')->sortBy('ordem');
            $depois = $procPhotos->where('tipo', 'depois')->sortBy('ordem');
        @endphp
        <div class="mb-6 {{ !$loop->last ? 'border-b border-gray-100 pb-6' : '' }}">
            <h5 class="text-sm font-semibold text-gray-700 mb-3">
                {{ $procedimento->nome }}
                <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                    {{ $procedimento->categoria === 'facial' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $procedimento->categoria === 'corporal' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $procedimento->categoria === 'capilar' ? 'bg-purple-100 text-purple-800' : '' }}
                    {{ $procedimento->categoria === 'outro' ? 'bg-gray-100 text-gray-800' : '' }}">
                    {{ ucfirst($procedimento->categoria) }}
                </span>
            </h5>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Coluna ANTES --}}
                <div>
                    <p class="text-xs font-medium text-blue-600 uppercase tracking-wide mb-2">Antes</p>
                    @if($antes->count())
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @foreach($antes as $foto)
                                <div class="relative group">
                                    <img src="{{ $foto->url }}" alt="Antes - {{ $procedimento->nome }}"
                                         class="h-28 w-full object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 transition"
                                         @click="lightboxSrc = '{{ $foto->url }}'; lightboxCaption = 'Antes - {{ $procedimento->nome }} ({{ $foto->data_registro->format('d/m/Y') }})'; lightbox = true" />
                                    <span class="absolute top-1 left-1 bg-blue-500 text-white text-xs px-1.5 py-0.5 rounded">Antes</span>
                                    @if($foto->observacoes)
                                        <p class="text-xs text-gray-500 mt-1 truncate" title="{{ $foto->observacoes }}">{{ $foto->observacoes }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400">{{ $foto->data_registro->format('d/m/Y') }}</p>

                                    @if($editable && $session && $patient)
                                        <form method="POST"
                                              action="{{ route('patients.sessions.fotos.destroy', [$patient, $session, $foto]) }}"
                                              class="absolute top-1 right-1 hidden group-hover:block"
                                              onsubmit="return confirm('Remover esta foto?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white rounded-full p-1 hover:bg-red-600 shadow">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-gray-400 italic">Nenhuma foto "antes" registrada.</p>
                    @endif
                </div>

                {{-- Coluna DEPOIS --}}
                <div>
                    <p class="text-xs font-medium text-green-600 uppercase tracking-wide mb-2">Depois</p>
                    @if($depois->count())
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @foreach($depois as $foto)
                                <div class="relative group">
                                    <img src="{{ $foto->url }}" alt="Depois - {{ $procedimento->nome }}"
                                         class="h-28 w-full object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 transition"
                                         @click="lightboxSrc = '{{ $foto->url }}'; lightboxCaption = 'Depois - {{ $procedimento->nome }} ({{ $foto->data_registro->format('d/m/Y') }})'; lightbox = true" />
                                    <span class="absolute top-1 left-1 bg-green-500 text-white text-xs px-1.5 py-0.5 rounded">Depois</span>
                                    @if($foto->observacoes)
                                        <p class="text-xs text-gray-500 mt-1 truncate" title="{{ $foto->observacoes }}">{{ $foto->observacoes }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400">{{ $foto->data_registro->format('d/m/Y') }}</p>

                                    @if($editable && $session && $patient)
                                        <form method="POST"
                                              action="{{ route('patients.sessions.fotos.destroy', [$patient, $session, $foto]) }}"
                                              class="absolute top-1 right-1 hidden group-hover:block"
                                              onsubmit="return confirm('Remover esta foto?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white rounded-full p-1 hover:bg-red-600 shadow">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-gray-400 italic">Nenhuma foto "depois" registrada.</p>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <p class="text-sm text-gray-400 text-center py-4">Nenhuma foto clinica registrada.</p>
    @endforelse

    {{-- Lightbox Modal --}}
    <div x-show="lightbox" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="lightbox = false"
         @keydown.escape.window="lightbox = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4">
        <div @click.stop class="relative max-w-4xl max-h-[90vh]">
            <button @click="lightbox = false"
                    class="absolute -top-3 -right-3 bg-white rounded-full p-1.5 shadow-lg hover:bg-gray-100 z-10">
                <svg class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <img :src="lightboxSrc" :alt="lightboxCaption"
                 class="max-h-[85vh] max-w-full rounded-lg shadow-2xl" />
            <p class="text-center text-white text-sm mt-2" x-text="lightboxCaption"></p>
        </div>
    </div>
</div>
