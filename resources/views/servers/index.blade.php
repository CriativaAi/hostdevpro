<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-[#783D19] leading-tight flex items-center gap-2.5">
                    <span>Servidores & Infraestrutura</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-[#5F6F52]/10 text-[#5F6F52] border border-[#5F6F52]/20 font-semibold">
                        {{ $kpis['total'] }} {{ $kpis['total'] === 1 ? 'Instância' : 'Instâncias' }}
                    </span>
                </h2>
                <p class="text-xs text-gray-500 mt-1">
                    Gestão e monitoramento de nós VPS, Cloud Dedicado e capacidade computacional.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('servers.create') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#5F6F52] hover:bg-[#48563e] text-white font-bold text-xs uppercase tracking-wider shadow-md hover:shadow-lg transition">
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
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Cards de Métricas / KPIs -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total de Servidores -->
                <div class="bg-white p-5 rounded-2xl border border-[#B99470]/25 shadow-sm">
                    <span class="text-xs font-bold text-[#5F6F52] uppercase tracking-wider block">Servidores Ativos</span>
                    <span class="text-3xl font-extrabold text-[#783D19] mt-2 block">{{ $kpis['total'] }}</span>
                    <span class="text-[11px] text-gray-400 mt-1 block">Nós físicos e virtuais</span>
                </div>

                <!-- Online -->
                <div class="bg-white p-5 rounded-2xl border border-[#B99470]/25 shadow-sm">
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider block">Instâncias Online</span>
                    <div class="flex items-baseline gap-2 mt-2">
                        <span class="text-3xl font-extrabold text-emerald-700">{{ $kpis['online'] }}</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    </div>
                    <span class="text-[11px] text-gray-400 mt-1 block">Uptime de rede operacional</span>
                </div>

                <!-- Total vCPU Cores -->
                <div class="bg-white p-5 rounded-2xl border border-[#B99470]/25 shadow-sm">
                    <span class="text-xs font-bold text-[#C4661F] uppercase tracking-wider block">Capacidade vCPU</span>
                    <span class="text-3xl font-extrabold text-[#C4661F] mt-2 block">{{ $kpis['total_cores'] }}</span>
                    <span class="text-[11px] text-gray-400 mt-1 block">Cores totais alocados</span>
                </div>

                <!-- Total RAM (GB) -->
                <div class="bg-white p-5 rounded-2xl border border-[#B99470]/25 shadow-sm">
                    <span class="text-xs font-bold text-indigo-700 uppercase tracking-wider block">Memória RAM Total</span>
                    <span class="text-3xl font-extrabold text-indigo-700 mt-2 block">{{ $kpis['total_ram_gb'] }} GB</span>
                    <span class="text-[11px] text-gray-400 mt-1 block">Memória de alta performance</span>
                </div>
            </div>

            <!-- Barra de Busca e Filtros -->
            <div class="bg-white p-4 rounded-2xl border border-[#B99470]/25 shadow-sm">
                <form method="GET" action="{{ route('servers.index') }}" class="flex flex-col md:flex-row items-center gap-3">
                    <div class="relative flex-grow w-full">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Buscar por nome, IP, hostname, provedor ou datacenter..." 
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-[#C4661F] focus:ring-2 focus:ring-[#C4661F]/20 outline-none transition">
                    </div>

                    <div class="w-full md:w-48">
                        <select name="status" 
                                onchange="this.form.submit()"
                                class="w-full py-2.5 px-3 rounded-xl border border-gray-200 text-sm focus:border-[#C4661F] focus:ring-2 focus:ring-[#C4661F]/20 outline-none transition text-gray-700">
                            <option value="">Todos os Status</option>
                            <option value="{{ \App\Models\Server::STATUS_ONLINE }}" @selected(request('status') === \App\Models\Server::STATUS_ONLINE)>🟢 Online</option>
                            <option value="{{ \App\Models\Server::STATUS_MAINTENANCE }}" @selected(request('status') === \App\Models\Server::STATUS_MAINTENANCE)>🟡 Manutenção</option>
                            <option value="{{ \App\Models\Server::STATUS_OFFLINE }}" @selected(request('status') === \App\Models\Server::STATUS_OFFLINE)>🔴 Offline</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full md:w-auto px-5 py-2.5 bg-[#5F6F52] hover:bg-[#48563e] text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-sm">
                        Filtrar
                    </button>

                    @if (request()->hasAny(['search', 'status']))
                        <a href="{{ route('servers.index') }}" class="w-full md:w-auto px-3 py-2 text-xs text-gray-500 hover:text-gray-700 font-semibold text-center transition">
                            Limpar
                        </a>
                    @endif
                </form>
            </div>

            <!-- Tabela / Lista de Servidores -->
            <div class="bg-white rounded-2xl border border-[#B99470]/25 shadow-sm overflow-hidden">
                @if ($servers->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 rounded-full bg-[#FEFAE0] text-[#5F6F52] mx-auto flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-[#783D19]">Nenhum servidor encontrado</h3>
                        <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">
                            Cadastre seu primeiro VPS ou servidor dedicado para começar a hospedar aplicações.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('servers.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#5F6F52] text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow hover:bg-[#48563e] transition">
                                Cadastrar Servidor
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-100 bg-[#FEFAE0]/50 text-[11px] font-bold uppercase tracking-wider text-[#5F6F52]">
                                    <th class="py-3.5 px-6">Servidor / Hostname</th>
                                    <th class="py-3.5 px-6">Endereço IP</th>
                                    <th class="py-3.5 px-6">Provedor & Localização</th>
                                    <th class="py-3.5 px-6">Hardware (vCPU/RAM/Disco)</th>
                                    <th class="py-3.5 px-6">Hospedagens</th>
                                    <th class="py-3.5 px-6">Status</th>
                                    <th class="py-3.5 px-6 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @foreach ($servers as $srv)
                                    <tr class="hover:bg-gray-50/80 transition-colors">
                                        <!-- Nome / Hostname -->
                                        <td class="py-4 px-6">
                                            <a href="{{ route('servers.show', $srv) }}" class="font-bold text-[#783D19] hover:text-[#C4661F] transition">
                                                {{ $srv->name }}
                                            </a>
                                            @if ($srv->hostname)
                                                <span class="block text-xs font-mono text-gray-400 mt-0.5">{{ $srv->hostname }}</span>
                                            @endif
                                        </td>

                                        <!-- IP Address -->
                                        <td class="py-4 px-6 font-mono text-xs text-gray-700">
                                            <span class="px-2 py-1 bg-gray-100 rounded-md border border-gray-200">
                                                {{ $srv->ip_address }}
                                            </span>
                                        </td>

                                        <!-- Provedor / Local -->
                                        <td class="py-4 px-6">
                                            <span class="font-semibold text-gray-800 block text-xs">{{ $srv->provider ?? 'Cloud Host' }}</span>
                                            <span class="text-[11px] text-gray-400 block">{{ $srv->datacenter_location ?? 'Global' }}</span>
                                        </td>

                                        <!-- Especificações -->
                                        <td class="py-4 px-6 text-xs text-gray-600">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-[#5F6F52]">{{ $srv->cpu_cores }} vCPU</span>
                                                <span>•</span>
                                                <span class="font-bold text-[#783D19]">{{ $srv->ram_gb }} GB RAM</span>
                                                <span>•</span>
                                                <span>{{ $srv->disk_gb }} GB NVMe</span>
                                            </div>
                                        </td>

                                        <!-- Hospedagens -->
                                        <td class="py-4 px-6">
                                            <a href="{{ route('hosting.index', ['server_id' => $srv->id]) }}" 
                                               class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#5F6F52]/10 text-[#5F6F52] font-semibold text-xs hover:bg-[#5F6F52]/20 transition"
                                               title="Ver contas neste servidor">
                                                <span>{{ $srv->hosting_accounts_count }}</span>
                                                <span class="text-[10px] uppercase font-bold">{{ $srv->hosting_accounts_count === 1 ? 'Conta' : 'Contas' }}</span>
                                            </a>
                                        </td>

                                        <!-- Status -->
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $srv->status_badge_classes }}">
                                                @if ($srv->status === \App\Models\Server::STATUS_ONLINE)
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                @endif
                                                {{ $srv->status_label }}
                                            </span>
                                        </td>

                                        <!-- Ações -->
                                        <td class="py-4 px-6 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('servers.show', $srv) }}" 
                                                   class="p-1.5 rounded-lg text-gray-400 hover:text-[#5F6F52] hover:bg-gray-100 transition"
                                                   title="Raio-X do Servidor">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </a>
                                                <a href="{{ route('servers.edit', $srv) }}" 
                                                   class="p-1.5 rounded-lg text-gray-400 hover:text-[#C4661F] hover:bg-gray-100 transition"
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
                    
                    @if ($servers->hasPages())
                        <div class="p-4 border-t border-gray-100 bg-[#FEFAE0]/30">
                            {{ $servers->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
