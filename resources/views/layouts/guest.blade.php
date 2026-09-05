<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Google Site Verification -->
        <meta name="google-site-verification" content="sZkUaTriLQlmmUBEgxmJkDmMo4EJD3lo39r4qCQxkG8">
        <meta name="robots" content="index, follow">

        <!-- SEO Meta Tags -->
        <title>{{ config('app.name', 'HostDevPro') }} — Autenticação & Portal do Cliente</title>
        <meta name="description" content="Acesse com segurança seu portal do cliente HostDevPro. Gerenciamento de hospedagem, instâncias VPS e faturamento com PIX.">

        <!-- PWA Meta Tags & Icons -->
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#ffffff">
        <link rel="apple-touch-icon" href="{{ asset('brand/icons/HDP-icon-256.png') }}">
        <link rel="icon" type="image/png" href="{{ asset('brand/icons/HDP-icon-64.png') }}">

        <!-- Google Fonts: Inter & Outfit -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

        <!-- Tailwind Play CDN para renderização 100% perfeita independente do vite -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                            display: ['Outfit', 'sans-serif'],
                            mono: ['JetBrains Mono', 'monospace'],
                        },
                        colors: {
                            cyber: {
                                lime: '#10b981',
                                rose: '#f43f5e',
                                orange: '#f97316',
                                dark: '#080d14',
                            }
                        }
                    }
                }
            };
        </script>

        <!-- Scripts Legados -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .font-display { font-family: 'Outfit', sans-serif; }
            .grid-bg {
                background-image: radial-gradient(#cbd5e1 1.2px, transparent 1.2px);
                background-size: 24px 24px;
            }

            /* Animação Pulsar com Glow Vibrante para o Logotipo */
            @keyframes logo-pulsar {
                0%, 100% {
                    transform: scale(1);
                    filter: drop-shadow(0 4px 10px rgba(249, 115, 22, 0.15));
                }
                50% {
                    transform: scale(1.06);
                    filter: drop-shadow(0 8px 25px rgba(249, 115, 22, 0.40)) drop-shadow(0 0 16px rgba(16, 185, 129, 0.30));
                }
            }
            .animate-logo-pulsar {
                animation: logo-pulsar 3.2s ease-in-out infinite;
                transform-origin: center;
                display: inline-block;
            }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-900 bg-[#fafbfc] min-h-screen flex flex-col justify-between relative overflow-x-hidden selection:bg-orange-500 selection:text-white">
        <!-- Fundo em Grid Pontilhado -->
        <div class="fixed inset-0 pointer-events-none -z-20 opacity-80 grid-bg" aria-hidden="true"></div>

        <!-- Ambient Orbs Neon Vibrantes (Zero Azul) -->
        <div class="fixed top-12 left-12 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl pointer-events-none -z-10" aria-hidden="true"></div>
        <div class="fixed bottom-12 right-12 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none -z-10" aria-hidden="true"></div>
        <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-rose-500/5 rounded-full blur-3xl pointer-events-none -z-10" aria-hidden="true"></div>

        <!-- Conteúdo Principal Centralizado -->
        <main class="flex-grow flex items-center justify-center p-4 sm:p-6 lg:p-8 relative z-10">
            {{ $slot }}
        </main>

        <!-- Rodapé Cyber Light de Autenticação -->
        <footer class="w-full py-6 border-t border-slate-200/80 bg-white/70 backdrop-blur-md relative z-10">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>HostDevPro Cloud &bull; Datacenter Equinix SP3 Brasil (12ms)</span>
                </div>
                <div class="flex items-center gap-4 text-slate-600">
                    <a href="https://hostdevpro.app.br" class="hover:text-orange-600 font-semibold transition">&larr; Voltar ao Portal</a>
                    <span>&bull;</span>
                    <a href="{{ route('terms.hosting') }}" target="_blank" class="hover:text-slate-950 transition font-medium">Termos de Hospedagem</a>
                    <span>&bull;</span>
                    <a href="{{ route('terms.privacy') }}" target="_blank" class="hover:text-slate-950 transition font-medium">Privacidade</a>
                </div>
                <div>
                    <span>Tecnologia por <a href="https://creativaai.com.br" target="_blank" class="text-slate-800 hover:text-orange-600 font-bold underline decoration-dotted">CreativaAi Hub</a></span>
                </div>
            </div>
        </footer>

        <!-- Prompt PWA Mobile -->
        @include('partials.pwa-install-prompt')
    </body>
</html>
