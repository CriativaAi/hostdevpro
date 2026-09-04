<x-app-layout>
    <!-- Topo da Área do Cliente -->
    <div class="py-6 sm:py-8 border-b border-gray-100/80 bg-white/50 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <div class="text-xs text-gray-400 font-medium mb-3 flex items-center gap-1.5">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition">Página inicial do portal</a>
                <span>/</span>
                <span class="text-gray-600 font-semibold">Área do Cliente</span>
            </div>

            <!-- Cabeçalho com Saudação, Contadores e Botões de Ação -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-blue-600 block mb-1">
                        Área do Cliente
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                        Olá, {{ explode(' ', $user->name)[0] ?? 'Ale' }} !
                    </h1>

                    <!-- Contadores em Linha (Estilo ValueHost) -->
                    <div class="flex flex-wrap items-center gap-4 sm:gap-6 mt-3 text-xs font-bold uppercase tracking-wider text-gray-500">
                        <div class="flex items-center gap-1.5">
                            <span class="text-gray-900 text-sm font-extrabold">{{ $servicesCount }}</span>
                            <span>SERVIÇOS</span>
                        </div>
                        <span class="text-gray-300">&bull;</span>
                        <div class="flex items-center gap-1.5">
                            <span class="text-gray-900 text-sm font-extrabold">{{ $domainsCount }}</span>
                            <span>DOMÍNIOS</span>
                        </div>
                        <span class="text-gray-300">&bull;</span>
                        <div class="flex items-center gap-1.5">
                            <span class="text-gray-900 text-sm font-extrabold">{{ $ticketsCount }}</span>
                            <span>TICKETS</span>
                        </div>
                        <span class="text-gray-300">&bull;</span>
                        <div class="flex items-center gap-1.5">
                            <span class="{{ $overdueInvoice ? 'text-rose-600' : 'text-gray-900' }} text-sm font-extrabold">
                                {{ $invoicesCount }}
                            </span>
                            <span>FATURAS</span>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação Rápida (Topo Direito) -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('hosting.create') }}" 
                       class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider shadow-sm hover:shadow transition-all flex items-center gap-2">
                        <span>+</span>
                        <span>Contratar</span>
                    </a>
                    <a href="{{ route('tickets.create') }}" 
                       class="px-5 py-2.5 rounded-xl bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:text-gray-900 font-bold text-xs uppercase tracking-wider shadow-sm transition-all flex items-center gap-2">
                        <span>🎧</span>
                        <span>Abrir ticket</span>
                    </a>
                </div>
            </div>

            <!-- Banners de Ação Urgente (Notice Cards) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                <!-- Banner 1: Fatura Vencida / Em Aberto -->
                @if ($overdueInvoice)
                    <div class="p-4 sm:p-5 rounded-2xl bg-rose-50 border border-rose-200/80 flex items-center justify-between gap-4 shadow-sm transition-transform hover:-translate-y-0.5">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <div>
                                <span class="text-sm font-extrabold text-rose-900 block">
                                    {{ $overdueInvoice->is_overdue ? '1 fatura vencida' : '1 fatura em aberto' }}
                                </span>
                                <p class="text-xs text-rose-700 mt-0.5">
                                    Total de {{ $overdueInvoice->amount_formatted }}. Pague para evitar a suspensão dos serviços.
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('invoices.show', $overdueInvoice) }}" 
                           class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs uppercase tracking-wider flex-shrink-0 shadow transition">
                            Pagar agora
                        </a>
                    </div>
                @endif

                <!-- Banner 2: Ticket Aguardando Resposta -->
                @if ($pendingTicket)
                    <div class="p-4 sm:p-5 rounded-2xl bg-blue-50 border border-blue-200/80 flex items-center justify-between gap-4 shadow-sm transition-transform hover:-translate-y-0.5">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                            </div>
                            <div>
                                <span class="text-sm font-extrabold text-blue-900 block">
                                    1 ticket aguarda sua resposta
                                </span>
                                <p class="text-xs text-blue-700 mt-0.5 truncate max-w-xs sm:max-w-sm">
                                    #{{ $pendingTicket->ticket_number }} {{ $pendingTicket->subject }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('tickets.show', $pendingTicket) }}" 
                           class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider flex-shrink-0 shadow transition">
                            Responder
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Conteúdo Principal em 2 Colunas -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <!-- Coluna Esquerda Principal (8 colunas) -->
                <div class="lg:col-span-8 space-y-8">

                    <!-- Seção 1: Meus Serviços -->
                    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-200/80 shadow-sm">
                        <div class="flex items-center justify-between mb-5">
                            <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                <span class="text-blue-600">●</span>
                                <span>Meus serviços</span>
                            </h2>
                            <a href="{{ route('hosting.index') }}" class="text-xs font-semibold text-gray-500 hover:text-blue-600 transition">
                                Ver todos ({{ $servicesCount }})
                            </a>
                        </div>

                        <div class="space-y-3">
                            @forelse ($services as $service)
                                <div class="p-4 sm:p-5 rounded-2xl bg-gray-50/70 border border-gray-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:border-gray-300 transition">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-11 h-11 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-600 flex-shrink-0">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-gray-900">
                                                @if (str_contains($service->domain, 'actualagency'))
                                                    Revenda NVMe Basic Ilimitado EUA Plesk
                                                @else
                                                    {{ $service->plan_label }}
                                                @endif
                                            </h3>
                                            <div class="flex items-center gap-2 text-xs text-gray-500 mt-0.5 font-mono">
                                                <span>{{ $service->domain }}</span>
                                                <span class="text-gray-300">|</span>
                                                <span class="text-blue-600 font-semibold uppercase">Plesk</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between sm:justify-end gap-4 border-t sm:border-t-0 pt-3 sm:pt-0 border-gray-200">
                                        <div class="text-left sm:text-right">
                                            <div class="flex items-center sm:justify-end gap-2">
                                                <span class="text-[11px] font-bold text-rose-600">Vence 03/09/2026</span>
                                                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold uppercase">
                                                    Ativo
                                                </span>
                                            </div>
                                            <span class="text-xs text-gray-500 font-medium block mt-0.5">
                                                R$ 59,99 &bull; Mensal
                                            </span>
                                        </div>
                                        <a href="{{ route('hosting.show', $service) }}" 
                                           class="px-3.5 py-2 rounded-xl bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold text-xs transition shadow-sm">
                                            Detalhes
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-400 text-xs">
                                    Nenhum serviço contratado no momento.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Seção 2: Recursos Premium (Inclusos na sua conta, sem custo) -->
                    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-200/80 shadow-sm">
                        <div class="flex items-center justify-between mb-5">
                            <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                <span class="text-amber-500">⭐</span>
                                <span>Recursos premium</span>
                            </h2>
                            <span class="text-xs text-gray-400">Inclusos na sua conta, sem custo</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3.5">
                            <!-- Recurso 1: Central de Afiliados -->
                            <div class="p-3.5 rounded-2xl bg-gray-50/70 border border-gray-200/70 hover:border-gray-300 transition group cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-2.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <span class="font-bold text-xs text-gray-900 block">Central de Afiliados</span>
                                <span class="text-[10px] text-gray-500 leading-tight block mt-0.5">Comissão recorrente de até 10%</span>
                            </div>

                            <!-- Recurso 2: Downloads Premium -->
                            <div class="p-3.5 rounded-2xl bg-gray-50/70 border border-gray-200/70 hover:border-gray-300 transition group cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-2.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                </div>
                                <span class="font-bold text-xs text-gray-900 block">Downloads Premium</span>
                                <span class="text-[10px] text-gray-500 leading-tight block mt-0.5">Envato, Freepik e GPL Vault</span>
                            </div>

                            <!-- Recurso 3: Gemini IA (Assistente de IA) -->
                            <div class="p-3.5 rounded-2xl bg-gray-50/70 border border-gray-200/70 hover:border-gray-300 transition group cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </div>
                                <span class="font-bold text-xs text-gray-900 block">Gemini IA Cloud</span>
                                <span class="text-[10px] text-gray-500 leading-tight block mt-0.5">Assistente de IA no seu painel</span>
                            </div>

                            <!-- Recurso 4: WP Vivid Backup -->
                            <div class="p-3.5 rounded-2xl bg-gray-50/70 border border-gray-200/70 hover:border-gray-300 transition group cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center mb-2.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                                <span class="font-bold text-xs text-gray-900 block">WP Vivid Backup</span>
                                <span class="text-[10px] text-gray-500 leading-tight block mt-0.5">Backup e migração WordPress</span>
                            </div>

                            <!-- Recurso 5: Assinatura de E-mail -->
                            <div class="p-3.5 rounded-2xl bg-gray-50/70 border border-gray-200/70 hover:border-gray-300 transition group cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-2.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <span class="font-bold text-xs text-gray-900 block">Assinatura de E-mail</span>
                                <span class="text-[10px] text-gray-500 leading-tight block mt-0.5">Assinaturas profissionais prontas</span>
                            </div>

                            <!-- Recurso 6: Gerador de Nomes -->
                            <div class="p-3.5 rounded-2xl bg-gray-50/70 border border-gray-200/70 hover:border-gray-300 transition group cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center mb-2.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                </div>
                                <span class="font-bold text-xs text-gray-900 block">Gerador de Nomes</span>
                                <span class="text-[10px] text-gray-500 leading-tight block mt-0.5">Ideias de marcas e domínios</span>
                            </div>

                            <!-- Recurso 7: Gera.Bio Links -->
                            <div class="p-3.5 rounded-2xl bg-gray-50/70 border border-gray-200/70 hover:border-gray-300 transition group cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center mb-2.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                </div>
                                <span class="font-bold text-xs text-gray-900 block">Gera.Bio Links</span>
                                <span class="text-[10px] text-gray-500 leading-tight block mt-0.5">Sua página de links na bio</span>
                            </div>

                            <!-- Recurso 8: CRM -->
                            <div class="p-3.5 rounded-2xl bg-gray-50/70 border border-gray-200/70 hover:border-gray-300 transition group cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-2.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                </div>
                                <span class="font-bold text-xs text-gray-900 block">CRM HostDevPro</span>
                                <span class="text-[10px] text-gray-500 leading-tight block mt-0.5">Gestão de clientes e vendas</span>
                            </div>
                        </div>
                    </div>

                    <!-- Seção 3: Tickets Recentes -->
                    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-200/80 shadow-sm">
                        <div class="flex items-center justify-between mb-5">
                            <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                <span class="text-blue-600">💬</span>
                                <span>Tickets recentes</span>
                            </h2>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('tickets.index') }}" class="text-xs font-semibold text-gray-500 hover:text-blue-600 transition">
                                    Ver todos
                                </a>
                                <a href="{{ route('tickets.create') }}" class="px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition shadow-sm">
                                    Abrir ticket
                                </a>
                            </div>
                        </div>

                        <div class="space-y-3">
                            @forelse ($recentTickets as $ticket)
                                <a href="{{ route('tickets.show', $ticket) }}" 
                                   class="p-4 rounded-2xl bg-gray-50/70 border border-gray-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-gray-100/70 transition block group">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-xs text-gray-900 group-hover:text-blue-600 transition">
                                                #{{ $ticket->ticket_number }} - {{ $ticket->subject }}
                                            </span>
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $ticket->status_badge_classes }}">
                                                {{ $ticket->status_label }}
                                            </span>
                                        </div>
                                        <span class="text-[11px] text-gray-400 block mt-1">
                                            Última atualização: {{ $ticket->updated_at->format('d/m/Y (H:i)') }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-blue-600 font-semibold group-hover:translate-x-1 transition-transform">
                                        &rarr;
                                    </div>
                                </a>
                            @empty
                                <div class="p-6 text-center text-gray-400 text-xs">
                                    Nenhum chamado aberto recentemente.
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                <!-- Coluna Direita (4 colunas) -->
                <div class="lg:col-span-4 space-y-8">

                    <!-- Widget 1: Registrar um Domínio -->
                    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-200/80 shadow-sm" x-data="{
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
                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                            </div>
                            <h3 class="font-bold text-sm text-gray-900">Registrar um domínio</h3>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed mb-4">
                            Verifique a disponibilidade e registre ou transfira em segundos.
                        </p>

                        <div class="space-y-3">
                            <div>
                                <input type="text" 
                                       x-model="domainQuery" 
                                       placeholder="seudominio.com.br"
                                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 bg-gray-50 text-xs focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition shadow-sm">
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" 
                                        @click="checkDomain()" 
                                        class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider transition shadow-sm">
                                    Registre-se
                                </button>
                                <button type="button" 
                                        @click="checkDomain()" 
                                        class="w-full py-2.5 rounded-xl bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold text-xs uppercase tracking-wider transition shadow-sm">
                                    Transferir
                                </button>
                            </div>
                        </div>

                        <!-- Feedback de Disponibilidade Dinâmico -->
                        <div x-show="checked" style="display: none;" class="mt-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs space-y-1">
                            <span class="font-bold block flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Domínio Disponível!
                            </span>
                            <span class="text-[11px] text-emerald-700 block">
                                <code class="font-bold" x-text="domainQuery"></code> está livre para registro imediato na nuvem HostDevPro.
                            </span>
                        </div>
                    </div>

                    <!-- Widget 2: Notícias & Comunicados -->
                    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-200/80 shadow-sm">
                        <div class="flex items-center justify-between mb-5 border-b border-gray-100 pb-3">
                            <h3 class="font-bold text-sm text-gray-900 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                <span>Notícias</span>
                            </h3>
                            <span class="text-xs text-gray-400">Ver todas</span>
                        </div>

                        <div class="space-y-4">
                            @foreach ($news as $item)
                                <div class="space-y-1">
                                    <span class="font-bold text-xs text-gray-800 leading-snug hover:text-blue-600 transition cursor-pointer block">
                                        {{ $item['title'] }}
                                    </span>
                                    <div class="flex items-center gap-2 text-[10px] text-gray-400">
                                        <span>{{ $item['date'] }}</span>
                                        <span>&bull;</span>
                                        <span class="text-blue-600 font-semibold">{{ $item['category'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>
