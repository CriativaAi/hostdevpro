<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-white tracking-tight leading-tight flex items-center gap-2.5">
                    <span>Central de Suporte & Chamados</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-cyan-500/10 text-cyan-400 border border-cyan-500/30 font-bold">
                        SLA Ativo
                    </span>
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Help desk corporativo com departamentos, fila de atendimento em tempo real e monitoramento de SLA.
                </p>
            </div>
            <a href="{{ route('tickets.create') }}" 
               class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs uppercase tracking-wider transition-all shadow-lg shadow-emerald-500/20 gap-2 transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Abrir Novo Chamado</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Mensagem Flash -->
            @if (session('success'))
                <div class="p-4 rounded-2xl bg-emerald-950/40 border border-emerald-500/40 text-emerald-300 text-xs flex items-center gap-2.5 shadow-xl backdrop-blur-xl">
                    <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Cards de Métricas / KPIs de Atendimento (Dark Frosted Glass, rounded-2xl) -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 sm:gap-5">
                <div class="p-5 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl min-w-0 overflow-hidden transition">
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider truncate">Total de Chamados</div>
                    <div class="text-2xl sm:text-3xl font-black text-white mt-1.5 tracking-tight">{{ $metrics['total'] }}</div>
                </div>

                <div class="p-5 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl min-w-0 overflow-hidden transition">
                    <div class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider truncate">Abertos / Novos</div>
                    <div class="text-2xl sm:text-3xl font-black text-emerald-400 mt-1.5 tracking-tight">{{ $metrics['open'] }}</div>
                </div>

                <div class="p-5 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl min-w-0 overflow-hidden transition">
                    <div class="text-[11px] font-bold text-amber-400 uppercase tracking-wider truncate">Em Atendimento</div>
                    <div class="text-2xl sm:text-3xl font-black text-amber-400 mt-1.5 tracking-tight">{{ $metrics['in_progress'] }}</div>
                </div>

                <div class="p-5 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl min-w-0 overflow-hidden transition">
                    <div class="text-[11px] font-bold text-cyan-400 uppercase tracking-wider truncate">Respondidos</div>
                    <div class="text-2xl sm:text-3xl font-black text-cyan-400 mt-1.5 tracking-tight">{{ $metrics['answered'] }}</div>
                </div>

                <div class="p-5 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl min-w-0 overflow-hidden transition col-span-2 md:col-span-1">
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider truncate">Resolvidos</div>
                    <div class="text-2xl sm:text-3xl font-black text-slate-300 mt-1.5 tracking-tight">{{ $metrics['closed'] }}</div>
                </div>
            </div>

            <!-- Barra de Filtros e Busca (Dark Frosted Glass, rounded-2xl) -->
            <div class="p-6 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-xl">
                <form method="GET" action="{{ route('tickets.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <!-- Busca Textual -->
                    <div class="md:col-span-4">
                        <label for="search" class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Buscar Chamado</label>
                        <input type="text" 
                               name="search" 
                               id="search" 
                               value="{{ $search }}" 
                               placeholder="Nº Ticket, assunto ou cliente..."
                               class="w-full py-2.5 px-4 rounded-xl bg-black/40 border border-white/10 text-white placeholder-slate-500 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                    </div>

                    <!-- Filtro Departamento -->
                    <div class="md:col-span-2">
                        <label for="department" class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Departamento</label>
                        <select name="department" id="department" class="w-full py-2.5 px-3 rounded-xl bg-black/40 border border-white/10 text-xs text-slate-200 outline-none focus:border-emerald-500">
                            <option value="" class="bg-slate-900 text-slate-300">Todos</option>
                            <option value="technical" {{ $department === 'technical' ? 'selected' : '' }} class="bg-slate-900 text-white">Suporte Técnico</option>
                            <option value="financial" {{ $department === 'financial' ? 'selected' : '' }} class="bg-slate-900 text-white">Financeiro</option>
                            <option value="commercial" {{ $department === 'commercial' ? 'selected' : '' }} class="bg-slate-900 text-white">Comercial</option>
                            <option value="devops" {{ $department === 'devops' ? 'selected' : '' }} class="bg-slate-900 text-white">DevOps & Infra</option>
                        </select>
                    </div>

                    <!-- Filtro Prioridade -->
                    <div class="md:col-span-2">
                        <label for="priority" class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Prioridade</label>
                        <select name="priority" id="priority" class="w-full py-2.5 px-3 rounded-xl bg-black/40 border border-white/10 text-xs text-slate-200 outline-none focus:border-emerald-500">
                            <option value="" class="bg-slate-900 text-slate-300">Todas</option>
                            <option value="low" {{ $priority === 'low' ? 'selected' : '' }} class="bg-slate-900 text-slate-300">Baixa</option>
                            <option value="medium" {{ $priority === 'medium' ? 'selected' : '' }} class="bg-slate-900 text-cyan-400">Média</option>
                            <option value="high" {{ $priority === 'high' ? 'selected' : '' }} class="bg-slate-900 text-amber-400">Alta</option>
                            <option value="urgent" {{ $priority === 'urgent' ? 'selected' : '' }} class="bg-slate-900 text-rose-400">Crítica / Urgente</option>
                        </select>
                    </div>

                    <!-- Filtro Status -->
                    <div class="md:col-span-2">
                        <label for="status" class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" id="status" class="w-full py-2.5 px-3 rounded-xl bg-black/40 border border-white/10 text-xs text-slate-200 outline-none focus:border-emerald-500">
                            <option value="" class="bg-slate-900 text-slate-300">Todos</option>
                            <option value="open" {{ $status === 'open' ? 'selected' : '' }} class="bg-slate-900 text-emerald-400">Aberto</option>
                            <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }} class="bg-slate-900 text-amber-400">Em Atendimento</option>
                            <option value="answered" {{ $status === 'answered' ? 'selected' : '' }} class="bg-slate-900 text-cyan-400">Respondido</option>
                            <option value="customer_reply" {{ $status === 'customer_reply' ? 'selected' : '' }} class="bg-slate-900 text-purple-400">Resp. Cliente</option>
                            <option value="closed" {{ $status === 'closed' ? 'selected' : '' }} class="bg-slate-900 text-slate-400">Fechado</option>
                        </select>
                    </div>

                    <!-- Botão Filtrar -->
                    <div class="md:col-span-2 flex gap-2">
                        <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider transition shadow-sm">
                            Filtrar
                        </button>
                        @if ($search || $status || $priority || $department || $clientId)
                            <a href="{{ route('tickets.index') }}" class="py-2.5 px-3 rounded-xl border border-white/10 text-slate-400 hover:text-white hover:bg-white/[0.06] transition text-xs font-bold flex items-center justify-center" title="Limpar filtros">
                                ✕
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabela de Chamados (Dark Frosted Glass, rounded-2xl) -->
            <div class="bg-white/[0.06] backdrop-blur-2xl rounded-2xl overflow-hidden border border-white/15 shadow-2xl">
                @if ($tickets->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-white/10 bg-white/[0.03] text-slate-400 text-[11px] uppercase tracking-wider font-bold">
                                    <th class="py-4 px-6">Protocolo / Assunto</th>
                                    <th class="py-4 px-6">Cliente</th>
                                    <th class="py-4 px-6">Departamento</th>
                                    <th class="py-4 px-6">Prioridade</th>
                                    <th class="py-4 px-6">Status</th>
                                    <th class="py-4 px-6">Última Atualização</th>
                                    <th class="py-4 px-6 text-right">Ação</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 text-sm">
                                @foreach ($tickets as $ticket)
                                    <tr class="hover:bg-white/[0.04] transition duration-150">
                                        <!-- Protocolo / Assunto -->
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-2">
                                                <span class="font-mono text-xs font-black text-cyan-400 bg-cyan-500/10 px-2 py-0.5 rounded border border-cyan-500/30">
                                                    {{ $ticket->ticket_number }}
                                                </span>
                                            </div>
                                            <a href="{{ route('tickets.show', $ticket) }}" class="font-bold text-white hover:text-cyan-400 transition mt-1 block">
                                                {{ $ticket->subject }}
                                            </a>
                                            @if ($ticket->hostingAccount)
                                                <span class="inline-flex items-center gap-1 text-[11px] text-slate-400 mt-0.5 font-mono">
                                                    🌐 {{ $ticket->hostingAccount->domain }}
                                                </span>
                                            @elseif ($ticket->server)
                                                <span class="inline-flex items-center gap-1 text-[11px] text-slate-400 mt-0.5 font-mono">
                                                    🖥️ {{ $ticket->server->name }}
                                                </span>
                                            @elseif ($ticket->project)
                                                <span class="inline-flex items-center gap-1 text-[11px] text-slate-400 mt-0.5 font-mono">
                                                    📦 {{ $ticket->project->name }}
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Cliente -->
                                        <td class="py-4 px-6">
                                            <a href="{{ route('clients.show', $ticket->client) }}" class="font-medium text-white hover:text-cyan-400 block">
                                                {{ $ticket->client->name }}
                                            </a>
                                            <span class="text-xs text-slate-400 block font-mono">{{ $ticket->client->email }}</span>
                                        </td>

                                        <!-- Departamento -->
                                        <td class="py-4 px-6 text-xs text-slate-300 font-medium">
                                            {{ $ticket->department_label }}
                                        </td>

                                        <!-- Prioridade -->
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold ring-1 ring-inset {{ $ticket->priority_badge_classes }}">
                                                {{ $ticket->priority_label }}
                                            </span>
                                        </td>

                                        <!-- Status -->
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold ring-1 ring-inset {{ $ticket->status_badge_classes }}">
                                                {{ $ticket->status_label }}
                                            </span>
                                        </td>

                                        <!-- Última Atualização -->
                                        <td class="py-4 px-6 text-xs text-slate-400">
                                            <span>{{ $ticket->last_reply_at ? $ticket->last_reply_at->diffForHumans() : $ticket->created_at->diffForHumans() }}</span>
                                        </td>

                                        <!-- Ações -->
                                        <td class="py-4 px-6 text-right">
                                            <a href="{{ route('tickets.show', $ticket) }}" 
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider transition">
                                                <span>Acessar</span>
                                                <span>&rarr;</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    @if ($tickets->hasPages())
                        <div class="p-6 border-t border-white/10 bg-white/[0.02]">
                            {{ $tickets->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 mx-auto flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-white">Nenhum chamado encontrado</h3>
                        <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                            Abra um novo chamado ou ajuste os filtros de pesquisa para visualizar tickets.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('tickets.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-400 text-slate-950 font-black text-xs uppercase tracking-wider rounded-xl shadow-lg transition">
                                Abrir Chamado
                            </a>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
