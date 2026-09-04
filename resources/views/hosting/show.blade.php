<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="font-bold text-2xl text-[#783D19] leading-tight">
                        {{ $hosting->domain }}
                    </h2>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $hosting->status_badge_classes }}">
                        @if ($hosting->status === \App\Models\HostingAccount::STATUS_ACTIVE)
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        @endif
                        {{ $hosting->status_label }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $hosting->ssl_badge_classes }}">
                        🔒 SSL {{ ucfirst($hosting->ssl_status) }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    Hospedada no servidor <strong class="text-gray-700">{{ $hosting->server->name }}</strong> ({{ $hosting->server->ip_address }})
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="https://{{ $hosting->domain }}" target="_blank" rel="noopener noreferrer"
                   class="px-4 py-2 rounded-xl bg-[#C4661F] hover:bg-[#a85314] text-white font-bold text-xs uppercase tracking-wider shadow transition flex items-center gap-1.5">
                    <span>Visitar Site</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                
                <!-- Toggle Suspensão -->
                <form method="POST" action="{{ route('hosting.toggle-status', $hosting) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    @if ($hosting->status === \App\Models\HostingAccount::STATUS_ACTIVE)
                        <button type="submit" 
                                class="px-4 py-2 rounded-xl bg-amber-100 border border-amber-300 text-amber-800 hover:bg-amber-200 font-bold text-xs uppercase tracking-wider transition"
                                onclick="return confirm('Deseja suspender esta hospedagem?');">
                            Suspender
                        </button>
                    @else
                        <button type="submit" 
                                class="px-4 py-2 rounded-xl bg-emerald-100 border border-emerald-300 text-emerald-800 hover:bg-emerald-200 font-bold text-xs uppercase tracking-wider transition">
                            Reativar
                        </button>
                    @endif
                </form>

                <a href="{{ route('hosting.edit', $hosting) }}" 
                   class="px-4 py-2 rounded-xl bg-white border border-gray-300 text-gray-700 font-semibold text-xs uppercase tracking-wider hover:bg-gray-50 transition">
                    Editar
                </a>
                <a href="{{ route('hosting.index') }}" 
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

            <!-- Alerta se estiver suspensa -->
            @if ($hosting->status === \App\Models\HostingAccount::STATUS_SUSPENDED)
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">⚠️</span>
                        <div>
                            <span class="font-bold block">Esta conta de hospedagem está suspensa</span>
                            <span class="text-xs text-rose-700">Motivo: {{ $hosting->suspended_reason ?? 'Suspensão administrativa' }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Grid de Informações Principais -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Cliente Proprietário -->
                <div class="bg-white rounded-3xl p-6 border border-[#B99470]/25 shadow-sm">
                    <h3 class="text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#5F6F52]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Cliente Titular</span>
                    </h3>
                    <div class="space-y-2">
                        <a href="{{ route('clients.show', $hosting->client) }}" class="font-bold text-base text-[#783D19] hover:text-[#C4661F] transition block">
                            {{ $hosting->client->name }}
                        </a>
                        <span class="text-xs text-gray-500 block">{{ $hosting->client->company ?? 'Pessoa Física' }}</span>
                        <span class="text-xs text-gray-500 font-mono block">{{ $hosting->client->email }}</span>
                        @if ($hosting->client->phone)
                            <span class="text-xs text-gray-500 block">{{ $hosting->client->phone }}</span>
                        @endif
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100">
                        <a href="{{ route('clients.show', $hosting->client) }}" class="text-xs text-[#5F6F52] font-semibold hover:underline">
                            Ver prontuário do cliente &rarr;
                        </a>
                    </div>
                </div>

                <!-- Servidor VPS -->
                <div class="bg-white rounded-3xl p-6 border border-[#B99470]/25 shadow-sm">
                    <h3 class="text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#5F6F52]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                        <span>Servidor Vinculado</span>
                    </h3>
                    <div class="space-y-2 text-sm">
                        <a href="{{ route('servers.show', $hosting->server) }}" class="font-bold text-[#783D19] hover:underline block">
                            {{ $hosting->server->name }}
                        </a>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="text-gray-500">IP:</span>
                            <span class="font-mono font-bold text-gray-800">{{ $hosting->server->ip_address }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="text-gray-500">Provedor:</span>
                            <span class="text-gray-700 font-medium">{{ $hosting->server->provider ?? 'Host' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="text-gray-500">Localização:</span>
                            <span class="text-gray-700 font-medium">{{ $hosting->server->datacenter_location ?? 'Brasil' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Plano & Runtime -->
                <div class="bg-white rounded-3xl p-6 border border-[#B99470]/25 shadow-sm">
                    <h3 class="text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#5F6F52]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>Plano & Recursos</span>
                    </h3>
                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between py-1 border-b border-gray-100">
                            <span class="text-gray-500">Plano Ativo:</span>
                            <span class="font-bold text-[#783D19]">{{ $hosting->plan_label }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-100">
                            <span class="text-gray-500">PHP Version:</span>
                            <span class="font-mono font-bold text-[#5F6F52]">PHP {{ $hosting->php_version }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-500">Tráfego Mensal:</span>
                            <span class="font-mono font-semibold text-gray-700">{{ round($hosting->bandwidth_quota_mb / 1024, 0) }} GB</span>
                        </div>
                        
                        <!-- Barra de Disco -->
                        <div class="pt-2">
                            <div class="flex justify-between text-[11px] mb-1">
                                <span class="text-gray-500">Armazenamento:</span>
                                <span class="font-bold font-mono text-gray-700">{{ $hosting->disk_used_gb }} / {{ $hosting->disk_quota_gb }} GB</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div class="bg-[#5F6F52] h-2 rounded-full" style="width: {{ $hosting->disk_usage_percentage }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guia de Apontamento DNS & Desacoplamento de E-mails (ValueHost) -->
            <div class="bg-white rounded-3xl p-6 md:p-8 border border-[#B99470]/25 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-[#FEFAE0] rounded-2xl text-[#5F6F52]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div class="flex-grow">
                        <h3 class="text-base font-bold text-[#783D19]">
                            Diretrizes de Apontamento DNS & Configuração de E-mail
                        </h3>
                        <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                            Para máxima performance e entregabilidade, este domínio deve ter a <strong>Zona Web</strong> apontada para o VPS HostDevPro e a <strong>Zona de E-mails (MX)</strong> mantida na ValueHost (configuração no Registro.br ou na zona DNS de autoridade).
                        </p>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-xs font-mono">
                            <!-- Apontamento Web -->
                            <div class="p-4 rounded-xl bg-gray-50 border border-gray-200">
                                <span class="font-bold text-[#5F6F52] block mb-1">🌐 Web (A / CNAME) &rarr; HostDevPro</span>
                                <div class="space-y-1 text-gray-700">
                                    <p><strong>Tipo A:</strong> @ (raiz) &rarr; <code>{{ $hosting->server->ip_address }}</code></p>
                                    <p><strong>Tipo CNAME:</strong> www &rarr; <code>{{ $hosting->domain }}</code></p>
                                </div>
                            </div>

                            <!-- Apontamento E-mail -->
                            <div class="p-4 rounded-xl bg-[#FEFAE0]/50 border border-[#B99470]/30">
                                <span class="font-bold text-[#C4661F] block mb-1">✉️ E-mails Corporativos &rarr; ValueHost</span>
                                <div class="space-y-1 text-gray-700">
                                    <p><strong>Tipo MX:</strong> @ &rarr; Servidores ValueHost</p>
                                    <p class="text-[11px] text-gray-500 font-sans">Caixas postais, Webmail e IMAP gerenciados com entregabilidade garantida.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notas Internas -->
            @if ($hosting->notes)
                <div class="bg-white rounded-3xl p-6 border border-[#B99470]/25 shadow-sm">
                    <h4 class="text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-2">Notas Técnicas Internas</h4>
                    <p class="text-xs text-gray-700 leading-relaxed whitespace-pre-line">{{ $hosting->notes }}</p>
                </div>
            @endif

            <!-- Exclusão da Hospedagem -->
            <div class="p-6 bg-red-50/50 rounded-3xl border border-red-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <h4 class="text-sm font-bold text-red-800">Remover esta Conta de Hospedagem</h4>
                    <p class="text-xs text-red-600 mt-0.5">
                        Esta ação enviará a conta para a lixeira lógica. O domínio será desvinculado.
                    </p>
                </div>
                <form method="POST" action="{{ route('hosting.destroy', $hosting) }}" onsubmit="return confirm('Tem certeza que deseja excluir a hospedagem {{ $hosting->domain }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-sm">
                        Excluir Hospedagem
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
