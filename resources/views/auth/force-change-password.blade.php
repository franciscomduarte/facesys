<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Por seguranca, voce precisa alterar sua senha temporaria antes de continuar.
    </div>

    <form method="POST" action="{{ route('password.force-update') }}">
        @csrf
        @method('PUT')

        <div>
            <x-input-label for="password" value="Nova Senha" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autofocus autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmar Nova Senha" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>Alterar Senha</x-primary-button>
        </div>
    </form>
</x-guest-layout>
