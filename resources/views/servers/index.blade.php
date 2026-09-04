<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-white tracking-tight leading-tight flex items-center gap-2.5">
                    <span>Servidores & Infraestrutura</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-cyan-500/10 text-cyan-400 border border-cyan-500/30 font-bold">
                        {{ $kpis['total'] }} {{ $kpis['total'] === 1 ? 'Instância' : 'Instâncias' }}
                    </span>
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Gestão e telemetria de nós VPS, Cloud Dedicado e capacidade computacional na nuvem.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('servers.create') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40 transition-all transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Novo Servidor</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Mensagem de Sucesso Flash -->
            @if (session('success'))
                <div class="p-4 rounded-2xl bg-emerald-950/40 border border-emerald-500/40 text-emerald-300 text-xs flex items-center justify-between shadow-xl backdrop-blur-xl">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Cards de Métricas / KPIs (Dark Frosted Glass, rounded-2xl) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Total de Servidores -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl min-w-0 overflow-hidden transition">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block truncate">Servidores Ativos</span>
                    <span class="text-3xl font-black text-white mt-2 block tracking-tight">{{ $kpis['total'] }}</span>
                    <span class="text-[11px] text-slate-400 mt-1 block truncate">Nós físicos e virtuais</span>
                </div>

                <!-- Online -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl min-w-0 overflow-hidden transition">
                    <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider block truncate">Instâncias Online</span>
                    <div class="flex items-baseline gap-2 mt-2">
                        <span class="text-3xl font-black text-emerald-400 tracking-tight">{{ $kpis['online'] }}</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    </div>
                    <span class="text-[11px] text-slate-400 mt-1 block truncate">Uptime de rede operacional</span>
                </div>

                <!-- Total vCPU Cores -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl min-w-0 overflow-hidden transition">
                    <span class="text-xs font-bold text-cyan-400 uppercase tracking-wider block truncate">Capacidade vCPU</span>
                    <span class="text-3xl font-black text-cyan-400 mt-2 block tracking-tight">{{ $kpis['total_cores'] }}</span>
                    <span class="text-[11px] text-slate-400 mt-1 block truncate">Cores totais alocados</span>
                </div>

                <!-- Total RAM (GB) -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl min-w-0 overflow-hidden transition">
                    <span class="text-xs font-bold text-purple-400 uppercase tracking-wider block truncate">Memória RAM Total</span>
                    <span class="text-3xl font-black text-purple-400 mt-2 block tracking-tight">{{ $kpis['total_ram_gb'] }} GB</span>
                    <span class="text-[11px] text-slate-400 mt-1 block truncate">Memória DDR5 de alta velocidade</span>
                </div>
            </div>

            <!-- Barra de Busca e Filtros (Dark Frosted Glass, rounded-2xl) -->
            <div class="p-6 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-xl">
                <form method="GET" action="{{ route('servers.index') }}" class="flex flex-col md:flex-row items-center gap-3">
                    <div class="relative flex-grow w-full">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Buscar por nome, IP, hostname ou datacenter..." 
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-black/40 border border-white/10 text-white placeholder-slate-500 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                    </div>

                    <div class="w-full md:w-48 flex-shrink-0">
                        <select name="status" 
                                onchange="this.form.submit()"
                                class="w-full py-2.5 px-3 rounded-xl bg-black/40 border border-white/10 text-xs text-slate-200 outline-none focus:border-emerald-500">
                            <option value="" class="bg-slate-900 text-slate-300">Todos os Status</option>
                            <option value="{{ \App\Models\Server::STATUS_ONLINE }}" @selected(request('status') === \App\Models\Server::STATUS_ONLINE) class="bg-slate-900 text-emerald-400">🟢 Online</option>
                            <option value="{{ \App\Models\Server::STATUS_MAINTENANCE }}" @selected(request('status') === \App\Models\Server::STATUS_MAINTENANCE) class="bg-slate-900 text-amber-400">🟡 Manutenção</option>
                            <option value="{{ \App\Models\Server::STATUS_OFFLINE }}" @selected(request('status') === \App\Models\Server::STATUS_OFFLINE) class="bg-slate-900 text-rose-400">🔴 Offline</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2 w-full md:w-auto">
                        <button type="submit" class="w-full md:w-auto px-5 py-2.5 bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-sm whitespace-nowrap">
                            Filtrar
                        </button>

                        @if (request()->hasAny(['search', 'status']))
                            <a href="{{ route('servers.index') }}" class="px-3 py-2 text-xs text-slate-400 hover:text-white transition whitespace-nowrap">
                                Limpar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabela / Lista de Servidores (Dark Frosted Glass, rounded-2xl) -->
            <div class="bg-white/[0.06] backdrop-blur-2xl rounded-2xl border border-white/15 shadow-2xl overflow-hidden">
                @if ($servers->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 mx-auto flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-white">Nenhum servidor encontrado</h3>
                        <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                            Cadastre seu primeiro VPS ou nó de processamento na nuvem HostDevPro.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('servers.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-400 text-slate-950 font-black text-xs uppercase tracking-wider rounded-xl shadow-lg transition">
                                Cadastrar Servidor
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-white/10 bg-white/[0.03] text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                    <th class="py-4 px-6">Servidor / Hostname</th>
                                    <th class="py-4 px-6">Endereço IP</th>
                                    <th class="py-4 px-6">Provedor & Localização</th>
                                    <th class="py-4 px-6">Hardware (vCPU/RAM/Disco)</th>
                                    <th class="py-4 px-6">Hospedagens</th>
                                    <th class="py-4 px-6">Status</th>
                                    <th class="py-4 px-6 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 text-sm">
                                @foreach ($servers as $srv)
                                    <tr class="hover:bg-white/[0.04] transition-colors">
                                        <!-- Nome / Hostname -->
                                        <td class="py-4 px-6">
                                            <a href="{{ route('servers.show', $srv) }}" class="font-bold text-white hover:text-cyan-400 transition">
                                                {{ $srv->name }}
                                            </a>
                                            @if ($srv->hostname)
                                                <span class="block text-xs font-mono text-slate-400 mt-0.5">{{ $srv->hostname }}</span>
                                            @endif
                                        </td>

                                        <!-- IP Address -->
                                        <td class="py-4 px-6 font-mono text-xs text-slate-300">
                                            <span class="px-2.5 py-1 bg-black/40 rounded-lg border border-white/10 text-emerald-400 font-bold">
                                                {{ $srv->ip_address }}
                                            </span>
                                        </td>

                                        <!-- Provedor / Local -->
                                        <td class="py-4 px-6">
                                            <span class="font-semibold text-white block text-xs">{{ $srv->provider ?? 'Cloud Host' }}</span>
                                            <span class="text-[11px] text-slate-400 block">{{ $srv->datacenter_location ?? 'São Paulo SP3' }}</span>
                                        </td>

                                        <!-- Especificações -->
                                        <td class="py-4 px-6 text-xs text-slate-300">
                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                <span class="font-bold text-cyan-400">{{ $srv->cpu_cores }} vCPU</span>
                                                <span class="text-slate-600">•</span>
                                                <span class="font-bold text-purple-400">{{ $srv->ram_gb }} GB RAM</span>
                                                <span class="text-slate-600">•</span>
                                                <span>{{ $srv->disk_gb }} GB NVMe</span>
                                            </div>
                                        </td>

                                        <!-- Hospedagens -->
                                        <td class="py-4 px-6">
                                            <a href="{{ route('hosting.index', ['server_id' => $srv->id]) }}" 
                                               class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 font-bold text-xs hover:bg-emerald-500/20 transition"
                                               title="Ver contas neste servidor">
                                                <span>{{ $srv->hosting_accounts_count }}</span>
                                                <span class="text-[10px] uppercase">{{ $srv->hosting_accounts_count === 1 ? 'Conta' : 'Contas' }}</span>
                                            </a>
                                        </td>

                                        <!-- Status -->
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $srv->status_badge_classes }}">
                                                @if ($srv->status === \App\Models\Server::STATUS_ONLINE)
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                                @endif
                                                {{ $srv->status_label }}
                                            </span>
                                        </td>

                                        <!-- Ações -->
                                        <td class="py-4 px-6 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('servers.show', $srv) }}" 
                                                   class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/[0.08] border border-transparent hover:border-white/10 transition"
                                                   title="Ver Detalhes">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </a>
                                                <a href="{{ route('servers.edit', $srv) }}" 
                                                   class="p-2 rounded-lg text-slate-400 hover:text-amber-400 hover:bg-amber-500/10 border border-transparent hover:border-amber-500/20 transition"
                                                   title="Editar Servidor">
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
                    @if ($servers->hasPages())
                        <div class="p-6 border-t border-white/10 bg-white/[0.02]">
                            {{ $servers->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
