@props(['action', 'method' => 'DELETE', 'title' => 'Confirmar', 'message' => 'Tem certeza que deseja realizar esta acao?', 'confirmText' => 'Confirmar', 'cancelText' => 'Cancelar'])

<div x-data="{ open: false }" {{ $attributes }}>
    <span @click="open = true">
        {{ $trigger }}
    </span>

    <template x-teleport="body">
        <div x-show="open" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="fixed inset-0 bg-black/50" @click="open = false"></div>
            <div class="relative bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $title }}</h3>
                <p class="text-gray-600 mb-6">{{ $message }}</p>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="open = false"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                        {{ $cancelText }}
                    </button>
                    <form method="POST" action="{{ $action }}">
                        @csrf
                        @method($method)
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            {{ $confirmText }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
