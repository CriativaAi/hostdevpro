<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="font-bold text-2xl text-[#783D19] leading-tight">
                        {{ $server->name }}
                    </h2>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $server->status_badge_classes }}">
                        @if ($server->status === \App\Models\Server::STATUS_ONLINE)
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        @endif
                        {{ $server->status_label }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-1 font-mono">
                    IP: {{ $server->ip_address }}:{{ $server->ssh_port }} • {{ $server->hostname ?? 'Sem hostname configurado' }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('hosting.create', ['server_id' => $server->id]) }}" 
                   class="px-4 py-2 rounded-xl bg-[#5F6F52] hover:bg-[#48563e] text-white font-bold text-xs uppercase tracking-wider shadow transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Nova Hospedagem</span>
                </a>
                <a href="{{ route('servers.edit', $server) }}" 
                   class="px-4 py-2 rounded-xl bg-white border border-gray-300 text-gray-700 font-semibold text-xs uppercase tracking-wider hover:bg-gray-50 transition">
                    Editar
                </a>
                <a href="{{ route('servers.index') }}" 
                   class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 font-semibold text-xs uppercase tracking-wider hover:bg-gray-50 transition">
                    &larr; Voltar
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

            <!-- Grid de Especificações de Hardware e Rede -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Hardware Specs -->
                <div class="bg-white rounded-3xl p-6 border border-[#B99470]/25 shadow-sm">
                    <h3 class="text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#5F6F52]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                        <span>Especificações Técnicas</span>
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between py-1.5 border-b border-gray-100">
                            <span class="text-gray-500 text-xs">vCPU Cores:</span>
                            <span class="font-bold text-[#783D19]">{{ $server->cpu_cores }} Cores</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-gray-100">
                            <span class="text-gray-500 text-xs">Memória RAM:</span>
                            <span class="font-bold text-[#783D19]">{{ $server->ram_gb }} GB ({{ $server->ram_mb }} MB)</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-gray-100">
                            <span class="text-gray-500 text-xs">Armazenamento:</span>
                            <span class="font-bold text-[#783D19]">{{ $server->disk_gb }} GB NVMe/SSD</span>
                        </div>
                        <div class="flex justify-between py-1.5">
                            <span class="text-gray-500 text-xs">Sistema:</span>
                            <span class="font-semibold text-gray-700 text-right">{{ $server->os ?? 'Linux' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Rede e Acesso -->
                <div class="bg-white rounded-3xl p-6 border border-[#B99470]/25 shadow-sm">
                    <h3 class="text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#5F6F52]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        <span>Conectividade & Datacenter</span>
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between py-1.5 border-b border-gray-100">
                            <span class="text-gray-500 text-xs">Endereço IP:</span>
                            <span class="font-mono font-bold text-[#783D19]">{{ $server->ip_address }}</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-gray-100">
                            <span class="text-gray-500 text-xs">Porta SSH:</span>
                            <span class="font-mono font-bold text-gray-700">{{ $server->ssh_port }}</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-gray-100">
                            <span class="text-gray-500 text-xs">Provedor:</span>
                            <span class="font-bold text-[#C4661F]">{{ $server->provider ?? 'Cloud Host' }}</span>
                        </div>
                        <div class="flex justify-between py-1.5">
                            <span class="text-gray-500 text-xs">Localização:</span>
                            <span class="font-semibold text-gray-700 text-right">{{ $server->datacenter_location ?? 'Global' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Resumo de Alocação -->
                <div class="bg-white rounded-3xl p-6 border border-[#B99470]/25 shadow-sm flex flex-col justify-between">
                    <div>
                        <h3 class="text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#5F6F52]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span>Hospedagens no Servidor</span>
                        </h3>
                        <div class="mt-2 text-center py-4 bg-[#FEFAE0]/40 rounded-2xl border border-[#B99470]/20">
                            <span class="text-4xl font-extrabold text-[#783D19] block">{{ $server->hostingAccounts->count() }}</span>
                            <span class="text-xs text-gray-500 font-medium">Contas Ativas</span>
                        </div>
                    </div>

                    @if ($server->notes)
                        <div class="mt-4 pt-3 border-t border-gray-100 text-xs text-gray-500 italic">
                            &ldquo;{{ $server->notes }}&rdquo;
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tabela de Contas de Hospedagem Neste Servidor -->
            <div class="bg-white rounded-3xl border border-[#B99470]/25 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h3 class="font-bold text-lg text-[#783D19]">
                            Contas de Hospedagem Vinculadas
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Websites, domínios e aplicações atualmente provisionados neste servidor.
                        </p>
                    </div>
                    <a href="{{ route('hosting.create', ['server_id' => $server->id]) }}" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#5F6F52] hover:bg-[#48563e] text-white text-xs font-bold uppercase tracking-wider transition shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Adicionar Conta</span>
                    </a>
                </div>

                @if ($server->hostingAccounts->isEmpty())
                    <div class="p-8 text-center text-xs text-gray-400">
                        Nenhuma conta de hospedagem vinculada a este servidor até o momento.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-[#FEFAE0]/50 text-[11px] font-bold uppercase tracking-wider text-[#5F6F52] border-b border-gray-100">
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
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($server->hostingAccounts as $acc)
                                    <tr class="hover:bg-gray-50/80 transition-colors">
                                        <td class="py-3.5 px-6 font-bold text-[#783D19]">
                                            <a href="{{ route('hosting.show', $acc) }}" class="hover:text-[#C4661F] transition">
                                                {{ $acc->domain }}
                                            </a>
                                        </td>
                                        <td class="py-3.5 px-6 text-xs text-gray-700">
                                            <a href="{{ route('clients.show', $acc->client) }}" class="hover:underline">
                                                {{ $acc->client->name }}
                                            </a>
                                        </td>
                                        <td class="py-3.5 px-6 text-xs text-gray-600">
                                            {{ $acc->plan_label }}
                                        </td>
                                        <td class="py-3.5 px-6 text-xs font-mono font-bold text-[#5F6F52]">
                                            PHP {{ $acc->php_version }}
                                        </td>
                                        <td class="py-3.5 px-6 text-xs">
                                            <div class="flex items-center gap-2">
                                                <div class="w-20 bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                                    <div class="bg-[#5F6F52] h-1.5 rounded-full" style="width: {{ $acc->disk_usage_percentage }}%"></div>
                                                </div>
                                                <span class="text-[11px] text-gray-500 font-mono">{{ $acc->disk_used_gb }} / {{ $acc->disk_quota_gb }} GB</span>
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
                                            <a href="{{ route('hosting.show', $acc) }}" class="text-xs text-[#5F6F52] font-semibold hover:underline">
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
            <div class="p-6 bg-red-50/50 rounded-3xl border border-red-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <h4 class="text-sm font-bold text-red-800">Remover este Servidor</h4>
                    <p class="text-xs text-red-600 mt-0.5">
                        Esta ação enviará o servidor para a lixeira lógica. Contas de hospedagem ativas podem ser afetadas.
                    </p>
                </div>
                <form method="POST" action="{{ route('servers.destroy', $server) }}" onsubmit="return confirm('Tem certeza que deseja remover este servidor?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-sm">
                        Excluir Servidor
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
