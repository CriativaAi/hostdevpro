<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HostDevPro') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100 min-h-screen flex flex-col justify-between relative selection:bg-indigo-600 selection:text-white">
        <!-- Fundo de Código (Easter Egg) -->
        @include('partials.code-background')

        <div class="flex-grow flex flex-col sm:justify-center items-center pt-8 sm:pt-0 relative z-10 px-4 my-8">
            <div class="mb-4">
                <a href="/" class="flex flex-col items-center gap-1 group">
                    <x-application-logo class="w-16 h-16 fill-current text-indigo-600 group-hover:scale-105 transition-transform" />
                    <span class="text-xl font-bold tracking-tight text-gray-900">HostDev<span class="text-indigo-600">Pro</span></span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-4 px-6 py-6 bg-white/95 backdrop-blur-sm shadow-xl border border-gray-100 overflow-hidden rounded-2xl">
                {{ $slot }}
            </div>
        </div>

        <!-- Rodapé com Marquee e Créditos -->
        @include('partials.footer')
    </body>
</html>
