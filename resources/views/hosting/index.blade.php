<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-white tracking-tight leading-tight flex items-center gap-2.5">
                    <span>Contas de Hospedagem & Plesk</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 font-bold">
                        {{ $kpis['total'] }} {{ $kpis['total'] === 1 ? 'Domínio' : 'Domínios' }}
                    </span>
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Gestão centralizada de sites, bancos de dados, cotas de disco e certificados SSL na nuvem.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('hosting.create') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40 transition-all transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Nova Hospedagem</span>
                </a>
            </div>
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

            <!-- Cards de Métricas / KPIs (Dark Frosted Glass, rounded-2xl) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Total -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl min-w-0 overflow-hidden relative group transition">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block truncate">Total de Hospedagens</span>
                    <span class="text-3xl font-black text-white mt-2 block tracking-tight">{{ $kpis['total'] }}</span>
                    <span class="text-[11px] text-slate-400 mt-1 block truncate">Domínios cadastrados</span>
                </div>

                <!-- Ativas -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl min-w-0 overflow-hidden relative group transition">
                    <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider block truncate">Contas Ativas</span>
                    <div class="flex items-baseline gap-2 mt-2">
                        <span class="text-3xl font-black text-emerald-400 tracking-tight">{{ $kpis['active'] }}</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    </div>
                    <span class="text-[11px] text-slate-400 mt-1 block truncate">Websites no ar</span>
                </div>

                <!-- Suspensas -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl min-w-0 overflow-hidden relative group transition">
                    <span class="text-xs font-bold text-rose-400 uppercase tracking-wider block truncate">Suspensas</span>
                    <span class="text-3xl font-black text-rose-400 mt-2 block tracking-tight">{{ $kpis['suspended'] }}</span>
                    <span class="text-[11px] text-slate-400 mt-1 block truncate">Inadimplência ou manutenção</span>
                </div>

                <!-- Painel Plesk / VPS -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl min-w-0 overflow-hidden relative group transition">
                    <span class="text-xs font-bold text-cyan-400 uppercase tracking-wider block truncate">Servidores Vinculados</span>
                    <span class="text-3xl font-black text-cyan-400 mt-2 block tracking-tight">{{ $servers->count() }}</span>
                    <span class="text-[11px] text-slate-400 mt-1 block truncate">Nós de processamento</span>
                </div>
            </div>

            <!-- Filtros & Busca Avançada (Dark Frosted Glass, rounded-2xl) -->
            <div class="p-6 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-xl">
                <form method="GET" action="{{ route('hosting.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                    
                    <!-- Busca por Texto -->
                    <div class="lg:col-span-2 relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Buscar por domínio, cliente..." 
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-black/40 border border-white/10 text-white placeholder-slate-500 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                    </div>

                    <!-- Filtro por Cliente -->
                    <div>
                        <select name="client_id" onchange="this.form.submit()" class="w-full py-2.5 px-3 rounded-xl bg-black/40 border border-white/10 text-xs text-slate-200 outline-none focus:border-emerald-500">
                            <option value="" class="bg-slate-900 text-slate-300">Todos os Clientes</option>
                            @foreach ($clients as $c)
                                <option value="{{ $c->id }}" @selected(request('client_id') == $c->id) class="bg-slate-900 text-white">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro por Servidor -->
                    <div>
                        <select name="server_id" onchange="this.form.submit()" class="w-full py-2.5 px-3 rounded-xl bg-black/40 border border-white/10 text-xs text-slate-200 outline-none focus:border-emerald-500">
                            <option value="" class="bg-slate-900 text-slate-300">Todos os Servidores</option>
                            @foreach ($servers as $s)
                                <option value="{{ $s->id }}" @selected(request('server_id') == $s->id) class="bg-slate-900 text-white">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro por Status -->
                    <div>
                        <select name="status" onchange="this.form.submit()" class="w-full py-2.5 px-3 rounded-xl bg-black/40 border border-white/10 text-xs text-slate-200 outline-none focus:border-emerald-500">
                            <option value="" class="bg-slate-900 text-slate-300">Todos os Status</option>
                            <option value="{{ \App\Models\HostingAccount::STATUS_ACTIVE }}" @selected(request('status') === \App\Models\HostingAccount::STATUS_ACTIVE) class="bg-slate-900 text-emerald-400">🟢 Ativa</option>
                            <option value="{{ \App\Models\HostingAccount::STATUS_SUSPENDED }}" @selected(request('status') === \App\Models\HostingAccount::STATUS_SUSPENDED) class="bg-slate-900 text-rose-400">🔴 Suspensa</option>
                            <option value="{{ \App\Models\HostingAccount::STATUS_PENDING }}" @selected(request('status') === \App\Models\HostingAccount::STATUS_PENDING) class="bg-slate-900 text-amber-400">🟡 Pendente</option>
                        </select>
                    </div>

                    <!-- Botão Filtrar / Limpar -->
                    <div class="flex items-center gap-2">
                        <button type="submit" class="w-full py-2.5 px-4 bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-sm">
                            Filtrar
                        </button>
                        @if (request()->hasAny(['search', 'client_id', 'server_id', 'status']))
                            <a href="{{ route('hosting.index') }}" class="px-2 text-xs text-slate-400 hover:text-white transition">
                                Limpar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabela de Contas de Hospedagem (Dark Frosted Glass, rounded-2xl) -->
            <div class="bg-white/[0.06] backdrop-blur-2xl rounded-2xl border border-white/15 shadow-2xl overflow-hidden">
                @if ($hostingAccounts->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 mx-auto flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-white">Nenhuma conta de hospedagem encontrada</h3>
                        <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                            Provisione uma nova conta para associar um domínio a um cliente e servidor.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('hosting.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-400 text-slate-950 font-black text-xs uppercase tracking-wider rounded-xl shadow-lg transition">
                                Nova Hospedagem
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-white/10 bg-white/[0.03] text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                    <th class="py-4 px-6">Domínio</th>
                                    <th class="py-4 px-6">Cliente</th>
                                    <th class="py-4 px-6">Servidor VPS</th>
                                    <th class="py-4 px-6">Plano & PHP</th>
                                    <th class="py-4 px-6">Armazenamento</th>
                                    <th class="py-4 px-6">SSL</th>
                                    <th class="py-4 px-6">Status</th>
                                    <th class="py-4 px-6 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 text-sm">
                                @foreach ($hostingAccounts as $acc)
                                    <tr class="hover:bg-white/[0.04] transition-colors">
                                        <!-- Domínio -->
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('hosting.show', $acc) }}" class="font-bold text-white hover:text-emerald-400 transition">
                                                    {{ $acc->domain }}
                                                </a>
                                                <a href="https://{{ $acc->domain }}" target="_blank" rel="noopener noreferrer" class="text-slate-400 hover:text-emerald-400" title="Visitar site">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                </a>
                                            </div>
                                            @if ($acc->username)
                                                <span class="block text-[11px] font-mono text-slate-400 mt-0.5">User: {{ $acc->username }}</span>
                                            @endif
                                        </td>

                                        <!-- Cliente -->
                                        <td class="py-4 px-6 text-xs text-slate-300">
                                            <a href="{{ route('clients.show', $acc->client) }}" class="font-semibold text-white hover:text-emerald-400 hover:underline block">
                                                {{ $acc->client->name }}
                                            </a>
                                            <span class="text-[11px] text-slate-400 block">{{ $acc->client->company ?? 'Pessoa Física' }}</span>
                                        </td>

                                        <!-- Servidor -->
                                        <td class="py-4 px-6 text-xs">
                                            <a href="{{ route('servers.show', $acc->server) }}" class="font-bold text-white hover:text-cyan-400 block">
                                                {{ $acc->server->name }}
                                            </a>
                                            <span class="font-mono text-[11px] text-slate-400 block">{{ $acc->server->ip_address }}</span>
                                        </td>

                                        <!-- Plano & PHP -->
                                        <td class="py-4 px-6 text-xs">
                                            <span class="font-semibold text-slate-200 block">{{ $acc->plan_label }}</span>
                                            <span class="font-mono text-[11px] font-bold text-emerald-400 block">PHP {{ $acc->php_version }}</span>
                                        </td>

                                        <!-- Uso de Disco -->
                                        <td class="py-4 px-6">
                                            <div class="w-28 bg-black/40 rounded-full h-1.5 overflow-hidden border border-white/10">
                                                <div class="bg-gradient-to-r from-emerald-500 to-cyan-400 h-1.5 rounded-full" style="width: {{ $acc->disk_usage_percentage }}%"></div>
                                            </div>
                                            <span class="text-[11px] text-slate-400 font-mono mt-1 block">
                                                {{ $acc->disk_used_gb }} / {{ $acc->disk_quota_gb }} GB ({{ $acc->disk_usage_percentage }}%)
                                            </span>
                                        </td>

                                        <!-- SSL -->
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $acc->ssl_badge_classes }}">
                                                @if ($acc->ssl_status === \App\Models\HostingAccount::SSL_ACTIVE)
                                                    <span>🔒</span>
                                                @endif
                                                {{ ucfirst($acc->ssl_status) }}
                                            </span>
                                        </td>

                                        <!-- Status -->
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $acc->status_badge_classes }}">
                                                @if ($acc->status === \App\Models\HostingAccount::STATUS_ACTIVE)
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                                @endif
                                                {{ $acc->status_label }}
                                            </span>
                                        </td>

                                        <!-- Ações Rápidas -->
                                        <td class="py-4 px-6 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <!-- Botão de Alternar Suspensão -->
                                                <form method="POST" action="{{ route('hosting.toggle-status', $acc) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if ($acc->status === \App\Models\HostingAccount::STATUS_ACTIVE)
                                                        <button type="submit" 
                                                                class="p-2 rounded-lg text-amber-400 hover:bg-amber-500/10 border border-transparent hover:border-amber-500/20 transition" 
                                                                title="Suspender conta"
                                                                onclick="return confirm('Deseja suspender a conta {{ $acc->domain }}?');">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        </button>
                                                    @else
                                                        <button type="submit" 
                                                                class="p-2 rounded-lg text-emerald-400 hover:bg-emerald-500/10 border border-transparent hover:border-emerald-500/20 transition" 
                                                                title="Reativar conta">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        </button>
                                                    @endif
                                                </form>

                                                <a href="{{ route('hosting.show', $acc) }}" 
                                                   class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/[0.08] border border-transparent hover:border-white/10 transition"
                                                   title="Ver Detalhes">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </a>
                                                <a href="{{ route('hosting.edit', $acc) }}" 
                                                   class="p-2 rounded-lg text-slate-400 hover:text-amber-400 hover:bg-amber-500/10 border border-transparent hover:border-amber-500/20 transition"
                                                   title="Editar Conta">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    @if ($hostingAccounts->hasPages())
                        <div class="p-6 border-t border-white/10 bg-white/[0.02]">
                            {{ $hostingAccounts->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
