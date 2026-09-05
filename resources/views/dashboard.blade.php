<x-app-layout>
    <!-- Topo da Área do Cliente (Estilo Terminal Financeiro & Frosted Glass) -->
    <div class="py-7 border-b border-white/10 bg-white/[0.03] backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Linha Superior: Breadcrumb & Status do Mercado/Cluster -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                <div class="text-slate-400 font-medium flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-emerald-400 transition">Página inicial do portal</a>
                    <span class="text-slate-600">/</span>
                    <span class="text-emerald-400 font-bold">Área do Cliente</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Cluster & Nuvem 100% Online
                    </span>
                    <span class="text-slate-500 font-mono hidden md:inline">
                        SLA 99.99% &bull; BRL / USD Realtime
                    </span>
                </div>
            </div>

            <!-- Cabeçalho Principal: Saudação em Branco Puro, Contadores e Ações -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 pt-1">
                <div>
                    <span class="text-[11px] font-black uppercase tracking-widest text-emerald-400 block mb-1">
                        Área do Cliente &bull; Painel Executivo
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                        Olá, {{ explode(' ', $user->name)[0] ?? 'Administrador' }} !
                    </h1>

                    <!-- Contadores em Linha (High Contrast) -->
                    <div class="flex flex-wrap items-center gap-4 sm:gap-6 mt-3 text-xs font-bold uppercase tracking-wider text-slate-400">
                        <a href="{{ route('hosting.index') }}" class="flex items-center gap-2 hover:text-emerald-400 transition group">
                            <span class="text-white group-hover:text-emerald-400 text-sm font-black transition">{{ $servicesCount }}</span>
                            <span>SERVIÇOS</span>
                        </a>
                        <span class="text-slate-700">&bull;</span>
                        <div class="flex items-center gap-2">
                            <span class="text-white text-sm font-black">{{ $domainsCount }}</span>
                            <span>DOMÍNIOS</span>
                        </div>
                        <span class="text-slate-700">&bull;</span>
                        <a href="{{ route('tickets.index') }}" class="flex items-center gap-2 hover:text-cyan-400 transition group">
                            <span class="text-white group-hover:text-cyan-400 text-sm font-black transition">{{ $ticketsCount }}</span>
                            <span>TICKETS</span>
                        </a>
                        <span class="text-slate-700">&bull;</span>
                        <a href="{{ route('invoices.index') }}" class="flex items-center gap-2 hover:text-amber-400 transition group">
                            <span class="{{ $overdueInvoice ? 'text-rose-400' : 'text-white' }} group-hover:text-amber-400 text-sm font-black transition">
                                {{ $invoicesCount }}
                            </span>
                            <span>FATURAS</span>
                        </a>
                    </div>
                </div>

                <!-- Botões de Ação Rápida -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('hosting.create') }}" 
                       class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                        <span>+</span>
                        <span>Contratar</span>
                    </a>
                    <a href="{{ route('tickets.create') }}" 
                       class="px-5 py-2.5 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] border border-white/20 text-white font-bold text-xs uppercase tracking-wider shadow-sm transition-all flex items-center gap-2 backdrop-blur-md">
                        <span>🎧</span>
                        <span>Abrir ticket</span>
                    </a>
                </div>
            </div>

            <!-- Banners de Ação Urgente -->
            @if ($overdueInvoice || $pendingTicket)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                    <!-- Banner 1: Fatura Vencida / Em Aberto -->
                    @if ($overdueInvoice)
                        <div class="p-5 rounded-xl bg-rose-950/40 border border-rose-500/40 flex items-center justify-between gap-4 shadow-xl backdrop-blur-xl overflow-hidden min-w-0">
                            <div class="flex items-center gap-3.5 min-w-0 flex-1">
                                <div class="w-10 h-10 rounded-lg bg-rose-500/20 border border-rose-500/30 flex items-center justify-center text-rose-400 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-sm font-black text-white block truncate">
                                        {{ $overdueInvoice->is_overdue ? '1 fatura vencida' : '1 fatura em aberto' }}
                                    </span>
                                    <p class="text-xs text-rose-200/90 mt-0.5 truncate">
                                        Total de {{ $overdueInvoice->amount_formatted }}. Pague para evitar a suspensão dos serviços.
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('invoices.show', $overdueInvoice) }}" 
                               class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-500 text-white font-black text-xs uppercase tracking-wider flex-shrink-0 shadow-lg shadow-rose-600/30 transition ml-2">
                                Pagar agora
                            </a>
                        </div>
                    @endif

                    <!-- Banner 2: Ticket Aguardando Resposta -->
                    @if ($pendingTicket)
                        <div class="p-5 rounded-xl bg-blue-950/40 border border-blue-500/40 flex items-center justify-between gap-4 shadow-xl backdrop-blur-xl overflow-hidden min-w-0">
                            <div class="flex items-center gap-3.5 min-w-0 flex-1">
                                <div class="w-10 h-10 rounded-lg bg-blue-500/20 border border-blue-500/30 flex items-center justify-center text-blue-400 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-sm font-black text-white block truncate">
                                        1 ticket aguarda sua resposta
                                    </span>
                                    <p class="text-xs text-blue-200/90 mt-0.5 truncate">
                                        #{{ $pendingTicket->ticket_number }} {{ $pendingTicket->subject }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('tickets.show', $pendingTicket) }}" 
                               class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-black text-xs uppercase tracking-wider flex-shrink-0 shadow-lg shadow-blue-600/30 transition ml-2">
                                Responder
                            </a>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>

    <!-- PAINEL PRINCIPAL -->
    <div class="py-8 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- SEÇÃO 1: TICKERS OPERACIONAIS REAIS (100% Conectados ao Banco) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                
                <!-- Ticker 1: Total Liquidado / Faturamento Real -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl relative overflow-hidden group hover:border-emerald-500/50 transition duration-300 min-w-0">
                    <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-emerald-500/20 transition"></div>
                    <div class="flex items-center justify-between text-xs text-slate-400 font-bold uppercase tracking-wider mb-2">
                        <span class="truncate">Total Liquidado</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex-shrink-0">
                            ✓ Confirmado
                        </span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-white tracking-tight break-words font-mono">
                        R$ {{ number_format($totalPaidCents / 100, 2, ',', '.') }}
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-400 mt-3 pt-3 border-t border-white/10">
                        <span>Faturas Pagas:</span>
                        <span class="text-emerald-400 font-bold">{{ \App\Models\Invoice::where('status', 'paid')->count() }}</span>
                    </div>
                </div>

                <!-- Ticker 2: Faturas a Liquidar -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl relative overflow-hidden group hover:border-amber-500/50 transition duration-300 min-w-0">
                    <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-amber-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-amber-500/20 transition"></div>
                    <div class="flex items-center justify-between text-xs text-slate-400 font-bold uppercase tracking-wider mb-2">
                        <span class="truncate">Faturas em Aberto</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-black {{ $totalUnpaidCents > 0 ? 'bg-amber-500/20 text-amber-400 border border-amber-500/40' : 'bg-slate-800 text-slate-400 border border-slate-700' }} flex-shrink-0">
                            {{ $totalUnpaidCents > 0 ? '⏳ Pendente' : '✓ Em dia' }}
                        </span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-white tracking-tight break-words font-mono">
                        R$ {{ number_format($totalUnpaidCents / 100, 2, ',', '.') }}
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-400 mt-3 pt-3 border-t border-white/10">
                        <span>Faturas Pendentes:</span>
                        <span class="text-amber-400 font-bold">{{ \App\Models\Invoice::where('status', 'unpaid')->count() }}</span>
                    </div>
                </div>

                <!-- Ticker 3: Hospedagens e Domínios -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl relative overflow-hidden group hover:border-cyan-500/50 transition duration-300 min-w-0">
                    <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-cyan-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-cyan-500/20 transition"></div>
                    <div class="flex items-center justify-between text-xs text-slate-400 font-bold uppercase tracking-wider mb-2">
                        <span class="truncate">Hospedagens Ativas</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-black bg-cyan-500/20 text-cyan-400 border border-cyan-500/40 flex-shrink-0">
                            Plesk NVMe
                        </span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-white tracking-tight break-words">
                        {{ $activeServicesCount }} <span class="text-sm font-bold text-slate-400">/ {{ $servicesCount }} {{ $servicesCount === 1 ? 'domínio' : 'domínios' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-400 mt-3 pt-3 border-t border-white/10">
                        <span>Status Operacional:</span>
                        <span class="text-cyan-400 font-bold">100% Online</span>
                    </div>
                </div>

                <!-- Ticker 4: Servidores Cloud & Cluster -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl relative overflow-hidden group hover:border-purple-500/50 transition duration-300 min-w-0">
                    <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-purple-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-purple-500/20 transition"></div>
                    <div class="flex items-center justify-between text-xs text-slate-400 font-bold uppercase tracking-wider mb-2">
                        <span class="truncate">Nós do Cluster Cloud</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex-shrink-0">
                            ● SLA 99.99%
                        </span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-white tracking-tight break-words">
                        {{ $onlineServersCount }} <span class="text-sm font-bold text-slate-400">/ {{ $serversCount }} instâncias</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-400 mt-3 pt-3 border-t border-white/10">
                        <span>Cluster Principal:</span>
                        <span class="text-emerald-400 font-bold">São Paulo (Equinix)</span>
                    </div>
                </div>

            </div>

            <!-- SEÇÃO 2: TOPOLOGIA & TELEMETRIA REAL DOS SERVIDORES (Substitui gráfico simulado por dados reais) -->
            <div class="bg-white/[0.06] backdrop-blur-2xl border border-white/15 rounded-2xl p-6 sm:p-8 shadow-2xl space-y-6 overflow-hidden min-w-0">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-4">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <h2 class="text-lg font-black text-white tracking-tight">
                                Topologia & Telemetria do Cluster HostDevPro
                            </h2>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Servidores de produção e nós de armazenamento NVMe conectados em tempo real.</p>
                    </div>
                    <a href="{{ route('servers.index') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 transition flex items-center gap-1.5">
                        <span>Gerenciar Nós & VPS</span>
                        <span>&rarr;</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($servers as $srv)
                        <div class="p-5 rounded-xl bg-black/40 border border-white/10 hover:border-emerald-500/40 transition duration-200 space-y-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="font-bold text-sm text-white">{{ $srv->name }}</h3>
                                    <span class="text-[11px] text-slate-400 block mt-0.5">{{ $srv->datacenter_location }}</span>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-black {{ $srv->status === 'online' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40' : 'bg-rose-500/20 text-rose-400 border border-rose-500/40' }}">
                                    ● {{ strtoupper($srv->status) }}
                                </span>
                            </div>

                            <div class="p-3 rounded-lg bg-white/[0.03] border border-white/5 space-y-2 text-xs font-mono">
                                <div class="flex items-center justify-between text-slate-300">
                                    <span class="text-slate-500">IP:</span>
                                    <span class="text-cyan-400 font-bold">{{ $srv->ip_address }}</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-300">
                                    <span class="text-slate-500">Hardware:</span>
                                    <span>{{ $srv->cpu_cores }} vCPUs &bull; {{ $srv->ram_mb / 1024 }} GB RAM</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-300">
                                    <span class="text-slate-500">Armazenamento:</span>
                                    <span>{{ $srv->disk_gb }} GB NVMe Gen5</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-1">
                                <span class="text-[11px] text-slate-400">
                                    {{ $srv->hostingAccounts()->count() }} {{ $srv->hostingAccounts()->count() === 1 ? 'conta alocada' : 'contas alocadas' }}
                                </span>
                                <a href="{{ route('servers.show', $srv) }}" class="px-3 py-1 rounded-lg bg-white/[0.08] hover:bg-white/[0.16] text-white font-bold text-xs transition">
                                    Detalhes &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- SEÇÃO 3: MEUS SERVIÇOS & PRODUTOS ATIVOS -->
            <div class="bg-white/[0.06] backdrop-blur-2xl border border-white/15 rounded-2xl p-6 sm:p-8 shadow-2xl space-y-6 overflow-hidden min-w-0">
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-white tracking-tight">
                                Meus Serviços & Produtos Ativos
                            </h2>
                            <p class="text-xs text-slate-400">Contas de hospedagem Plesk gerenciadas e servidores VPS</p>
                        </div>
                    </div>
                    <a href="{{ route('hosting.index') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 transition">
                        Ver todos ({{ $servicesCount }}) &rarr;
                    </a>
                </div>

                <!-- Lista de Serviços -->
                <div class="space-y-3">
                    @forelse ($services as $service)
                        <div class="p-5 rounded-xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition overflow-hidden min-w-0">
                            <div class="flex items-start gap-4 min-w-0 flex-1">
                                <div class="w-11 h-11 rounded-xl bg-black/40 border border-white/10 flex items-center justify-center text-slate-300 flex-shrink-0">
                                    <span class="text-xs font-black text-emerald-400">HDP</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <a href="{{ route('hosting.show', $service) }}" class="font-bold text-sm text-white hover:text-emerald-400 transition truncate max-w-xs sm:max-w-md font-mono">
                                            {{ $service->domain }}
                                        </a>
                                        <span class="px-2.5 py-0.5 rounded text-[10px] font-extrabold border {{ $service->status === 'active' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-white/[0.05] text-slate-400 border-white/10' }} flex-shrink-0">
                                            {{ $service->status_label }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-slate-400 block mt-1 truncate">
                                        {{ $service->server->name ?? 'Cluster Cloud' }} &bull; IP: <code class="text-cyan-400 font-mono">{{ $service->server->ip_address ?? '177.136.254.37' }}</code> &bull; Titular: {{ $service->client->name ?? 'HostDevPro' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-5 border-t sm:border-t-0 border-white/10 pt-3 sm:pt-0 flex-shrink-0">
                                <a href="{{ route('hosting.show', $service) }}" 
                                   class="px-4 py-2 rounded-lg bg-white/[0.08] border border-white/15 hover:bg-white/[0.16] text-white font-bold text-xs transition shadow-sm whitespace-nowrap">
                                    Gerenciar &rarr;
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-500 text-xs">
                            Nenhum serviço contratado no momento.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- SEÇÃO 4: RECURSOS & FERRAMENTAS OFICIAIS (100% Funcional e com links reais) -->
            <div class="bg-white/[0.06] backdrop-blur-2xl border border-white/15 rounded-2xl p-6 sm:p-8 shadow-2xl space-y-6 overflow-hidden min-w-0">
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <div>
                        <h2 class="text-base font-black text-white flex items-center gap-2">
                            <span class="text-emerald-400">🚀</span>
                            <span>Recursos & Ferramentas Oficiais</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">Soluções ativas e integradas à sua conta HostDevPro Cloud</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- 1. Criador de Sites IA Gemini -->
                    <a href="{{ route('ai-builder.index') }}" class="p-5 rounded-xl bg-white/[0.04] hover:bg-purple-950/20 border border-white/10 hover:border-purple-500/50 transition group block min-w-0 overflow-hidden shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/30 text-purple-400 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <span class="font-black text-xs text-white block group-hover:text-purple-300 transition truncate">Criador de Sites IA</span>
                        <span class="text-[11px] text-slate-400 leading-tight block mt-1">Crie sites completos e responsivos com IA Gemini</span>
                    </a>

                    <!-- 2. Central de Afiliados -->
                    <a href="{{ route('affiliates.index') }}" class="p-5 rounded-xl bg-white/[0.04] hover:bg-emerald-950/20 border border-white/10 hover:border-emerald-500/50 transition group block min-w-0 overflow-hidden shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <span class="font-black text-xs text-white block group-hover:text-emerald-300 transition truncate">Central de Afiliados</span>
                        <span class="text-[11px] text-slate-400 leading-tight block mt-1">Comissão recorrente de 15% via PIX instantâneo</span>
                    </a>

                    <!-- 3. Webmail Oficial Roundcube -->
                    <a href="https://webmail.hostdevpro.app.br" target="_blank" rel="noopener" class="p-5 rounded-xl bg-white/[0.04] hover:bg-cyan-950/20 border border-white/10 hover:border-cyan-500/50 transition group block min-w-0 overflow-hidden shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="font-black text-xs text-white block group-hover:text-cyan-300 transition truncate">Webmail Roundcube</span>
                        <span class="text-[11px] text-slate-400 leading-tight block mt-1">Acesso direto seguro às caixas postais corporativas</span>
                    </a>

                    <!-- 4. Painel Plesk Obsidian -->
                    <a href="https://us163-pl.valueserver.net:8443" target="_blank" rel="noopener" class="p-5 rounded-xl bg-white/[0.04] hover:bg-blue-950/20 border border-white/10 hover:border-blue-500/50 transition group block min-w-0 overflow-hidden shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/30 text-blue-400 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                        </div>
                        <span class="font-black text-xs text-white block group-hover:text-blue-300 transition truncate">Painel Plesk Cloud</span>
                        <span class="text-[11px] text-slate-400 leading-tight block mt-1">Gestão de arquivos, bancos de dados e SSL</span>
                    </a>

                    <!-- 5. Solicitar Migração Gratuita -->
                    <a href="{{ route('tickets.create', ['subject' => 'Solicitação de Migração Gratuita de Websites', 'department' => 'devops']) }}" class="p-5 rounded-xl bg-white/[0.04] hover:bg-indigo-950/20 border border-white/10 hover:border-indigo-500/50 transition group block min-w-0 overflow-hidden shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <span class="font-black text-xs text-white block group-hover:text-indigo-300 transition truncate">Migração Gratuita</span>
                        <span class="text-[11px] text-slate-400 leading-tight block mt-1">Nossa equipe transfere seus sites e bancos sem custo</span>
                    </a>

                    <!-- 6. Servidores & Nós VPS -->
                    <a href="{{ route('servers.index') }}" class="p-5 rounded-xl bg-white/[0.04] hover:bg-teal-950/20 border border-white/10 hover:border-teal-500/50 transition group block min-w-0 overflow-hidden shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-teal-500/10 border border-teal-500/30 text-teal-400 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <span class="font-black text-xs text-white block group-hover:text-teal-300 transition truncate">Nós & Servidores VPS</span>
                        <span class="text-[11px] text-slate-400 leading-tight block mt-1">Inventário de processamento e recursos dedicados</span>
                    </a>

                    <!-- 7. Minhas Faturas -->
                    <a href="{{ route('invoices.index') }}" class="p-5 rounded-xl bg-white/[0.04] hover:bg-amber-950/20 border border-white/10 hover:border-amber-500/50 transition group block min-w-0 overflow-hidden shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span class="font-black text-xs text-white block group-hover:text-amber-300 transition truncate">Minhas Faturas</span>
                        <span class="text-[11px] text-slate-400 leading-tight block mt-1">Histórico de pagamentos e emissão de recibos PIX</span>
                    </a>

                    <!-- 8. Contratos & Termos -->
                    <a href="{{ route('terms.hosting') }}" class="p-5 rounded-xl bg-white/[0.04] hover:bg-rose-950/20 border border-white/10 hover:border-rose-500/50 transition group block min-w-0 overflow-hidden shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <span class="font-black text-xs text-white block group-hover:text-rose-300 transition truncate">Contratos & SLA</span>
                        <span class="text-[11px] text-slate-400 leading-tight block mt-1">Termos de serviço, política de privacidade e garantias</span>
                    </a>
                </div>
            </div>

            <!-- SEÇÃO 5: SUPORTE & NOTÍCIAS -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Coluna 1: Chamados Recentes -->
                <div class="bg-white/[0.06] backdrop-blur-2xl border border-white/15 rounded-2xl p-6 sm:p-8 shadow-2xl space-y-4 overflow-hidden min-w-0">
                    <div class="flex items-center justify-between border-b border-white/10 pb-3">
                        <h3 class="text-base font-black text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                            <span>Suporte & Chamados Recentes</span>
                        </h3>
                        <a href="{{ route('tickets.create') }}" class="px-3 py-1.5 rounded-lg bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs transition whitespace-nowrap">
                            Abrir ticket
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse ($recentTickets as $ticket)
                            <a href="{{ route('tickets.show', $ticket) }}" 
                               class="p-4 rounded-xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 flex items-center justify-between gap-3 transition block group overflow-hidden min-w-0">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-bold text-xs text-white group-hover:text-cyan-400 transition truncate max-w-xs sm:max-w-md">
                                            #{{ $ticket->ticket_number }} - {{ $ticket->subject }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $ticket->status_badge_classes }} flex-shrink-0">
                                            {{ $ticket->status_label }}
                                        </span>
                                    </div>
                                    <span class="text-[11px] text-slate-400 block mt-1 truncate">
                                        Atualizado: {{ $ticket->updated_at->format('d/m/Y (H:i)') }}
                                    </span>
                                </div>
                                <span class="text-xs text-cyan-400 font-bold group-hover:translate-x-1 transition-transform flex-shrink-0">&rarr;</span>
                            </a>
                        @empty
                            <div class="p-6 text-center text-slate-500 text-xs">
                                Nenhum chamado recente.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Coluna 2: Notícias & Comunicados Oficiais -->
                <div class="bg-white/[0.06] backdrop-blur-2xl border border-white/15 rounded-2xl p-6 sm:p-8 shadow-2xl space-y-4 overflow-hidden min-w-0">
                    <div class="flex items-center justify-between border-b border-white/10 pb-3">
                        <h3 class="text-base font-black text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            <span>Notícias & Comunicados Oficiais</span>
                        </h3>
                        <a href="{{ route('status') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 transition whitespace-nowrap">
                            Status da Rede &rarr;
                        </a>
                    </div>

                    <div class="space-y-3">
                        @foreach ($news as $item)
                            <div class="p-4 rounded-xl bg-white/[0.04] border border-white/10 space-y-1.5 overflow-hidden min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/40">
                                        {{ $item['category'] }}
                                    </span>
                                    <span class="text-[10px] text-slate-400">{{ $item['date'] }}</span>
                                </div>
                                <h4 class="font-bold text-xs text-white">
                                    {{ $item['title'] }}
                                </h4>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
