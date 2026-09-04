<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HostDevPro &bull; Infraestrutura Cloud, Hospedagem Plesk & VPS de Alta Performance</title>
    <meta name="description" content="Infraestrutura Cloud corporativa, Hospedagem Plesk NVMe, servidores VPS dedicados e automação DevOps de alta performance no Brasil.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Favicon & PWA -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#020617">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#020617] text-slate-100 min-h-screen selection:bg-emerald-500 selection:text-slate-950 relative overflow-x-hidden">
    
    <!-- Fundo Holográfico & Marca d'água de Código DevOps -->
    @include('partials.code-background')

    <!-- Luzes Difusas de Ambiente (Glow Neon) -->
    <div class="fixed top-0 left-1/4 w-[600px] h-[350px] bg-emerald-500/[0.07] rounded-full blur-[140px] pointer-events-none -z-10"></div>
    <div class="fixed top-1/3 right-10 w-[500px] h-[400px] bg-purple-500/[0.06] rounded-full blur-[150px] pointer-events-none -z-10"></div>
    <div class="fixed bottom-10 left-10 w-[550px] h-[350px] bg-cyan-500/[0.06] rounded-full blur-[140px] pointer-events-none -z-10"></div>

    <!-- Barra de Navegação Pública Superior (Dark Frosted Glass) -->
    <header class="sticky top-0 z-50 bg-[#020617]/85 backdrop-blur-2xl border-b border-white/10 transition duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo HDP com Glow -->
                <div class="flex items-center gap-8">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 group focus:outline-none">
                        <div class="relative flex items-center justify-center">
                            <span class="text-2xl font-black tracking-tighter text-white group-hover:text-emerald-400 transition">
                                &lt;HDP/&gt;
                            </span>
                            <div class="absolute -inset-1 bg-emerald-500/20 rounded-lg blur-md group-hover:bg-emerald-500/40 transition opacity-0 group-hover:opacity-100 -z-10"></div>
                        </div>
                        <div class="hidden sm:block">
                            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-400 block leading-none">
                                HOSTDEVPRO CLOUD
                            </span>
                            <span class="text-[9px] font-semibold text-slate-400 block tracking-wider leading-tight">
                                INFRAESTRUTURA & DEVOPS
                            </span>
                        </div>
                    </a>

                    <!-- Links de Navegação Desktop -->
                    <nav class="hidden md:flex items-center gap-6 text-xs font-bold uppercase tracking-wider text-slate-300">
                        <a href="#planos" class="hover:text-emerald-400 transition">Hospedagem & Planos</a>
                        <a href="#vps" class="hover:text-emerald-400 transition">VPS Cloud</a>
                        <a href="{{ route('affiliates.index') }}" class="hover:text-emerald-400 transition flex items-center gap-1.5">
                            <span>Afiliados</span>
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/40">15%</span>
                        </a>
                        <a href="{{ route('status') }}" class="hover:text-cyan-400 transition flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>Status da Rede</span>
                        </a>
                    </nav>
                </div>

                <!-- Ações Direitas (Webmail, Login, Contratar) -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Webmail -->
                    <a href="https://webmail.hostdevpro.app.br" target="_blank" rel="noopener noreferrer" 
                       class="hidden lg:flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/[0.04] hover:bg-white/[0.09] border border-white/10 text-slate-300 hover:text-white font-bold text-xs uppercase tracking-wider transition">
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>Webmail</span>
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}" 
                           class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                            <span>Meu Painel</span>
                            <span>&rarr;</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="px-4 py-2 rounded-xl bg-white/[0.06] hover:bg-white/[0.12] border border-white/15 text-white font-bold text-xs uppercase tracking-wider transition">
                            Entrar
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                                <span>Criar Conta</span>
                                <span>&rarr;</span>
                            </a>
                        @endif
                    @endauth
                </div>

            </div>
        </div>
    </header>

    <!-- HERO SECTION: Infraestrutura Cloud & DevOps -->
    <section class="relative pt-12 sm:pt-20 pb-16 sm:pb-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-4xl mx-auto space-y-6">
                
                <!-- Status Pill Animado -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 shadow-sm backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Cluster Brasil 100% Operacional &bull; Datacenter São Paulo SP3</span>
                </div>

                <!-- Título Principal com Tipografia Forte -->
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-white tracking-tight leading-[1.08]">
                    Hospedagem & VPS Cloud para <span class="bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400 bg-clip-text text-transparent">Projetos Críticos</span>
                </h1>

                <!-- Subtítulo Explicativo -->
                <p class="text-base sm:text-xl text-slate-300 max-w-2xl mx-auto leading-relaxed">
                    Instâncias VPS NVMe Gen5 dedicadas, painel Plesk gerenciado e automação DevOps com latência ultra-baixa de até <span class="text-emerald-400 font-bold">12ms no Brasil</span>.
                </p>

                <!-- Botões de Ação Principal -->
                <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('register') }}" 
                       class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-sm uppercase tracking-wider shadow-xl shadow-emerald-500/25 hover:shadow-emerald-500/40 transition-all flex items-center justify-center gap-3 transform hover:-translate-y-0.5">
                        <span>Iniciar Agora &bull; Primeiro Acesso</span>
                        <span>&rarr;</span>
                    </a>
                    <a href="#planos" 
                       class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-white/[0.06] hover:bg-white/[0.12] border border-white/20 text-white font-bold text-sm uppercase tracking-wider shadow-sm transition-all flex items-center justify-center gap-2 backdrop-blur-md">
                        <span>Ver Todos os Planos</span>
                    </a>
                </div>

                <!-- Micro Destaques de Confiança -->
                <div class="pt-6 flex flex-wrap items-center justify-center gap-6 sm:gap-10 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Ativação Instantânea</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>SLA 99.99% Garantido</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Suporte WhatsApp & SLA</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>PIX com Baixa Automática</span>
                    </div>
                </div>

            </div>

            <!-- CARDS DE MÉTRICAS EM TEMPO REAL (Mesmo Estilo do Dashboard, rounded-2xl) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pt-16 max-w-7xl mx-auto">
                
                <!-- Card 1 -->
                <div class="p-6 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-xl relative overflow-hidden group hover:border-emerald-500/50 transition duration-300 min-w-0">
                    <div class="flex items-center justify-between text-xs text-slate-400 font-bold uppercase tracking-wider mb-2">
                        <span>Disponibilidade</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/40">
                            ONLINE
                        </span>
                    </div>
                    <div class="text-3xl font-black text-white tracking-tight">
                        99.99<span class="text-emerald-400">%</span>
                    </div>
                    <div class="text-[11px] text-slate-400 mt-2.5 pt-2.5 border-t border-white/10">
                        Monitoramento contínuo em 3 zonas
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="p-6 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-xl relative overflow-hidden group hover:border-cyan-500/50 transition duration-300 min-w-0">
                    <div class="flex items-center justify-between text-xs text-slate-400 font-bold uppercase tracking-wider mb-2">
                        <span>Latência Nacional</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-black bg-cyan-500/20 text-cyan-400 border border-cyan-500/40">
                            SP3
                        </span>
                    </div>
                    <div class="text-3xl font-black text-white tracking-tight">
                        &lt; 14 <span class="text-sm font-bold text-slate-400">ms</span>
                    </div>
                    <div class="text-[11px] text-slate-400 mt-2.5 pt-2.5 border-t border-white/10">
                        Conexão direta com IX.br São Paulo
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="p-6 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-xl relative overflow-hidden group hover:border-purple-500/50 transition duration-300 min-w-0">
                    <div class="flex items-center justify-between text-xs text-slate-400 font-bold uppercase tracking-wider mb-2">
                        <span>Armazenamento</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-black bg-purple-500/20 text-purple-400 border border-purple-500/40">
                            GEN 5
                        </span>
                    </div>
                    <div class="text-3xl font-black text-white tracking-tight">
                        100<span class="text-sm font-bold text-slate-400">% NVMe</span>
                    </div>
                    <div class="text-[11px] text-slate-400 mt-2.5 pt-2.5 border-t border-white/10">
                        Leitura até 7.500 MB/s por nó
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="p-6 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-xl relative overflow-hidden group hover:border-amber-500/50 transition duration-300 min-w-0">
                    <div class="flex items-center justify-between text-xs text-slate-400 font-bold uppercase tracking-wider mb-2">
                        <span>Mitigação DDoS</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-black bg-amber-500/20 text-amber-400 border border-amber-500/40">
                            CAMADA 7
                        </span>
                    </div>
                    <div class="text-3xl font-black text-white tracking-tight">
                        3.2 <span class="text-sm font-bold text-slate-400">Tbps</span>
                    </div>
                    <div class="text-[11px] text-slate-400 mt-2.5 pt-2.5 border-t border-white/10">
                        Proteção ativa contra ataques volumétricos
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- SEÇÃO DE PLANOS & HOSPEDAGEM (Dark Frosted Glass, rounded-2xl) -->
    <section id="planos" class="py-16 sm:py-24 border-t border-white/10 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <span class="text-xs font-black uppercase tracking-widest text-emerald-400 block">
                    PLANOS SOB MEDIDA
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                    Escolha a Solução Ideal para o Seu Negócio
                </h2>
                <p class="text-sm sm:text-base text-slate-300">
                    Planos flexíveis sem fidelidade forçada. Escale seus recursos conforme a sua demanda cresce.
                </p>
            </div>

            <!-- Grid de 3 Planos Principais -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Plano 1: Hospedagem Plesk NVMe Basic -->
                <div class="p-8 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-2xl flex flex-col justify-between transition duration-300 group hover:border-emerald-500/50 min-w-0 overflow-hidden">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold bg-white/[0.05] text-slate-300 border border-white/10">
                                STARTUP
                            </span>
                            <span class="text-xs font-mono text-emerald-400 font-bold">Plesk Obsidian</span>
                        </div>

                        <div>
                            <h3 class="text-xl font-black text-white tracking-tight">
                                Hospedagem Plesk NVMe
                            </h3>
                            <p class="text-xs text-slate-400 mt-1">
                                Perfeito para sites institucionais, blogs e e-commerces em crescimento.
                            </p>
                        </div>

                        <div class="pt-2 border-t border-white/10">
                            <div class="flex items-baseline gap-1">
                                <span class="text-sm font-bold text-slate-400">R$</span>
                                <span class="text-4xl font-black text-white tracking-tight">29,90</span>
                                <span class="text-xs text-slate-400">/ mês</span>
                            </div>
                            <span class="text-[11px] text-emerald-400 font-semibold block mt-1">
                                Sem taxa de adesão &bull; PIX instantâneo
                            </span>
                        </div>

                        <!-- Recursos do Plano -->
                        <ul class="space-y-3 text-xs text-slate-300 pt-4 border-t border-white/10">
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span><strong>10 GB</strong> Armazenamento NVMe Gen5</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Tráfego Mensal Ilimitado</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>SSL Gratuito Let's Encrypt</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Contas de E-mail Corporativas</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>PHP 8.2, 8.3, 8.4 e Node.js</span>
                            </li>
                        </ul>
                    </div>

                    <div class="pt-8">
                        <a href="{{ route('register') }}" 
                           class="w-full py-3 rounded-xl bg-white/[0.08] hover:bg-white/[0.16] border border-white/20 text-white font-bold text-xs uppercase tracking-wider transition flex items-center justify-center gap-2">
                            <span>Contratar Agora</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- Plano 2: Revenda Plesk NVMe Turbo (Destaque Principal) -->
                <div class="p-8 rounded-2xl bg-gradient-to-b from-white/[0.12] to-white/[0.04] backdrop-blur-2xl border-2 border-emerald-500/60 shadow-2xl flex flex-col justify-between transition duration-300 relative group min-w-0 overflow-hidden transform md:-translate-y-2">
                    <div class="absolute -right-8 -top-8 w-32 h-32 bg-emerald-500/20 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-lg text-xs font-black bg-emerald-500 text-slate-950 shadow-md">
                                MAIS POPULAR
                            </span>
                            <span class="text-xs font-mono text-emerald-400 font-bold">Revenda Ilimitada</span>
                        </div>

                        <div>
                            <h3 class="text-xl font-black text-white tracking-tight">
                                Revenda Plesk Turbo NVMe
                            </h3>
                            <p class="text-xs text-slate-300 mt-1">
                                Ideal para agências e devs gerenciarem múltiplos domínios de clientes.
                            </p>
                        </div>

                        <div class="pt-2 border-t border-white/10">
                            <div class="flex items-baseline gap-1">
                                <span class="text-sm font-bold text-slate-400">R$</span>
                                <span class="text-4xl font-black text-emerald-400 tracking-tight">59,99</span>
                                <span class="text-xs text-slate-400">/ mês</span>
                            </div>
                            <span class="text-[11px] text-emerald-400 font-semibold block mt-1">
                                DNS Personalizado (ns1/ns2) incluso
                            </span>
                        </div>

                        <!-- Recursos do Plano -->
                        <ul class="space-y-3 text-xs text-slate-200 pt-4 border-t border-white/10">
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span><strong>Espaço Ilimitado</strong> com NVMe Gen5</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Painel Plesk Multi-tenant com Marca Própria</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Contas de Clientes Ilimitadas</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>WP Toolkit Pro com Staging em 1 clique</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Backups Diários Automáticos Remotos</span>
                            </li>
                        </ul>
                    </div>

                    <div class="pt-8">
                        <a href="{{ route('register') }}" 
                           class="w-full py-3.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/25 transition-all flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                            <span>Ativar Meu Plano Agora</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- Plano 3: VPS Cloud Dedicado -->
                <div class="p-8 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-2xl flex flex-col justify-between transition duration-300 group hover:border-cyan-500/50 min-w-0 overflow-hidden" id="vps">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold bg-cyan-500/20 text-cyan-400 border border-cyan-500/40">
                                DEDICADO
                            </span>
                            <span class="text-xs font-mono text-cyan-400 font-bold">Root & Docker</span>
                        </div>

                        <div>
                            <h3 class="text-xl font-black text-white tracking-tight">
                                VPS Cloud Enterprise
                            </h3>
                            <p class="text-xs text-slate-400 mt-1">
                                Recursos 100% dedicados para microsserviços, Docker, APIs e banco de dados.
                            </p>
                        </div>

                        <div class="pt-2 border-t border-white/10">
                            <div class="flex items-baseline gap-1">
                                <span class="text-sm font-bold text-slate-400">R$</span>
                                <span class="text-4xl font-black text-white tracking-tight">99,90</span>
                                <span class="text-xs text-slate-400">/ mês</span>
                            </div>
                            <span class="text-[11px] text-cyan-400 font-semibold block mt-1">
                                IP Dedicado IPv4 + IPv6 incluso
                            </span>
                        </div>

                        <!-- Recursos do Plano -->
                        <ul class="space-y-3 text-xs text-slate-300 pt-4 border-t border-white/10">
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span><strong>4 vCPUs</strong> AMD EPYC High Frequency</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span><strong>8 GB</strong> Memória RAM DDR5</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span><strong>160 GB</strong> Armazenamento NVMe Gen5</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Acesso Root Completo via SSH</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Docker, OpenResty e Portainer prontos</span>
                            </li>
                        </ul>
                    </div>

                    <div class="pt-8">
                        <a href="{{ route('register') }}" 
                           class="w-full py-3 rounded-xl bg-white/[0.08] hover:bg-white/[0.16] border border-white/20 text-white font-bold text-xs uppercase tracking-wider transition flex items-center justify-center gap-2">
                            <span>Configurar Instância</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- SEÇÃO RECURSOS EXCLUSIVOS & DEVOPS (rounded-2xl) -->
    <section class="py-16 sm:py-24 border-t border-white/10 bg-white/[0.02]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <span class="text-xs font-black uppercase tracking-widest text-emerald-400 block">
                    TECNOLOGIA DE PONTA
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                    Recursos que Potencializam sua Infraestrutura
                </h2>
                <p class="text-sm sm:text-base text-slate-300">
                    Ferramentas integradas para desenvolvedores e agências pouparem horas de suporte e configuração.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Recurso 1: Gemini IA Cloud -->
                <div class="p-6 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-xl space-y-3 group hover:border-emerald-500/50 transition">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-base font-black text-white group-hover:text-emerald-400 transition">
                        Gemini IA Cloud Integrado
                    </h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Diagnóstico inteligente de erros em aplicações PHP e Node.js diretamente no painel do cliente.
                    </p>
                </div>

                <!-- Recurso 2: WP Vivid Backup -->
                <div class="p-6 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-xl space-y-3 group hover:border-teal-500/50 transition">
                    <div class="w-10 h-10 rounded-xl bg-teal-500/10 border border-teal-500/30 text-teal-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                    </div>
                    <h3 class="text-base font-black text-white group-hover:text-teal-400 transition">
                        WP Vivid Backup & Staging
                    </h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Crie ambientes de testes para sites WordPress e sincronize em produção com 1 clique.
                    </p>
                </div>

                <!-- Recurso 3: Central de Afiliados -->
                <div class="p-6 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-xl space-y-3 group hover:border-purple-500/50 transition">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/30 text-purple-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="text-base font-black text-white group-hover:text-purple-400 transition">
                        Programa de Afiliados 15%
                    </h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Ganhe comissões recorrentes vitalícias em cada fatura paga de clientes que você indicar.
                    </p>
                </div>

                <!-- Recurso 4: Conexão Brasil Ultra-Rápida -->
                <div class="p-6 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-xl space-y-3 group hover:border-amber-500/50 transition">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                    </div>
                    <h3 class="text-base font-black text-white group-hover:text-amber-400 transition">
                        Datacenter SP3 Tier III
                    </h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Infraestrutura em São Paulo com roteamento BGP multi-operadora e redundância de energia N+1.
                    </p>
                </div>

                <!-- Recurso 5: Migração Gratuita -->
                <div class="p-6 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-xl space-y-3 group hover:border-cyan-500/50 transition">
                    <div class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    </div>
                    <h3 class="text-base font-black text-white group-hover:text-cyan-400 transition">
                        Migração Gratuita & Sem Queda
                    </h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Nossos especialistas transferem seus domínios, sites e e-mails do provedor antigo sem custo.
                    </p>
                </div>

                <!-- Recurso 6: Suporte de Engenharia -->
                <div class="p-6 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-xl space-y-3 group hover:border-emerald-500/50 transition">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <h3 class="text-base font-black text-white group-hover:text-emerald-400 transition">
                        Suporte por Engenheiros DevOps
                    </h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Atendimento direto via chamado, WhatsApp ou e-mail com profissionais que entendem de código.
                    </p>
                </div>

            </div>

        </div>
    </section>

    <!-- RODAPÉ CORPORATIVO COM BARRA DE ATENDIMENTO -->
    @include('partials.footer')

</body>
</html>
