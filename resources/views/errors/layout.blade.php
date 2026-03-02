<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title') - {{ config('app.name', 'LC Estetica') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 flex flex-col items-center justify-center px-4">
            <div class="max-w-lg w-full">
                <div class="text-center mb-8">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2">
                        <svg class="h-10 w-10 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                        </svg>
                        <span class="font-bold text-xl text-gray-800">{{ config('app.name', 'LC Estetica') }}</span>
                    </a>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <div class="p-8 text-center">
                        <div class="text-6xl font-bold text-indigo-600 mb-2">@yield('code')</div>
                        <h1 class="text-xl font-semibold text-gray-800 mb-3">@yield('title')</h1>
                        <p class="text-gray-500 mb-8">@yield('message')</p>

                        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                            @auth
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700 transition">
                                    Ir para o Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700 transition">
                                    Fazer Login
                                </a>
                            @endauth
                            <button onclick="history.back()" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-gray-700 hover:bg-gray-300 transition">
                                Voltar
                            </button>
                        </div>
                    </div>
                </div>

                <p class="text-center text-xs text-gray-400 mt-6">
                    Se o problema persistir, entre em contato com o suporte.
                </p>
            </div>
        </div>
    </body>
</html>
