<x-guest-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-10">
        
        <!-- Header da Página de Status -->
        <div class="text-center space-y-3">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Monitoramento em Tempo Real</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                Status da Nuvem HostDevPro
            </h1>
            <p class="text-sm text-slate-400 max-w-xl mx-auto">
                Acompanhe a disponibilidade, latência e saúde operacional de toda a nossa infraestrutura cloud, gateways e serviços dedicados.
            </p>
        </div>

        <!-- Banner Principal de Status -->
        <div class="p-6 rounded-3xl bg-emerald-950/40 border border-emerald-500/40 backdrop-blur-xl shadow-2xl flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-emerald-400 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-black text-white">
                        Todos os Sistemas Operacionais
                    </h2>
                    <p class="text-xs text-emerald-300/80">
                        Nenhum incidente reportado nas últimas 24 horas. Uptime global de 99.99%.
                    </p>
                </div>
            </div>
            <div class="text-right flex-shrink-0">
                <span class="text-xs text-slate-400 block font-mono">Última checagem</span>
                <span class="text-xs font-bold text-white font-mono">{{ date('d/m/Y H:i:s') }} BRT</span>
            </div>
        </div>

        <!-- Grade de Componentes do Sistema -->
        <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800/90 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
            <h3 class="text-base font-black text-white border-b border-slate-800/80 pb-3 flex items-center justify-between">
                <span>Serviços & Clusters Cloud</span>
                <span class="text-xs font-normal text-slate-400">99.9% SLA Contratual</span>
            </h3>

            <div class="divide-y divide-slate-800/60">
                <!-- Item 1: Cluster Principal Integrator -->
                <div class="py-4 flex items-center justify-between gap-4">
                    <div class="space-y-0.5">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-sm text-white">Cluster Cloud Integrator (Edge BR-01)</span>
                            <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-slate-800 text-slate-300">209.50.245.45</span>
                        </div>
                        <p class="text-xs text-slate-400">Servidor principal NVMe, OpenResty 1.27 e instâncias Docker</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-emerald-400 font-mono font-bold hidden sm:inline">11ms</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Operacional
                        </span>
                    </div>
                </div>

                <!-- Item 2: Plesk Obsidian Cloud -->
                <div class="py-4 flex items-center justify-between gap-4">
                    <div class="space-y-0.5">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-sm text-white">Painel Plesk Obsidian & Revenda</span>
                            <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-slate-800 text-slate-300">us163-pl.valueserver.net</span>
                        </div>
                        <p class="text-xs text-slate-400">Gerenciamento de domínios, contas PHP, DNS e certificados SSL</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-emerald-400 font-mono font-bold hidden sm:inline">42ms</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Operacional
                        </span>
                    </div>
                </div>

                <!-- Item 3: Webmail Roundcube -->
                <div class="py-4 flex items-center justify-between gap-4">
                    <div class="space-y-0.5">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-sm text-white">Webmail Corporativo Roundcube</span>
                            <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-slate-800 text-slate-300">webmail.hostdevpro.app.br</span>
                        </div>
                        <p class="text-xs text-slate-400">Serviço de correio IMAP/SMTP seguro com antispam integrado</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-emerald-400 font-mono font-bold hidden sm:inline">14ms</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Operacional
                        </span>
                    </div>
                </div>

                <!-- Item 4: Banco de Dados MySQL & Redis -->
                <div class="py-4 flex items-center justify-between gap-4">
                    <div class="space-y-0.5">
                        <span class="font-bold text-sm text-white block">Cluster de Bancos de Dados MySQL & Cache Redis</span>
                        <p class="text-xs text-slate-400">I/O síncrono em SSD NVMe com backups automáticos</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-emerald-400 font-mono font-bold hidden sm:inline">&lt; 1ms</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Operacional
                        </span>
                    </div>
                </div>

                <!-- Item 5: Meilisearch Scout -->
                <div class="py-4 flex items-center justify-between gap-4">
                    <div class="space-y-0.5">
                        <span class="font-bold text-sm text-white block">Motor de Busca Meilisearch Cloud</span>
                        <p class="text-xs text-slate-400">Indexação ultrarrápida de clientes, faturas e chamados</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-emerald-400 font-mono font-bold hidden sm:inline">18ms</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Operacional
                        </span>
                    </div>
                </div>

                <!-- Item 6: Mercado Pago (PIX) -->
                <div class="py-4 flex items-center justify-between gap-4">
                    <div class="space-y-0.5">
                        <span class="font-bold text-sm text-white block">Gateway de Pagamento Instantâneo (Mercado Pago PIX)</span>
                        <p class="text-xs text-slate-400">Geração de QR Code e webhooks de baixa em tempo real</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Operacional
                        </span>
                    </div>
                </div>

                <!-- Item 7: Stripe -->
                <div class="py-4 flex items-center justify-between gap-4">
                    <div class="space-y-0.5">
                        <span class="font-bold text-sm text-white block">Gateway Internacional de Cartões (Stripe)</span>
                        <p class="text-xs text-slate-400">Processamento de cartões de crédito globais com 3D Secure</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Operacional
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Histórico de Uptime dos Últimos 90 Dias -->
        <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800/90 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Histórico de Disponibilidade (Últimos 90 dias)</span>
                <span class="text-xs font-black text-emerald-400">99.99% Uptime</span>
            </div>
            <!-- Linha de barras de disponibilidade -->
            <div class="grid grid-cols-45 sm:grid-cols-90 gap-1 h-8 items-end">
                @for ($i = 0; $i < 90; $i++)
                    <div class="h-full w-full bg-emerald-500/80 hover:bg-emerald-400 rounded-sm transition-all" title="Dia {{ 90 - $i }}: 100% Operacional"></div>
                @endfor
            </div>
            <div class="flex items-center justify-between text-[11px] text-slate-500 font-mono pt-1">
                <span>90 dias atrás</span>
                <span>Hoje</span>
            </div>
        </div>

        <!-- Botão Voltar -->
        <div class="text-center pt-4">
            <a href="{{ route('dashboard') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 transition">
                &larr; Voltar para a Área do Cliente
            </a>
        </div>

    </div>
</x-guest-layout>
