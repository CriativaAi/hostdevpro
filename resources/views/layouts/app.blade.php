<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- SEO Primary Meta Tags -->
        <title>{{ config('app.name', 'HostDevPro') }} — Gestão Cloud, Hospedagem & VPS</title>
        <meta name="title" content="{{ config('app.name', 'HostDevPro') }} — Gestão Cloud, Hospedagem & VPS">
        <meta name="description" content="HostDevPro Cloud: Infraestrutura corporativa de alta performance, instâncias VPS NVMe dedicadas, hospedagem com painel Plesk e faturamento instantâneo com PIX.">
        <meta name="keywords" content="hospedagem de sites, vps brasil, servidor dedicado, plesk obsidian, nuvem cloud, devops, laravel host">
        <meta name="robots" content="index, follow">

        <!-- Open Graph / Facebook / WhatsApp -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="HostDevPro Cloud — Infraestrutura & Hospedagem de Alta Performance">
        <meta property="og:description" content="Nuvem corporativa com latência ultrabaixa no Brasil, servidores NVMe e gestão simplificada.">
        <meta property="og:image" content="{{ asset('brand/logos/dark/HostDevPro-horizontal-white.webp') }}">

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="HostDevPro Cloud — Infraestrutura & Hospedagem">
        <meta name="twitter:description" content="Gestão avançada de instâncias VPS, contas Plesk e chamados com suporte técnico especializado.">
        <meta name="twitter:image" content="{{ asset('brand/logos/dark/HostDevPro-horizontal-white.webp') }}">

        <!-- PWA Meta Tags & Icons -->
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#020617">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="HostDevPro">
        <link rel="apple-touch-icon" href="{{ asset('brand/icons/HDP-icon-256.png') }}">
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

        <!-- Prompt Inteligente de Instalação PWA no Celular -->
        @include('partials.pwa-install-prompt')
    </body>
</html>
