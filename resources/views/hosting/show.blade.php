<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <h2 class="font-black text-2xl text-white tracking-tight leading-tight">
                        {{ $hosting->domain }}
                    </h2>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $hosting->status_badge_classes }}">
                        @if ($hosting->status === \App\Models\HostingAccount::STATUS_ACTIVE)
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        @endif
                        {{ $hosting->status_label }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $hosting->ssl_badge_classes }}">
                        🔒 SSL {{ ucfirst($hosting->ssl_status) }}
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-1">
                    Hospedada no servidor <strong class="text-slate-200">{{ $hosting->server->name }}</strong> ({{ $hosting->server->ip_address }})
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('ai-builder.create', ['hosting_id' => $hosting->id]) }}" 
                   class="px-4 py-2 rounded-xl bg-gradient-to-r from-purple-600 via-pink-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-purple-500/25 transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>✨ Criar Site com IA</span>
                </a>

                <a href="https://{{ $hosting->domain }}" target="_blank" rel="noopener noreferrer"
                   class="px-4 py-2 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 font-bold text-xs uppercase tracking-wider shadow-sm transition flex items-center gap-1.5">
                    <span>Visitar Site</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                
                <!-- Toggle Suspensão -->
                <form method="POST" action="{{ route('hosting.toggle-status', $hosting) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    @if ($hosting->status === \App\Models\HostingAccount::STATUS_ACTIVE)
                        <button type="submit" 
                                class="px-4 py-2 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 hover:bg-amber-500/20 font-bold text-xs uppercase tracking-wider transition"
                                onclick="return confirm('Deseja suspender esta hospedagem?');">
                            Suspender
                        </button>
                    @else
                        <button type="submit" 
                                class="px-4 py-2 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 hover:bg-emerald-500/20 font-bold text-xs uppercase tracking-wider transition">
                            Reativar
                        </button>
                    @endif
                </form>

                <a href="{{ route('hosting.edit', $hosting) }}" 
                   class="px-4 py-2 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider transition">
                    Editar
                </a>
                <a href="{{ route('hosting.index') }}" 
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

            <!-- Alerta se estiver suspensa -->
            @if ($hosting->status === \App\Models\HostingAccount::STATUS_SUSPENDED)
                <div class="p-4 rounded-2xl bg-rose-950/40 border border-rose-500/40 text-rose-300 text-sm flex items-center justify-between shadow-xl backdrop-blur-xl">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">⚠️</span>
                        <div>
                            <span class="font-bold block">Esta conta de hospedagem está suspensa</span>
                            <span class="text-xs text-rose-400">Motivo: {{ $hosting->suspended_reason ?? 'Suspensão administrativa' }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Grid de Informações Principais -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Cliente Proprietário -->
                <div class="bg-white/[0.06] backdrop-blur-xl rounded-3xl p-6 border border-white/15 shadow-xl">
                    <h3 class="text-xs font-bold text-emerald-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Cliente Titular</span>
                    </h3>
                    <div class="space-y-2">
                        <a href="{{ route('clients.show', $hosting->client) }}" class="font-bold text-base text-white hover:text-emerald-400 transition block">
                            {{ $hosting->client->name }}
                        </a>
                        <span class="text-xs text-slate-400 block">{{ $hosting->client->company ?? 'Pessoa Física' }}</span>
                        <span class="text-xs text-slate-400 font-mono block">{{ $hosting->client->email }}</span>
                        @if ($hosting->client->phone)
                            <span class="text-xs text-slate-400 block">{{ $hosting->client->phone }}</span>
                        @endif
                    </div>
                    <div class="mt-4 pt-3 border-t border-white/10">
                        <a href="{{ route('clients.show', $hosting->client) }}" class="text-xs text-emerald-400 font-semibold hover:underline">
                            Ver prontuário do cliente &rarr;
                        </a>
                    </div>
                </div>

                <!-- Servidor VPS -->
                <div class="bg-white/[0.06] backdrop-blur-xl rounded-3xl p-6 border border-white/15 shadow-xl">
                    <h3 class="text-xs font-bold text-cyan-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                        <span>Servidor Vinculado</span>
                    </h3>
                    <div class="space-y-2 text-sm">
                        <a href="{{ route('servers.show', $hosting->server) }}" class="font-bold text-white hover:text-cyan-400 hover:underline block">
                            {{ $hosting->server->name }}
                        </a>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="text-slate-400">IP:</span>
                            <span class="font-mono font-bold text-cyan-300">{{ $hosting->server->ip_address }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="text-slate-400">Provedor:</span>
                            <span class="text-slate-200 font-medium">{{ $hosting->server->provider ?? 'Host' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="text-slate-400">Localização:</span>
                            <span class="text-slate-200 font-medium">{{ $hosting->server->datacenter_location ?? 'Brasil' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Plano & Runtime -->
                <div class="bg-white/[0.06] backdrop-blur-xl rounded-3xl p-6 border border-white/15 shadow-xl">
                    <h3 class="text-xs font-bold text-purple-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>Plano & Recursos</span>
                    </h3>
                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between py-1 border-b border-white/10">
                            <span class="text-slate-400">Plano Ativo:</span>
                            <span class="font-bold text-white">{{ $hosting->plan_label }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-white/10">
                            <span class="text-slate-400">PHP Version:</span>
                            <span class="font-mono font-bold text-emerald-400">PHP {{ $hosting->php_version }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-slate-400">Tráfego Mensal:</span>
                            <span class="font-mono font-semibold text-slate-200">{{ round($hosting->bandwidth_quota_mb / 1024, 0) }} GB</span>
                        </div>
                        
                        <!-- Barra de Disco -->
                        <div class="pt-2">
                            <div class="flex justify-between text-[11px] mb-1">
                                <span class="text-slate-400">Armazenamento:</span>
                                <span class="font-bold font-mono text-slate-200">{{ $hosting->disk_used_gb }} / {{ $hosting->disk_quota_gb }} GB</span>
                            </div>
                            <div class="w-full bg-slate-900 rounded-full h-2 overflow-hidden border border-white/10">
                                <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-2 rounded-full" style="width: {{ $hosting->disk_usage_percentage }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Painel de Controle Plesk & Credenciais de Acesso -->
            <div class="bg-white/[0.06] backdrop-blur-xl rounded-3xl p-6 md:p-8 border border-white/15 shadow-xl">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-5 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-indigo-500/10 border border-indigo-500/30 rounded-2xl text-indigo-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">
                                Painel de Controle Plesk & Credenciais
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">
                                Acesso direto ao painel de hospedagem ValueHost, gerenciamento de caixas postais e FTP.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="https://us163-pl.valueserver.net:8443" target="_blank" rel="noopener noreferrer" 
                           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-indigo-600/20 transition">
                            <span>Acessar Plesk</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5" x-data="{ 
                    copiedUser: false, 
                    copiedPass: false, 
                    showSecret: false,
                    copyText(text, type) {
                        navigator.clipboard.writeText(text).then(() => {
                            if (type === 'user') { this.copiedUser = true; setTimeout(() => this.copiedUser = false, 2500); }
                            if (type === 'pass') { this.copiedPass = true; setTimeout(() => this.copiedPass = false, 2500); }
                        });
                    }
                }">
                    <!-- Usuário -->
                    <div class="p-4 rounded-2xl bg-slate-900/80 border border-white/10">
                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">
                            Usuário do Painel / FTP
                        </span>
                        <div class="flex items-center justify-between bg-slate-950/60 px-3.5 py-2.5 rounded-xl border border-white/10">
                            <span class="font-mono font-bold text-white text-sm">
                                {{ $hosting->username ?? 'alexcla1' }}
                            </span>
                            <button type="button" 
                                    @click="copyText('{{ $hosting->username ?? 'alexcla1' }}', 'user')"
                                    class="text-slate-400 hover:text-emerald-400 transition text-xs font-semibold flex items-center gap-1 focus:outline-none"
                                    title="Copiar Usuário">
                                <span x-show="!copiedUser" class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    <span class="hidden sm:inline">Copiar</span>
                                </span>
                                <span x-show="copiedUser" style="display: none;" class="text-emerald-400 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Copiado!</span>
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Senha com Olhinho & Copiar -->
                    <div class="p-4 rounded-2xl bg-slate-900/80 border border-white/10">
                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">
                            Senha de Acesso ao Painel
                        </span>
                        <div class="flex items-center justify-between bg-slate-950/60 px-3.5 py-2.5 rounded-xl border border-white/10">
                            <span class="font-mono font-bold text-white text-sm tracking-wider" 
                                  x-text="showSecret ? 'Al951357@2026@#' : '•••••••••••••'">
                            </span>
                            <div class="flex items-center gap-2">
                                <!-- Botão Copiar -->
                                <button type="button" 
                                        @click="copyText('Al951357@2026@#', 'pass')"
                                        class="text-slate-400 hover:text-emerald-400 transition text-xs font-semibold flex items-center gap-1 focus:outline-none"
                                        title="Copiar Senha">
                                    <span x-show="!copiedPass" class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    </span>
                                    <span x-show="copiedPass" style="display: none;" class="text-emerald-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                </button>
                                <!-- Olhinho (Ver/Ocultar) -->
                                <button type="button" 
                                        @click="showSecret = !showSecret" 
                                        class="text-slate-400 hover:text-emerald-400 transition p-1 focus:outline-none"
                                        :title="showSecret ? 'Ocultar senha' : 'Ver senha'">
                                    <svg x-show="!showSecret" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showSecret" style="display: none;" class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-2">
                    <span>Problemas com a senha? Altere no menu esquerdo do painel Plesk.</span>
                    <a href="https://webmail.hostdevpro.app.br" target="_blank" rel="noopener noreferrer" class="text-cyan-400 font-bold hover:underline">
                        Abrir Webmail Oficial &rarr;
                    </a>
                </div>
            </div>

            <!-- Guia Oficial de Apontamento DNS: Web (HostDevPro) & E-mails (ValueHost) -->
            <div class="bg-white/[0.06] backdrop-blur-xl rounded-3xl p-6 md:p-8 border border-white/15 shadow-xl">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-5 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-cyan-500/10 border border-cyan-500/30 rounded-2xl text-cyan-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">
                                Diretrizes Oficiais de Apontamento DNS
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">
                                Padrão oficial HostDevPro: Aplicação Web na Nuvem VPS Dedicada e E-mails no Cluster ValueHost / MailBaby.
                            </p>
                        </div>
                    </div>
                    <div>
                        <button onclick="copyDnsInstructions()" id="btn-copy-dns" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white text-xs font-bold transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            <span id="copy-btn-text">Copiar Registros DNS</span>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 text-xs">
                    <!-- Coluna 1: Zona Web (HostDevPro Cloud) -->
                    <div class="p-5 rounded-2xl bg-slate-900/80 border border-white/10 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-cyan-400 flex items-center gap-1.5 text-xs uppercase tracking-wider">
                                <span>🌐</span> Zona Web &bull; Nuvem VPS HostDevPro
                            </span>
                            <span class="px-2 py-0.5 rounded-md bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 text-[10px] font-bold">Nuvem Ativa</span>
                        </div>
                        <div class="space-y-2 font-mono">
                            <div class="p-2.5 rounded-xl bg-slate-950/60 border border-white/10 flex justify-between items-center">
                                <div>
                                    <span class="text-slate-400 text-[10px] block">TIPO A (RAIZ / @)</span>
                                    <span class="font-bold text-white">{{ $hosting->domain }}</span>
                                </div>
                                <code class="text-cyan-400 bg-cyan-500/10 border border-cyan-500/20 px-2 py-1 rounded text-xs">{{ $hosting->server->ip_address }}</code>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-950/60 border border-white/10 flex justify-between items-center">
                                <div>
                                    <span class="text-slate-400 text-[10px] block">CNAME (WWW)</span>
                                    <span class="font-bold text-white">www.{{ $hosting->domain }}</span>
                                </div>
                                <code class="text-cyan-400 bg-cyan-500/10 border border-cyan-500/20 px-2 py-1 rounded text-xs">{{ $hosting->domain }}</code>
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-400 leading-relaxed font-sans">
                            Tráfego HTTPS com balanceador OpenResty e isolamento de contêineres no servidor {{ $hosting->server->name }}.
                        </p>
                    </div>

                    <!-- Coluna 2: Zona E-mail (ValueHost Cluster) -->
                    <div class="p-5 rounded-2xl bg-slate-900/80 border border-white/10 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-amber-400 flex items-center gap-1.5 text-xs uppercase tracking-wider">
                                <span>✉️</span> E-mails &bull; Cluster ValueHost (Plesk)
                            </span>
                            <span class="px-2 py-0.5 rounded-md bg-amber-500/10 text-amber-400 border border-amber-500/30 text-[10px] font-bold">Relay MailBaby</span>
                        </div>
                        <div class="space-y-2 font-mono">
                            <div class="p-2.5 rounded-xl bg-slate-950/60 border border-white/10 flex justify-between items-center">
                                <div>
                                    <span class="text-slate-400 text-[10px] block">TIPO A (MAIL)</span>
                                    <span class="font-bold text-white">mail.{{ $hosting->domain }}</span>
                                </div>
                                <code class="text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2 py-1 rounded text-xs">177.136.254.37</code>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-950/60 border border-white/10 flex justify-between items-center">
                                <div>
                                    <span class="text-slate-400 text-[10px] block">TIPO MX (PRIORIDADE 10)</span>
                                    <span class="font-bold text-white">@ (raiz)</span>
                                </div>
                                <code class="text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2 py-1 rounded text-xs">mail.{{ $hosting->domain }}</code>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-950/60 border border-white/10 flex justify-between items-center">
                                <div>
                                    <span class="text-slate-400 text-[10px] block">TIPO A (WEBMAIL)</span>
                                    <span class="font-bold text-white">webmail.{{ $hosting->domain }}</span>
                                </div>
                                <code class="text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2 py-1 rounded text-xs">177.136.254.37</code>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-950/60 border border-white/10">
                                <span class="text-slate-400 text-[10px] block mb-1">TXT (SPF ANTI-SPAM)</span>
                                <code class="text-[10px] text-amber-300 bg-slate-950/90 border border-white/10 p-1.5 rounded block break-all">v=spf1 +a +mx +a:us163-pl.valueserver.net include:relay.mailbaby.net -all</code>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-1">
                            <span class="text-[11px] text-slate-400 font-sans">Caixas postais, Roundcube e POP/IMAP/SMTP.</span>
                            <a href="https://webmail.hostdevpro.app.br" target="_blank" rel="noopener noreferrer" class="text-[11px] font-bold text-cyan-400 hover:underline">
                                Abrir Webmail &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Script para Copiar Instruções de DNS -->
            <script>
                function copyDnsInstructions() {
                    const text = `INSTRUÇÕES DE APONTAMENTO DNS - HOSTDEVPRO CLOUD
Domínio: {{ $hosting->domain }}
Cliente: {{ $hosting->client->name }}

1. APONTAMENTO WEB (Site):
- Entrada: @ (raiz) | Tipo: A | Destino: {{ $hosting->server->ip_address }}
- Entrada: www | Tipo: CNAME | Destino: {{ $hosting->domain }}

2. APONTAMENTO E-MAILS (Cluster ValueHost / MailBaby):
- Entrada: mail | Tipo: A | Destino: 177.136.254.37
- Entrada: webmail | Tipo: A | Destino: 177.136.254.37
- Entrada: @ (raiz) | Tipo: MX (Prioridade 10) | Destino: mail.{{ $hosting->domain }}
- Entrada: @ (raiz) | Tipo: TXT (SPF) | Destino: v=spf1 +a +mx +a:us163-pl.valueserver.net include:relay.mailbaby.net -all

Webmail Oficial: https://webmail.hostdevpro.app.br`;

                    navigator.clipboard.writeText(text).then(() => {
                        const btnText = document.getElementById('copy-btn-text');
                        btnText.innerText = 'Copiado com Sucesso!';
                        setTimeout(() => {
                            btnText.innerText = 'Copiar Registros DNS';
                        }, 3000);
                    });
                }
            </script>

            <!-- Notas Internas -->
            @if ($hosting->notes)
                <div class="bg-white/[0.06] backdrop-blur-xl rounded-3xl p-6 border border-white/15 shadow-xl">
                    <h4 class="text-xs font-bold text-emerald-400 uppercase tracking-wider mb-2">Notas Técnicas Internas</h4>
                    <p class="text-xs text-slate-300 leading-relaxed whitespace-pre-line">{{ $hosting->notes }}</p>
                </div>
            @endif

            <!-- Exclusão da Hospedagem -->
            <div class="p-6 bg-red-950/20 backdrop-blur-xl rounded-3xl border border-red-500/20 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <h4 class="text-sm font-bold text-red-400">Remover esta Conta de Hospedagem</h4>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Esta ação enviará a conta para a lixeira lógica. O domínio será desvinculado.
                    </p>
                </div>
                <form method="POST" action="{{ route('hosting.destroy', $hosting) }}" onsubmit="return confirm('Tem certeza que deseja excluir a hospedagem {{ $hosting->domain }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 text-red-400 font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-sm">
                        Excluir Hospedagem
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
