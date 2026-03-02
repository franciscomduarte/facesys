<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>LC Estetica - Assinatura Digital</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen">
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-3xl mx-auto py-4 px-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <h1 class="text-xl font-semibold text-gray-800">LC Estetica</h1>
                        <span class="text-sm text-gray-500">Assinatura Digital</span>
                    </div>
                </div>
            </header>

            <main class="max-w-3xl mx-auto py-8 px-4 sm:px-6">
                @if (session('error'))
                    <div class="mb-6 rounded-md bg-red-50 p-4">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-md bg-red-50 p-4">
                        <ul class="list-disc list-inside text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </main>

            <footer class="border-t border-gray-200 mt-12">
                <div class="max-w-3xl mx-auto py-4 px-4 sm:px-6 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} LC Estetica. Documento assinado eletronicamente.
                </div>
            </footer>
        </div>
        @stack('scripts')
    </body>
</html>
