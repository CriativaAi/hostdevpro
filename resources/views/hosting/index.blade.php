<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-[#783D19] leading-tight flex items-center gap-2.5">
                    <span>Contas de Hospedagem</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-[#5F6F52]/10 text-[#5F6F52] border border-[#5F6F52]/20 font-semibold">
                        {{ $kpis['total'] }} {{ $kpis['total'] === 1 ? 'Domínio' : 'Domínios' }}
                    </span>
                </h2>
                <p class="text-xs text-gray-500 mt-1">
                    Gestão centralizada de sites, bancos de dados, cotas de disco e certificados SSL.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('hosting.create') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#5F6F52] hover:bg-[#48563e] text-white font-bold text-xs uppercase tracking-wider shadow-md hover:shadow-lg transition">
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
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2.5 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Cards de Métricas / KPIs -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total -->
                <div class="bg-white p-5 rounded-2xl border border-[#B99470]/25 shadow-sm">
                    <span class="text-xs font-bold text-[#5F6F52] uppercase tracking-wider block">Total de Hospedagens</span>
                    <span class="text-3xl font-extrabold text-[#783D19] mt-2 block">{{ $kpis['total'] }}</span>
                    <span class="text-[11px] text-gray-400 mt-1 block">Domínios cadastrados</span>
                </div>

                <!-- Ativas -->
                <div class="bg-white p-5 rounded-2xl border border-[#B99470]/25 shadow-sm">
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider block">Contas Ativas</span>
                    <div class="flex items-baseline gap-2 mt-2">
                        <span class="text-3xl font-extrabold text-emerald-700">{{ $kpis['active'] }}</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    </div>
                    <span class="text-[11px] text-gray-400 mt-1 block">Websites no ar</span>
                </div>

                <!-- Suspensas -->
                <div class="bg-white p-5 rounded-2xl border border-[#B99470]/25 shadow-sm">
                    <span class="text-xs font-bold text-rose-700 uppercase tracking-wider block">Suspensas</span>
                    <span class="text-3xl font-extrabold text-rose-700 mt-2 block">{{ $kpis['suspended'] }}</span>
                    <span class="text-[11px] text-gray-400 mt-1 block">Inadimplência ou manutenção</span>
                </div>

                <!-- SSL Ativo -->
                <div class="bg-white p-5 rounded-2xl border border-[#B99470]/25 shadow-sm">
                    <span class="text-xs font-bold text-indigo-700 uppercase tracking-wider block">SSL Automatizado</span>
                    <span class="text-3xl font-extrabold text-indigo-700 mt-2 block">{{ $kpis['ssl_active'] }}</span>
                    <span class="text-[11px] text-gray-400 mt-1 block">Let's Encrypt protegido</span>
                </div>
            </div>

            <!-- Barra de Busca e Filtros Combinados -->
            <div class="bg-white p-4 rounded-2xl border border-[#B99470]/25 shadow-sm">
                <form method="GET" action="{{ route('hosting.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                    <!-- Busca Textual -->
                    <div class="lg:col-span-2 relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Buscar por domínio, cliente ou usuário..." 
                               class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-200 text-xs focus:border-[#C4661F] focus:ring-2 focus:ring-[#C4661F]/20 outline-none transition">
                    </div>

                    <!-- Filtro por Cliente -->
                    <div>
                        <select name="client_id" onchange="this.form.submit()" class="w-full py-2 px-3 rounded-xl border border-gray-200 text-xs text-gray-700 outline-none focus:border-[#C4661F]">
                            <option value="">Todos os Clientes</option>
                            @foreach ($clients as $c)
                                <option value="{{ $c->id }}" @selected(request('client_id') == $c->id)>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro por Servidor -->
                    <div>
                        <select name="server_id" onchange="this.form.submit()" class="w-full py-2 px-3 rounded-xl border border-gray-200 text-xs text-gray-700 outline-none focus:border-[#C4661F]">
                            <option value="">Todos os Servidores</option>
                            @foreach ($servers as $s)
                                <option value="{{ $s->id }}" @selected(request('server_id') == $s->id)>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro por Status -->
                    <div>
                        <select name="status" onchange="this.form.submit()" class="w-full py-2 px-3 rounded-xl border border-gray-200 text-xs text-gray-700 outline-none focus:border-[#C4661F]">
                            <option value="">Todos os Status</option>
                            <option value="{{ \App\Models\HostingAccount::STATUS_ACTIVE }}" @selected(request('status') === \App\Models\HostingAccount::STATUS_ACTIVE)>🟢 Ativa</option>
                            <option value="{{ \App\Models\HostingAccount::STATUS_SUSPENDED }}" @selected(request('status') === \App\Models\HostingAccount::STATUS_SUSPENDED)>🔴 Suspensa</option>
                            <option value="{{ \App\Models\HostingAccount::STATUS_PENDING }}" @selected(request('status') === \App\Models\HostingAccount::STATUS_PENDING)>🟡 Pendente</option>
                        </select>
                    </div>

                    <!-- Botão Filtrar / Limpar -->
                    <div class="flex items-center gap-2">
                        <button type="submit" class="w-full py-2 px-4 bg-[#5F6F52] hover:bg-[#48563e] text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-sm">
                            Filtrar
                        </button>
                        @if (request()->hasAny(['search', 'client_id', 'server_id', 'status', 'plan']))
                            <a href="{{ route('hosting.index') }}" class="px-2 text-xs text-gray-500 hover:text-gray-700 transition">
                                Limpar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabela de Contas de Hospedagem -->
            <div class="bg-white rounded-2xl border border-[#B99470]/25 shadow-sm overflow-hidden">
                @if ($hostingAccounts->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 rounded-full bg-[#FEFAE0] text-[#5F6F52] mx-auto flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-[#783D19]">Nenhuma conta de hospedagem encontrada</h3>
                        <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">
                            Provisione uma nova conta para associar um domínio a um cliente e servidor.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('hosting.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#5F6F52] text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow hover:bg-[#48563e] transition">
                                Nova Hospedagem
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-100 bg-[#FEFAE0]/50 text-[11px] font-bold uppercase tracking-wider text-[#5F6F52]">
                                    <th class="py-3.5 px-6">Domínio</th>
                                    <th class="py-3.5 px-6">Cliente</th>
                                    <th class="py-3.5 px-6">Servidor VPS</th>
                                    <th class="py-3.5 px-6">Plano & PHP</th>
                                    <th class="py-3.5 px-6">Armazenamento</th>
                                    <th class="py-3.5 px-6">SSL</th>
                                    <th class="py-3.5 px-6">Status</th>
                                    <th class="py-3.5 px-6 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @foreach ($hostingAccounts as $acc)
                                    <tr class="hover:bg-gray-50/80 transition-colors">
                                        <!-- Domínio -->
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('hosting.show', $acc) }}" class="font-bold text-[#783D19] hover:text-[#C4661F] transition">
                                                    {{ $acc->domain }}
                                                </a>
                                                <a href="https://{{ $acc->domain }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-[#C4661F]" title="Visitar site">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                </a>
                                            </div>
                                            @if ($acc->username)
                                                <span class="block text-[11px] font-mono text-gray-400 mt-0.5">User: {{ $acc->username }}</span>
                                            @endif
                                        </td>

                                        <!-- Cliente -->
                                        <td class="py-4 px-6 text-xs text-gray-700">
                                            <a href="{{ route('clients.show', $acc->client) }}" class="font-semibold text-gray-800 hover:text-[#5F6F52] hover:underline block">
                                                {{ $acc->client->name }}
                                            </a>
                                            <span class="text-[11px] text-gray-400 block">{{ $acc->client->company ?? 'Pessoa Física' }}</span>
                                        </td>

                                        <!-- Servidor -->
                                        <td class="py-4 px-6 text-xs">
                                            <a href="{{ route('servers.show', $acc->server) }}" class="font-bold text-gray-800 hover:underline block">
                                                {{ $acc->server->name }}
                                            </a>
                                            <span class="font-mono text-[11px] text-gray-400 block">{{ $acc->server->ip_address }}</span>
                                        </td>

                                        <!-- Plano & PHP -->
                                        <td class="py-4 px-6 text-xs">
                                            <span class="font-semibold text-gray-800 block">{{ $acc->plan_label }}</span>
                                            <span class="font-mono text-[11px] font-bold text-[#5F6F52] block">PHP {{ $acc->php_version }}</span>
                                        </td>

                                        <!-- Uso de Disco -->
                                        <td class="py-4 px-6">
                                            <div class="w-28 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                                <div class="bg-[#5F6F52] h-1.5 rounded-full" style="width: {{ $acc->disk_usage_percentage }}%"></div>
                                            </div>
                                            <span class="text-[11px] text-gray-500 font-mono mt-1 block">
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
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
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
                                                                class="p-1.5 rounded-lg text-amber-600 hover:bg-amber-50 transition" 
                                                                title="Suspender conta"
                                                                onclick="return confirm('Deseja suspender a conta {{ $acc->domain }}?');">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        </button>
                                                    @else
                                                        <button type="submit" 
                                                                class="p-1.5 rounded-lg text-emerald-600 hover:bg-emerald-50 transition" 
                                                                title="Reativar conta">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        </button>
                                                    @endif
                                                </form>

                                                <a href="{{ route('hosting.show', $acc) }}" 
                                                   class="p-1.5 rounded-lg text-gray-400 hover:text-[#5F6F52] hover:bg-gray-100 transition"
                                                   title="Ver Detalhes">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </a>
                                                <a href="{{ route('hosting.edit', $acc) }}" 
                                                   class="p-1.5 rounded-lg text-gray-400 hover:text-[#C4661F] hover:bg-gray-100 transition"
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

                    @if ($hostingAccounts->hasPages())
                        <div class="p-4 border-t border-gray-100 bg-[#FEFAE0]/30">
                            {{ $hostingAccounts->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
