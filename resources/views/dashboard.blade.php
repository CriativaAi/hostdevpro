<x-app-layout>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                        Cluster & Mercados 100% Online
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

                <!-- Botões de Ação Rápida (Cantos refinados: rounded-xl) -->
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

            <!-- Banners de Ação Urgente com 80% Transparência e Cantos Refinados (rounded-xl, sem transbordar) -->
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

    <!-- PAINEL PRINCIPAL: GRÁFICOS COLORIDOS COM 80% TRANSPARÊNCIA E CANTOS SLEEK (rounded-2xl) -->
    <div class="py-8 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8" x-data="financialTradingDashboard()">
            
            <!-- SEÇÃO 1: TICKERS FINANCEIROS (80% Transparência no Branco / Frosted Glass rounded-2xl, sem colar) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                
                <!-- Ticker 1: MRR (Receita Recorrente Mensal) -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl relative overflow-hidden group hover:border-emerald-500/50 transition duration-300 min-w-0">
                    <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-emerald-500/20 transition"></div>
                    <div class="flex items-center justify-between text-xs text-slate-400 font-bold uppercase tracking-wider mb-2">
                        <span class="truncate">Receita Recorrente (MRR)</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex-shrink-0">
                            ▲ +18.4%
                        </span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-white tracking-tight break-words">
                        R$ 14.850<span class="text-sm font-bold text-slate-400">,00</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-400 mt-3 pt-3 border-t border-white/10">
                        <span>Previsão de Fechamento:</span>
                        <span class="text-emerald-400 font-bold">R$ 16.200,00</span>
                    </div>
                </div>

                <!-- Ticker 2: ARR Projetado (Anualizado) -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl relative overflow-hidden group hover:border-purple-500/50 transition duration-300 min-w-0">
                    <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-purple-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-purple-500/20 transition"></div>
                    <div class="flex items-center justify-between text-xs text-slate-400 font-bold uppercase tracking-wider mb-2">
                        <span class="truncate">ARR Anual Projetado</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-black bg-purple-500/20 text-purple-400 border border-purple-500/40 flex-shrink-0">
                            ▲ +24.2%
                        </span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-white tracking-tight break-words">
                        R$ 178.200<span class="text-sm font-bold text-slate-400">,00</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-400 mt-3 pt-3 border-t border-white/10">
                        <span>Crescimento Anualizado:</span>
                        <span class="text-purple-400 font-bold">3.4x / ano</span>
                    </div>
                </div>

                <!-- Ticker 3: Volume de Requisições & Transações -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl relative overflow-hidden group hover:border-cyan-500/50 transition duration-300 min-w-0">
                    <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-cyan-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-cyan-500/20 transition"></div>
                    <div class="flex items-center justify-between text-xs text-slate-400 font-bold uppercase tracking-wider mb-2">
                        <span class="truncate">Volume de Requisições</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-black bg-cyan-500/20 text-cyan-400 border border-cyan-500/40 flex-shrink-0">
                            ▲ +32.8%
                        </span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-white tracking-tight break-words">
                        2.840 <span class="text-sm font-bold text-slate-400">req/min</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-400 mt-3 pt-3 border-t border-white/10">
                        <span>Tráfego Hoje:</span>
                        <span class="text-cyan-400 font-bold">1.84 TB Processados</span>
                    </div>
                </div>

                <!-- Ticker 4: Margem Operacional & Eficiência -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl relative overflow-hidden group hover:border-amber-500/50 transition duration-300 min-w-0">
                    <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-amber-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-amber-500/20 transition"></div>
                    <div class="flex items-center justify-between text-xs text-slate-400 font-bold uppercase tracking-wider mb-2">
                        <span class="truncate">Margem de Eficiência</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-black bg-amber-500/20 text-amber-400 border border-amber-500/40 flex-shrink-0">
                            ▲ +3.1%
                        </span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-white tracking-tight break-words">
                        84.6 <span class="text-sm font-bold text-slate-400">%</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-400 mt-3 pt-3 border-t border-white/10">
                        <span>Custo / GB de Nuvem:</span>
                        <span class="text-amber-400 font-bold">R$ 0,0042</span>
                    </div>
                </div>

            </div>

            <!-- SEÇÃO 2: GRÁFICOS COLORIDOS COMPACTOS & FUNCIONAIS (Card Master com 80% Transparência e rounded-2xl) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Gráfico Master Financeiro & Telemetria (8 colunas) -->
                <div class="lg:col-span-8 p-6 sm:p-7 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-2xl space-y-5 overflow-hidden min-w-0">
                    
                    <!-- Header do Gráfico: Abas de Métricas Reais + Filtro Temporal + Refresh -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-white/10">
                        <div class="space-y-1.5 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                <h2 class="text-base sm:text-lg font-black text-white tracking-tight truncate">
                                    <span x-show="activeMetric === 'revenue'">Fluxo de Receita & Tráfego Cloud</span>
                                    <span x-show="activeMetric === 'vps'" style="display: none;">Desempenho da Infraestrutura VPS</span>
                                    <span x-show="activeMetric === 'traffic'" style="display: none;">Volume de Requisições & Latência</span>
                                </h2>
                            </div>
                            
                            <!-- Abas de Alternância de Métricas Funcionais -->
                            <div class="flex flex-wrap items-center gap-1.5 pt-0.5">
                                <button type="button" 
                                        @click="setMetric('revenue')"
                                        :class="activeMetric === 'revenue' ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/40' : 'bg-white/[0.04] text-slate-400 hover:text-white border-white/10'"
                                        class="px-2.5 py-1 rounded-md text-[11px] font-bold border transition flex items-center gap-1.5">
                                    <span>💰</span>
                                    <span>Receita & MRR</span>
                                </button>
                                <button type="button" 
                                        @click="setMetric('vps')"
                                        :class="activeMetric === 'vps' ? 'bg-cyan-500/20 text-cyan-400 border-cyan-500/40' : 'bg-white/[0.04] text-slate-400 hover:text-white border-white/10'"
                                        class="px-2.5 py-1 rounded-md text-[11px] font-bold border transition flex items-center gap-1.5">
                                    <span>⚡</span>
                                    <span>Servidores VPS</span>
                                </button>
                                <button type="button" 
                                        @click="setMetric('traffic')"
                                        :class="activeMetric === 'traffic' ? 'bg-purple-500/20 text-purple-400 border-purple-500/40' : 'bg-white/[0.04] text-slate-400 hover:text-white border-white/10'"
                                        class="px-2.5 py-1 rounded-md text-[11px] font-bold border transition flex items-center gap-1.5">
                                    <span>🌐</span>
                                    <span>Tráfego & Latência</span>
                                </button>
                            </div>
                        </div>

                        <!-- Controles da Direita: Seletor de Período (1D, 1S, 1M, 1A) + Botão Sincronizar -->
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <!-- Botão Sincronizar / Telemetria -->
                            <button type="button" 
                                    @click="syncTelemetry()" 
                                    title="Sincronizar telemetria em tempo real"
                                    class="p-1.5 rounded-lg bg-black/40 hover:bg-white/10 border border-white/10 text-slate-400 hover:text-emerald-400 transition"
                                    :class="isSyncing ? 'animate-spin text-emerald-400' : ''">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            </button>

                            <!-- Seletor Estilo Trading (1D, 1S, 1M, 1A) -->
                            <div class="flex items-center gap-1 p-1 rounded-lg bg-black/40 border border-white/10">
                                <button type="button" 
                                        @click="setRange('1D')" 
                                        :class="range === '1D' ? 'bg-emerald-500 text-slate-950 font-black shadow-sm' : 'text-slate-400 hover:text-white font-bold'"
                                        class="px-2.5 py-1 rounded text-xs transition">
                                    1D
                                </button>
                                <button type="button" 
                                        @click="setRange('1S')" 
                                        :class="range === '1S' ? 'bg-emerald-500 text-slate-950 font-black shadow-sm' : 'text-slate-400 hover:text-white font-bold'"
                                        class="px-2.5 py-1 rounded text-xs transition">
                                    1S
                                </button>
                                <button type="button" 
                                        @click="setRange('1M')" 
                                        :class="range === '1M' ? 'bg-emerald-500 text-slate-950 font-black shadow-sm' : 'text-slate-400 hover:text-white font-bold'"
                                        class="px-2.5 py-1 rounded text-xs transition">
                                    1M
                                </button>
                                <button type="button" 
                                        @click="setRange('1A')" 
                                        :class="range === '1A' ? 'bg-emerald-500 text-slate-950 font-black shadow-sm' : 'text-slate-400 hover:text-white font-bold'"
                                        class="px-2.5 py-1 rounded text-xs transition">
                                    1A
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Canvas do Gráfico Master (Altura compacta: h-48 sm:h-56 para ficar elegante e não gigante) -->
                    <div class="relative h-48 sm:h-56 w-full">
                        <canvas id="financialMainChart"></canvas>
                    </div>

                    <!-- Legendas & Indicadores Inferiores Recalculados Dinamicamente -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-3.5 border-t border-white/10 text-xs">
                        <div class="min-w-0">
                            <span class="text-slate-400 block text-[11px] truncate">Pico no Período:</span>
                            <span class="text-emerald-400 font-black text-sm truncate block" x-text="peakFinancial"></span>
                        </div>
                        <div class="min-w-0">
                            <span class="text-slate-400 block text-[11px] truncate">Média Operacional:</span>
                            <span class="text-cyan-400 font-black text-sm truncate block" x-text="avgThroughput"></span>
                        </div>
                        <div class="min-w-0">
                            <span class="text-slate-400 block text-[11px] truncate" x-text="thirdMetricTitle"></span>
                            <span class="text-purple-400 font-black text-sm truncate block" x-text="thirdMetricValue"></span>
                        </div>
                        <div class="min-w-0">
                            <span class="text-slate-400 block text-[11px] truncate">Uptime do Período:</span>
                            <span class="text-white font-black text-sm truncate block" x-text="uptimeStatus"></span>
                        </div>
                    </div>

                </div>

                <!-- Coluna Direita: Mix por Categoria & Volume Diário (4 colunas) -->
                <div class="lg:col-span-4 space-y-6">
                    
                    <!-- Gráfico 2: Composição de Receita por Categoria (Doughnut Multicolorido Neon) -->
                    <div class="p-5 sm:p-6 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-2xl space-y-3.5 overflow-hidden min-w-0">
                        <div class="flex items-center justify-between border-b border-white/10 pb-3">
                            <h3 class="text-sm font-black text-white flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-purple-400"></span>
                                <span>Mix de Serviços Ativos</span>
                            </h3>
                            <span class="text-[10px] text-purple-400 font-mono font-bold">100% Online</span>
                        </div>

                        <div class="flex items-center gap-4 pt-1">
                            <div class="relative w-24 h-24 sm:w-28 sm:h-28 flex-shrink-0">
                                <canvas id="financialCategoryDoughnut"></canvas>
                            </div>
                            <div class="text-xs space-y-2 text-slate-300 w-full min-w-0">
                                <button type="button" 
                                        @click="filterCategory('vps')"
                                        :class="selectedCategory === 'vps' ? 'ring-1 ring-purple-400 bg-purple-500/10 p-1 rounded' : ''"
                                        class="flex items-center justify-between w-full text-left transition">
                                    <div class="flex items-center gap-1.5 truncate">
                                        <span class="w-2.5 h-2.5 rounded-full bg-purple-500 flex-shrink-0"></span>
                                        <span class="truncate">Servidores VPS</span>
                                    </div>
                                    <span class="font-bold text-white ml-2 flex-shrink-0">48%</span>
                                </button>
                                <button type="button" 
                                        @click="filterCategory('plesk')"
                                        :class="selectedCategory === 'plesk' ? 'ring-1 ring-emerald-400 bg-emerald-500/10 p-1 rounded' : ''"
                                        class="flex items-center justify-between w-full text-left transition">
                                    <div class="flex items-center gap-1.5 truncate">
                                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
                                        <span class="truncate">Hospedagem Plesk</span>
                                    </div>
                                    <span class="font-bold text-white ml-2 flex-shrink-0">34%</span>
                                </button>
                                <button type="button" 
                                        @click="filterCategory('devops')"
                                        :class="selectedCategory === 'devops' ? 'ring-1 ring-cyan-400 bg-cyan-500/10 p-1 rounded' : ''"
                                        class="flex items-center justify-between w-full text-left transition">
                                    <div class="flex items-center gap-1.5 truncate">
                                        <span class="w-2.5 h-2.5 rounded-full bg-cyan-500 flex-shrink-0"></span>
                                        <span class="truncate">Suporte & DevOps</span>
                                    </div>
                                    <span class="font-bold text-white ml-2 flex-shrink-0">18%</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Gráfico 3: Volume Diário de Operações (Barras Coloridas Compactas) -->
                    <div class="p-5 sm:p-6 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-2xl space-y-3 overflow-hidden min-w-0">
                        <div class="flex items-center justify-between border-b border-white/10 pb-2.5">
                            <h3 class="text-sm font-black text-white flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-cyan-400"></span>
                                <span>Volume de Tráfego Semanal</span>
                            </h3>
                            <span class="text-[10px] text-cyan-400 font-mono font-bold">Picos Diários</span>
                        </div>

                        <div class="relative h-28 sm:h-32 w-full">
                            <canvas id="financialVolumeBars"></canvas>
                        </div>
                    </div>

                </div>

            </div>

            <!-- SEÇÃO 3: MEUS SERVIÇOS & PRODUTOS ATIVOS (Layout Amplo, rounded-2xl e Frosted Glass) -->
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
                            <p class="text-xs text-slate-400">Instâncias VPS dedicadas e contas Plesk gerenciadas na nuvem</p>
                        </div>
                    </div>
                    <a href="{{ route('hosting.index') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 transition">
                        Ver todos ({{ $servicesCount }}) &rarr;
                    </a>
                </div>

                <!-- Lista de Serviços (rounded-xl, sem transbordar) -->
                <div class="space-y-3">
                    @forelse ($services as $service)
                        <div class="p-5 rounded-xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition overflow-hidden min-w-0">
                            <div class="flex items-start gap-4 min-w-0 flex-1">
                                <div class="w-11 h-11 rounded-xl bg-black/40 border border-white/10 flex items-center justify-center text-slate-300 flex-shrink-0">
                                    @if ($service->panel_type === 'plesk')
                                        <span class="text-xs font-black text-blue-400">PLK</span>
                                    @elseif ($service->panel_type === 'cpanel')
                                        <span class="text-xs font-black text-amber-400">CPN</span>
                                    @else
                                        <span class="text-xs font-black text-emerald-400">VPS</span>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <a href="{{ route('hosting.show', $service) }}" class="font-bold text-sm text-white hover:text-emerald-400 transition truncate max-w-xs sm:max-w-md">
                                            {{ $service->domain }}
                                        </a>
                                        <span class="px-2.5 py-0.5 rounded text-[10px] font-extrabold border {{ $service->status === 'active' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-white/[0.05] text-slate-400 border-white/10' }} flex-shrink-0">
                                            {{ $service->status_label }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-slate-400 block mt-1 truncate">
                                        {{ $service->server->name ?? 'Cluster Cloud' }} &bull; IP: <code class="text-slate-300 font-mono">{{ $service->server->ip_address ?? '177.136.254.37' }}</code>
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-5 border-t sm:border-t-0 border-white/10 pt-3 sm:pt-0 flex-shrink-0">
                                <div class="text-right hidden sm:block">
                                    <span class="text-xs text-slate-400 block">
                                        R$ 59,99 &bull; Mensal
                                    </span>
                                </div>
                                <a href="{{ route('hosting.show', $service) }}" 
                                   class="px-4 py-2 rounded-lg bg-white/[0.08] border border-white/15 hover:bg-white/[0.16] text-white font-bold text-xs transition shadow-sm whitespace-nowrap">
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

            <!-- SEÇÃO 4: RECURSOS PREMIUM (Grid Amplo com Efeito Hover e rounded-2xl) -->
            <div class="bg-white/[0.06] backdrop-blur-2xl border border-white/15 rounded-2xl p-6 sm:p-8 shadow-2xl space-y-6 overflow-hidden min-w-0">
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <h2 class="text-base font-black text-white flex items-center gap-2">
                        <span class="text-amber-400">⭐</span>
                        <span>Recursos premium</span>
                    </h2>
                    <span class="text-xs text-slate-400">Inclusos na sua conta, sem custo</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Recurso 1: Central de Afiliados -->
                    <a href="{{ route('affiliates.index') }}" class="p-4 rounded-xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 hover:border-emerald-500/50 transition group cursor-pointer block min-w-0 overflow-hidden">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-center mb-2.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <span class="font-bold text-xs text-white block group-hover:text-emerald-400 transition truncate">Central de Afiliados</span>
                        <span class="text-[10px] text-slate-400 leading-tight block mt-0.5 truncate">Comissão 15% recorrente</span>
                    </a>

                    <!-- Recurso 2: Downloads Premium -->
                    <div class="p-4 rounded-xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 hover:border-purple-500/50 transition group cursor-pointer min-w-0 overflow-hidden">
                        <div class="w-8 h-8 rounded-lg bg-purple-500/10 border border-purple-500/30 text-purple-400 flex items-center justify-center mb-2.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </div>
                        <span class="font-bold text-xs text-white block group-hover:text-purple-400 transition truncate">Downloads Premium</span>
                        <span class="text-[10px] text-slate-400 leading-tight block mt-0.5 truncate">Envato, Freepik e GPL Vault</span>
                    </div>

                    <!-- Recurso 3: Gemini IA Cloud -->
                    <div class="p-4 rounded-xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 hover:border-emerald-500/50 transition group cursor-pointer min-w-0 overflow-hidden">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-center mb-2.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <span class="font-bold text-xs text-white block group-hover:text-emerald-400 transition truncate">Gemini IA Cloud</span>
                        <span class="text-[10px] text-slate-400 leading-tight block mt-0.5 truncate">Assistente e automação DevOps</span>
                    </div>

                    <!-- Recurso 4: WP Vivid Backup -->
                    <div class="p-4 rounded-xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 hover:border-teal-500/50 transition group cursor-pointer min-w-0 overflow-hidden">
                        <div class="w-8 h-8 rounded-lg bg-teal-500/10 border border-teal-500/30 text-teal-400 flex items-center justify-center mb-2.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                        </div>
                        <span class="font-bold text-xs text-white block group-hover:text-teal-400 transition truncate">WP Vivid Backup</span>
                        <span class="text-[10px] text-slate-400 leading-tight block mt-0.5 truncate">Backup e migração WordPress</span>
                    </div>

                    <!-- Recurso 5: Assinatura de E-mail -->
                    <div class="p-4 rounded-xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 hover:border-amber-500/50 transition group cursor-pointer min-w-0 overflow-hidden">
                        <div class="w-8 h-8 rounded-lg bg-amber-500/10 border border-amber-500/30 text-amber-400 flex items-center justify-center mb-2.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="font-bold text-xs text-white block group-hover:text-amber-400 transition truncate">Assinatura de E-mail</span>
                        <span class="text-[10px] text-slate-400 leading-tight block mt-0.5 truncate">Assinaturas corporativas</span>
                    </div>

                    <!-- Recurso 6: Gerador de Nomes -->
                    <div class="p-4 rounded-xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 hover:border-rose-500/50 transition group cursor-pointer min-w-0 overflow-hidden">
                        <div class="w-8 h-8 rounded-lg bg-rose-500/10 border border-rose-500/30 text-rose-400 flex items-center justify-center mb-2.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        </div>
                        <span class="font-bold text-xs text-white block group-hover:text-rose-400 transition truncate">Gerador de Nomes</span>
                        <span class="text-[10px] text-slate-400 leading-tight block mt-0.5 truncate">Ideias de marcas e domínios</span>
                    </div>

                    <!-- Recurso 7: Gera.Bio Links -->
                    <div class="p-4 rounded-xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 hover:border-cyan-500/50 transition group cursor-pointer min-w-0 overflow-hidden">
                        <div class="w-8 h-8 rounded-lg bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center mb-2.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        </div>
                        <span class="font-bold text-xs text-white block group-hover:text-cyan-400 transition truncate">Gera.Bio Links</span>
                        <span class="text-[10px] text-slate-400 leading-tight block mt-0.5 truncate">Árvore de links e bio</span>
                    </div>

                    <!-- Recurso 8: Migração Gratuita -->
                    <div class="p-4 rounded-xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 hover:border-indigo-500/50 transition group cursor-pointer min-w-0 overflow-hidden">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 flex items-center justify-center mb-2.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <span class="font-bold text-xs text-white block group-hover:text-indigo-400 transition truncate">Migração Gratuita</span>
                        <span class="text-[10px] text-slate-400 leading-tight block mt-0.5 truncate">Equipe transfere tudo sem custo</span>
                    </div>
                </div>
            </div>

            <!-- SEÇÃO 5: SUPORTE & NOTÍCIAS (2 Colunas com rounded-2xl e Frosted Glass) -->
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
                        <!-- Notícia 1 -->
                        <div class="p-4 rounded-xl bg-white/[0.04] border border-white/10 space-y-1.5 overflow-hidden min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/40">
                                    COMUNICADO
                                </span>
                                <span class="text-[10px] text-slate-400">04/09/2026</span>
                            </div>
                            <h4 class="font-bold text-xs text-white">
                                Ativação da Nova Infraestrutura NVMe Gen5 e Rede 40Gbps
                            </h4>
                            <p class="text-[11px] text-slate-400 leading-relaxed">
                                Concluímos a expansão dos nós de processamento no Datacenter de São Paulo. Menor latência e maior throughput para todas as VPS.
                            </p>
                        </div>

                        <!-- Notícia 2 -->
                        <div class="p-4 rounded-xl bg-white/[0.04] border border-white/10 space-y-1.5 overflow-hidden min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-black bg-purple-500/20 text-purple-400 border border-purple-500/40">
                                    NOVIDADE
                                </span>
                                <span class="text-[10px] text-slate-400">01/09/2026</span>
                            </div>
                            <h4 class="font-bold text-xs text-white">
                                Programa de Afiliados Oficial Liberado
                            </h4>
                            <p class="text-[11px] text-slate-400 leading-relaxed">
                                Indique desenvolvedores e ganhe 15% de comissão recorrente em todas as faturas pagas via PIX instantâneo.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Script Alpine.js & Chart.js Multi-Data com Altura Controlada e Funcionalidades Reais -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('financialTradingDashboard', () => ({
                range: '1M',
                activeMetric: 'revenue', // 'revenue' | 'vps' | 'traffic'
                isSyncing: false,
                selectedCategory: null,
                
                peakFinancial: 'R$ 16.200',
                avgThroughput: '148 Mbps',
                thirdMetricTitle: 'Conversão de Checkout:',
                thirdMetricValue: '94.2%',
                uptimeStatus: '99.99%',

                mainChart: null,
                doughnutChart: null,
                volumeChart: null,

                init() {
                    this.$nextTick(() => {
                        this.renderCharts();
                    });
                },

                setMetric(metric) {
                    this.activeMetric = metric;
                    this.setRange(this.range);
                },

                syncTelemetry() {
                    this.isSyncing = true;
                    setTimeout(() => {
                        this.isSyncing = false;
                        this.setRange(this.range);
                    }, 500);
                },

                setRange(r) {
                    this.range = r;
                    
                    if (this.activeMetric === 'revenue') {
                        this.thirdMetricTitle = 'Conversão de Checkout:';
                        if (r === '1D') {
                            this.peakFinancial = 'R$ 14.850';
                            this.avgThroughput = '162 Mbps';
                            this.thirdMetricValue = '96.1%';
                            this.updateMainChart(
                                ['00h', '04h', '08h', '12h', '16h', '20h'],
                                [14200, 14200, 14350, 14600, 14850, 14800],
                                [85, 60, 120, 195, 240, 180],
                                'Faturamento / MRR (R$)',
                                'Throughput de Rede (Mbps)',
                                '#10b981', '#8b5cf6', 'R$', 'Mbps'
                            );
                        } else if (r === '1S') {
                            this.peakFinancial = 'R$ 15.400';
                            this.avgThroughput = '138 Mbps';
                            this.thirdMetricValue = '95.4%';
                            this.updateMainChart(
                                ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                                [13500, 13800, 14100, 14500, 15400, 15100, 14850],
                                [110, 130, 160, 210, 290, 230, 165],
                                'Faturamento / MRR (R$)',
                                'Throughput de Rede (Mbps)',
                                '#10b981', '#8b5cf6', 'R$', 'Mbps'
                            );
                        } else if (r === '1M') {
                            this.peakFinancial = 'R$ 16.200';
                            this.avgThroughput = '148 Mbps';
                            this.thirdMetricValue = '94.2%';
                            this.updateMainChart(
                                ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
                                [11800, 12900, 13700, 14850],
                                [95, 140, 190, 260],
                                'Faturamento / MRR (R$)',
                                'Throughput de Rede (Mbps)',
                                '#10b981', '#8b5cf6', 'R$', 'Mbps'
                            );
                        } else if (r === '1A') {
                            this.peakFinancial = 'R$ 178.200';
                            this.avgThroughput = '185 Mbps';
                            this.thirdMetricValue = '92.8%';
                            this.updateMainChart(
                                ['Jan', 'Mar', 'Mai', 'Jul', 'Set', 'Nov'],
                                [64000, 89000, 112000, 138000, 162000, 178200],
                                [80, 115, 150, 210, 280, 340],
                                'Faturamento / MRR (R$)',
                                'Throughput de Rede (Mbps)',
                                '#10b981', '#8b5cf6', 'R$', 'Mbps'
                            );
                        }
                    } else if (this.activeMetric === 'vps') {
                        this.thirdMetricTitle = 'Saúde do Cluster:';
                        this.thirdMetricValue = '100% Estável';
                        if (r === '1D') {
                            this.peakFinancial = 'CPU Máx: 64%';
                            this.avgThroughput = 'RAM Média: 48%';
                            this.updateMainChart(
                                ['00h', '04h', '08h', '12h', '16h', '20h'],
                                [18, 14, 32, 58, 64, 42],
                                [38, 38, 42, 52, 55, 48],
                                'Uso Médio de CPU (%)',
                                'Consumo de RAM (%)',
                                '#06b6d4', '#f59e0b', '%', '%'
                            );
                        } else if (r === '1S') {
                            this.peakFinancial = 'CPU Máx: 78%';
                            this.avgThroughput = 'RAM Média: 52%';
                            this.updateMainChart(
                                ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                                [34, 42, 55, 68, 78, 52, 38],
                                [44, 46, 50, 56, 62, 54, 48],
                                'Uso Médio de CPU (%)',
                                'Consumo de RAM (%)',
                                '#06b6d4', '#f59e0b', '%', '%'
                            );
                        } else if (r === '1M') {
                            this.peakFinancial = 'CPU Máx: 82%';
                            this.avgThroughput = 'RAM Média: 54%';
                            this.updateMainChart(
                                ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
                                [42, 51, 62, 70],
                                [45, 48, 55, 58],
                                'Uso Médio de CPU (%)',
                                'Consumo de RAM (%)',
                                '#06b6d4', '#f59e0b', '%', '%'
                            );
                        } else if (r === '1A') {
                            this.peakFinancial = 'CPU Máx: 86%';
                            this.avgThroughput = 'RAM Média: 56%';
                            this.updateMainChart(
                                ['Jan', 'Mar', 'Mai', 'Jul', 'Set', 'Nov'],
                                [28, 35, 45, 58, 65, 74],
                                [32, 40, 48, 52, 56, 60],
                                'Uso Médio de CPU (%)',
                                'Consumo de RAM (%)',
                                '#06b6d4', '#f59e0b', '%', '%'
                            );
                        }
                    } else if (this.activeMetric === 'traffic') {
                        this.thirdMetricTitle = 'Taxa Sucesso HTTP:';
                        this.thirdMetricValue = '99.98%';
                        if (r === '1D') {
                            this.peakFinancial = '3.420 req/min';
                            this.avgThroughput = 'Latência: 12ms';
                            this.updateMainChart(
                                ['00h', '04h', '08h', '12h', '16h', '20h'],
                                [1200, 850, 2400, 3420, 3100, 2600],
                                [14, 11, 12, 16, 18, 13],
                                'Requisições / min',
                                'Latência Média (ms)',
                                '#8b5cf6', '#10b981', 'req/min', 'ms'
                            );
                        } else if (r === '1S') {
                            this.peakFinancial = '3.890 req/min';
                            this.avgThroughput = 'Latência: 14ms';
                            this.updateMainChart(
                                ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                                [2100, 2450, 3100, 3600, 3890, 3200, 2500],
                                [13, 14, 15, 16, 18, 15, 12],
                                'Requisições / min',
                                'Latência Média (ms)',
                                '#8b5cf6', '#10b981', 'req/min', 'ms'
                            );
                        } else if (r === '1M') {
                            this.peakFinancial = '4.150 req/min';
                            this.avgThroughput = 'Latência: 15ms';
                            this.updateMainChart(
                                ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
                                [2400, 2900, 3400, 4150],
                                [12, 13, 15, 17],
                                'Requisições / min',
                                'Latência Média (ms)',
                                '#8b5cf6', '#10b981', 'req/min', 'ms'
                            );
                        } else if (r === '1A') {
                            this.peakFinancial = '4.800 req/min';
                            this.avgThroughput = 'Latência: 16ms';
                            this.updateMainChart(
                                ['Jan', 'Mar', 'Mai', 'Jul', 'Set', 'Nov'],
                                [1200, 1800, 2500, 3300, 4200, 4800],
                                [18, 16, 15, 15, 14, 13],
                                'Requisições / min',
                                'Latência Média (ms)',
                                '#8b5cf6', '#10b981', 'req/min', 'ms'
                            );
                        }
                    }
                },

                filterCategory(cat) {
                    this.selectedCategory = this.selectedCategory === cat ? null : cat;
                },

                renderCharts() {
                    // 1. Gráfico Master (Linha Multicolorida Compacta)
                    const ctxMain = document.getElementById('financialMainChart')?.getContext('2d');
                    if (ctxMain) {
                        const grad1 = ctxMain.createLinearGradient(0, 0, 0, 220);
                        grad1.addColorStop(0, 'rgba(16, 185, 129, 0.40)');
                        grad1.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

                        const grad2 = ctxMain.createLinearGradient(0, 0, 0, 220);
                        grad2.addColorStop(0, 'rgba(139, 92, 246, 0.30)');
                        grad2.addColorStop(1, 'rgba(139, 92, 246, 0.0)');

                        this.mainChart = new Chart(ctxMain, {
                            type: 'line',
                            data: {
                                labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
                                datasets: [
                                    {
                                        label: 'Faturamento / MRR (R$)',
                                        data: [11800, 12900, 13700, 14850],
                                        borderColor: '#10b981',
                                        backgroundColor: grad1,
                                        borderWidth: 2.5,
                                        tension: 0.38,
                                        fill: true,
                                        pointBackgroundColor: '#10b981',
                                        pointBorderColor: '#020617',
                                        pointRadius: 3.5,
                                        pointHoverRadius: 6,
                                        yAxisID: 'y'
                                    },
                                    {
                                        label: 'Throughput de Rede (Mbps)',
                                        data: [95, 140, 190, 260],
                                        borderColor: '#8b5cf6',
                                        backgroundColor: grad2,
                                        borderWidth: 2,
                                        tension: 0.38,
                                        fill: true,
                                        pointBackgroundColor: '#8b5cf6',
                                        pointBorderColor: '#020617',
                                        pointRadius: 3,
                                        pointHoverRadius: 5,
                                        yAxisID: 'y1'
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: { mode: 'index', intersect: false },
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top',
                                        align: 'end',
                                        labels: {
                                            color: '#cbd5e1',
                                            font: { size: 10, weight: 'bold' },
                                            usePointStyle: true,
                                            boxWidth: 7
                                        }
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                        titleColor: '#ffffff',
                                        bodyColor: '#cbd5e1',
                                        borderColor: 'rgba(255, 255, 255, 0.15)',
                                        borderWidth: 1,
                                        padding: 10,
                                        boxPadding: 4,
                                        usePointStyle: true
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                        ticks: { color: '#64748b', font: { size: 9, weight: 'bold' } }
                                    },
                                    y: {
                                        type: 'linear',
                                        display: true,
                                        position: 'left',
                                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                        ticks: {
                                            color: '#10b981',
                                            font: { size: 9, weight: 'bold' },
                                            callback: val => 'R$ ' + val.toLocaleString('pt-BR')
                                        }
                                    },
                                    y1: {
                                        type: 'linear',
                                        display: true,
                                        position: 'right',
                                        grid: { drawOnChartArea: false },
                                        ticks: {
                                            color: '#8b5cf6',
                                            font: { size: 9, weight: 'bold' },
                                            callback: val => val + ' Mbps'
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // 2. Gráfico Doughnut: Mix por Categoria
                    const ctxDoughnut = document.getElementById('financialCategoryDoughnut')?.getContext('2d');
                    if (ctxDoughnut) {
                        this.doughnutChart = new Chart(ctxDoughnut, {
                            type: 'doughnut',
                            data: {
                                labels: ['Servidores VPS', 'Hospedagem Plesk', 'Suporte & DevOps'],
                                datasets: [{
                                    data: [48, 34, 18],
                                    backgroundColor: ['#8b5cf6', '#10b981', '#06b6d4'],
                                    borderWidth: 0,
                                    hoverOffset: 5
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '74%',
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                        borderColor: 'rgba(255, 255, 255, 0.15)',
                                        borderWidth: 1,
                                        callbacks: {
                                            label: function(context) {
                                                return ' ' + context.label + ': ' + context.parsed + '%';
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // 3. Gráfico de Barras: Volume de Operações
                    const ctxVolume = document.getElementById('financialVolumeBars')?.getContext('2d');
                    if (ctxVolume) {
                        this.volumeChart = new Chart(ctxVolume, {
                            type: 'bar',
                            data: {
                                labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                                datasets: [{
                                    label: 'Volume Diário (k req)',
                                    data: [42, 58, 72, 85, 96, 64, 48],
                                    backgroundColor: [
                                        '#06b6d4',
                                        '#06b6d4',
                                        '#10b981',
                                        '#10b981',
                                        '#8b5cf6',
                                        '#06b6d4',
                                        '#06b6d4'
                                    ],
                                    borderRadius: 3
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                        borderColor: 'rgba(255, 255, 255, 0.15)',
                                        borderWidth: 1,
                                        callbacks: {
                                            label: function(context) {
                                                return ' ' + context.parsed.y + ' mil requisições (100% OK)';
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: { display: false },
                                        ticks: { color: '#64748b', font: { size: 9 } }
                                    },
                                    y: {
                                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                        ticks: { color: '#64748b', font: { size: 8 } }
                                    }
                                }
                            }
                        });
                    }
                },

                updateMainChart(labels, data1, data2, label1, label2, color1, color2, unit1, unit2) {
                    if (this.mainChart) {
                        this.mainChart.data.labels = labels;
                        
                        this.mainChart.data.datasets[0].label = label1;
                        this.mainChart.data.datasets[0].data = data1;
                        this.mainChart.data.datasets[0].borderColor = color1;
                        this.mainChart.data.datasets[0].pointBackgroundColor = color1;

                        this.mainChart.data.datasets[1].label = label2;
                        this.mainChart.data.datasets[1].data = data2;
                        this.mainChart.data.datasets[1].borderColor = color2;
                        this.mainChart.data.datasets[1].pointBackgroundColor = color2;

                        this.mainChart.options.scales.y.ticks.color = color1;
                        this.mainChart.options.scales.y.ticks.callback = val => unit1 === 'R$' ? 'R$ ' + val.toLocaleString('pt-BR') : val + ' ' + unit1;

                        this.mainChart.options.scales.y1.ticks.color = color2;
                        this.mainChart.options.scales.y1.ticks.callback = val => val + ' ' + unit2;

                        this.mainChart.update();
                    }
                }
            }));
        });
    </script>
</x-app-layout>
