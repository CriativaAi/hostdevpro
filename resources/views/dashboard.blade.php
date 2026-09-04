<x-app-layout>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Topo da Área do Cliente (Dark Glassmorphism) -->
    <div class="py-6 sm:py-8 border-b border-slate-800/80 bg-slate-950/40 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <div class="text-xs text-slate-400 font-medium mb-3 flex items-center gap-1.5">
                <a href="{{ route('dashboard') }}" class="hover:text-emerald-400 transition">Página inicial do portal</a>
                <span class="text-slate-600">/</span>
                <span class="text-emerald-400 font-semibold">Área do Cliente</span>
            </div>

            <!-- Cabeçalho com Saudação, Contadores e Botões de Ação -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-emerald-400">
                            Área do Cliente
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Nuvem HostDevPro Ativa
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                        Olá, {{ explode(' ', $user->name)[0] ?? 'Ale' }} !
                    </h1>

                    <!-- Contadores em Linha (Estilo ValueHost/Cloud Pro) -->
                    <div class="flex flex-wrap items-center gap-4 sm:gap-6 mt-3 text-xs font-bold uppercase tracking-wider text-slate-400">
                        <a href="{{ route('hosting.index') }}" class="flex items-center gap-1.5 hover:text-emerald-400 transition group">
                            <span class="text-white group-hover:text-emerald-400 text-sm font-black transition">{{ $servicesCount }}</span>
                            <span>SERVIÇOS</span>
                        </a>
                        <span class="text-slate-700">&bull;</span>
                        <div class="flex items-center gap-1.5">
                            <span class="text-white text-sm font-black">{{ $domainsCount }}</span>
                            <span>DOMÍNIOS</span>
                        </div>
                        <span class="text-slate-700">&bull;</span>
                        <a href="{{ route('tickets.index') }}" class="flex items-center gap-1.5 hover:text-cyan-400 transition group">
                            <span class="text-white group-hover:text-cyan-400 text-sm font-black transition">{{ $ticketsCount }}</span>
                            <span>TICKETS</span>
                        </a>
                        <span class="text-slate-700">&bull;</span>
                        <a href="{{ route('invoices.index') }}" class="flex items-center gap-1.5 hover:text-amber-400 transition group">
                            <span class="{{ $overdueInvoice ? 'text-rose-400' : 'text-white' }} group-hover:text-amber-400 text-sm font-black transition">
                                {{ $invoicesCount }}
                            </span>
                            <span>FATURAS</span>
                        </a>
                    </div>
                </div>

                <!-- Botões de Ação Rápida (Topo Direito) -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('hosting.create') }}" 
                       class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                        <span>+</span>
                        <span>Contratar</span>
                    </a>
                    <a href="{{ route('tickets.create') }}" 
                       class="px-5 py-2.5 rounded-xl bg-slate-800/90 hover:bg-slate-700/90 border border-slate-700 text-slate-200 hover:text-white font-bold text-xs uppercase tracking-wider shadow-sm transition-all flex items-center gap-2">
                        <span>🎧</span>
                        <span>Abrir ticket</span>
                    </a>
                </div>
            </div>

            <!-- Banners de Ação Urgente (Notice Cards) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                <!-- Banner 1: Fatura Vencida / Em Aberto -->
                @if ($overdueInvoice)
                    <div class="p-4 sm:p-5 rounded-2xl bg-rose-950/30 border border-rose-500/40 flex items-center justify-between gap-4 shadow-xl backdrop-blur-sm transition-transform hover:-translate-y-0.5">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-rose-500/20 border border-rose-500/30 flex items-center justify-center text-rose-400 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <div>
                                <span class="text-sm font-black text-rose-200 block">
                                    {{ $overdueInvoice->is_overdue ? '1 fatura vencida' : '1 fatura em aberto' }}
                                </span>
                                <p class="text-xs text-rose-300/80 mt-0.5">
                                    Total de {{ $overdueInvoice->amount_formatted }}. Pague para evitar a suspensão dos serviços.
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('invoices.show', $overdueInvoice) }}" 
                           class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-black text-xs uppercase tracking-wider flex-shrink-0 shadow-lg shadow-rose-600/30 transition">
                            Pagar agora
                        </a>
                    </div>
                @endif

                <!-- Banner 2: Ticket Aguardando Resposta -->
                @if ($pendingTicket)
                    <div class="p-4 sm:p-5 rounded-2xl bg-blue-950/30 border border-blue-500/40 flex items-center justify-between gap-4 shadow-xl backdrop-blur-sm transition-transform hover:-translate-y-0.5">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/20 border border-blue-500/30 flex items-center justify-center text-blue-400 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                            </div>
                            <div>
                                <span class="text-sm font-black text-blue-200 block">
                                    1 ticket aguarda sua resposta
                                </span>
                                <p class="text-xs text-blue-300/80 mt-0.5 truncate max-w-xs sm:max-w-sm">
                                    #{{ $pendingTicket->ticket_number }} {{ $pendingTicket->subject }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('tickets.show', $pendingTicket) }}" 
                           class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-black text-xs uppercase tracking-wider flex-shrink-0 shadow-lg shadow-blue-600/30 transition">
                            Responder
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- SEÇÃO PRINCIPAL: GRÁFICOS DE DESEMPENHO & TELEMETRIA EM TEMPO REAL -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Card Master de Telemetria e Desempenho Cloud -->
            <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800/90 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden"
                 x-data="performanceAnalytics()">
                
                <!-- Glow decorativo superior -->
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Header da Seção de Telemetria -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-6 border-b border-slate-800/80 relative z-10">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <div class="w-8 h-8 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <h2 class="text-xl font-black text-white tracking-tight">
                                Telemetria em Tempo Real & Performance Cloud
                            </h2>
                        </div>
                        <p class="text-xs text-slate-400">
                            Cluster Integrator & Plesk • Latência Média 11ms • SLA 99.99% Uptime Dedicado
                        </p>
                    </div>

                    <!-- Seletor de Período dos Gráficos -->
                    <div class="flex items-center gap-1.5 p-1 rounded-xl bg-slate-950/80 border border-slate-800">
                        <button type="button" 
                                @click="setPeriod('24h')" 
                                :class="period === '24h' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 shadow-sm' : 'text-slate-400 hover:text-white'"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition">
                            Últimas 24h
                        </button>
                        <button type="button" 
                                @click="setPeriod('7d')" 
                                :class="period === '7d' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 shadow-sm' : 'text-slate-400 hover:text-white'"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition">
                            7 Dias
                        </button>
                        <button type="button" 
                                @click="setPeriod('30d')" 
                                :class="period === '30d' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 shadow-sm' : 'text-slate-400 hover:text-white'"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition">
                            30 Dias
                        </button>
                    </div>
                </div>

                <!-- 4 Indicadores Rápidos de Hardware (Gauges de Servidor) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 py-6 border-b border-slate-800/80 relative z-10">
                    <!-- Gauge 1: CPU -->
                    <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-bold uppercase tracking-wider">CPU do Cluster</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                26.4% Ótimo
                            </span>
                        </div>
                        <div class="text-2xl font-black text-white">
                            26.4 <span class="text-xs font-normal text-slate-400">%</span>
                        </div>
                        <!-- Progress bar -->
                        <div class="w-full h-1.5 rounded-full bg-slate-800 overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full" style="width: 26.4%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500 block">8 vCPUs Dedicados Xeon 3.4GHz</span>
                    </div>

                    <!-- Gauge 2: Memória RAM -->
                    <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-bold uppercase tracking-wider">Memória RAM</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-cyan-500/10 text-cyan-400 border border-cyan-500/30">
                                36.2% Normal
                            </span>
                        </div>
                        <div class="text-2xl font-black text-white">
                            5.8 <span class="text-xs font-normal text-slate-400">/ 16 GB</span>
                        </div>
                        <div class="w-full h-1.5 rounded-full bg-slate-800 overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-cyan-500 to-blue-400 rounded-full" style="width: 36.2%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500 block">Buffer I/O & Cache Ativos</span>
                    </div>

                    <!-- Gauge 3: Armazenamento NVMe -->
                    <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-bold uppercase tracking-wider">NVMe SSD</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-500/10 text-indigo-400 border border-indigo-500/30">
                                19.4% Livre
                            </span>
                        </div>
                        <div class="text-2xl font-black text-white">
                            48.5 <span class="text-xs font-normal text-slate-400">/ 250 GB</span>
                        </div>
                        <div class="w-full h-1.5 rounded-full bg-slate-800 overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-400 rounded-full" style="width: 19.4%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500 block">Velocidade I/O 3.200 MB/s</span>
                    </div>

                    <!-- Gauge 4: Uptime & Rede -->
                    <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-bold uppercase tracking-wider">Disponibilidade</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                99.99% Uptime
                            </span>
                        </div>
                        <div class="text-2xl font-black text-white">
                            142.8 <span class="text-xs font-normal text-slate-400">Mbps</span>
                        </div>
                        <div class="w-full h-1.5 rounded-full bg-slate-800 overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-emerald-400 to-teal-300 rounded-full" style="width: 45%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500 block">Pico Registrado: 340 Mbps</span>
                    </div>
                </div>

                <!-- Painel dos 3 Gráficos Interativos -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 pt-6 relative z-10">

                    <!-- Gráfico 1: Throughput de Rede & Tráfego Web (8 colunas) -->
                    <div class="lg:col-span-8 p-5 rounded-2xl bg-slate-950/60 border border-slate-800/90 flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                                    <span>Throughput de Rede & Tráfego Web</span>
                                </h3>
                                <span class="text-[11px] text-slate-400">Vazão de dados em tempo real (Megabits por segundo)</span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-black text-emerald-400" x-text="bandwidthPeak"></span>
                                <span class="text-[10px] text-slate-500 block">Pico do Período</span>
                            </div>
                        </div>

                        <!-- Canvas do Chart.js -->
                        <div class="relative h-64 sm:h-72 w-full">
                            <canvas id="networkTrafficChart"></canvas>
                        </div>
                    </div>

                    <!-- Gráfico 2: Latência Global & Cache Hit Ratio (4 colunas) -->
                    <div class="lg:col-span-4 space-y-6">

                        <!-- Mini Gráfico: Latência por PoP Edge -->
                        <div class="p-5 rounded-2xl bg-slate-950/60 border border-slate-800/90">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-cyan-400"></span>
                                    <span>Latência Edge PoP</span>
                                </h3>
                                <span class="text-[10px] text-slate-400">Tempo de Resposta</span>
                            </div>
                            <div class="relative h-36 w-full">
                                <canvas id="edgeLatencyChart"></canvas>
                            </div>
                        </div>

                        <!-- Mini Gráfico: Eficiência do Proxy Cache (OpenResty) -->
                        <div class="p-5 rounded-2xl bg-slate-950/60 border border-slate-800/90">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-teal-400"></span>
                                    <span>OpenResty Proxy Cache</span>
                                </h3>
                                <span class="text-xs font-extrabold text-emerald-400">94.8% Hit</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="relative w-24 h-24 flex-shrink-0">
                                    <canvas id="cacheHitChart"></canvas>
                                </div>
                                <div class="text-[11px] space-y-1 text-slate-300">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                        <span>94.8% Em Memória</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-slate-400">
                                        <span class="w-2 h-2 rounded-full bg-slate-700"></span>
                                        <span>5.2% PHP 8.4</span>
                                    </div>
                                    <span class="text-[10px] text-slate-500 block pt-1">
                                        Economia de ~78% de tráfego
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <!-- GRID PRINCIPAL DE GESTÃO (SERVIÇOS, RECURSOS PREMIUM, SUPORTE, DOMÍNIOS) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <!-- Coluna Esquerda Principal (8 colunas) -->
                <div class="lg:col-span-8 space-y-8">

                    <!-- Seção 1: Meus Serviços & Produtos Ativos -->
                    <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800/90 rounded-3xl p-6 sm:p-7 shadow-2xl">
                        <div class="flex items-center justify-between mb-5 border-b border-slate-800/80 pb-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                                </div>
                                <h2 class="text-base font-black text-white tracking-tight">
                                    Meus Serviços & Produtos Ativos
                                </h2>
                            </div>
                            <a href="{{ route('hosting.index') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 transition">
                                Ver todos ({{ $servicesCount }}) &rarr;
                            </a>
                        </div>

                        <!-- Lista de Serviços -->
                        <div class="space-y-3">
                            @forelse ($services as $service)
                                <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:border-slate-700 transition">
                                    <div class="flex items-start gap-3.5">
                                        <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-300 flex-shrink-0">
                                            @if ($service->panel_type === 'plesk')
                                                <span class="text-xs font-black text-blue-400">PLK</span>
                                            @elseif ($service->panel_type === 'cpanel')
                                                <span class="text-xs font-black text-amber-400">CPN</span>
                                            @else
                                                <span class="text-xs font-black text-emerald-400">VPS</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('hosting.show', $service) }}" class="font-bold text-sm text-white hover:text-emerald-400 transition">
                                                    {{ $service->domain }}
                                                </a>
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold border {{ $service->status === 'active' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-slate-800 text-slate-400 border-slate-700' }}">
                                                    {{ $service->status_label }}
                                                </span>
                                            </div>
                                            <span class="text-xs text-slate-400 block mt-0.5">
                                                {{ $service->server->name ?? 'Cluster Cloud' }} ({{ $service->server->ip_address ?? '177.136.254.37' }})
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between sm:justify-end gap-4 border-t sm:border-t-0 border-slate-800/80 pt-3 sm:pt-0">
                                        <div class="text-right hidden sm:block">
                                            <span class="text-[11px] text-slate-400 block">
                                                R$ 59,99 &bull; Mensal
                                            </span>
                                        </div>
                                        <a href="{{ route('hosting.show', $service) }}" 
                                           class="px-4 py-2 rounded-xl bg-slate-900 border border-slate-700 hover:bg-slate-800 text-slate-200 hover:text-white font-bold text-xs transition shadow-sm">
                                            Detalhes
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

                    <!-- Seção 2: Recursos Premium (Inclusos na sua conta, sem custo) -->
                    <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800/90 rounded-3xl p-6 sm:p-7 shadow-2xl">
                        <div class="flex items-center justify-between mb-5 border-b border-slate-800/80 pb-4">
                            <h2 class="text-base font-black text-white flex items-center gap-2">
                                <span class="text-amber-400">⭐</span>
                                <span>Recursos premium</span>
                            </h2>
                            <span class="text-xs text-slate-400">Inclusos na sua conta, sem custo</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3.5">
                            <!-- Recurso 1: Central de Afiliados -->
                            <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 hover:border-slate-700 transition group cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-blue-500/10 border border-blue-500/30 text-blue-400 flex items-center justify-center mb-2.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <span class="font-bold text-xs text-white block group-hover:text-blue-400 transition">Central de Afiliados</span>
                                <span class="text-[10px] text-slate-400 leading-tight block mt-0.5">Comissão recorrente de até 10%</span>
                            </div>

                            <!-- Recurso 2: Downloads Premium -->
                            <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 hover:border-slate-700 transition group cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-purple-500/10 border border-purple-500/30 text-purple-400 flex items-center justify-center mb-2.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                </div>
                                <span class="font-bold text-xs text-white block group-hover:text-purple-400 transition">Downloads Premium</span>
                                <span class="text-[10px] text-slate-400 leading-tight block mt-0.5">Envato, Freepik e GPL Vault</span>
                            </div>

                            <!-- Recurso 3: Gemini IA Cloud -->
                            <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 hover:border-slate-700 transition group cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-center mb-2.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </div>
                                <span class="font-bold text-xs text-white block group-hover:text-emerald-400 transition">Gemini IA Cloud</span>
                                <span class="text-[10px] text-slate-400 leading-tight block mt-0.5">Assistente de IA no seu painel</span>
                            </div>

                            <!-- Recurso 4: WP Vivid Backup -->
                            <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 hover:border-slate-700 transition group cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-teal-500/10 border border-teal-500/30 text-teal-400 flex items-center justify-center mb-2.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                                <span class="font-bold text-xs text-white block group-hover:text-teal-400 transition">WP Vivid Backup</span>
                                <span class="text-[10px] text-slate-400 leading-tight block mt-0.5">Backup e migração WordPress</span>
                            </div>

                            <!-- Recurso 5: Assinatura de E-mail -->
                            <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 hover:border-slate-700 transition group cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 flex items-center justify-center mb-2.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <span class="font-bold text-xs text-white block group-hover:text-amber-400 transition">Assinatura de E-mail</span>
                                <span class="text-[10px] text-slate-400 leading-tight block mt-0.5">Assinaturas profissionais</span>
                            </div>

                            <!-- Recurso 6: Gerador de Nomes -->
                            <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 hover:border-slate-700 transition group cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 flex items-center justify-center mb-2.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                </div>
                                <span class="font-bold text-xs text-white block group-hover:text-rose-400 transition">Gerador de Nomes</span>
                                <span class="text-[10px] text-slate-400 leading-tight block mt-0.5">Ideias de marcas e domínios</span>
                            </div>

                            <!-- Recurso 7: Gera.Bio Links -->
                            <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 hover:border-slate-700 transition group cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center mb-2.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                </div>
                                <span class="font-bold text-xs text-white block group-hover:text-cyan-400 transition">Gera.Bio Links</span>
                                <span class="text-[10px] text-slate-400 leading-tight block mt-0.5">Árvore de links moderna</span>
                            </div>

                            <!-- Recurso 8: Migração Gratuita -->
                            <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 hover:border-slate-700 transition group cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 flex items-center justify-center mb-2.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                </div>
                                <span class="font-bold text-xs text-white block group-hover:text-indigo-400 transition">Migração Gratuita</span>
                                <span class="text-[10px] text-slate-400 leading-tight block mt-0.5">Equipe técnica transfere tudo</span>
                            </div>
                        </div>
                    </div>

                    <!-- Seção 3: Suporte & Chamados Recentes -->
                    <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800/90 rounded-3xl p-6 sm:p-7 shadow-2xl">
                        <div class="flex items-center justify-between mb-5 border-b border-slate-800/80 pb-4">
                            <h2 class="text-base font-black text-white flex items-center gap-2">
                                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                <span>Suporte & Chamados Recentes</span>
                            </h2>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('tickets.index') }}" class="text-xs font-bold text-slate-400 hover:text-white transition">
                                    Ver todos ({{ $ticketsCount }})
                                </a>
                                <a href="{{ route('tickets.create') }}" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-white font-bold text-xs transition shadow-sm">
                                    Abrir ticket
                                </a>
                            </div>
                        </div>

                        <div class="space-y-3">
                            @forelse ($recentTickets as $ticket)
                                <a href="{{ route('tickets.show', $ticket) }}" 
                                   class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:border-slate-700 transition block group">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-xs text-white group-hover:text-cyan-400 transition">
                                                #{{ $ticket->ticket_number }} - {{ $ticket->subject }}
                                            </span>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $ticket->status_badge_classes }}">
                                                {{ $ticket->status_label }}
                                            </span>
                                        </div>
                                        <span class="text-[11px] text-slate-400 block mt-1">
                                            Última atualização: {{ $ticket->updated_at->format('d/m/Y (H:i)') }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-cyan-400 font-bold group-hover:translate-x-1 transition-transform">
                                        &rarr;
                                    </div>
                                </a>
                            @empty
                                <div class="p-6 text-center text-slate-500 text-xs">
                                    Nenhum chamado aberto recentemente.
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                <!-- Coluna Direita (4 colunas) -->
                <div class="lg:col-span-4 space-y-8">

                    <!-- Widget 1: Registrar um Domínio -->
                    <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800/90 rounded-3xl p-6 sm:p-7 shadow-2xl" 
                         x-data="{
                            domainQuery: '',
                            checked: false,
                            available: null,
                            checkDomain() {
                                if (!this.domainQuery) return;
                                this.checked = true;
                                this.available = true;
                            }
                         }">
                        <div class="flex items-center gap-2.5 mb-2">
                            <div class="w-8 h-8 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                            </div>
                            <h3 class="font-bold text-sm text-white">Registrar um domínio</h3>
                        </div>
                        <p class="text-xs text-slate-400 leading-relaxed mb-4">
                            Verifique a disponibilidade e registre ou transfira em segundos.
                        </p>

                        <div class="space-y-3">
                            <div>
                                <input type="text" 
                                       x-model="domainQuery" 
                                       placeholder="seudominio.com.br"
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-800 text-white bg-slate-950/80 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition placeholder-slate-600 shadow-inner">
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" 
                                        @click="checkDomain()" 
                                        class="w-full py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs uppercase tracking-wider transition shadow-lg shadow-emerald-500/20">
                                    Registre-se
                                </button>
                                <button type="button" 
                                        @click="checkDomain()" 
                                        class="w-full py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-white font-bold text-xs uppercase tracking-wider transition">
                                    Transferir
                                </button>
                            </div>
                        </div>

                        <!-- Feedback de Disponibilidade Dinâmico -->
                        <div x-show="checked" style="display: none;" class="mt-4 p-3.5 rounded-xl bg-emerald-950/40 border border-emerald-500/40 text-emerald-200 text-xs space-y-1">
                            <span class="font-bold block flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                Domínio Disponível!
                            </span>
                            <span class="text-[11px] text-emerald-300/80 block">
                                <code class="font-bold text-emerald-300" x-text="domainQuery"></code> está livre para registro imediato na nuvem HostDevPro.
                            </span>
                        </div>
                    </div>

                    <!-- Widget 2: Notícias & Comunicados -->
                    <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800/90 rounded-3xl p-6 sm:p-7 shadow-2xl">
                        <div class="flex items-center justify-between mb-5 border-b border-slate-800/80 pb-3">
                            <h3 class="font-bold text-sm text-white flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                <span>Notícias</span>
                            </h3>
                            <span class="text-xs text-slate-500">Ver todas</span>
                        </div>

                        <div class="space-y-4">
                            @foreach ($news as $item)
                                <div class="space-y-1">
                                    <span class="font-bold text-xs text-slate-200 leading-snug hover:text-emerald-400 transition cursor-pointer block">
                                        {{ $item['title'] }}
                                    </span>
                                    <div class="flex items-center gap-2 text-[10px] text-slate-500">
                                        <span>{{ $item['date'] }}</span>
                                        <span>&bull;</span>
                                        <span class="text-emerald-400 font-bold">{{ $item['category'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Script de Inicialização dos Gráficos com Chart.js -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('performanceAnalytics', () => ({
                period: '24h',
                bandwidthPeak: '342.6 Mbps',
                trafficChart: null,
                latencyChart: null,
                cacheChart: null,

                init() {
                    this.$nextTick(() => {
                        this.initCharts();
                    });
                },

                setPeriod(p) {
                    this.period = p;
                    if (p === '24h') {
                        this.bandwidthPeak = '342.6 Mbps';
                        this.updateTrafficChart(
                            ['00:00', '03:00', '06:00', '09:00', '12:00', '15:00', '18:00', '21:00', 'Agora'],
                            [85, 62, 54, 142, 218, 342, 280, 195, 142],
                            [42, 31, 28, 71, 109, 171, 140, 97, 71]
                        );
                    } else if (p === '7d') {
                        this.bandwidthPeak = '485.1 Mbps';
                        this.updateTrafficChart(
                            ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                            [210, 245, 310, 290, 485, 380, 260],
                            [105, 122, 155, 145, 242, 190, 130]
                        );
                    } else if (p === '30d') {
                        this.bandwidthPeak = '520.4 Mbps';
                        this.updateTrafficChart(
                            ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
                            [320, 410, 520, 390],
                            [160, 205, 260, 195]
                        );
                    }
                },

                initCharts() {
                    const ctxTraffic = document.getElementById('networkTrafficChart')?.getContext('2d');
                    if (ctxTraffic) {
                        // Gradiente esmeralda neon
                        const gradientEmerald = ctxTraffic.createLinearGradient(0, 0, 0, 260);
                        gradientEmerald.addColorStop(0, 'rgba(16, 185, 129, 0.35)');
                        gradientEmerald.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

                        // Gradiente ciano neon
                        const gradientCyan = ctxTraffic.createLinearGradient(0, 0, 0, 260);
                        gradientCyan.addColorStop(0, 'rgba(6, 182, 212, 0.25)');
                        gradientCyan.addColorStop(1, 'rgba(6, 182, 212, 0.0)');

                        this.trafficChart = new Chart(ctxTraffic, {
                            type: 'line',
                            data: {
                                labels: ['00:00', '03:00', '06:00', '09:00', '12:00', '15:00', '18:00', '21:00', 'Agora'],
                                datasets: [
                                    {
                                        label: 'Download / Entrada (Mbps)',
                                        data: [85, 62, 54, 142, 218, 342, 280, 195, 142],
                                        borderColor: '#10b981',
                                        backgroundColor: gradientEmerald,
                                        borderWidth: 2.5,
                                        tension: 0.4,
                                        fill: true,
                                        pointBackgroundColor: '#10b981',
                                        pointBorderColor: '#020617',
                                        pointHoverRadius: 6,
                                    },
                                    {
                                        label: 'Upload / Saída (Mbps)',
                                        data: [42, 31, 28, 71, 109, 171, 140, 97, 71],
                                        borderColor: '#06b6d4',
                                        backgroundColor: gradientCyan,
                                        borderWidth: 2,
                                        tension: 0.4,
                                        fill: true,
                                        pointBackgroundColor: '#06b6d4',
                                        pointBorderColor: '#020617',
                                        pointHoverRadius: 5,
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top',
                                        align: 'end',
                                        labels: {
                                            color: '#94a3b8',
                                            font: { size: 11, weight: 'bold' },
                                            boxWidth: 10,
                                            usePointStyle: true
                                        }
                                    },
                                    tooltip: {
                                        backgroundColor: '#0f172a',
                                        titleColor: '#f8fafc',
                                        bodyColor: '#94a3b8',
                                        borderColor: '#334155',
                                        borderWidth: 1,
                                        padding: 12,
                                        boxPadding: 6,
                                        usePointStyle: true,
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: { color: 'rgba(51, 65, 85, 0.3)' },
                                        ticks: { color: '#64748b', font: { size: 10 } }
                                    },
                                    y: {
                                        grid: { color: 'rgba(51, 65, 85, 0.3)' },
                                        ticks: { color: '#64748b', font: { size: 10 } }
                                    }
                                }
                            }
                        });
                    }

                    // Gráfico 2: Latência Edge
                    const ctxLatency = document.getElementById('edgeLatencyChart')?.getContext('2d');
                    if (ctxLatency) {
                        this.latencyChart = new Chart(ctxLatency, {
                            type: 'bar',
                            data: {
                                labels: ['São Paulo', 'Rio', 'Santiago', 'Miami', 'Frankfurt'],
                                datasets: [{
                                    label: 'Ping (ms)',
                                    data: [11, 15, 32, 62, 118],
                                    backgroundColor: [
                                        '#10b981',
                                        '#10b981',
                                        '#06b6d4',
                                        '#3b82f6',
                                        '#8b5cf6'
                                    ],
                                    borderRadius: 6
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: '#0f172a',
                                        titleColor: '#f8fafc',
                                        bodyColor: '#94a3b8',
                                        borderColor: '#334155',
                                        borderWidth: 1
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: { color: 'rgba(51, 65, 85, 0.3)' },
                                        ticks: { color: '#64748b', font: { size: 9 } }
                                    },
                                    y: {
                                        grid: { display: false },
                                        ticks: { color: '#cbd5e1', font: { size: 10, weight: 'bold' } }
                                    }
                                }
                            }
                        });
                    }

                    // Gráfico 3: Cache Hit Doughnut
                    const ctxCache = document.getElementById('cacheHitChart')?.getContext('2d');
                    if (ctxCache) {
                        this.cacheChart = new Chart(ctxCache, {
                            type: 'doughnut',
                            data: {
                                labels: ['Cache Hit', 'Cache Miss'],
                                datasets: [{
                                    data: [94.8, 5.2],
                                    backgroundColor: ['#10b981', '#334155'],
                                    borderWidth: 0,
                                    hoverOffset: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '78%',
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: '#0f172a',
                                        titleColor: '#f8fafc',
                                        bodyColor: '#94a3b8',
                                        borderColor: '#334155',
                                        borderWidth: 1
                                    }
                                }
                            }
                        });
                    }
                },

                updateTrafficChart(labels, downloadData, uploadData) {
                    if (this.trafficChart) {
                        this.trafficChart.data.labels = labels;
                        this.trafficChart.data.datasets[0].data = downloadData;
                        this.trafficChart.data.datasets[1].data = uploadData;
                        this.trafficChart.update();
                    }
                }
            }));
        });
    </script>
</x-app-layout>
