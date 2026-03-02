<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('patients.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Novo Paciente</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-clinic-card>
                <form method="POST" action="{{ route('patients.store') }}">
                    @csrf
                    @include('patients._form')

                    <div class="flex items-center justify-end gap-4 mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('patients.index') }}"
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                            Cancelar
                        </a>
                        <x-primary-button>Cadastrar Paciente</x-primary-button>
                    </div>
                </form>
            </x-clinic-card>
        </div>
    </div>
</x-app-layout>
