<x-app-layout>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Topo da Área do Cliente (Estilo Terminal Financeiro & Cloud) -->
    <div class="py-8 border-b border-slate-800/80 bg-slate-950/60 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Linha Superior: Breadcrumb & Status do Mercado/Cluster -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                <div class="text-slate-400 font-medium flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-emerald-400 transition">Página inicial do portal</a>
                    <span class="text-slate-600">/</span>
                    <span class="text-emerald-400 font-bold">Área do Cliente</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Cluster & Mercados 100% Online
                    </span>
                    <span class="text-slate-500 font-mono hidden md:inline">
                        SLA 99.99% &bull; BRL / USD Realtime
                    </span>
                </div>
            </div>

            <!-- Cabeçalho Principal: Saudação em Branco Puro, Contadores e Ações -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 pt-2">
                <div>
                    <span class="text-[11px] font-black uppercase tracking-widest text-emerald-400 block mb-1">
                        Área do Cliente &bull; Painel Executivo
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                        Olá, {{ explode(' ', $user->name)[0] ?? 'Ale' }} !
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
                       class="px-5 py-2.5 rounded-xl bg-slate-800/90 hover:bg-slate-700/90 border border-slate-700 text-slate-200 hover:text-white font-bold text-xs uppercase tracking-wider shadow-sm transition-all flex items-center gap-2">
                        <span>🎧</span>
                        <span>Abrir ticket</span>
                    </a>
                </div>
            </div>

            <!-- Banners de Ação Urgente com Alto Contraste -->
            @if ($overdueInvoice || $pendingTicket)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                    <!-- Banner 1: Fatura Vencida / Em Aberto -->
                    @if ($overdueInvoice)
                        <div class="p-5 rounded-2xl bg-rose-950/40 border border-rose-500/50 flex items-center justify-between gap-4 shadow-xl backdrop-blur-md">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-rose-500/20 border border-rose-500/30 flex items-center justify-center text-rose-400 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                </div>
                                <div>
                                    <span class="text-sm font-black text-white block">
                                        {{ $overdueInvoice->is_overdue ? '1 fatura vencida' : '1 fatura em aberto' }}
                                    </span>
                                    <p class="text-xs text-rose-300 mt-0.5">
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
                        <div class="p-5 rounded-2xl bg-blue-950/40 border border-blue-500/50 flex items-center justify-between gap-4 shadow-xl backdrop-blur-md">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-blue-500/20 border border-blue-500/30 flex items-center justify-center text-blue-400 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                </div>
                                <div>
                                    <span class="text-sm font-black text-white block">
                                        1 ticket aguarda sua resposta
                                    </span>
                                    <p class="text-xs text-blue-300 mt-0.5 truncate max-w-xs sm:max-w-sm">
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
            @endif

        </div>
    </div>

    <!-- PAINEL PRINCIPAL: GRÁFICOS COLORIDOS ESTILO IMAGEM FINANCEIRA / TRADINGVIEW -->
    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10" x-data="financialTradingDashboard()">
            
            <!-- SEÇÃO 1: TICKERS FINANCEIROS (Estilo Bolsa de Valores / Crypto Terminal) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                
                <!-- Ticker 1: MRR (Receita Recorrente Mensal) -->
                <div class="p-6 rounded-3xl bg-slate-900/80 backdrop-blur-xl border border-slate-800/90 shadow-2xl relative overflow-hidden group hover:border-emerald-500/50 transition duration-300">
                    <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-emerald-500/20 transition"></div>
                    <div class="flex items-center justify-between text-xs text-slate-400 font-bold uppercase tracking-wider mb-2">
                        <span>Receita Recorrente (MRR)</span>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/40">
                            ▲ +18.4%
                        </span>
                    </div>
                    <div class="text-3xl font-black text-white tracking-tight">
                        R$ 14.850<span class="text-sm font-bold text-slate-400">,00</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-500 mt-3 pt-3 border-t border-slate-800/60">
                        <span>Previsão de Fechamento:</span>
                        <span class="text-emerald-400 font-bold">R$ 16.200,00</span>
                    </div>
                </div>

                <!-- Ticker 2: ARR Projetado (Anualizado) -->
                <div class="p-6 rounded-3xl bg-slate-900/80 backdrop-blur-xl border border-slate-800/90 shadow-2xl relative overflow-hidden group hover:border-purple-500/50 transition duration-300">
                    <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-purple-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-purple-500/20 transition"></div>
                    <div class="flex items-center justify-between text-xs text-slate-400 font-bold uppercase tracking-wider mb-2">
                        <span>ARR Anual Projetado</span>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-purple-500/20 text-purple-400 border border-purple-500/40">
                            ▲ +24.2%
                        </span>
                    </div>
                    <div class="text-3xl font-black text-white tracking-tight">
                        R$ 178.200<span class="text-sm font-bold text-slate-400">,00</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-500 mt-3 pt-3 border-t border-slate-800/60">
                        <span>Taxa de Crescimento Anual:</span>
                        <span class="text-purple-400 font-bold">3.4x / ano</span>
                    </div>
                </div>

                <!-- Ticker 3: Volume de Requisições & Transações -->
                <div class="p-6 rounded-3xl bg-slate-900/80 backdrop-blur-xl border border-slate-800/90 shadow-2xl relative overflow-hidden group hover:border-cyan-500/50 transition duration-300">
                    <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-cyan-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-cyan-500/20 transition"></div>
                    <div class="flex items-center justify-between text-xs text-slate-400 font-bold uppercase tracking-wider mb-2">
                        <span>Volume de Requisições</span>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-cyan-500/20 text-cyan-400 border border-cyan-500/40">
                            ▲ +32.8%
                        </span>
                    </div>
                    <div class="text-3xl font-black text-white tracking-tight">
                        2.840 <span class="text-sm font-bold text-slate-400">req/min</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-500 mt-3 pt-3 border-t border-slate-800/60">
                        <span>Tráfego Hoje:</span>
                        <span class="text-cyan-400 font-bold">1.84 TB Processados</span>
                    </div>
                </div>

                <!-- Ticker 4: Margem Operacional & Eficiência -->
                <div class="p-6 rounded-3xl bg-slate-900/80 backdrop-blur-xl border border-slate-800/90 shadow-2xl relative overflow-hidden group hover:border-amber-500/50 transition duration-300">
                    <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-amber-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-amber-500/20 transition"></div>
                    <div class="flex items-center justify-between text-xs text-slate-400 font-bold uppercase tracking-wider mb-2">
                        <span>Margem de Eficiência</span>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-amber-500/20 text-amber-400 border border-amber-500/40">
                            ▲ +3.1%
                        </span>
                    </div>
                    <div class="text-3xl font-black text-white tracking-tight">
                        84.6 <span class="text-sm font-bold text-slate-400">%</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-500 mt-3 pt-3 border-t border-slate-800/60">
                        <span>Custo / GB de Nuvem:</span>
                        <span class="text-amber-400 font-bold">R$ 0,0042</span>
                    </div>
                </div>

            </div>

            <!-- SEÇÃO 2: GRÁFICOS COLORIDOS DE PERFORMANCE & TRADING (Master Chart + Doughnut + Volume) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Gráfico Master Financeiro / Trading Chart (8 colunas) -->
                <div class="lg:col-span-8 p-6 sm:p-8 rounded-3xl bg-slate-900/80 backdrop-blur-xl border border-slate-800/90 shadow-2xl space-y-6">
                    
                    <!-- Header do Gráfico Financeiro -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-800/80">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-emerald-400 animate-ping"></span>
                                <h2 class="text-lg font-black text-white tracking-tight">
                                    Fluxo de Desempenho & Receita Cloud (Real-time)
                                </h2>
                            </div>
                            <p class="text-xs text-slate-400 mt-0.5">
                                Curva de faturamento recorrente vs Throughput de dados da infraestrutura
                            </p>
                        </div>

                        <!-- Seletor Estilo Trading (1D, 1S, 1M, 1A) -->
                        <div class="flex items-center gap-1.5 p-1 rounded-xl bg-slate-950/80 border border-slate-800">
                            <button type="button" 
                                    @click="setRange('1D')" 
                                    :class="range === '1D' ? 'bg-emerald-500 text-slate-950 font-black' : 'text-slate-400 hover:text-white font-bold'"
                                    class="px-3 py-1 rounded-lg text-xs transition">
                                1D
                            </button>
                            <button type="button" 
                                    @click="setRange('1S')" 
                                    :class="range === '1S' ? 'bg-emerald-500 text-slate-950 font-black' : 'text-slate-400 hover:text-white font-bold'"
                                    class="px-3 py-1 rounded-lg text-xs transition">
                                1S
                            </button>
                            <button type="button" 
                                    @click="setRange('1M')" 
                                    :class="range === '1M' ? 'bg-emerald-500 text-slate-950 font-black' : 'text-slate-400 hover:text-white font-bold'"
                                    class="px-3 py-1 rounded-lg text-xs transition">
                                1M
                            </button>
                            <button type="button" 
                                    @click="setRange('1A')" 
                                    :class="range === '1A' ? 'bg-emerald-500 text-slate-950 font-black' : 'text-slate-400 hover:text-white font-bold'"
                                    class="px-3 py-1 rounded-lg text-xs transition">
                                1A
                            </button>
                        </div>
                    </div>

                    <!-- Canvas do Gráfico Master (Multi-gradient Neon) -->
                    <div class="relative h-72 sm:h-80 w-full">
                        <canvas id="financialMainChart"></canvas>
                    </div>

                    <!-- Legendas & Indicadores Inferiores -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 border-t border-slate-800/60 text-xs">
                        <div>
                            <span class="text-slate-500 block">Pico Financeiro:</span>
                            <span class="text-emerald-400 font-black text-sm" x-text="peakFinancial"></span>
                        </div>
                        <div>
                            <span class="text-slate-500 block">Throughput Médio:</span>
                            <span class="text-cyan-400 font-black text-sm" x-text="avgThroughput"></span>
                        </div>
                        <div>
                            <span class="text-slate-500 block">Conversão de Checkout:</span>
                            <span class="text-purple-400 font-black text-sm">94.2%</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block">Uptime do Período:</span>
                            <span class="text-white font-black text-sm">99.99%</span>
                        </div>
                    </div>

                </div>

                <!-- Coluna Direita: Distribuição por Planos & Volume (4 colunas) -->
                <div class="lg:col-span-4 space-y-8">
                    
                    <!-- Gráfico 2: Composição de Receita por Categoria (Doughnut Multicolorido Neon) -->
                    <div class="p-6 sm:p-7 rounded-3xl bg-slate-900/80 backdrop-blur-xl border border-slate-800/90 shadow-2xl space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-800/80 pb-3">
                            <h3 class="text-sm font-black text-white flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-purple-400"></span>
                                <span>Mix de Receita por Categoria</span>
                            </h3>
                            <span class="text-[10px] text-purple-400 font-mono font-bold">100% Ativo</span>
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <div class="relative w-32 h-32 flex-shrink-0">
                                <canvas id="financialCategoryDoughnut"></canvas>
                            </div>
                            <div class="text-xs space-y-2 text-slate-300 w-full">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                                        <span>Servidores VPS</span>
                                    </div>
                                    <span class="font-bold text-white">48%</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                        <span>Hospedagem Plesk</span>
                                    </div>
                                    <span class="font-bold text-white">34%</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2.5 h-2.5 rounded-full bg-cyan-500"></span>
                                        <span>Suporte & DevOps</span>
                                    </div>
                                    <span class="font-bold text-white">18%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráfico 3: Volume Diário de Operações (Barras Coloridas de Volume) -->
                    <div class="p-6 sm:p-7 rounded-3xl bg-slate-900/80 backdrop-blur-xl border border-slate-800/90 shadow-2xl space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-800/80 pb-3">
                            <h3 class="text-sm font-black text-white flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-cyan-400"></span>
                                <span>Volume de Tráfego & Transações</span>
                            </h3>
                            <span class="text-[10px] text-cyan-400 font-mono font-bold">Picos Diários</span>
                        </div>

                        <div class="relative h-36 w-full">
                            <canvas id="financialVolumeBars"></canvas>
                        </div>
                    </div>

                </div>

            </div>

            <!-- SEÇÃO 3: MEUS SERVIÇOS & PRODUTOS ATIVOS (Layout Amplo e Organizado) -->
            <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800/90 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
                <div class="flex items-center justify-between border-b border-slate-800/80 pb-4">
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

                <!-- Lista de Serviços -->
                <div class="space-y-3">
                    @forelse ($services as $service)
                        <div class="p-5 rounded-2xl bg-slate-950/50 border border-slate-800/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:border-slate-700 transition">
                            <div class="flex items-start gap-4">
                                <div class="w-11 h-11 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-300 flex-shrink-0">
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
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border {{ $service->status === 'active' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-slate-800 text-slate-400 border-slate-700' }}">
                                            {{ $service->status_label }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-slate-400 block mt-1">
                                        {{ $service->server->name ?? 'Cluster Cloud' }} &bull; IP: <code class="text-slate-300 font-mono">{{ $service->server->ip_address ?? '177.136.254.37' }}</code>
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-5 border-t sm:border-t-0 border-slate-800/80 pt-3 sm:pt-0">
                                <div class="text-right hidden sm:block">
                                    <span class="text-xs text-slate-400 block">
                                        R$ 59,99 &bull; Mensal
                                    </span>
                                </div>
                                <a href="{{ route('hosting.show', $service) }}" 
                                   class="px-4 py-2 rounded-xl bg-slate-800 border border-slate-700 hover:bg-slate-700 text-white font-bold text-xs transition shadow-sm">
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

            <!-- SEÇÃO 4: RECURSOS PREMIUM (Grid Amplo com Efeito Hover) -->
            <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800/90 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
                <div class="flex items-center justify-between border-b border-slate-800/80 pb-4">
                    <h2 class="text-base font-black text-white flex items-center gap-2">
                        <span class="text-amber-400">⭐</span>
                        <span>Recursos premium</span>
                    </h2>
                    <span class="text-xs text-slate-400">Inclusos na sua conta, sem custo</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Recurso 1: Central de Afiliados -->
                    <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 hover:border-blue-500/50 transition group cursor-pointer">
                        <div class="w-8 h-8 rounded-xl bg-blue-500/10 border border-blue-500/30 text-blue-400 flex items-center justify-center mb-2.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <span class="font-bold text-xs text-white block group-hover:text-blue-400 transition">Central de Afiliados</span>
                        <span class="text-[10px] text-slate-400 leading-tight block mt-0.5">Comissão recorrente de até 10%</span>
                    </div>

                    <!-- Recurso 2: Downloads Premium -->
                    <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 hover:border-purple-500/50 transition group cursor-pointer">
                        <div class="w-8 h-8 rounded-xl bg-purple-500/10 border border-purple-500/30 text-purple-400 flex items-center justify-center mb-2.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </div>
                        <span class="font-bold text-xs text-white block group-hover:text-purple-400 transition">Downloads Premium</span>
                        <span class="text-[10px] text-slate-400 leading-tight block mt-0.5">Envato, Freepik e GPL Vault</span>
                    </div>

                    <!-- Recurso 3: Gemini IA Cloud -->
                    <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 hover:border-emerald-500/50 transition group cursor-pointer">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-center mb-2.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <span class="font-bold text-xs text-white block group-hover:text-emerald-400 transition">Gemini IA Cloud</span>
                        <span class="text-[10px] text-slate-400 leading-tight block mt-0.5">Assistente de IA no seu painel</span>
                    </div>

                    <!-- Recurso 4: WP Vivid Backup -->
                    <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 hover:border-teal-500/50 transition group cursor-pointer">
                        <div class="w-8 h-8 rounded-xl bg-teal-500/10 border border-teal-500/30 text-teal-400 flex items-center justify-center mb-2.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <span class="font-bold text-xs text-white block group-hover:text-teal-400 transition">WP Vivid Backup</span>
                        <span class="text-[10px] text-slate-400 leading-tight block mt-0.5">Backup e migração WordPress</span>
                    </div>

                    <!-- Recurso 5: Assinatura de E-mail -->
                    <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 hover:border-amber-500/50 transition group cursor-pointer">
                        <div class="w-8 h-8 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 flex items-center justify-center mb-2.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="font-bold text-xs text-white block group-hover:text-amber-400 transition">Assinatura de E-mail</span>
                        <span class="text-[10px] text-slate-400 leading-tight block mt-0.5">Assinaturas profissionais</span>
                    </div>

                    <!-- Recurso 6: Gerador de Nomes -->
                    <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 hover:border-rose-500/50 transition group cursor-pointer">
                        <div class="w-8 h-8 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 flex items-center justify-center mb-2.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        </div>
                        <span class="font-bold text-xs text-white block group-hover:text-rose-400 transition">Gerador de Nomes</span>
                        <span class="text-[10px] text-slate-400 leading-tight block mt-0.5">Ideias de marcas e nomes</span>
                    </div>

                    <!-- Recurso 7: Gera.Bio Links -->
                    <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 hover:border-cyan-500/50 transition group cursor-pointer">
                        <div class="w-8 h-8 rounded-xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center mb-2.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        </div>
                        <span class="font-bold text-xs text-white block group-hover:text-cyan-400 transition">Gera.Bio Links</span>
                        <span class="text-[10px] text-slate-400 leading-tight block mt-0.5">Árvore de links moderna</span>
                    </div>

                    <!-- Recurso 8: Migração Gratuita -->
                    <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 hover:border-indigo-500/50 transition group cursor-pointer">
                        <div class="w-8 h-8 rounded-xl bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 flex items-center justify-center mb-2.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <span class="font-bold text-xs text-white block group-hover:text-indigo-400 transition">Migração Gratuita</span>
                        <span class="text-[10px] text-slate-400 leading-tight block mt-0.5">Equipe transfere tudo</span>
                    </div>
                </div>
            </div>

            <!-- SEÇÃO 5: SUPORTE & NOTÍCIAS (2 Colunas Equilibradas) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Coluna 1: Chamados Recentes -->
                <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800/90 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-800/80 pb-3">
                        <h3 class="text-base font-black text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                            <span>Suporte & Chamados Recentes</span>
                        </h3>
                        <a href="{{ route('tickets.create') }}" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-white font-bold text-xs transition">
                            Abrir ticket
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse ($recentTickets as $ticket)
                            <a href="{{ route('tickets.show', $ticket) }}" 
                               class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 flex items-center justify-between gap-3 hover:border-slate-700 transition block group">
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
                                        Atualizado: {{ $ticket->updated_at->format('d/m/Y (H:i)') }}
                                    </span>
                                </div>
                                <span class="text-xs text-cyan-400 font-bold group-hover:translate-x-1 transition-transform">&rarr;</span>
                            </a>
                        @empty
                            <div class="p-6 text-center text-slate-500 text-xs">
                                Nenhum chamado recente.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Coluna 2: Notícias & Comunicados Oficiais -->
                <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800/90 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-800/80 pb-3">
                        <h3 class="text-base font-black text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            <span>Notícias & Comunicados</span>
                        </h3>
                        <span class="text-xs text-slate-500">Oficial</span>
                    </div>

                    <div class="space-y-4">
                        @foreach ($news as $item)
                            <div class="p-3.5 rounded-2xl bg-slate-950/50 border border-slate-800/80 space-y-1">
                                <span class="font-bold text-xs text-white hover:text-emerald-400 transition cursor-pointer block">
                                    {{ $item['title'] }}
                                </span>
                                <div class="flex items-center gap-2 text-[10px] text-slate-400">
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

    <!-- SCRIPT ALPINE.JS + CHART.JS PARA GRÁFICOS COLORIDOS ESTILO TRADING -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('financialTradingDashboard', () => ({
                range: '1M',
                peakFinancial: 'R$ 16.200',
                avgThroughput: '148 Mbps',
                mainChart: null,
                doughnutChart: null,
                volumeChart: null,

                init() {
                    this.$nextTick(() => {
                        this.renderCharts();
                    });
                },

                setRange(r) {
                    this.range = r;
                    if (r === '1D') {
                        this.peakFinancial = 'R$ 14.850';
                        this.avgThroughput = '122 Mbps';
                        this.updateMainChart(
                            ['00h', '04h', '08h', '12h', '16h', '20h', 'Agora'],
                            [12800, 13100, 13450, 14200, 14850, 14600, 14850],
                            [80, 65, 110, 185, 240, 195, 150]
                        );
                    } else if (r === '1S') {
                        this.peakFinancial = 'R$ 15.400';
                        this.avgThroughput = '138 Mbps';
                        this.updateMainChart(
                            ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                            [13500, 13800, 14100, 14500, 15400, 15100, 14850],
                            [110, 130, 160, 210, 290, 230, 165]
                        );
                    } else if (r === '1M') {
                        this.peakFinancial = 'R$ 16.200';
                        this.avgThroughput = '148 Mbps';
                        this.updateMainChart(
                            ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
                            [11800, 12900, 13700, 14850],
                            [95, 140, 190, 260]
                        );
                    } else if (r === '1A') {
                        this.peakFinancial = 'R$ 178.200';
                        this.avgThroughput = '185 Mbps';
                        this.updateMainChart(
                            ['Jan', 'Mar', 'Mai', 'Jul', 'Set', 'Nov'],
                            [64000, 89000, 112000, 138000, 162000, 178200],
                            [80, 115, 150, 210, 280, 340]
                        );
                    }
                },

                renderCharts() {
                    // 1. Gráfico Master: Curva Financeira Multicolorida (TradingView Style)
                    const ctxMain = document.getElementById('financialMainChart')?.getContext('2d');
                    if (ctxMain) {
                        // Gradiente esmeralda neon
                        const gradEmerald = ctxMain.createLinearGradient(0, 0, 0, 280);
                        gradEmerald.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
                        gradEmerald.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

                        // Gradiente roxo neon
                        const gradPurple = ctxMain.createLinearGradient(0, 0, 0, 280);
                        gradPurple.addColorStop(0, 'rgba(139, 92, 246, 0.3)');
                        gradPurple.addColorStop(1, 'rgba(139, 92, 246, 0.0)');

                        this.mainChart = new Chart(ctxMain, {
                            type: 'line',
                            data: {
                                labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
                                datasets: [
                                    {
                                        label: 'Faturamento / MRR (R$)',
                                        data: [11800, 12900, 13700, 14850],
                                        borderColor: '#10b981',
                                        backgroundColor: gradEmerald,
                                        borderWidth: 3,
                                        tension: 0.38,
                                        fill: true,
                                        pointBackgroundColor: '#10b981',
                                        pointBorderColor: '#020617',
                                        pointRadius: 4,
                                        pointHoverRadius: 7,
                                        yAxisID: 'y'
                                    },
                                    {
                                        label: 'Throughput de Rede (Mbps)',
                                        data: [95, 140, 190, 260],
                                        borderColor: '#8b5cf6',
                                        backgroundColor: gradPurple,
                                        borderWidth: 2.5,
                                        tension: 0.38,
                                        fill: true,
                                        pointBackgroundColor: '#8b5cf6',
                                        pointBorderColor: '#020617',
                                        pointRadius: 3,
                                        pointHoverRadius: 6,
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
                                            font: { size: 11, weight: 'bold' },
                                            usePointStyle: true,
                                            boxWidth: 8
                                        }
                                    },
                                    tooltip: {
                                        backgroundColor: '#0f172a',
                                        titleColor: '#ffffff',
                                        bodyColor: '#94a3b8',
                                        borderColor: '#334155',
                                        borderWidth: 1,
                                        padding: 12,
                                        boxPadding: 6,
                                        usePointStyle: true
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: { color: 'rgba(51, 65, 85, 0.25)' },
                                        ticks: { color: '#64748b', font: { size: 10, weight: 'bold' } }
                                    },
                                    y: {
                                        type: 'linear',
                                        display: true,
                                        position: 'left',
                                        grid: { color: 'rgba(51, 65, 85, 0.25)' },
                                        ticks: {
                                            color: '#10b981',
                                            font: { size: 10, weight: 'bold' },
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
                                            font: { size: 10, weight: 'bold' },
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
                                    hoverOffset: 6
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '76%',
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: '#0f172a',
                                        borderColor: '#334155',
                                        borderWidth: 1
                                    }
                                }
                            }
                        });
                    }

                    // 3. Gráfico de Barras: Volume de Operações / Tráfego
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
                                    borderRadius: 6
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: '#0f172a',
                                        borderColor: '#334155',
                                        borderWidth: 1
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: { display: false },
                                        ticks: { color: '#64748b', font: { size: 10 } }
                                    },
                                    y: {
                                        grid: { color: 'rgba(51, 65, 85, 0.25)' },
                                        ticks: { color: '#64748b', font: { size: 9 } }
                                    }
                                }
                            }
                        });
                    }
                },

                updateMainChart(labels, financialData, throughputData) {
                    if (this.mainChart) {
                        this.mainChart.data.labels = labels;
                        this.mainChart.data.datasets[0].data = financialData;
                        this.mainChart.data.datasets[1].data = throughputData;
                        this.mainChart.update();
                    }
                }
            }));
        });
    </script>
</x-app-layout>
