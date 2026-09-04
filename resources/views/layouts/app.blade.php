<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HostDevPro') }} — Cloud Infrastructure</title>
        <link rel="icon" type="image/webp" href="{{ asset('brand/icons/HDP-icon-64.webp') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-100 bg-hostdev-cloud min-h-screen flex flex-col justify-between relative overflow-x-hidden selection:bg-emerald-500 selection:text-slate-950">
        <!-- Elementos decorativos de iluminação (Orbs) -->
        <div class="fixed top-20 left-10 w-72 h-72 bg-blue-600/10 rounded-full blur-3xl pointer-events-none -z-10" aria-hidden="true"></div>
        <div class="fixed bottom-10 right-20 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none -z-10" aria-hidden="true"></div>

        <!-- Fundo de Código (Easter Egg) -->
        @include('partials.code-background')

        <!-- Container Principal -->
        <div class="flex-grow flex flex-col relative z-10">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-[#020617]/70 backdrop-blur-md border-b border-slate-800/80 shadow-md">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-grow">
                {{ $slot }}
            </main>
        </div>

        <!-- Rodapé com Marquee e Créditos -->
        @include('partials.footer')
    </body>
</html>
