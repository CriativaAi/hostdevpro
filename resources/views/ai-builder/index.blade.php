<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-white tracking-tight leading-tight flex items-center gap-2.5">
                    <span>Criador de Sites com IA</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-gradient-to-r from-purple-500/20 to-pink-500/20 text-purple-300 border border-purple-500/30 font-bold flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-pulse"></span>
                        Gemini 3.6 Flash
                    </span>
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Gere páginas de alta conversão, responsivas e prontas para seus clientes em segundos com inteligência artificial.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('ai-builder.create') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-purple-600 via-pink-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-purple-500/25 hover:shadow-purple-500/40 transition-all transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Criar Novo Site com IA</span>
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
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block truncate">Total de Sites Criados</span>
                    <span class="text-3xl font-black text-white mt-2 block tracking-tight">{{ $kpis['total'] }}</span>
                    <span class="text-[11px] text-slate-400 mt-1 block truncate">Projetos gerados por IA</span>
                </div>

                <!-- Publicados -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl min-w-0 overflow-hidden relative group transition">
                    <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider block truncate">Sites Publicados</span>
                    <div class="flex items-baseline gap-2 mt-2">
                        <span class="text-3xl font-black text-emerald-400 tracking-tight">{{ $kpis['published'] }}</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    </div>
                    <span class="text-[11px] text-slate-400 mt-1 block truncate">Online no servidor</span>
                </div>

                <!-- Rascunhos -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl min-w-0 overflow-hidden relative group transition">
                    <span class="text-xs font-bold text-amber-400 uppercase tracking-wider block truncate">Em Edição / Rascunhos</span>
                    <span class="text-3xl font-black text-amber-400 mt-2 block tracking-tight">{{ $kpis['drafts'] }}</span>
                    <span class="text-[11px] text-slate-400 mt-1 block truncate">Ajustes no Studio</span>
                </div>

                <!-- Motor IA -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl min-w-0 overflow-hidden relative group transition">
                    <span class="text-xs font-bold text-purple-400 uppercase tracking-wider block truncate">Motor de Inteligência</span>
                    <div class="flex items-baseline gap-2 mt-2">
                        <span class="text-2xl font-black text-purple-300 tracking-tight">Gemini 3.6</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-purple-500/20 text-purple-300 border border-purple-500/30 font-bold uppercase">Flash</span>
                    </div>
                    <span class="text-[11px] text-slate-400 mt-1 block truncate">Single-page Tailwind CSS</span>
                </div>
            </div>

            <!-- Filtros & Busca -->
            <div class="p-6 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-xl">
                <form method="GET" action="{{ route('ai-builder.index') }}" class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div class="sm:col-span-2 relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Buscar por nome da empresa, nicho..." 
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-black/40 border border-white/10 text-white placeholder-slate-500 text-xs focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 outline-none transition">
                    </div>

                    <div>
                        <select name="status" onchange="this.form.submit()" class="w-full py-2.5 px-3 rounded-xl bg-black/40 border border-white/10 text-xs text-slate-200 outline-none focus:border-purple-500">
                            <option value="" class="bg-slate-900 text-slate-300">Todos os Status</option>
                            <option value="{{ \App\Models\AiGeneratedSite::STATUS_PUBLISHED }}" @selected(request('status') === \App\Models\AiGeneratedSite::STATUS_PUBLISHED) class="bg-slate-900 text-emerald-400">🟢 Publicado</option>
                            <option value="{{ \App\Models\AiGeneratedSite::STATUS_DRAFT }}" @selected(request('status') === \App\Models\AiGeneratedSite::STATUS_DRAFT) class="bg-slate-900 text-amber-400">🟡 Rascunho</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="w-full py-2.5 px-4 bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-sm">
                            Filtrar
                        </button>
                        @if (request()->hasAny(['search', 'status']))
                            <a href="{{ route('ai-builder.index') }}" class="px-2 text-xs text-slate-400 hover:text-white transition">
                                Limpar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Grade de Sites Criados -->
            @if ($sites->isEmpty())
                <div class="p-12 text-center rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-2xl">
                    <div class="w-16 h-16 rounded-2xl bg-purple-500/10 border border-purple-500/30 text-purple-400 mx-auto flex items-center justify-center mb-4 shadow-lg shadow-purple-500/10">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">Nenhum site gerado ainda</h3>
                    <p class="text-xs text-slate-400 mt-2 max-w-md mx-auto">
                        Crie uma página de pouso profissional em menos de 1 minuto apenas respondendo algumas perguntas simples.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('ai-builder.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 via-pink-600 to-indigo-600 text-white font-black text-xs uppercase tracking-wider rounded-xl shadow-xl shadow-purple-500/30 hover:shadow-purple-500/50 transition transform hover:-translate-y-0.5">
                            ✨ Iniciar Criador com IA
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($sites as $site)
                        <div class="group rounded-2xl bg-white/[0.06] hover:bg-white/[0.09] backdrop-blur-2xl border border-white/15 hover:border-purple-500/40 transition-all duration-300 shadow-xl overflow-hidden flex flex-col justify-between">
                            
                            <!-- Topo do Card -->
                            <div class="p-6">
                                <div class="flex items-start justify-between gap-3 mb-3">
                                    <div>
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-500/15 text-purple-300 border border-purple-500/30 uppercase tracking-wider mb-2">
                                            {{ $site->niche }}
                                        </span>
                                        <h4 class="text-base font-black text-white group-hover:text-purple-300 transition">
                                            {{ $site->business_name }}
                                        </h4>
                                    </div>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $site->status_badge_classes }}">
                                        @if ($site->status === \App\Models\AiGeneratedSite::STATUS_PUBLISHED)
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                        @endif
                                        {{ $site->status_label }}
                                    </span>
                                </div>

                                <p class="text-xs text-slate-400 line-clamp-2 mt-1">
                                    {{ $site->description ?: 'Página de alta conversão gerada com Google Gemini IA.' }}
                                </p>

                                <!-- Metadados -->
                                <div class="mt-4 pt-4 border-t border-white/10 space-y-2 text-xs">
                                    <div class="flex items-center justify-between text-slate-400">
                                        <span class="text-[11px]">Estilo Visual:</span>
                                        <span class="font-semibold text-slate-200">{{ $site->style_label }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-slate-400">
                                        <span class="text-[11px]">Hospedagem:</span>
                                        @if ($site->hostingAccount)
                                            <a href="{{ route('hosting.show', $site->hostingAccount) }}" class="font-mono text-emerald-400 hover:underline">
                                                {{ $site->hostingAccount->domain }}
                                            </a>
                                        @else
                                            <span class="text-slate-500 italic">Avulso / Download</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center justify-between text-slate-400">
                                        <span class="text-[11px]">Revisões IA:</span>
                                        <span class="font-mono text-slate-300">{{ $site->revisions_count }}x gerado</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Barra de Ações Inferior -->
                            <div class="px-6 py-4 bg-black/30 border-t border-white/10 flex items-center justify-between gap-2">
                                <a href="{{ route('ai-builder.studio', $site) }}" 
                                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold text-xs shadow-sm transition">
                                    <span>Abrir Studio</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>

                                <div class="flex items-center gap-1">
                                    <!-- Ver Preview -->
                                    <a href="{{ route('ai-builder.preview', $site) }}" 
                                       target="_blank" 
                                       class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/[0.08] transition" 
                                       title="Visualizar em Nova Aba">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>

                                    <!-- Baixar HTML -->
                                    <a href="{{ route('ai-builder.download.html', $site) }}" 
                                       class="p-2 rounded-lg text-slate-400 hover:text-emerald-400 hover:bg-white/[0.08] transition" 
                                       title="Baixar index.html">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    </a>

                                    <!-- Excluir -->
                                    <form method="POST" action="{{ route('ai-builder.destroy', $site) }}" class="inline" onsubmit="return confirm('Deseja excluir este website gerado?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 transition" title="Excluir">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($sites->hasPages())
                    <div class="p-6 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15">
                        {{ $sites->links() }}
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>
