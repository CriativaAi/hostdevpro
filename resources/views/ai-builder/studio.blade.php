<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('ai-builder.index') }}" 
                   class="p-2 rounded-xl bg-white/[0.06] hover:bg-white/[0.12] border border-white/15 text-slate-300 hover:text-white transition"
                   title="Voltar aos sites">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="font-black text-xl text-white tracking-tight leading-tight">
                            {{ $aiSite->business_name }}
                        </h2>
                        <span class="text-[10px] px-2.5 py-0.5 rounded-full bg-purple-500/20 text-purple-300 border border-purple-500/30 font-bold uppercase">
                            {{ $aiSite->niche }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $aiSite->status_badge_classes }}" id="status-badge">
                            @if ($aiSite->status === \App\Models\AiGeneratedSite::STATUS_PUBLISHED)
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            @endif
                            {{ $aiSite->status_label }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5 flex items-center gap-2">
                        <span>Estilo: <strong class="text-slate-300">{{ $aiSite->style_label }}</strong></span>
                        <span>•</span>
                        <span id="revisions-count-label">{{ $aiSite->revisions_count }}ª revisão</span>
                        @if ($aiSite->hostingAccount)
                            <span>•</span>
                            <span class="font-mono text-emerald-400">Domínio: {{ $aiSite->hostingAccount->domain }}</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Botões Superiores do Studio -->
            <div class="flex flex-wrap items-center gap-2">
                <!-- Dispositivos (Desktop, Tablet, Mobile) -->
                <div class="flex items-center bg-black/40 p-1 rounded-xl border border-white/10 text-xs">
                    <button type="button" 
                            onclick="setDevice('desktop')" 
                            id="btn-device-desktop"
                            class="px-2.5 py-1.5 rounded-lg text-white bg-white/[0.12] transition font-bold flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="hidden sm:inline">Desktop</span>
                    </button>
                    <button type="button" 
                            onclick="setDevice('tablet')" 
                            id="btn-device-tablet"
                            class="px-2.5 py-1.5 rounded-lg text-slate-400 hover:text-white transition font-medium flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        <span class="hidden sm:inline">Tablet</span>
                    </button>
                    <button type="button" 
                            onclick="setDevice('mobile')" 
                            id="btn-device-mobile"
                            class="px-2.5 py-1.5 rounded-lg text-slate-400 hover:text-white transition font-medium flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        <span class="hidden sm:inline">Mobile</span>
                    </button>
                </div>

                <!-- Abrir em Nova Aba -->
                <a href="{{ route('ai-builder.preview', $aiSite) }}" 
                   target="_blank" 
                   class="p-2.5 rounded-xl bg-white/[0.06] hover:bg-white/[0.12] border border-white/15 text-slate-300 hover:text-white text-xs transition" 
                   title="Abrir em Nova Aba">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>

                <!-- Download index.html -->
                <a href="{{ route('ai-builder.download.html', $aiSite) }}" 
                   class="px-3.5 py-2 rounded-xl bg-white/[0.06] hover:bg-white/[0.12] border border-white/15 text-slate-200 hover:text-white font-bold text-xs uppercase tracking-wider transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>HTML</span>
                </a>

                <!-- Download ZIP -->
                <a href="{{ route('ai-builder.download.zip', $aiSite) }}" 
                   class="px-3.5 py-2 rounded-xl bg-white/[0.06] hover:bg-white/[0.12] border border-white/15 text-slate-200 hover:text-white font-bold text-xs uppercase tracking-wider transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    <span>ZIP</span>
                </a>

                <!-- Publicar no Servidor -->
                <form method="POST" action="{{ route('ai-builder.publish', $aiSite) }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40 transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Publicar</span>
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Notificação Flash -->
            @if (session('success'))
                <div class="mb-4 p-4 rounded-2xl bg-emerald-950/40 border border-emerald-500/40 text-emerald-300 text-xs flex items-center gap-2.5 shadow-xl backdrop-blur-xl">
                    <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Layout Principal do Studio: 2 Colunas -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Coluna Esquerda: Chat & Ajustes com IA (4 colunas) -->
                <div class="lg:col-span-4 space-y-5">
                    
                    <!-- Card de Refinamento / Chat com Gemini -->
                    <div class="p-6 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-xl space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-white/10">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-purple-500/20 text-purple-400 border border-purple-500/30 flex items-center justify-center font-bold text-xs">
                                    ✨
                                </div>
                                <h3 class="text-sm font-bold text-white">Ajustar com Gemini IA</h3>
                            </div>
                            <span class="text-[11px] text-purple-400 font-mono font-semibold">Gemini 3.6</span>
                        </div>

                        <p class="text-xs text-slate-400">
                            Peça alterações na linguagem natural. A inteligência artificial reescreverá o código mantendo a estrutura.
                        </p>

                        <!-- Sugestões de Prompt Rápidas -->
                        <div class="space-y-1.5">
                            <span class="text-[10px] uppercase tracking-wider font-bold text-slate-500 block">Sugestões de melhoria:</span>
                            <div class="flex flex-wrap gap-1.5">
                                <button type="button" 
                                        onclick="setInstruction('Mude as cores para azul marinho escuro e detalhes em dourado metálico.')"
                                        class="px-2.5 py-1 rounded-lg bg-white/[0.04] hover:bg-purple-500/20 border border-white/10 hover:border-purple-500/30 text-slate-300 hover:text-purple-300 text-[11px] transition">
                                    🎨 Mudar Cores
                                </button>
                                <button type="button" 
                                        onclick="setInstruction('Adicione mais 2 depoimentos de clientes com 5 estrelas e avaliações positivas.')"
                                        class="px-2.5 py-1 rounded-lg bg-white/[0.04] hover:bg-purple-500/20 border border-white/10 hover:border-purple-500/30 text-slate-300 hover:text-purple-300 text-[11px] transition">
                                    ⭐ Mais Depoimentos
                                </button>
                                <button type="button" 
                                        onclick="setInstruction('Adicione uma seção de Garantia de 30 Dias ou Dinheiro de Volta com selo dourado.')"
                                        class="px-2.5 py-1 rounded-lg bg-white/[0.04] hover:bg-purple-500/20 border border-white/10 hover:border-purple-500/30 text-slate-300 hover:text-purple-300 text-[11px] transition">
                                    🛡️ Selo de Garantia
                                </button>
                                <button type="button" 
                                        onclick="setInstruction('Torne a chamada para o WhatsApp mais urgente, com oferta por tempo limitado.')"
                                        class="px-2.5 py-1 rounded-lg bg-white/[0.04] hover:bg-purple-500/20 border border-white/10 hover:border-purple-500/30 text-slate-300 hover:text-purple-300 text-[11px] transition">
                                    🔥 Oferta Urgente
                                </button>
                            </div>
                        </div>

                        <!-- Formulário de Refinamento -->
                        <form id="refine-form" onsubmit="submitRefine(event)" class="space-y-3">
                            <textarea id="instruction-input" 
                                      rows="3" 
                                      required
                                      placeholder="Ex: Mude a cor do botão para esmeralda brilhante e adicione uma seção de perguntas frequentes..."
                                      class="w-full px-3.5 py-2.5 rounded-xl bg-black/40 border border-white/10 text-white placeholder-slate-500 text-xs focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 outline-none transition"></textarea>

                            <button type="submit" 
                                    id="refine-submit-btn"
                                    class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-purple-500/20 transition flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 animate-spin hidden" id="refine-spinner" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span id="refine-btn-text">✨ Aplicar Alteração com IA</span>
                            </button>
                        </form>

                        <div id="refine-feedback" class="hidden text-xs p-3 rounded-xl"></div>
                    </div>

                    <!-- Informações de Publicação & Hospedagem -->
                    <div class="p-6 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-xl space-y-3">
                        <h4 class="text-xs font-bold text-slate-300 uppercase tracking-wider">Status do Website</h4>
                        
                        @if ($aiSite->status === \App\Models\AiGeneratedSite::STATUS_PUBLISHED)
                            <div class="p-3.5 rounded-xl bg-emerald-950/30 border border-emerald-500/30 text-emerald-300 text-xs space-y-1">
                                <div class="font-bold flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span>Publicado com Sucesso</span>
                                </div>
                                <p class="text-[11px] text-emerald-300/80">
                                    Publicado em {{ $aiSite->published_at ? $aiSite->published_at->format('d/m/Y H:i') : 'recentemente' }}.
                                </p>
                                @if ($aiSite->published_path)
                                    <a href="{{ asset($aiSite->published_path) }}" target="_blank" class="text-xs text-white font-mono underline block pt-1">
                                        🔗 {{ asset($aiSite->published_path) }}
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="p-3.5 rounded-xl bg-amber-950/30 border border-amber-500/30 text-amber-300 text-xs space-y-1">
                                <div class="font-bold flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                    <span>Modo Rascunho / Studio</span>
                                </div>
                                <p class="text-[11px] text-amber-300/80">
                                    Você pode fazer ajustes ilimitados com a IA antes de colocar no ar ou baixar o pacote.
                                </p>
                            </div>
                        @endif

                        <!-- Guia Rápido de Hospedagem -->
                        <div class="pt-3 border-t border-white/10 text-xs text-slate-400 space-y-1.5">
                            <span class="font-bold text-slate-300 text-[11px] block">Opções de implantação:</span>
                            <p>• <strong>1-Clique:</strong> Se vinculado a um domínio do painel, clique em "Publicar" acima.</p>
                            <p>• <strong>Download Manual:</strong> Baixe o arquivo <code>index.html</code> ou o ZIP e envie para a pasta <code>public_html</code> de qualquer cPanel ou Plesk.</p>
                        </div>
                    </div>

                </div>

                <!-- Coluna Direita: Live Interactive Preview Frame (8 colunas) -->
                <div class="lg:col-span-8 flex flex-col items-center">
                    
                    <!-- Barra de Controle do Preview -->
                    <div class="w-full mb-3 flex items-center justify-between px-2 text-xs text-slate-400">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500/80"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></span>
                            <span class="font-mono text-[11px] text-slate-400 ml-2" id="preview-url-bar">
                                https://{{ $aiSite->hostingAccount ? $aiSite->hostingAccount->domain : 'preview-studio.hostdevpro.app.br' }}
                            </span>
                        </div>
                        <button type="button" 
                                onclick="reloadPreview()" 
                                class="flex items-center gap-1 hover:text-white transition" 
                                title="Recarregar Prévia">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>Recarregar</span>
                        </button>
                    </div>

                    <!-- Container com Moldura Responsiva -->
                    <div id="device-wrapper" 
                         class="w-full transition-all duration-300 mx-auto rounded-2xl overflow-hidden border border-white/20 shadow-2xl bg-slate-950 flex flex-col"
                         style="height: 780px;">
                        <iframe id="site-preview-iframe" 
                                src="{{ route('ai-builder.preview', $aiSite) }}" 
                                class="w-full h-full border-0 bg-white"
                                title="Prévia do Site Gerado"></iframe>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <script>
        function setDevice(type) {
            var wrapper = document.getElementById('device-wrapper');
            var btnDesk = document.getElementById('btn-device-desktop');
            var btnTab = document.getElementById('btn-device-tablet');
            var btnMob = document.getElementById('btn-device-mobile');

            [btnDesk, btnTab, btnMob].forEach(function(b) {
                b.classList.remove('bg-white/[0.12]', 'text-white');
                b.classList.add('text-slate-400');
            });

            if (type === 'mobile') {
                wrapper.style.maxWidth = '375px';
                btnMob.classList.add('bg-white/[0.12]', 'text-white');
                btnMob.classList.remove('text-slate-400');
            } else if (type === 'tablet') {
                wrapper.style.maxWidth = '768px';
                btnTab.classList.add('bg-white/[0.12]', 'text-white');
                btnTab.classList.remove('text-slate-400');
            } else {
                wrapper.style.maxWidth = '100%';
                btnDesk.classList.add('bg-white/[0.12]', 'text-white');
                btnDesk.classList.remove('text-slate-400');
            }
        }

        function setInstruction(text) {
            document.getElementById('instruction-input').value = text;
            document.getElementById('instruction-input').focus();
        }

        function reloadPreview() {
            var iframe = document.getElementById('site-preview-iframe');
            iframe.src = iframe.src.split('?')[0] + '?t=' + new Date().getTime();
        }

        function submitRefine(e) {
            e.preventDefault();
            var input = document.getElementById('instruction-input');
            var btn = document.getElementById('refine-submit-btn');
            var spinner = document.getElementById('refine-spinner');
            var text = document.getElementById('refine-btn-text');
            var feedback = document.getElementById('refine-feedback');

            var instruction = input.value.trim();
            if (!instruction) return;

            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
            spinner.classList.remove('hidden');
            text.innerText = 'Refinando com Gemini...';
            feedback.classList.add('hidden');

            fetch('{{ route('ai-builder.refine', $aiSite) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ instruction: instruction })
            })
            .then(function(res) {
                return res.json().then(function(data) {
                    return { ok: res.ok, data: data };
                });
            })
            .then(function(result) {
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'cursor-not-allowed');
                spinner.classList.add('hidden');
                text.innerText = '✨ Aplicar Alteração com IA';

                if (result.ok && result.data.success) {
                    input.value = '';
                    feedback.className = 'text-xs p-3 rounded-xl bg-emerald-950/40 border border-emerald-500/40 text-emerald-300';
                    feedback.innerText = '✓ ' + result.data.message;
                    feedback.classList.remove('hidden');

                    if (result.data.revisions_count) {
                        document.getElementById('revisions-count-label').innerText = result.data.revisions_count + 'ª revisão';
                    }

                    reloadPreview();
                } else {
                    feedback.className = 'text-xs p-3 rounded-xl bg-rose-950/40 border border-rose-500/40 text-rose-300';
                    feedback.innerText = 'Erro: ' + (result.data.message || 'Falha ao refinar com a IA.');
                    feedback.classList.remove('hidden');
                }
            })
            .catch(function(err) {
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'cursor-not-allowed');
                spinner.classList.add('hidden');
                text.innerText = '✨ Aplicar Alteração com IA';

                feedback.className = 'text-xs p-3 rounded-xl bg-rose-950/40 border border-rose-500/40 text-rose-300';
                feedback.innerText = 'Erro de conexão: ' + err.message;
                feedback.classList.remove('hidden');
            });
        }
    </script>
</x-app-layout>
