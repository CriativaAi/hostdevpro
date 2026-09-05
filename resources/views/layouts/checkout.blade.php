<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Checkout Seguro — HostDevPro Cloud</title>
    <meta name="description" content="Assine sua hospedagem de alta performance NVMe na HostDevPro com ativação instantânea e PIX.">

    <!-- PWA & Icons -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#05080e">
    <link rel="icon" type="image/png" href="/brand/icons/HDP-icon-64.png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    colors: {
                        cyber: {
                            bg: '#05080e',
                            surface: '#090d16',
                            card: '#0e1524',
                            border: '#1e293b',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #05080e;
            color: #f1f5f9;
            font-family: 'Inter', sans-serif;
        }
        .font-display { font-family: 'Outfit', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        @keyframes heartPulse {
            0% { transform: scale(1); filter: drop-shadow(0 0 3px rgba(249, 115, 22, 0.4)); }
            14% { transform: scale(1.08); filter: drop-shadow(0 0 10px rgba(249, 115, 22, 0.8)); }
            28% { transform: scale(1); filter: drop-shadow(0 0 3px rgba(249, 115, 22, 0.4)); }
            42% { transform: scale(1.06); filter: drop-shadow(0 0 8px rgba(16, 185, 129, 0.7)); }
            70% { transform: scale(1); filter: drop-shadow(0 0 2px rgba(249, 115, 22, 0.3)); }
        }
        .logo-heartbeat {
            animation: heartPulse 2.8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
            transform-origin: center;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between selection:bg-emerald-500 selection:text-black">

    <!-- NAVBAR TOPO CHECKOUT -->
    <header class="w-full border-b border-slate-800/80 bg-[#070b13]/80 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="https://hostdevpro.app.br" class="flex items-center gap-2.5 group">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-orange-500 to-amber-500 p-0.5 shadow-md shadow-orange-500/20 logo-heartbeat">
                    <div class="w-full h-full bg-[#090d16] rounded-[10px] flex items-center justify-center font-display font-black text-white text-base">
                        H
                    </div>
                </div>
                <div>
                    <span class="font-display font-black text-lg text-white tracking-tight">HOST<span class="text-emerald-400">DEV</span>PRO</span>
                    <span class="text-[10px] font-mono text-slate-400 ml-1 uppercase">Checkout</span>
                </div>
            </a>

            <div class="flex items-center gap-4 text-xs">
                <a href="https://hostdevpro.app.br" class="text-slate-400 hover:text-white transition flex items-center gap-1">
                    <span>&larr; Portal Principal</span>
                </a>
                <span class="text-slate-700">|</span>
                <a href="https://wa.me/5511921381308" target="_blank" class="hidden sm:flex items-center gap-1.5 text-emerald-400 hover:text-emerald-300 font-semibold transition">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>Suporte WhatsApp</span>
                </a>
            </div>
        </div>
    </header>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- RODAPÉ DE SEGURANÇA -->
    <footer class="w-full border-t border-slate-800/80 bg-[#060911] py-8 text-xs text-slate-500">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-1 text-emerald-400 font-bold">
                    <span>🔒 SSL 256-Bit</span>
                </div>
                <span>&bull;</span>
                <span>Ativação Instantânea via PIX</span>
                <span>&bull;</span>
                <span>Datacenter Equinix SP3</span>
            </div>
            <div class="flex items-center gap-4 text-slate-400">
                <a href="{{ route('terms.hosting') }}" target="_blank" class="hover:text-emerald-400 transition">Contrato de Hospedagem</a>
                <span>&bull;</span>
                <a href="{{ route('terms.privacy') }}" target="_blank" class="hover:text-emerald-400 transition">Privacidade</a>
                <span>&bull;</span>
                <span>CreativaAi Hub</span>
            </div>
        </div>
    </footer>

</body>
</html>
