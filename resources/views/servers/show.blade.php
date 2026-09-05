<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <h2 class="font-black text-2xl text-white tracking-tight leading-tight">
                        {{ $server->name }}
                    </h2>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $server->status_badge_classes }}">
                        @if ($server->status === \App\Models\Server::STATUS_ONLINE)
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        @endif
                        {{ $server->status_label }}
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-1 font-mono">
                    IP: <span class="text-cyan-300 font-bold">{{ $server->ip_address }}</span>:{{ $server->ssh_port }} • {{ $server->hostname ?? 'Sem hostname configurado' }}
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('hosting.create', ['server_id' => $server->id]) }}" 
                   class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Nova Hospedagem</span>
                </a>
                <a href="{{ route('servers.edit', $server) }}" 
                   class="px-4 py-2 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider transition">
                    Editar
                </a>
                <a href="{{ route('servers.index') }}" 
                   class="px-4 py-2 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider transition">
                    &larr; Voltar
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

            <!-- Grid de Especificações de Hardware e Rede -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Hardware Specs -->
                <div class="bg-white/[0.06] backdrop-blur-xl rounded-3xl p-6 border border-white/15 shadow-xl">
                    <h3 class="text-xs font-bold text-emerald-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                        <span>Especificações Técnicas</span>
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between py-1.5 border-b border-white/10">
                            <span class="text-slate-400 text-xs">vCPU Cores:</span>
                            <span class="font-bold text-white">{{ $server->cpu_cores }} Cores</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-white/10">
                            <span class="text-slate-400 text-xs">Memória RAM:</span>
                            <span class="font-bold text-white">{{ $server->ram_gb }} GB ({{ $server->ram_mb }} MB)</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-white/10">
                            <span class="text-slate-400 text-xs">Armazenamento:</span>
                            <span class="font-bold text-white">{{ $server->disk_gb }} GB NVMe/SSD</span>
                        </div>
                        <div class="flex justify-between py-1.5">
                            <span class="text-slate-400 text-xs">Sistema:</span>
                            <span class="font-semibold text-slate-200 text-right">{{ $server->os ?? 'Linux' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Rede e Acesso -->
                <div class="bg-white/[0.06] backdrop-blur-xl rounded-3xl p-6 border border-white/15 shadow-xl">
                    <h3 class="text-xs font-bold text-cyan-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        <span>Conectividade & Datacenter</span>
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between py-1.5 border-b border-white/10">
                            <span class="text-slate-400 text-xs">Endereço IP:</span>
                            <span class="font-mono font-bold text-cyan-300">{{ $server->ip_address }}</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-white/10">
                            <span class="text-slate-400 text-xs">Porta SSH:</span>
                            <span class="font-mono font-bold text-slate-200">{{ $server->ssh_port }}</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-white/10">
                            <span class="text-slate-400 text-xs">Provedor:</span>
                            <span class="font-bold text-amber-400">{{ $server->provider ?? 'Cloud Host' }}</span>
                        </div>
                        <div class="flex justify-between py-1.5">
                            <span class="text-slate-400 text-xs">Localização:</span>
                            <span class="font-semibold text-slate-200 text-right">{{ $server->datacenter_location ?? 'Global' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Resumo de Alocação -->
                <div class="bg-white/[0.06] backdrop-blur-xl rounded-3xl p-6 border border-white/15 shadow-xl flex flex-col justify-between">
                    <div>
                        <h3 class="text-xs font-bold text-purple-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span>Hospedagens no Servidor</span>
                        </h3>
                        <div class="mt-2 text-center py-4 bg-slate-900/80 rounded-2xl border border-white/10">
                            <span class="text-4xl font-black text-white block">{{ $server->hostingAccounts->count() }}</span>
                            <span class="text-xs text-slate-400 font-medium">Contas Ativas</span>
                        </div>
                    </div>

                    @if ($server->notes)
                        <div class="mt-4 pt-3 border-t border-white/10 text-xs text-slate-400 italic">
                            &ldquo;{{ $server->notes }}&rdquo;
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tabela de Contas de Hospedagem Neste Servidor -->
            <div class="bg-white/[0.06] backdrop-blur-xl rounded-3xl border border-white/15 shadow-xl overflow-hidden">
                <div class="p-6 border-b border-white/10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h3 class="font-black text-lg text-white">
                            Contas de Hospedagem Vinculadas
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Websites, domínios e aplicações atualmente provisionados neste servidor.
                        </p>
                    </div>
                    <a href="{{ route('hosting.create', ['server_id' => $server->id]) }}" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 text-slate-950 text-xs font-black uppercase tracking-wider transition shadow-lg shadow-emerald-500/20">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Adicionar Conta</span>
                    </a>
                </div>

                @if ($server->hostingAccounts->isEmpty())
                    <div class="p-8 text-center text-xs text-slate-400">
                        Nenhuma conta de hospedagem vinculada a este servidor até o momento.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-900/90 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-white/10">
                                    <th class="py-3 px-6">Domínio</th>
                                    <th class="py-3 px-6">Cliente</th>
                                    <th class="py-3 px-6">Plano</th>
                                    <th class="py-3 px-6">PHP</th>
                                    <th class="py-3 px-6">Uso de Disco</th>
                                    <th class="py-3 px-6">SSL</th>
                                    <th class="py-3 px-6">Status</th>
                                    <th class="py-3 px-6 text-right">Ação</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach ($server->hostingAccounts as $acc)
                                    <tr class="hover:bg-white/[0.04] transition-colors">
                                        <td class="py-3.5 px-6 font-bold text-white">
                                            <a href="{{ route('hosting.show', $acc) }}" class="hover:text-cyan-400 transition font-mono">
                                                {{ $acc->domain }}
                                            </a>
                                        </td>
                                        <td class="py-3.5 px-6 text-xs text-slate-300">
                                            <a href="{{ route('clients.show', $acc->client) }}" class="hover:underline hover:text-white">
                                                {{ $acc->client->name }}
                                            </a>
                                        </td>
                                        <td class="py-3.5 px-6 text-xs text-slate-400">
                                            {{ $acc->plan_label }}
                                        </td>
                                        <td class="py-3.5 px-6 text-xs font-mono font-bold text-emerald-400">
                                            PHP {{ $acc->php_version }}
                                        </td>
                                        <td class="py-3.5 px-6 text-xs">
                                            <div class="flex items-center gap-2">
                                                <div class="w-20 bg-slate-900 rounded-full h-1.5 overflow-hidden border border-white/10">
                                                    <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-1.5 rounded-full" style="width: {{ $acc->disk_usage_percentage }}%"></div>
                                                </div>
                                                <span class="text-[11px] text-slate-400 font-mono">{{ $acc->disk_used_gb }} / {{ $acc->disk_quota_gb }} GB</span>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-6">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold border {{ $acc->ssl_badge_classes }}">
                                                {{ ucfirst($acc->ssl_status) }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-6">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold border {{ $acc->status_badge_classes }}">
                                                {{ $acc->status_label }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-6 text-right">
                                            <a href="{{ route('hosting.show', $acc) }}" class="text-xs text-emerald-400 font-semibold hover:underline">
                                                Detalhes &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Exclusão do Servidor -->
            <div class="p-6 bg-red-950/20 backdrop-blur-xl rounded-3xl border border-red-500/20 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <h4 class="text-sm font-bold text-red-400">Remover este Servidor</h4>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Esta ação enviará o servidor para a lixeira lógica. Contas de hospedagem ativas podem ser afetadas.
                    </p>
                </div>
                <form method="POST" action="{{ route('servers.destroy', $server) }}" onsubmit="return confirm('Tem certeza que deseja remover este servidor?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 text-red-400 font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-sm">
                        Excluir Servidor
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
