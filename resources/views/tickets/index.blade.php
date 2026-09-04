<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-[#783D19] leading-tight">
                    Central de Suporte & Chamados
                </h2>
                <p class="text-xs text-gray-500 mt-1">
                    Help desk corporativo com departamentos, fila de atendimento e gestão de SLA.
                </p>
            </div>
            <a href="{{ route('tickets.create') }}" 
               class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-[#5F6F52] hover:bg-[#783D19] text-white font-bold text-xs uppercase tracking-wider transition-all duration-200 shadow-md gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Abrir Novo Chamado</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Mensagem Flash -->
            @if (session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2.5 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Cards de Métricas / KPIs de Atendimento -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white rounded-2xl p-4 border border-[#B99470]/25 shadow-sm">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total de Chamados</div>
                    <div class="text-2xl font-black text-[#783D19] mt-1">{{ $metrics['total'] }}</div>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-emerald-200/60 shadow-sm">
                    <div class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">Abertos / Novos</div>
                    <div class="text-2xl font-black text-emerald-600 mt-1">{{ $metrics['open'] }}</div>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-[#B99470]/30 shadow-sm bg-[#FEFAE0]/30">
                    <div class="text-xs font-semibold text-[#783D19] uppercase tracking-wider">Em Atendimento</div>
                    <div class="text-2xl font-black text-[#C4661F] mt-1">{{ $metrics['in_progress'] }}</div>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-blue-200/60 shadow-sm">
                    <div class="text-xs font-semibold text-blue-700 uppercase tracking-wider">Respondidos</div>
                    <div class="text-2xl font-black text-blue-600 mt-1">{{ $metrics['answered'] }}</div>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-sm">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Resolvidos / Fechados</div>
                    <div class="text-2xl font-black text-gray-600 mt-1">{{ $metrics['closed'] }}</div>
                </div>
            </div>

            <!-- Barra de Filtros e Busca -->
            <div class="bg-white rounded-2xl p-4 md:p-6 border border-[#B99470]/25 shadow-sm">
                <form method="GET" action="{{ route('tickets.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <!-- Busca Textual -->
                    <div class="md:col-span-4">
                        <label for="search" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-1.5">Buscar Chamado</label>
                        <input type="text" 
                               name="search" 
                               id="search" 
                               value="{{ $search }}" 
                               placeholder="Nº Ticket, assunto ou cliente..."
                               class="w-full rounded-xl border-gray-300 text-sm focus:border-[#5F6F52] focus:ring-[#5F6F52] shadow-sm">
                    </div>

                    <!-- Filtro Departamento -->
                    <div class="md:col-span-2">
                        <label for="department" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-1.5">Departamento</label>
                        <select name="department" id="department" class="w-full rounded-xl border-gray-300 text-xs focus:border-[#5F6F52] focus:ring-[#5F6F52] shadow-sm">
                            <option value="">Todos os Departamentos</option>
                            <option value="technical" {{ $department === 'technical' ? 'selected' : '' }}>Suporte Técnico</option>
                            <option value="financial" {{ $department === 'financial' ? 'selected' : '' }}>Financeiro</option>
                            <option value="commercial" {{ $department === 'commercial' ? 'selected' : '' }}>Comercial</option>
                            <option value="devops" {{ $department === 'devops' ? 'selected' : '' }}>DevOps & Infra</option>
                        </select>
                    </div>

                    <!-- Filtro Prioridade -->
                    <div class="md:col-span-2">
                        <label for="priority" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-1.5">Prioridade</label>
                        <select name="priority" id="priority" class="w-full rounded-xl border-gray-300 text-xs focus:border-[#5F6F52] focus:ring-[#5F6F52] shadow-sm">
                            <option value="">Todas as Prioridades</option>
                            <option value="low" {{ $priority === 'low' ? 'selected' : '' }}>Baixa</option>
                            <option value="medium" {{ $priority === 'medium' ? 'selected' : '' }}>Média</option>
                            <option value="high" {{ $priority === 'high' ? 'selected' : '' }}>Alta</option>
                            <option value="urgent" {{ $priority === 'urgent' ? 'selected' : '' }}>Crítica / Urgente</option>
                        </select>
                    </div>

                    <!-- Filtro Status -->
                    <div class="md:col-span-2">
                        <label for="status" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" id="status" class="w-full rounded-xl border-gray-300 text-xs focus:border-[#5F6F52] focus:ring-[#5F6F52] shadow-sm">
                            <option value="">Todos os Status</option>
                            <option value="open" {{ $status === 'open' ? 'selected' : '' }}>Aberto</option>
                            <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>Em Atendimento</option>
                            <option value="answered" {{ $status === 'answered' ? 'selected' : '' }}>Respondido</option>
                            <option value="customer_reply" {{ $status === 'customer_reply' ? 'selected' : '' }}>Resp. Cliente</option>
                            <option value="closed" {{ $status === 'closed' ? 'selected' : '' }}>Fechado</option>
                        </select>
                    </div>

                    <!-- Botão Filtrar -->
                    <div class="md:col-span-2 flex gap-2">
                        <button type="submit" class="w-full py-2 px-4 rounded-xl bg-[#5F6F52] hover:bg-[#783D19] text-white font-bold text-xs uppercase tracking-wider transition shadow-sm">
                            Filtrar
                        </button>
                        @if ($search || $status || $priority || $department || $clientId)
                            <a href="{{ route('tickets.index') }}" class="py-2 px-3 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-100 transition text-xs font-bold flex items-center justify-center" title="Limpar filtros">
                                ✕
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabela de Chamados -->
            <div class="bg-white rounded-3xl overflow-hidden border border-[#B99470]/25 shadow-sm">
                @if ($tickets->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-[#B99470]/20 bg-[#FEFAE0]/40 text-[#783D19] text-xs uppercase tracking-wider font-bold">
                                    <th class="py-4 px-6">Protocolo / Assunto</th>
                                    <th class="py-4 px-6">Cliente</th>
                                    <th class="py-4 px-6">Departamento</th>
                                    <th class="py-4 px-6">Prioridade</th>
                                    <th class="py-4 px-6">Status</th>
                                    <th class="py-4 px-6">Última Atualização</th>
                                    <th class="py-4 px-6 text-right">Ação</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @foreach ($tickets as $ticket)
                                    <tr class="hover:bg-amber-50/30 transition duration-150">
                                        <!-- Protocolo / Assunto -->
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-2">
                                                <span class="font-mono text-xs font-bold text-[#783D19] bg-[#FEFAE0] px-2 py-0.5 rounded border border-[#B99470]/30">
                                                    {{ $ticket->ticket_number }}
                                                </span>
                                            </div>
                                            <a href="{{ route('tickets.show', $ticket) }}" class="font-bold text-gray-900 hover:text-[#C4661F] transition mt-1 block">
                                                {{ $ticket->subject }}
                                            </a>
                                            @if ($ticket->hostingAccount)
                                                <span class="inline-flex items-center gap-1 text-[11px] text-gray-500 mt-0.5 font-mono">
                                                    🌐 {{ $ticket->hostingAccount->domain }}
                                                </span>
                                            @elseif ($ticket->server)
                                                <span class="inline-flex items-center gap-1 text-[11px] text-gray-500 mt-0.5 font-mono">
                                                    🖥️ {{ $ticket->server->name }}
                                                </span>
                                            @elseif ($ticket->project)
                                                <span class="inline-flex items-center gap-1 text-[11px] text-gray-500 mt-0.5 font-mono">
                                                    📦 {{ $ticket->project->name }}
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Cliente -->
                                        <td class="py-4 px-6">
                                            <a href="{{ route('clients.show', $ticket->client) }}" class="font-medium text-gray-900 hover:text-[#5F6F52] block">
                                                {{ $ticket->client->name }}
                                            </a>
                                            <span class="text-xs text-gray-400 block font-mono">{{ $ticket->client->email }}</span>
                                        </td>

                                        <!-- Departamento -->
                                        <td class="py-4 px-6 text-xs text-gray-700 font-medium">
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
                                        <td class="py-4 px-6 text-xs text-gray-500">
                                            <span>{{ $ticket->last_reply_at ? $ticket->last_reply_at->diffForHumans() : $ticket->created_at->diffForHumans() }}</span>
                                        </td>

                                        <!-- Ações -->
                                        <td class="py-4 px-6 text-right">
                                            <a href="{{ route('tickets.show', $ticket) }}" 
                                               class="inline-flex items-center px-3 py-1.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-[#5F6F52] hover:text-white hover:border-transparent font-semibold text-xs transition gap-1">
                                                <span>Atender</span>
                                                <span>&rarr;</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($tickets->hasPages())
                        <div class="p-6 border-t border-gray-100 bg-gray-50">
                            {{ $tickets->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 rounded-full bg-[#FEFAE0] flex items-center justify-center text-[#783D19] mx-auto mb-4 text-2xl">
                            🎫
                        </div>
                        <h3 class="font-bold text-base text-gray-900">Nenhum chamado encontrado</h3>
                        <p class="text-xs text-gray-500 mt-1 max-w-md mx-auto">
                            Não existem tickets com os filtros selecionados. Abra um novo chamado ou redefina os filtros.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('tickets.create') }}" class="px-5 py-2.5 rounded-xl bg-[#5F6F52] hover:bg-[#783D19] text-white font-bold text-xs uppercase tracking-wider transition shadow-sm inline-flex items-center gap-2">
                                <span>+ Abrir Chamado Agora</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
