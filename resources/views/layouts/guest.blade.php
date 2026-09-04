<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HostDevPro') }} — Portal do Cliente</title>
        <link rel="icon" type="image/webp" href="{{ asset('brand/icons/HDP-icon-64.webp') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-[#FEFAE0]/70 min-h-screen flex flex-col justify-between relative selection:bg-[#C4661F] selection:text-white">
        <!-- Fundo de Código (Easter Egg) -->
        @include('partials.code-background')

        <!-- Conteúdo Principal Centralizado -->
        <div class="flex-grow flex items-center justify-center p-4 sm:p-6 lg:p-8 relative z-10">
            {{ $slot }}
        </div>

        <!-- Rodapé Corporativo com Marquee e Créditos -->
        @include('partials.footer')
    </body>
</html>
