<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <h2 class="font-black text-2xl text-white tracking-tight leading-tight flex items-center gap-2">
                        <span>{{ $hosting->domain }}</span>
                        <a href="https://{{ $hosting->domain }}" target="_blank" rel="noopener noreferrer" class="text-slate-400 hover:text-emerald-400 transition" title="Abrir site em nova aba">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
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
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-mono font-semibold border border-purple-500/30 bg-purple-500/10 text-purple-300">
                        ⚡ PHP {{ $hosting->php_version }}
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-1 flex items-center gap-2">
                    <span>HostDevPro Cloud &bull; Servidor <strong class="text-slate-200">{{ $hosting->server->name }}</strong> (<span class="font-mono text-cyan-300">{{ $hosting->server->ip_address }}</span>)</span>
                    <span>&bull; Cliente: <strong class="text-slate-200">{{ $hosting->client->name }}</strong></span>
                </p>
            </div>

            <!-- Botões de Ação Superior -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('ai-builder.create', ['hosting_id' => $hosting->id]) }}" 
                   class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-purple-600 via-pink-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-purple-500/20 transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>✨ Criar Site IA</span>
                </a>

                <a href="{{ route('hosting.control.backup', $hosting) }}" 
                   class="px-3.5 py-2 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider transition flex items-center gap-1.5"
                   title="Gerar e baixar arquivo ZIP com todos os arquivos da hospedagem">
                    <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Backup .ZIP</span>
                </a>

                <a href="https://webmail.hostdevpro.app.br" target="_blank" rel="noopener noreferrer"
                   class="px-3.5 py-2 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 text-amber-400 font-bold text-xs uppercase tracking-wider transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Webmail</span>
                </a>

                <a href="https://us163-pl.valueserver.net:8443" target="_blank" rel="noopener noreferrer"
                   class="px-3.5 py-2 rounded-xl bg-indigo-600/20 hover:bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 font-bold text-xs uppercase tracking-wider transition flex items-center gap-1.5">
                    <span>Plesk</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>

                <a href="{{ route('hosting.edit', $hosting) }}" 
                   class="px-3 py-2 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider transition">
                    Editar
                </a>

                <a href="{{ route('hosting.index') }}" 
                   class="px-3 py-2 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider transition">
                    &larr; Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="controlCenterApp()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alertas Flash -->
            <template x-if="notification.show">
                <div :class="notification.type === 'error' ? 'bg-rose-950/60 border-rose-500/50 text-rose-300' : 'bg-emerald-950/60 border-emerald-500/50 text-emerald-300'"
                     class="p-4 rounded-2xl border text-xs flex items-center justify-between shadow-2xl backdrop-blur-xl transition">
                    <div class="flex items-center gap-2.5">
                        <span x-text="notification.type === 'error' ? '⚠️' : '✅'" class="text-base"></span>
                        <span x-text="notification.message" class="font-medium"></span>
                    </div>
                    <button @click="notification.show = false" class="text-slate-400 hover:text-white">&times;</button>
                </div>
            </template>

            <!-- Alerta Suspensão -->
            @if ($hosting->status === \App\Models\HostingAccount::STATUS_SUSPENDED)
                <div class="p-4 rounded-2xl bg-rose-950/40 border border-rose-500/40 text-rose-300 text-sm flex items-center justify-between shadow-xl backdrop-blur-xl">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">⚠️</span>
                        <div>
                            <span class="font-bold block">Esta conta de hospedagem está suspensa</span>
                            <span class="text-xs text-rose-400">Motivo: {{ $hosting->suspended_reason ?? 'Suspensão administrativa' }}</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('hosting.toggle-status', $hosting) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-3.5 py-1.5 bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 rounded-xl text-xs font-bold uppercase hover:bg-emerald-500/30 transition">
                            Reativar Agora
                        </button>
                    </form>
                </div>
            @endif

            <!-- KPIs de Recursos da Hospedagem -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Disco NVMe -->
                <div class="bg-white/[0.06] backdrop-blur-xl rounded-2xl p-5 border border-white/15 shadow-xl">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Armazenamento</span>
                        <span class="text-xs font-mono font-bold text-emerald-400">{{ $hosting->disk_usage_percentage }}%</span>
                    </div>
                    <div class="text-lg font-black text-white font-mono mb-2">
                        {{ $hosting->disk_used_gb }} <span class="text-xs text-slate-400 font-normal">/ {{ $hosting->disk_quota_gb }} GB</span>
                    </div>
                    <div class="w-full bg-slate-900 rounded-full h-1.5 overflow-hidden border border-white/10">
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-1.5 rounded-full" style="width: {{ $hosting->disk_usage_percentage }}%"></div>
                    </div>
                </div>

                <!-- Tráfego Mensal -->
                <div class="bg-white/[0.06] backdrop-blur-xl rounded-2xl p-5 border border-white/15 shadow-xl">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tráfego Mensal</span>
                        <span class="text-xs font-mono font-bold text-cyan-400">Ilimitado</span>
                    </div>
                    <div class="text-lg font-black text-white font-mono mb-1">
                        {{ round($hosting->bandwidth_quota_mb / 1024, 0) }} GB
                    </div>
                    <span class="text-[11px] text-slate-400">Porta 1Gbps / Proteção Anti-DDoS</span>
                </div>

                <!-- PHP Runtime -->
                <div class="bg-white/[0.06] backdrop-blur-xl rounded-2xl p-5 border border-white/15 shadow-xl">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Versão do PHP</span>
                        <span class="w-2 h-2 rounded-full bg-purple-400 animate-pulse"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <select x-model="phpVersion" @change="changePhpVersion()" 
                                class="bg-slate-900 text-purple-300 font-mono font-bold text-sm border border-purple-500/30 rounded-xl px-2.5 py-1 focus:ring-purple-500 focus:border-purple-500 w-full">
                            <option value="8.4">PHP 8.4 (Ultra Rápido)</option>
                            <option value="8.3">PHP 8.3 (Estável)</option>
                            <option value="8.2">PHP 8.2</option>
                            <option value="8.1">PHP 8.1</option>
                        </select>
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">OPcache & JIT habilitados</span>
                </div>

                <!-- SSL Let's Encrypt -->
                <div class="bg-white/[0.06] backdrop-blur-xl rounded-2xl p-5 border border-white/15 shadow-xl">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Certificado SSL</span>
                        <span class="text-xs font-bold text-emerald-400">Let's Encrypt</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-white flex items-center gap-1.5">
                            <span>🔒 HTTPS Ativo</span>
                        </span>
                        <button @click="renewSsl()" class="text-[11px] font-bold text-cyan-400 hover:text-cyan-300 underline">
                            Revalidar
                        </button>
                    </div>
                    <span class="text-[11px] text-slate-400 mt-1 block">Auto-renovação a cada 90 dias</span>
                </div>
            </div>

            <!-- Navegação por Abas do Painel -->
            <div class="border-b border-white/10 flex flex-wrap gap-2 pb-1">
                <button @click="activeTab = 'files'; loadFiles();"
                        :class="activeTab === 'files' ? 'bg-cyan-500/20 text-cyan-300 border-cyan-500/40' : 'text-slate-400 hover:text-white border-transparent hover:bg-white/[0.04]'"
                        class="px-4 py-2.5 rounded-2xl text-xs font-bold border transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                    <span>📁 Gerenciador de Arquivos & Editor</span>
                </button>

                <button @click="activeTab = 'apps'; loadAppsCatalog();"
                        :class="activeTab === 'apps' ? 'bg-cyan-500/20 text-cyan-300 border-cyan-500/40' : 'text-slate-400 hover:text-white border-transparent hover:bg-white/[0.04]'"
                        class="px-4 py-2.5 rounded-2xl text-xs font-bold border transition flex items-center gap-2">
                    <span class="text-amber-400 font-black">⚡</span>
                    <span>1-Click Apps & Marketplace</span>
                </button>

                <button @click="activeTab = 'dns'; loadDnsRecords();"
                        :class="activeTab === 'dns' ? 'bg-cyan-500/20 text-cyan-300 border-cyan-500/40' : 'text-slate-400 hover:text-white border-transparent hover:bg-white/[0.04]'"
                        class="px-4 py-2.5 rounded-2xl text-xs font-bold border transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    <span>🌐 Zonas DNS (Plesk Live)</span>
                </button>

                <button @click="activeTab = 'emails'"
                        :class="activeTab === 'emails' ? 'bg-cyan-500/20 text-cyan-300 border-cyan-500/40' : 'text-slate-400 hover:text-white border-transparent hover:bg-white/[0.04]'"
                        class="px-4 py-2.5 rounded-2xl text-xs font-bold border transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>✉️ E-mails Corporativos</span>
                </button>

                <button @click="activeTab = 'databases'; loadDatabases();"
                        :class="activeTab === 'databases' ? 'bg-cyan-500/20 text-cyan-300 border-cyan-500/40' : 'text-slate-400 hover:text-white border-transparent hover:bg-white/[0.04]'"
                        class="px-4 py-2.5 rounded-2xl text-xs font-bold border transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                    <span>🗄️ Bancos MySQL</span>
                </button>

                <button @click="activeTab = 'credentials'"
                        :class="activeTab === 'credentials' ? 'bg-cyan-500/20 text-cyan-300 border-cyan-500/40' : 'text-slate-400 hover:text-white border-transparent hover:bg-white/[0.04]'"
                        class="px-4 py-2.5 rounded-2xl text-xs font-bold border transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    <span>🔑 Acesso Plesk & FTP</span>
                </button>

                <button @click="activeTab = 'advanced'"
                        :class="activeTab === 'advanced' ? 'bg-cyan-500/20 text-cyan-300 border-cyan-500/40' : 'text-slate-400 hover:text-white border-transparent hover:bg-white/[0.04]'"
                        class="px-4 py-2.5 rounded-2xl text-xs font-bold border transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>⚙️ Ferramentas & Logs</span>
                </button>
            </div>

            <!-- ========================================== -->
            <!-- ABA 1: GERENCIADOR DE ARQUIVOS & EDITOR    -->
            <!-- ========================================== -->
            <div x-show="activeTab === 'files'" x-transition class="space-y-4">
                <div class="bg-white/[0.06] backdrop-blur-xl rounded-3xl p-6 border border-white/15 shadow-xl">
                    <!-- Barra Superior do Gerenciador de Arquivos -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 mb-5 border-b border-white/10">
                        <div class="flex items-center gap-2 overflow-x-auto text-xs font-mono">
                            <button @click="navigateTo('')" class="text-cyan-400 hover:text-cyan-300 font-bold flex items-center gap-1">
                                <span>/public_html</span>
                            </button>
                            <template x-for="(segment, idx) in pathSegments()" :key="idx">
                                <span class="flex items-center gap-2">
                                    <span class="text-slate-500">/</span>
                                    <button @click="navigateTo(getSegmentPath(idx))" class="text-slate-300 hover:text-white" x-text="segment"></button>
                                </span>
                            </template>
                        </div>

                        <!-- Botões de Ação de Arquivos -->
                        <div class="flex flex-wrap items-center gap-2">
                            <button @click="showNewFileModal = true" class="px-3 py-1.5 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white text-xs font-bold transition flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Novo Arquivo</span>
                            </button>

                            <button @click="showNewFolderModal = true" class="px-3 py-1.5 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white text-xs font-bold transition flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                                <span>Nova Pasta</span>
                            </button>

                            <!-- Upload Button Trigger -->
                            <label class="px-3 py-1.5 rounded-xl bg-cyan-600/30 hover:bg-cyan-600/40 border border-cyan-500/40 text-cyan-300 text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <span>Enviar Arquivos</span>
                                <input type="file" @change="handleFileUpload($event)" class="hidden" multiple>
                            </label>

                            <button @click="loadFiles()" class="p-1.5 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] text-slate-300 hover:text-white transition" title="Atualizar">
                                <svg class="w-4 h-4" :class="loadingFiles ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Tabela de Arquivos e Pastas -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b border-white/10 text-slate-400 uppercase tracking-wider font-semibold">
                                    <th class="pb-3 pl-2">Nome</th>
                                    <th class="pb-3">Tamanho</th>
                                    <th class="pb-3">Modificado</th>
                                    <th class="pb-3 text-right pr-2">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <!-- Botão de Voltar Pasta se não estiver na raiz -->
                                <template x-if="currentPath !== ''">
                                    <tr @click="navigateUp()" class="hover:bg-white/[0.04] cursor-pointer transition">
                                        <td class="py-2.5 pl-2 font-mono text-cyan-400 font-bold flex items-center gap-2">
                                            <span>📁 ..</span>
                                            <span class="text-slate-500 font-normal">(Diretório superior)</span>
                                        </td>
                                        <td class="py-2.5 text-slate-500">-</td>
                                        <td class="py-2.5 text-slate-500">-</td>
                                        <td class="py-2.5 text-right pr-2"></td>
                                    </tr>
                                </template>

                                <!-- Itens Listados -->
                                <template x-for="item in fileItems" :key="item.path">
                                    <tr class="hover:bg-white/[0.04] group transition">
                                        <td class="py-2.5 pl-2">
                                            <div class="flex items-center gap-2">
                                                <span x-text="getFileIcon(item)" class="text-base"></span>
                                                <button x-show="item.is_dir" @click="navigateTo(item.path)" 
                                                        class="font-bold text-white hover:text-cyan-300 transition text-left" 
                                                        x-text="item.name"></button>
                                                <button x-show="!item.is_dir && item.is_editable" @click="openEditor(item.path)"
                                                        class="font-mono text-slate-200 hover:text-cyan-300 transition text-left" 
                                                        x-text="item.name"></button>
                                                <span x-show="!item.is_dir && !item.is_editable" 
                                                      class="font-mono text-slate-300" 
                                                      x-text="item.name"></span>
                                            </div>
                                        </td>
                                        <td class="py-2.5 font-mono text-slate-400" x-text="item.formatted_size"></td>
                                        <td class="py-2.5 text-slate-400" x-text="item.modified_at"></td>
                                        <td class="py-2.5 text-right pr-2">
                                            <div class="flex items-center justify-end gap-1.5 opacity-80 group-hover:opacity-100 transition">
                                                <!-- Editar Código -->
                                                <button x-show="item.is_editable" @click="openEditor(item.path)"
                                                        class="px-2 py-1 rounded bg-indigo-500/20 hover:bg-indigo-500/30 text-indigo-300 font-semibold text-[11px] transition">
                                                    Editar
                                                </button>
                                                <!-- Extrair ZIP -->
                                                <button x-show="item.is_zip" @click="extractZipFile(item.path)"
                                                        class="px-2 py-1 rounded bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 font-semibold text-[11px] transition">
                                                    Extrair ZIP
                                                </button>
                                                <!-- Excluir -->
                                                <button @click="deleteItem(item.path, item.name)"
                                                        class="p-1 rounded text-rose-400 hover:bg-rose-500/20 transition" title="Excluir">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>

                                <template x-if="fileItems.length === 0 && !loadingFiles">
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-slate-500">
                                            Pasta vazia. Envie seus arquivos ou crie novos arquivos acima.
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- ABA: 1-CLICK APPS & MARKETPLACE            -->
            <!-- ========================================== -->
            <div x-show="activeTab === 'apps'" x-transition class="space-y-6">
                <!-- Header Banner com Estilo Cyber Glass -->
                <div class="bg-gradient-to-r from-indigo-950/60 via-slate-900/80 to-cyan-950/60 backdrop-blur-xl rounded-3xl p-6 sm:p-8 border border-white/15 shadow-2xl relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-bold uppercase tracking-wider mb-3">
                                <span>⚡</span> Automação Instantânea HostDevPro
                            </div>
                            <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                                1-Click App Marketplace & Instalador Automático
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-300 mt-2 max-w-2xl leading-relaxed">
                                Instale WordPress 6.7 completo em Português, Landing Pages de alta conversão, Laravel 12 ou páginas VIP no domínio <strong class="text-cyan-400">{{ $hosting->domain }}</strong>. Bancos MySQL, credenciais e configurações de segurança são provisionados automaticamente.
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <span class="px-3 py-1.5 rounded-xl bg-slate-950/80 border border-cyan-500/30 text-cyan-300 font-mono flex items-center gap-1.5">
                                <i class="fa-solid fa-database text-cyan-400"></i> MySQL Provisionado
                            </span>
                            <span class="px-3 py-1.5 rounded-xl bg-slate-950/80 border border-emerald-500/30 text-emerald-300 font-mono flex items-center gap-1.5">
                                <i class="fa-solid fa-shield-halved text-emerald-400"></i> SSL 256-Bit Ready
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Carregamento do Catálogo -->
                <div x-show="loadingApps" class="py-16 text-center">
                    <div class="inline-block animate-spin w-8 h-8 border-4 border-cyan-500 border-t-transparent rounded-full mb-3"></div>
                    <p class="text-xs text-slate-400 font-medium">Carregando catálogo de aplicativos...</p>
                </div>

                <!-- Grid de Aplicativos -->
                <div x-show="!loadingApps" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <template x-for="app in appsCatalog" :key="app.id">
                        <div class="bg-white/[0.04] hover:bg-white/[0.07] backdrop-blur-xl rounded-3xl p-6 sm:p-7 border border-white/10 hover:border-cyan-500/40 transition-all duration-300 shadow-xl flex flex-col justify-between group relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/[0.03] to-purple-500/[0.03] opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>

                            <div class="relative z-10">
                                <div class="flex items-start justify-between gap-4 mb-4">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shadow-lg border"
                                             :class="{
                                                 'bg-indigo-500/20 text-indigo-400 border-indigo-500/30': app.id === 'wordpress',
                                                 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30': app.id === 'sales_lp',
                                                 'bg-rose-500/20 text-rose-400 border-rose-500/30': app.id === 'laravel',
                                                 'bg-cyan-500/20 text-cyan-400 border-cyan-500/30': app.id === 'coming_soon'
                                             }">
                                            <i :class="app.icon"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-base font-extrabold text-white group-hover:text-cyan-300 transition-colors" x-text="app.name"></h3>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-[11px] font-semibold text-slate-400" x-text="app.category"></span>
                                                <span class="text-slate-600">•</span>
                                                <span class="text-[10px] font-mono text-cyan-400 bg-cyan-950/60 px-2 py-0.5 rounded-md border border-cyan-500/20" x-text="'v' + app.version"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-1.5">
                                        <span class="text-[10px] font-extrabold tracking-wider uppercase px-2.5 py-1 rounded-full border shadow-sm"
                                              :class="{
                                                  'bg-indigo-500/20 text-indigo-300 border-indigo-500/30': app.badge_color === 'indigo',
                                                  'bg-emerald-500/20 text-emerald-300 border-emerald-500/30': app.badge_color === 'emerald',
                                                  'bg-rose-500/20 text-rose-300 border-rose-500/30': app.badge_color === 'rose',
                                                  'bg-cyan-500/20 text-cyan-300 border-cyan-500/30': app.badge_color === 'cyan'
                                              }"
                                              x-text="app.badge">
                                        </span>
                                        <template x-if="app.is_installed">
                                            <span class="text-[10px] font-bold text-emerald-400 bg-emerald-950/80 border border-emerald-500/30 px-2 py-0.5 rounded-full flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Ativo no Site
                                            </span>
                                        </template>
                                    </div>
                                </div>

                                <p class="text-xs font-semibold text-slate-300 mb-2" x-text="app.tagline"></p>
                                <p class="text-xs text-slate-400 leading-relaxed mb-4" x-text="app.description"></p>

                                <div class="bg-slate-950/60 rounded-2xl p-3.5 border border-white/5 mb-5 space-y-2">
                                    <template x-for="(feat, idx) in app.features" :key="idx">
                                        <div class="flex items-center gap-2 text-[11px] text-slate-300">
                                            <i class="fa-solid fa-circle-check text-cyan-400 text-xs"></i>
                                            <span x-text="feat"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="relative z-10 pt-4 border-t border-white/10 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-2 text-[10px] font-mono text-slate-400">
                                    <span class="bg-white/5 px-2 py-1 rounded" x-text="app.php_req"></span>
                                    <span class="bg-white/5 px-2 py-1 rounded" x-text="app.db_req"></span>
                                </div>

                                <button @click="openInstallModal(app)"
                                        class="px-5 py-2.5 rounded-xl font-extrabold text-xs transition-all duration-200 flex items-center gap-2 shadow-lg"
                                        :class="app.is_installed
                                            ? 'bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700'
                                            : 'bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-950 shadow-cyan-500/20'">
                                    <i class="fa-solid fa-bolt text-xs"></i>
                                    <span x-text="app.is_installed ? 'Reinstalar App' : 'Instalar em 1-Clique'"></span>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- ABA 2: ZONAS DNS (PLESK REST API)          -->
            <!-- ========================================== -->
            <div x-show="activeTab === 'dns'" x-transition class="space-y-4">
                <div class="bg-white/[0.06] backdrop-blur-xl rounded-3xl p-6 border border-white/15 shadow-xl">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 mb-5 border-b border-white/10">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-bold text-white">Registros DNS em Tempo Real</h3>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                    Plesk API v2 Sincronizado
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 mt-0.5">
                                Alterações feitas aqui refletem imediatamente nos nameservers do cluster ValueHost e VPS HostDevPro.
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="showAddDnsModal = true" class="px-3.5 py-2 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-lg shadow-cyan-600/20">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Adicionar Registro</span>
                            </button>
                            <button @click="loadDnsRecords()" class="p-2 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] text-slate-300 hover:text-white transition" title="Recarregar DNS">
                                <svg class="w-4 h-4" :class="loadingDns ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Tabela de Registros DNS -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b border-white/10 text-slate-400 uppercase tracking-wider font-semibold">
                                    <th class="pb-3 pl-2">Tipo</th>
                                    <th class="pb-3">Entrada (Host)</th>
                                    <th class="pb-3">Valor (Destino)</th>
                                    <th class="pb-3">Opt / Prioridade</th>
                                    <th class="pb-3 text-right pr-2">Ação</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 font-mono">
                                <template x-for="rec in dnsRecords" :key="rec.id">
                                    <tr class="hover:bg-white/[0.04] transition">
                                        <td class="py-2.5 pl-2">
                                            <span class="px-2 py-0.5 rounded text-[11px] font-bold"
                                                  :class="{
                                                      'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30': rec.type === 'A',
                                                      'bg-purple-500/20 text-purple-300 border border-purple-500/30': rec.type === 'CNAME',
                                                      'bg-amber-500/20 text-amber-300 border border-amber-500/30': rec.type === 'MX',
                                                      'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30': rec.type === 'TXT',
                                                      'bg-slate-700 text-slate-200': rec.type === 'NS'
                                                  }"
                                                  x-text="rec.type"></span>
                                        </td>
                                        <td class="py-2.5 text-white font-bold" x-text="rec.host"></td>
                                        <td class="py-2.5 text-slate-300 break-all" x-text="rec.value"></td>
                                        <td class="py-2.5 text-slate-400" x-text="rec.opt || '-'"></td>
                                        <td class="py-2.5 text-right pr-2">
                                            <button @click="deleteDnsRecord(rec.id)" 
                                                    class="p-1 rounded text-rose-400 hover:bg-rose-500/20 transition" title="Excluir Registro">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Card de Ajuda Rápida de Apontamento DNS -->
                <div class="bg-white/[0.06] backdrop-blur-xl rounded-3xl p-6 border border-white/15 shadow-xl grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                    <div>
                        <h4 class="font-bold text-cyan-400 uppercase tracking-wider mb-2">Apontamento Web HostDevPro</h4>
                        <div class="space-y-1.5 font-mono p-3 bg-slate-950/60 rounded-xl border border-white/10">
                            <div><strong>@ (A):</strong> {{ $hosting->server->ip_address }}</div>
                            <div><strong>www (CNAME):</strong> {{ $hosting->domain }}</div>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold text-amber-400 uppercase tracking-wider mb-2">Apontamento de E-mails (Cluster ValueHost)</h4>
                        <div class="space-y-1.5 font-mono p-3 bg-slate-950/60 rounded-xl border border-white/10">
                            <div><strong>mail (A):</strong> 177.136.254.37</div>
                            <div><strong>@ (MX):</strong> mail.{{ $hosting->domain }} (Prioridade 10)</div>
                            <div><strong>TXT (SPF):</strong> v=spf1 +a +mx +a:us163-pl.valueserver.net include:relay.mailbaby.net -all</div>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-2">Compatível com Registro.br, Cloudflare e GoDaddy.</p>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- ABA 3: E-MAILS CORPORATIVOS                -->
            <!-- ========================================== -->
            <div x-show="activeTab === 'emails'" x-transition class="space-y-4">
                <div class="bg-white/[0.06] backdrop-blur-xl rounded-3xl p-6 md:p-8 border border-white/15 shadow-xl">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 mb-6 border-b border-white/10">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-amber-500/10 border border-amber-500/30 rounded-2xl text-amber-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-white">Central de E-mails Corporativos</h3>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    Acesse o Webmail oficial ou configure suas caixas postais no Outlook, Thunderbird e Smartphones.
                                </p>
                            </div>
                        </div>
                        <div>
                            <a href="https://webmail.hostdevpro.app.br" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-amber-500/20 transition">
                                <span>Abrir Webmail Roundcube</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Configurações de Conexão IMAP / SMTP -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="p-5 rounded-2xl bg-slate-900/80 border border-white/10 space-y-3">
                            <h4 class="font-bold text-xs uppercase tracking-wider text-cyan-400 flex items-center gap-2">
                                <span>📥 Servidor de Entrada (IMAP / POP3)</span>
                            </h4>
                            <div class="space-y-2 font-mono text-xs">
                                <div class="p-2.5 rounded-xl bg-slate-950/60 flex justify-between">
                                    <span class="text-slate-400">Servidor IMAP:</span>
                                    <span class="text-white font-bold">mail.{{ $hosting->domain }}</span>
                                </div>
                                <div class="p-2.5 rounded-xl bg-slate-950/60 flex justify-between">
                                    <span class="text-slate-400">Porta IMAP:</span>
                                    <span class="text-cyan-300 font-bold">993 (SSL/TLS)</span>
                                </div>
                                <div class="p-2.5 rounded-xl bg-slate-950/60 flex justify-between">
                                    <span class="text-slate-400">Porta POP3:</span>
                                    <span class="text-cyan-300 font-bold">995 (SSL/TLS)</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-900/80 border border-white/10 space-y-3">
                            <h4 class="font-bold text-xs uppercase tracking-wider text-amber-400 flex items-center gap-2">
                                <span>📤 Servidor de Saída (SMTP Relay)</span>
                            </h4>
                            <div class="space-y-2 font-mono text-xs">
                                <div class="p-2.5 rounded-xl bg-slate-950/60 flex justify-between">
                                    <span class="text-slate-400">Servidor SMTP:</span>
                                    <span class="text-white font-bold">mail.{{ $hosting->domain }}</span>
                                </div>
                                <div class="p-2.5 rounded-xl bg-slate-950/60 flex justify-between">
                                    <span class="text-slate-400">Porta SMTP:</span>
                                    <span class="text-amber-300 font-bold">465 (SSL/TLS) ou 587 (STARTTLS)</span>
                                </div>
                                <div class="p-2.5 rounded-xl bg-slate-950/60 flex justify-between">
                                    <span class="text-slate-400">Autenticação:</span>
                                    <span class="text-white font-bold">Requerida (Mesmo login e senha)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-2">
                        <span>Para criar ou alterar senhas de caixas de e-mail, acesse a aba "Correio" no Painel Plesk.</span>
                        <a href="https://us163-pl.valueserver.net:8443" target="_blank" rel="noopener noreferrer" class="text-indigo-400 font-bold hover:underline">
                            Gerenciar Caixas Postais no Plesk &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- ABA 4: BANCOS DE DADOS MYSQL & PHPMYADMIN  -->
            <!-- ========================================== -->
            <div x-show="activeTab === 'databases'" x-transition class="space-y-4">
                <div class="bg-white/[0.06] backdrop-blur-xl rounded-3xl p-6 md:p-8 border border-white/15 shadow-xl">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 mb-6 border-b border-white/10">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-purple-500/10 border border-purple-500/30 rounded-2xl text-purple-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-white">Bancos de Dados MySQL</h3>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    Crie bases de dados relacionais e acesse o phpMyAdmin para gerenciar tabelas e executar queries SQL.
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="showNewDbModal = true" class="px-3.5 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-lg shadow-purple-600/20">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Criar Novo Banco</span>
                            </button>
                            <a href="https://phpmyadmin.hostdevpro.app.br" target="_blank" rel="noopener noreferrer"
                               class="px-3.5 py-2 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white text-xs font-bold transition flex items-center gap-1.5">
                                <span>Abrir phpMyAdmin</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Credenciais Padrão de Conexão Local -->
                    <div class="p-4 rounded-2xl bg-slate-900/80 border border-white/10 grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs font-mono mb-6">
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-sans">Host do Banco:</span>
                            <span class="font-bold text-white">localhost (127.0.0.1)</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-sans">Porta MySQL:</span>
                            <span class="font-bold text-purple-300">3306</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-sans">Charset Padrão:</span>
                            <span class="font-bold text-white">utf8mb4_unicode_ci</span>
                        </div>
                    </div>

                    <!-- Lista de Bancos do Domínio -->
                    <div class="space-y-3">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Bancos Ativos</h4>
                        <template x-if="databases.length > 0">
                            <div class="space-y-2">
                                <template x-for="db in databases" :key="db.id || db.name">
                                    <div class="p-3.5 rounded-2xl bg-slate-950/60 border border-white/10 flex items-center justify-between text-xs">
                                        <div class="flex items-center gap-2.5">
                                            <span class="text-purple-400">🗄️</span>
                                            <span class="font-mono font-bold text-white" x-text="db.name"></span>
                                            <span class="text-slate-500 font-mono text-[11px]" x-text="'(Tipo: ' + (db.type || 'mysql') + ')'"></span>
                                        </div>
                                        <a href="https://phpmyadmin.hostdevpro.app.br" target="_blank" rel="noopener noreferrer" 
                                           class="text-cyan-400 font-bold hover:underline flex items-center gap-1">
                                            <span>phpMyAdmin</span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="databases.length === 0">
                            <div class="p-6 text-center text-slate-500 text-xs bg-slate-950/40 rounded-2xl border border-white/5">
                                Nenhum banco de dados personalizado criado ainda. Clique em "Criar Novo Banco" acima.
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- ABA 5: ACESSO PLESK & FTP                  -->
            <!-- ========================================== -->
            <div x-show="activeTab === 'credentials'" x-transition class="space-y-4">
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
                                    Painel Plesk Obsidian & Credenciais FTP
                                </h3>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    Acesse o cluster ValueHost para gerenciamento avançado ou conecte via FileZilla/FTP.
                                </p>
                            </div>
                        </div>
                        <div>
                            <a href="https://us163-pl.valueserver.net:8443" target="_blank" rel="noopener noreferrer" 
                               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-indigo-600/20 transition">
                                <span>Acessar Painel Plesk</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
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
                                        @click="copyToClipboard('{{ $hosting->username ?? 'alexcla1' }}', 'Usuário')"
                                        class="text-slate-400 hover:text-emerald-400 transition text-xs font-semibold flex items-center gap-1"
                                        title="Copiar Usuário">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    <span>Copiar</span>
                                </button>
                            </div>
                        </div>

                        <!-- Senha -->
                        <div class="p-4 rounded-2xl bg-slate-900/80 border border-white/10">
                            <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">
                                Senha de Acesso
                            </span>
                            <div class="flex items-center justify-between bg-slate-950/60 px-3.5 py-2.5 rounded-xl border border-white/10">
                                <span class="font-mono font-bold text-white text-sm tracking-wider" 
                                      x-text="showPassword ? 'Al951357@2026@#' : '•••••••••••••'">
                                </span>
                                <div class="flex items-center gap-2">
                                    <button type="button" 
                                            @click="copyToClipboard('Al951357@2026@#', 'Senha')"
                                            class="text-slate-400 hover:text-emerald-400 transition text-xs font-semibold flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    </button>
                                    <button type="button" 
                                            @click="showPassword = !showPassword" 
                                            class="text-slate-400 hover:text-emerald-400 transition p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dados do Servidor FTP -->
                    <div class="mt-5 p-4 rounded-2xl bg-slate-900/50 border border-white/10 grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs font-mono">
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-sans">Host FTP:</span>
                            <span class="font-bold text-cyan-300">ftp.{{ $hosting->domain }} ou {{ $hosting->server->ip_address }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-sans">Porta FTP:</span>
                            <span class="font-bold text-white">21 (ou 22 SFTP)</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-sans">Modo de Transferência:</span>
                            <span class="font-bold text-emerald-400">Passivo (TLS Explícito)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- ABA 6: FERRAMENTAS AVANÇADAS & LOGS       -->
            <!-- ========================================== -->
            <div x-show="activeTab === 'advanced'" x-transition class="space-y-6">
                <!-- Informações do Servidor -->
                <div class="bg-white/[0.06] backdrop-blur-xl rounded-3xl p-6 border border-white/15 shadow-xl">
                    <h3 class="text-xs font-bold text-cyan-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                        <span>Servidor Vinculado & Infraestrutura</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                        <div class="p-3 bg-slate-950/60 rounded-xl border border-white/10">
                            <span class="text-slate-400 block text-[10px]">Nome do Servidor</span>
                            <span class="font-bold text-white">{{ $hosting->server->name }}</span>
                        </div>
                        <div class="p-3 bg-slate-950/60 rounded-xl border border-white/10">
                            <span class="text-slate-400 block text-[10px]">Endereço IP Dedicado</span>
                            <span class="font-mono font-bold text-cyan-300">{{ $hosting->server->ip_address }}</span>
                        </div>
                        <div class="p-3 bg-slate-950/60 rounded-xl border border-white/10">
                            <span class="text-slate-400 block text-[10px]">Datacenter</span>
                            <span class="font-medium text-slate-200">{{ $hosting->server->datacenter_location ?? 'Brasil (São Paulo)' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Suspensão & Remoção -->
                <div class="p-6 bg-red-950/20 backdrop-blur-xl rounded-3xl border border-red-500/20 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <h4 class="text-sm font-bold text-red-400">Remover esta Conta de Hospedagem</h4>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Esta ação enviará a conta para a lixeira lógica. Os arquivos e banco de dados serão preservados em backup.
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

        <!-- ========================================== -->
        <!-- MODAL: INSTALADOR 1-CLIQUE DE APLICATIVOS  -->
        <!-- ========================================== -->
        <div x-show="showInstallModal" x-transition.opacity style="display: none;"
             class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-slate-900 border border-white/20 rounded-3xl p-6 sm:p-8 w-full max-w-xl shadow-2xl space-y-6 relative my-8"
                 @click.away="if (!installingApp) showInstallModal = false">
                
                <!-- Cabeçalho do Modal -->
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 flex items-center justify-center text-xl">
                            <i :class="selectedApp ? selectedApp.icon : 'fa-solid fa-box-open'"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white flex items-center gap-2">
                                <span x-text="selectedApp ? selectedApp.name : 'Instalar Aplicativo'"></span>
                            </h3>
                            <p class="text-[11px] text-slate-400">Domínio de Destino: <strong class="text-cyan-400">{{ $hosting->domain }}</strong></p>
                        </div>
                    </div>
                    <button x-show="!installingApp" @click="showInstallModal = false" class="text-slate-400 hover:text-white transition p-1 text-xl">
                        &times;
                    </button>
                </div>

                <!-- Formulário de Instalação (se ainda não concluiu) -->
                <div x-show="!installResult" class="space-y-4">
                    <!-- Opções específicas para WordPress -->
                    <template x-if="selectedApp && selectedApp.id === 'wordpress'">
                        <div class="space-y-3 bg-slate-950/60 p-4 rounded-2xl border border-white/5">
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1">Título do Site WordPress:</label>
                                <input type="text" x-model="installForm.site_title" placeholder="Meu Novo Site"
                                       class="w-full bg-slate-900 border border-white/15 rounded-xl px-3 py-2 text-xs text-white focus:border-cyan-500 focus:outline-none">
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1">Usuário Administrador:</label>
                                    <input type="text" x-model="installForm.admin_user" placeholder="admin"
                                           class="w-full bg-slate-900 border border-white/15 rounded-xl px-3 py-2 text-xs text-white focus:border-cyan-500 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1">E-mail do Administrador:</label>
                                    <input type="email" x-model="installForm.admin_email" placeholder="admin@{{ $hosting->domain }}"
                                           class="w-full bg-slate-900 border border-white/15 rounded-xl px-3 py-2 text-xs text-white focus:border-cyan-500 focus:outline-none">
                                </div>
                            </div>
                            <div class="text-[11px] text-emerald-400 flex items-center gap-1.5 pt-1">
                                <i class="fa-solid fa-circle-check"></i> Banco MySQL e Senhas 256-bit gerados automaticamente.
                            </div>
                        </div>
                    </template>

                    <!-- Opções para Landing Page de Vendas -->
                    <template x-if="selectedApp && selectedApp.id === 'sales_lp'">
                        <div class="space-y-3 bg-slate-950/60 p-4 rounded-2xl border border-white/5">
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1">Nome do Produto / Marca:</label>
                                <input type="text" x-model="installForm.product_name" placeholder="Ex: Minha Empresa Digital"
                                       class="w-full bg-slate-900 border border-white/15 rounded-xl px-3 py-2 text-xs text-white focus:border-cyan-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1">Headline (Título Principal):</label>
                                <input type="text" x-model="installForm.headline" placeholder="A Solução Definitiva..."
                                       class="w-full bg-slate-900 border border-white/15 rounded-xl px-3 py-2 text-xs text-white focus:border-cyan-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1">WhatsApp para Leads (com DDD):</label>
                                <input type="text" x-model="installForm.whatsapp" placeholder="5511999999999"
                                       class="w-full bg-slate-900 border border-white/15 rounded-xl px-3 py-2 text-xs text-white focus:border-cyan-500 focus:outline-none">
                            </div>
                        </div>
                    </template>

                    <!-- Opções para Coming Soon -->
                    <template x-if="selectedApp && selectedApp.id === 'coming_soon'">
                        <div class="space-y-3 bg-slate-950/60 p-4 rounded-2xl border border-white/5">
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1">Título do Lançamento:</label>
                                <input type="text" x-model="installForm.site_title" placeholder="Grande Lançamento Em Breve"
                                       class="w-full bg-slate-900 border border-white/15 rounded-xl px-3 py-2 text-xs text-white focus:border-cyan-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1">WhatsApp de Contato:</label>
                                <input type="text" x-model="installForm.whatsapp" placeholder="5511999999999"
                                       class="w-full bg-slate-900 border border-white/15 rounded-xl px-3 py-2 text-xs text-white focus:border-cyan-500 focus:outline-none">
                            </div>
                        </div>
                    </template>

                    <!-- Checkbox de Limpeza -->
                    <div class="flex items-center gap-2.5 pt-1">
                        <input type="checkbox" id="clean_root_checkbox" x-model="installForm.clean_root" class="rounded bg-slate-950 border-white/20 text-cyan-500 focus:ring-cyan-500">
                        <label for="clean_root_checkbox" class="text-xs text-slate-300">
                            Limpar arquivos existentes do diretório raiz para uma instalação limpa (Recomendado).
                        </label>
                    </div>

                    <!-- Barra de Progresso durante a instalação -->
                    <div x-show="installingApp" class="space-y-2 pt-3 border-t border-white/10">
                        <div class="flex justify-between text-xs text-slate-300">
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
                                <span x-show="installStep === 1">1/4: Provisionando banco de dados MySQL e ambiente...</span>
                                <span x-show="installStep === 2">2/4: Extraindo pacotes e gerando arquivos de configuração...</span>
                                <span x-show="installStep === 3">3/4: Vinculando permissões e otimizando servidor Nginx...</span>
                                <span x-show="installStep === 4">4/4: Instalação finalizada com sucesso!</span>
                            </span>
                            <span class="font-mono text-cyan-400" x-text="installProgress + '%'"></span>
                        </div>
                        <div class="w-full bg-slate-950 rounded-full h-2.5 overflow-hidden border border-white/10">
                            <div class="bg-gradient-to-r from-cyan-500 to-indigo-500 h-2.5 rounded-full transition-all duration-300"
                                 :style="'width: ' + installProgress + '%'"></div>
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div x-show="!installingApp" class="flex justify-end gap-3 pt-3 border-t border-white/10">
                        <button @click="showInstallModal = false" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-400 hover:text-white transition">
                            Cancelar
                        </button>
                        <button @click="triggerInstallApp()" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-950 font-black text-xs shadow-lg shadow-cyan-500/20 transition-all flex items-center gap-2">
                            <i class="fa-solid fa-rocket"></i>
                            <span>Iniciar Instalação Agora</span>
                        </button>
                    </div>
                </div>

                <!-- Box de Resultado / Credenciais Geradas -->
                <div x-show="installResult" class="space-y-4">
                    <div class="bg-emerald-950/40 border border-emerald-500/40 rounded-2xl p-5 space-y-3">
                        <div class="flex items-center gap-3 text-emerald-400">
                            <i class="fa-solid fa-circle-check text-2xl"></i>
                            <div>
                                <h4 class="text-sm font-bold text-white">Instalação Concluída com Sucesso!</h4>
                                <p class="text-xs text-slate-300" x-text="installResult ? installResult.message : ''"></p>
                            </div>
                        </div>

                        <!-- Credenciais se houver -->
                        <template x-if="installResult && installResult.credentials">
                            <div class="bg-slate-950/90 rounded-xl p-4 border border-emerald-500/20 text-xs font-mono space-y-2 mt-2">
                                <p class="text-[11px] font-sans font-bold text-emerald-300 uppercase tracking-wider mb-2">Credenciais Provisionadas:</p>
                                <template x-if="installResult.credentials.db_name">
                                    <div class="flex justify-between border-b border-white/5 pb-1">
                                        <span class="text-slate-400">Banco MySQL:</span>
                                        <span class="text-cyan-400 font-bold" x-text="installResult.credentials.db_name"></span>
                                    </div>
                                </template>
                                <template x-if="installResult.credentials.db_user">
                                    <div class="flex justify-between border-b border-white/5 pb-1">
                                        <span class="text-slate-400">Usuário MySQL:</span>
                                        <span class="text-indigo-400" x-text="installResult.credentials.db_user"></span>
                                    </div>
                                </template>
                                <template x-if="installResult.credentials.db_pass">
                                    <div class="flex justify-between border-b border-white/5 pb-1">
                                        <span class="text-slate-400">Senha do Banco:</span>
                                        <span class="text-emerald-400" x-text="installResult.credentials.db_pass"></span>
                                    </div>
                                </template>
                                <template x-if="installResult.credentials.admin_user">
                                    <div class="flex justify-between border-b border-white/5 pb-1">
                                        <span class="text-slate-400">Usuário WP:</span>
                                        <span class="text-amber-400" x-text="installResult.credentials.admin_user"></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                        <template x-if="installResult && installResult.site_url">
                            <a :href="installResult.site_url" target="_blank" class="w-full sm:w-auto text-center px-6 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 text-xs font-black transition">
                                <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Abrir Site Instalado
                            </a>
                        </template>
                        <button @click="showInstallModal = false; activeTab = 'files'; loadFiles();" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold transition">
                            Ver Arquivos no Gerenciador
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODAL: HOSTDEVPRO CODE STUDIO (EDITOR)     -->
        <!-- ========================================== -->
        <div x-show="showEditorModal" x-transition.opacity style="display: none;"
             class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
            <div class="bg-slate-900 border border-white/20 rounded-3xl w-full max-w-5xl h-[85vh] flex flex-col shadow-2xl overflow-hidden"
                 @keydown.window.escape="showEditorModal = false"
                 @keydown.window.ctrl.s.prevent="saveFileContent()">
                <!-- Header do Editor -->
                <div class="p-4 border-b border-white/10 flex items-center justify-between bg-slate-950/60">
                    <div class="flex items-center gap-3">
                        <span class="text-cyan-400 font-mono text-sm">📝</span>
                        <div>
                            <span class="font-bold text-sm text-white font-mono" x-text="editorFilename"></span>
                            <span class="text-xs text-slate-500 font-mono block" x-text="editorFilepath"></span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="saveFileContent()" 
                                :disabled="savingFile"
                                class="px-4 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs uppercase tracking-wider transition flex items-center gap-1.5 shadow-lg shadow-emerald-600/20">
                            <span x-show="!savingFile">💾 Salvar (Ctrl+S)</span>
                            <span x-show="savingFile">Salvando...</span>
                        </button>
                        <button @click="showEditorModal = false" 
                                class="p-1.5 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] text-slate-400 hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Textarea com Numeração e Código -->
                <div class="flex-1 p-4 bg-slate-950 relative overflow-hidden flex flex-col">
                    <textarea x-model="editorContent" 
                              class="w-full flex-1 bg-transparent text-emerald-300 font-mono text-xs leading-relaxed border-none focus:ring-0 focus:outline-none resize-none selection:bg-emerald-500 selection:text-slate-950"
                              spellcheck="false"></textarea>
                </div>

                <!-- Footer do Editor -->
                <div class="px-4 py-2 bg-slate-950 border-t border-white/10 flex items-center justify-between text-[11px] text-slate-400 font-mono">
                    <span x-text="'Caracteres: ' + editorContent.length"></span>
                    <span>HostDevPro Code Studio &bull; UTF-8</span>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODAL: NOVO ARQUIVO                        -->
        <!-- ========================================== -->
        <div x-show="showNewFileModal" x-transition.opacity style="display: none;"
             class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-slate-900 border border-white/20 rounded-3xl p-6 w-full max-w-md shadow-2xl space-y-4">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <span>📄 Novo Arquivo</span>
                </h3>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Nome do Arquivo (com extensão):</label>
                    <input type="text" x-model="newFileName" placeholder="ex: index.php, contato.html, style.css"
                           class="w-full bg-slate-950 border border-white/15 rounded-xl px-3 py-2 text-xs font-mono text-white focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button @click="showNewFileModal = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-400 hover:text-white">Cancelar</button>
                    <button @click="createNewFile()" class="px-4 py-2 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-xs transition">Criar Arquivo</button>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODAL: NOVA PASTA                          -->
        <!-- ========================================== -->
        <div x-show="showNewFolderModal" x-transition.opacity style="display: none;"
             class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-slate-900 border border-white/20 rounded-3xl p-6 w-full max-w-md shadow-2xl space-y-4">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <span>📁 Nova Pasta</span>
                </h3>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Nome da Pasta:</label>
                    <input type="text" x-model="newFolderName" placeholder="ex: assets, css, imagens"
                           class="w-full bg-slate-950 border border-white/15 rounded-xl px-3 py-2 text-xs font-mono text-white focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button @click="showNewFolderModal = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-400 hover:text-white">Cancelar</button>
                    <button @click="createNewFolder()" class="px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs transition">Criar Pasta</button>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODAL: NOVO REGISTRO DNS                   -->
        <!-- ========================================== -->
        <div x-show="showAddDnsModal" x-transition.opacity style="display: none;"
             class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-slate-900 border border-white/20 rounded-3xl p-6 w-full max-w-lg shadow-2xl space-y-4">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <span>🌐 Adicionar Registro DNS (Plesk)</span>
                </h3>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Tipo:</label>
                        <select x-model="newDns.type" class="w-full bg-slate-950 border border-white/15 rounded-xl px-3 py-2 text-xs font-bold text-white">
                            <option value="A">A</option>
                            <option value="CNAME">CNAME</option>
                            <option value="MX">MX</option>
                            <option value="TXT">TXT</option>
                            <option value="AAAA">AAAA</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Host / Entrada:</label>
                        <input type="text" x-model="newDns.host" placeholder="ex: sub, mail, @"
                               class="w-full bg-slate-950 border border-white/15 rounded-xl px-3 py-2 text-xs font-mono text-white">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Valor / Destino:</label>
                    <input type="text" x-model="newDns.value" placeholder="ex: 1.2.3.4 ou destino.com"
                           class="w-full bg-slate-950 border border-white/15 rounded-xl px-3 py-2 text-xs font-mono text-white">
                </div>
                <div x-show="newDns.type === 'MX'">
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Prioridade MX:</label>
                    <input type="number" x-model="newDns.opt" placeholder="10"
                           class="w-full bg-slate-950 border border-white/15 rounded-xl px-3 py-2 text-xs font-mono text-white">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button @click="showAddDnsModal = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-400 hover:text-white">Cancelar</button>
                    <button @click="submitAddDns()" class="px-4 py-2 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-xs transition">Salvar Registro</button>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODAL: NOVO BANCO DE DADOS MYSQL           -->
        <!-- ========================================== -->
        <div x-show="showNewDbModal" x-transition.opacity style="display: none;"
             class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-slate-900 border border-white/20 rounded-3xl p-6 w-full max-w-md shadow-2xl space-y-4">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <span>🗄️ Criar Banco de Dados MySQL</span>
                </h3>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Nome do Banco:</label>
                    <input type="text" x-model="newDb.name" placeholder="ex: meubanco_db"
                           class="w-full bg-slate-950 border border-white/15 rounded-xl px-3 py-2 text-xs font-mono text-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Usuário do Banco:</label>
                    <input type="text" x-model="newDb.username" placeholder="ex: user_db"
                           class="w-full bg-slate-950 border border-white/15 rounded-xl px-3 py-2 text-xs font-mono text-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Senha Segura:</label>
                    <input type="text" x-model="newDb.password" placeholder="Mínimo 8 caracteres"
                           class="w-full bg-slate-950 border border-white/15 rounded-xl px-3 py-2 text-xs font-mono text-white">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button @click="showNewDbModal = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-400 hover:text-white">Cancelar</button>
                    <button @click="submitNewDb()" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs transition">Criar Banco</button>
                </div>
            </div>
        </div>

    </div>

    <!-- Script Alpine.js para o HostDevPro Cloud Control Center -->
    <script>
        function controlCenterApp() {
            return {
                activeTab: 'files',
                phpVersion: '{{ $hosting->php_version ?? "8.4" }}',
                showPassword: false,
                
                // Notificações
                notification: { show: false, message: '', type: 'success' },
                notify(msg, type = 'success') {
                    this.notification = { show: true, message: msg, type: type };
                    setTimeout(() => this.notification.show = false, 4000);
                },

                // Arquivos
                currentPath: '',
                fileItems: [],
                loadingFiles: false,
                showNewFileModal: false,
                newFileName: '',
                showNewFolderModal: false,
                newFolderName: '',

                // Editor de Código
                showEditorModal: false,
                editorFilepath: '',
                editorFilename: '',
                editorContent: '',
                savingFile: false,

                // DNS
                dnsRecords: [],
                loadingDns: false,
                showAddDnsModal: false,
                newDns: { type: 'A', host: '', value: '', opt: '' },

                // Bancos
                databases: [],
                loadingDatabases: false,
                showNewDbModal: false,
                newDb: { name: '', username: '', password: '' },

                // 1-Click Apps & Marketplace
                appsCatalog: [],
                loadingApps: false,
                showInstallModal: false,
                selectedApp: null,
                installingApp: false,
                installStep: 0,
                installProgress: 0,
                installResult: null,
                installForm: {
                    site_title: '{{ $hosting->domain }}',
                    admin_user: 'admin',
                    admin_email: '{{ $hosting->client?->email ?? "admin@" . $hosting->domain }}',
                    admin_pass: '',
                    product_name: '{{ $hosting->domain }}',
                    headline: 'A Solução Definitiva Para o Seu Negócio Online',
                    subheadline: 'Infraestrutura de alta performance com estabilidade e tecnologia de ponta.',
                    whatsapp: '{{ $hosting->client?->whatsapp ?? "5511999999999" }}',
                    clean_root: true,
                },

                init() {
                    this.loadFiles();
                    this.loadAppsCatalog();
                },

                // Gestão de Arquivos
                async loadFiles() {
                    this.loadingFiles = true;
                    try {
                        const res = await fetch(`{{ route('hosting.control.files', $hosting) }}?path=${encodeURIComponent(this.currentPath)}`);
                        const data = await res.json();
                        if (data.success) {
                            this.fileItems = data.data.items;
                            this.currentPath = data.data.current_path || '';
                        }
                    } catch (e) {
                        this.notify('Erro ao carregar arquivos: ' + e.message, 'error');
                    } finally {
                        this.loadingFiles = false;
                    }
                },

                navigateTo(path) {
                    this.currentPath = path;
                    this.loadFiles();
                },

                navigateUp() {
                    if (!this.currentPath) return;
                    const parts = this.currentPath.split('/');
                    parts.pop();
                    this.navigateTo(parts.join('/'));
                },

                pathSegments() {
                    return this.currentPath ? this.currentPath.split('/') : [];
                },

                getSegmentPath(index) {
                    return this.pathSegments().slice(0, index + 1).join('/');
                },

                getFileIcon(item) {
                    if (item.is_dir) return '📁';
                    if (item.is_zip) return '📦';
                    if (['php', 'html', 'js', 'css', 'json', 'sql'].includes(item.extension)) return '📄';
                    if (['png', 'jpg', 'jpeg', 'svg', 'webp', 'gif'].includes(item.extension)) return '🖼️';
                    return '📃';
                },

                async createNewFile() {
                    if (!this.newFileName.trim()) return;
                    try {
                        const res = await fetch(`{{ route('hosting.control.create-file', $hosting) }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ path: this.currentPath, filename: this.newFileName })
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.notify(data.message);
                            this.showNewFileModal = false;
                            this.newFileName = '';
                            this.loadFiles();
                        } else {
                            this.notify(data.message, 'error');
                        }
                    } catch (e) {
                        this.notify('Falha ao criar arquivo: ' + e.message, 'error');
                    }
                },

                async createNewFolder() {
                    if (!this.newFolderName.trim()) return;
                    try {
                        const res = await fetch(`{{ route('hosting.control.create-folder', $hosting) }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ path: this.currentPath, folder_name: this.newFolderName })
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.notify(data.message);
                            this.showNewFolderModal = false;
                            this.newFolderName = '';
                            this.loadFiles();
                        } else {
                            this.notify(data.message, 'error');
                        }
                    } catch (e) {
                        this.notify('Falha ao criar pasta: ' + e.message, 'error');
                    }
                },

                async handleFileUpload(event) {
                    const files = event.target.files;
                    if (!files.length) return;

                    for (let file of files) {
                        const formData = new FormData();
                        formData.append('file', file);
                        formData.append('path', this.currentPath);
                        formData.append('extract_zip', file.name.endsWith('.zip') ? '1' : '0');

                        try {
                            const res = await fetch(`{{ route('hosting.control.upload', $hosting) }}`, {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                body: formData
                            });
                            const data = await res.json();
                            if (data.success) {
                                this.notify(data.message || `Arquivo ${file.name} enviado com sucesso!`);
                            } else {
                                this.notify(data.message, 'error');
                            }
                        } catch (e) {
                            this.notify('Erro no upload: ' + e.message, 'error');
                        }
                    }
                    this.loadFiles();
                },

                async extractZipFile(filepath) {
                    if (!confirm('Deseja descompactar este arquivo ZIP no diretório atual?')) return;
                    try {
                        const res = await fetch(`{{ route('hosting.control.extract-zip', $hosting) }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ filepath: filepath })
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.notify(data.message);
                            this.loadFiles();
                        } else {
                            this.notify(data.message, 'error');
                        }
                    } catch (e) {
                        this.notify('Erro ao extrair ZIP: ' + e.message, 'error');
                    }
                },

                async deleteItem(path, name) {
                    if (!confirm(`Tem certeza que deseja excluir "${name}"? Esta ação não pode ser desfeita.`)) return;
                    try {
                        const res = await fetch(`{{ route('hosting.control.delete-item', $hosting) }}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ path: path })
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.notify(data.message);
                            this.loadFiles();
                        } else {
                            this.notify(data.message, 'error');
                        }
                    } catch (e) {
                        this.notify('Erro ao excluir: ' + e.message, 'error');
                    }
                },

                // Code Editor
                async openEditor(filepath) {
                    try {
                        const res = await fetch(`{{ route('hosting.control.file-content', $hosting) }}?filepath=${encodeURIComponent(filepath)}`);
                        const data = await res.json();
                        if (data.success) {
                            this.editorFilepath = data.filepath;
                            this.editorFilename = data.filename;
                            this.editorContent = data.content;
                            this.showEditorModal = true;
                        } else {
                            this.notify(data.message, 'error');
                        }
                    } catch (e) {
                        this.notify('Erro ao abrir editor: ' + e.message, 'error');
                    }
                },

                async saveFileContent() {
                    this.savingFile = true;
                    try {
                        const res = await fetch(`{{ route('hosting.control.save-file', $hosting) }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ filepath: this.editorFilepath, content: this.editorContent })
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.notify(data.message);
                            this.loadFiles();
                        } else {
                            this.notify(data.message, 'error');
                        }
                    } catch (e) {
                        this.notify('Erro ao salvar arquivo: ' + e.message, 'error');
                    } finally {
                        this.savingFile = false;
                    }
                },

                // Gestão de DNS
                async loadDnsRecords() {
                    this.loadingDns = true;
                    try {
                        const res = await fetch(`{{ route('hosting.control.dns.list', $hosting) }}`);
                        const data = await res.json();
                        if (data.success) {
                            this.dnsRecords = data.records;
                        }
                    } catch (e) {
                        this.notify('Erro ao carregar DNS: ' + e.message, 'error');
                    } finally {
                        this.loadingDns = false;
                    }
                },

                async submitAddDns() {
                    try {
                        const res = await fetch(`{{ route('hosting.control.dns.store', $hosting) }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(this.newDns)
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.notify('Registro DNS adicionado com sucesso!');
                            this.showAddDnsModal = false;
                            this.newDns = { type: 'A', host: '', value: '', opt: '' };
                            this.loadDnsRecords();
                        } else {
                            this.notify(data.message || 'Falha ao adicionar DNS', 'error');
                        }
                    } catch (e) {
                        this.notify('Erro ao salvar DNS: ' + e.message, 'error');
                    }
                },

                async deleteDnsRecord(recordId) {
                    if (!confirm('Deseja realmente remover este registro DNS?')) return;
                    try {
                        const res = await fetch(`{{ url('hosting/' . $hosting->id . '/control/dns') }}/${recordId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.notify(data.message);
                            this.loadDnsRecords();
                        } else {
                            this.notify(data.message, 'error');
                        }
                    } catch (e) {
                        this.notify('Erro ao excluir DNS: ' + e.message, 'error');
                    }
                },

                // Bancos MySQL
                async loadDatabases() {
                    this.loadingDatabases = true;
                    try {
                        const res = await fetch(`{{ route('hosting.control.databases.list', $hosting) }}`);
                        const data = await res.json();
                        if (data.success) {
                            this.databases = data.databases;
                        }
                    } catch (e) {
                        this.notify('Erro ao carregar bancos: ' + e.message, 'error');
                    } finally {
                        this.loadingDatabases = false;
                    }
                },

                async submitNewDb() {
                    try {
                        const res = await fetch(`{{ route('hosting.control.databases.store', $hosting) }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(this.newDb)
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.notify('Banco de dados criado com sucesso!');
                            this.showNewDbModal = false;
                            this.newDb = { name: '', username: '', password: '' };
                            this.loadDatabases();
                        } else {
                            this.notify(data.message || 'Falha ao criar banco MySQL', 'error');
                        }
                    } catch (e) {
                        this.notify('Erro ao criar banco: ' + e.message, 'error');
                    }
                },

                // Runtime PHP & SSL
                async changePhpVersion() {
                    try {
                        const res = await fetch(`{{ route('hosting.control.update-php', $hosting) }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ php_version: this.phpVersion })
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.notify(data.message);
                        }
                    } catch (e) {
                        this.notify('Erro ao trocar PHP: ' + e.message, 'error');
                    }
                },

                async renewSsl() {
                    try {
                        const res = await fetch(`{{ route('hosting.control.renew-ssl', $hosting) }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.notify(data.message);
                        }
                    } catch (e) {
                        this.notify('Erro ao renovar SSL: ' + e.message, 'error');
                    }
                },

                // 1-Click Apps & Marketplace
                async loadAppsCatalog() {
                    this.loadingApps = true;
                    try {
                        const res = await fetch(`{{ route('hosting.control.apps.catalog', $hosting) }}`);
                        const data = await res.json();
                        if (data.success) {
                            this.appsCatalog = data.apps;
                        }
                    } catch (e) {
                        this.notify('Erro ao carregar catálogo: ' + e.message, 'error');
                    } finally {
                        this.loadingApps = false;
                    }
                },

                openInstallModal(app) {
                    this.selectedApp = app;
                    this.installResult = null;
                    this.installProgress = 0;
                    this.installStep = 0;
                    this.installingApp = false;
                    this.showInstallModal = true;
                },

                async triggerInstallApp() {
                    if (!this.selectedApp) return;
                    this.installingApp = true;
                    this.installProgress = 20;
                    this.installStep = 1;

                    const timer = setInterval(() => {
                        if (this.installProgress < 85) {
                            this.installProgress += 15;
                            if (this.installProgress >= 40 && this.installStep === 1) this.installStep = 2;
                            if (this.installProgress >= 70 && this.installStep === 2) this.installStep = 3;
                        }
                    }, 400);

                    try {
                        const payload = {
                            app_id: this.selectedApp.id,
                            ...this.installForm
                        };

                        const res = await fetch(`{{ route('hosting.control.apps.install', $hosting) }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        clearInterval(timer);
                        const data = await res.json();

                        if (data.success) {
                            this.installProgress = 100;
                            this.installStep = 4;
                            this.installResult = data.data;
                            this.notify(data.message || 'Aplicação instalada com sucesso!');
                            this.loadAppsCatalog();
                        } else {
                            this.notify(data.message || 'Falha ao instalar aplicativo.', 'error');
                        }
                    } catch (e) {
                        clearInterval(timer);
                        this.notify('Erro na instalação: ' + e.message, 'error');
                    } finally {
                        this.installingApp = false;
                    }
                },

                copyToClipboard(text, label) {
                    navigator.clipboard.writeText(text).then(() => {
                        this.notify(`${label} copiado para a área de transferência!`);
                    });
                }
            };
        }
    </script>
</x-app-layout>
