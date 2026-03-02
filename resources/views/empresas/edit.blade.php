<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('empresas.show', $empresa) }}" class="text-gray-500 hover:text-gray-700">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar: {{ $empresa->nome_fantasia }}</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('empresas.update', $empresa) }}">
                @csrf
                @method('PUT')
                @include('empresas._form')

                <div class="flex items-center justify-end gap-4 mt-6">
                    <a href="{{ route('empresas.show', $empresa) }}" class="text-gray-600 hover:text-gray-800">Cancelar</a>
                    <x-primary-button>Salvar Alteracoes</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
