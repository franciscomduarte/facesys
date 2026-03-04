<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Esqueceu sua senha? Sem problema. Informe seu e-mail e enviaremos um link para redefinicao de senha.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <a href="{{ route('login') }}" class="underline text-sm text-gray-600 hover:text-gray-900">
                Voltar ao login
            </a>
            <x-primary-button>
                Enviar Link de Redefinicao
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
