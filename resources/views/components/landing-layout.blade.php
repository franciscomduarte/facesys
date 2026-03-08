<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistema completo para gestão de clínicas de estética. Prontuário digital, agenda inteligente, mapa facial, prescrições e muito mais.">

    <title>{{ $title ?? 'SkinFlow - Sistema para Clínicas de Estética' }}</title>

    <link rel="dns-prefetch" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-white text-gray-900" style="font-family: 'Inter', sans-serif;">
    @include('landing.sections.navbar')

    <main>
        {{ $slot }}
    </main>

    @include('landing.sections.footer')
    @include('landing.sections.whatsapp')

    @stack('scripts')
</body>
</html>
