<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- SEO Meta Tags -->
        <title>{{ config('app.name', 'HostDevPro') }} — Autenticação & Portal do Cliente</title>
        <meta name="description" content="Acesse com segurança seu portal do cliente HostDevPro. Gerenciamento de hospedagem, instâncias VPS e faturamento com PIX.">
        <meta name="robots" content="index, follow">

        <!-- PWA Meta Tags & Icons -->
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#020617">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <link rel="apple-touch-icon" href="{{ asset('brand/icons/HDP-icon-256.png') }}">
        <link rel="icon" type="image/webp" href="{{ asset('brand/icons/HDP-icon-64.webp') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-100 bg-hostdev-cloud min-h-screen flex flex-col justify-between relative overflow-x-hidden selection:bg-emerald-500 selection:text-slate-950">
        <!-- Elementos decorativos de iluminação (Orbs) -->
        <div class="fixed top-20 left-10 w-72 h-72 bg-blue-600/10 rounded-full blur-3xl pointer-events-none -z-10" aria-hidden="true"></div>
        <div class="fixed bottom-10 right-20 w-96 h-96 bg-emerald-600/10 rounded-full blur-3xl pointer-events-none -z-10" aria-hidden="true"></div>

        <!-- Fundo de Código (Easter Egg) -->
        @include('partials.code-background')

        <!-- Conteúdo Principal Centralizado -->
        <div class="flex-grow flex items-center justify-center p-4 sm:p-6 lg:p-8 relative z-10">
            {{ $slot }}
        </div>

        <!-- Rodapé Corporativo com Marquee e Créditos -->
        @include('partials.footer')

        <!-- Prompt PWA Mobile -->
        @include('partials.pwa-install-prompt')
    </body>
</html>
