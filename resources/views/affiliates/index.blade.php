<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-black text-xl sm:text-2xl text-white tracking-tight">
                            Programa de Afiliados & Indicações
                        </h2>
                        <p class="text-xs text-slate-400 font-medium">
                            Indique clientes para a HostDevPro Cloud e fature 15% de comissão por cada assinatura
                        </p>
                    </div>
                </div>
            </div>

            @if($affiliate)
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Afiliado Ativo &bull; {{ $affiliate->referral_code }}
                    </span>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Mensagens de Alerta / Feedback Flash -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-sm flex items-center justify-between shadow-lg shadow-emerald-950/20">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-300 text-sm flex items-center gap-3 shadow-lg shadow-rose-950/20">
                    <svg class="w-5 h-5 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(!$affiliate)
                <!-- ========================================== -->
                <!-- ESTADO 1: CARD DE ONBOARDING & ATIVAÇÃO    -->
                <!-- (Exato teor e disposição da captura)       -->
                <!-- ========================================== -->
                <div class="rounded-2xl bg-slate-900/80 backdrop-blur-md border border-slate-800/80 p-8 sm:p-12 shadow-2xl relative overflow-hidden">
                    <!-- Glow decorativo de fundo -->
                    <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="relative z-10 max-w-3xl">
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                            Ganhe dinheiro indicando clientes para nós
                        </h3>
                        <p class="mt-2 text-base text-slate-300 font-medium">
                            Ative sua conta de afiliado e comece a faturar hoje mesmo...
                        </p>

                        <ul class="mt-8 space-y-4 text-sm text-slate-300 leading-relaxed">
                            <li class="flex items-start gap-3">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 mt-2 flex-shrink-0"></span>
                                <span>Pagamos comissões por cada assinatura realizada através do seu link de inscrição personalizado.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 mt-2 flex-shrink-0"></span>
                                <span>Utilizamos cookies para rastrear os visitantes que você nos indica, de forma que os usuários que você indica não precisem comprar imediatamente para que você receba sua comissão. Os cookies permanecem ativos por até <strong>90 dias</strong> após a visita inicial.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 mt-2 flex-shrink-0"></span>
                                <span>Para mais informações, entre em contato conosco ou abra um chamado em nosso suporte.</span>
                            </li>
                        </ul>

                        <!-- Botão de Ativação estilo Pill como na imagem -->
                        <div class="mt-12 flex justify-center sm:justify-start">
                            <form action="{{ route('affiliates.activate') }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="inline-flex items-center justify-center px-10 py-3.5 rounded-full border border-emerald-500/40 bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-400 hover:text-emerald-300 font-bold text-base transition-all duration-300 shadow-lg shadow-emerald-950/30 hover:shadow-emerald-900/50 hover:scale-105 active:scale-95 group">
                                    <span>Ativar Conta de Afiliado</span>
                                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            @else
                <!-- ========================================== -->
                <!-- ESTADO 2: DASHBOARD COMPLETO DO AFILIADO   -->
                <!-- ========================================== -->

                <!-- Card de Link de Indicação & Compartilhamento -->
                <div class="rounded-2xl bg-slate-900/80 backdrop-blur-md border border-slate-800/80 p-6 sm:p-8 shadow-xl relative overflow-hidden"
                     x-data="{ 
                         copied: false,
                         link: '{{ $affiliate->referral_url }}',
                         copyLink() {
                             navigator.clipboard.writeText(this.link);
                             this.copied = true;
                             setTimeout(() => this.copied = false, 2500);
                         }
                     }">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                        <div class="space-y-1 max-w-xl">
                            <span class="text-xs uppercase font-bold tracking-wider text-emerald-400 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                Seu Link de Indicação Exclusivo
                            </span>
                            <h4 class="text-lg font-bold text-white">
                                Compartilhe com agências, desenvolvedores e clientes
                            </h4>
                            <p class="text-xs text-slate-400">
                                Quem clicar no seu link terá um cookie gravado por <strong class="text-slate-200">90 dias</strong>. Qualquer serviço assinado gera <strong class="text-emerald-400">15% de comissão</strong> para você.
                            </p>
                        </div>

                        <!-- Barra do Link com Copiar e WhatsApp -->
                        <div class="w-full lg:w-auto flex-1 max-w-2xl space-y-3">
                            <div class="flex items-center gap-2 p-1.5 rounded-xl bg-slate-950/80 border border-slate-800">
                                <input type="text" 
                                       readonly 
                                       :value="link" 
                                       class="w-full bg-transparent border-none text-xs sm:text-sm font-mono text-emerald-300 focus:ring-0 px-3 select-all">
                                <button type="button" 
                                        @click="copyLink()"
                                        class="px-4 py-2 rounded-lg bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 hover:text-emerald-200 text-xs font-bold transition flex items-center gap-1.5 flex-shrink-0 border border-emerald-500/30">
                                    <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    <svg x-show="copied" class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span x-text="copied ? 'Copiado!' : 'Copiar'"></span>
                                </button>
                            </div>

                            <div class="flex flex-wrap items-center gap-3">
                                <a href="https://api.whatsapp.com/send?text={{ urlencode('🚀 Conheça a HostDevPro Cloud: hospedagem de alta performance, servidores VPS NVMe dedicados e suporte 24/7. Cadastre-se pelo link: ' . $affiliate->referral_url) }}" 
                                   target="_blank"
                                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-[#25D366]/15 hover:bg-[#25D366]/25 text-[#25D366] text-xs font-bold border border-[#25D366]/30 transition">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.539 1.93.824 2.791.825 3.181 0 5.767-2.586 5.768-5.766 0-3.18-2.587-5.765-5.768-5.765zm3.385 8.169c-.145.407-.847.777-1.177.826-.33.048-.759.074-1.229-.074-.326-.103-.746-.245-1.284-.476-2.277-.978-3.766-3.287-3.879-3.438-.113-.151-.926-1.233-.926-2.351 0-1.118.583-1.668.791-1.897.208-.228.455-.286.607-.286.152 0 .303.001.436.008.141.007.33-.053.516.395.193.468.66 1.613.717 1.731.058.118.096.257.019.412-.077.155-.116.252-.232.387-.116.136-.244.305-.349.409-.116.116-.238.243-.102.476.136.233.606.999 1.299 1.616.891.794 1.642 1.039 1.875 1.155.233.116.37.098.506-.058.136-.156.583-.679.739-.912.155-.233.31-.194.524-.116.213.078 1.35.637 1.583.753.233.116.388.174.446.271.058.098.058.564-.087.971z"/></svg>
                                    Compartilhar no WhatsApp
                                </a>

                                <span class="text-[11px] text-slate-500 font-mono">
                                    Cookie: 90 dias ativos
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4 Indicadores Rápidos (KPIs em Dark Frosted Glass) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Cliques / Visitantes -->
                    <div class="p-6 rounded-2xl bg-slate-900/80 backdrop-blur-md border border-slate-800/80 shadow-lg relative overflow-hidden group hover:border-slate-700 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Cliques no Link</span>
                            <div class="p-2 rounded-xl bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="text-2xl sm:text-3xl font-black text-white font-mono">
                                {{ number_format($affiliate->visitors_count, 0, ',', '.') }}
                            </span>
                            <p class="text-[11px] text-slate-500 mt-1">Visitantes únicos rastreados</p>
                        </div>
                    </div>

                    <!-- Assinaturas / Conversões -->
                    <div class="p-6 rounded-2xl bg-slate-900/80 backdrop-blur-md border border-slate-800/80 shadow-lg relative overflow-hidden group hover:border-slate-700 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Conversões</span>
                            <div class="p-2 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="text-2xl sm:text-3xl font-black text-white font-mono">
                                {{ number_format($affiliate->conversions_count, 0, ',', '.') }}
                            </span>
                            <p class="text-[11px] text-emerald-400 mt-1">
                                Taxa de conversão: {{ $affiliate->conversion_rate }}
                            </p>
                        </div>
                    </div>

                    <!-- Saldo Disponível para Saque -->
                    <div class="p-6 rounded-2xl bg-slate-900/80 backdrop-blur-md border border-emerald-500/30 shadow-lg relative overflow-hidden group">
                        <div class="absolute -top-10 -right-10 w-24 h-24 bg-emerald-500/15 rounded-full blur-xl pointer-events-none"></div>
                        <div class="flex items-center justify-between relative z-10">
                            <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">Saldo Disponível</span>
                            <div class="p-2 rounded-xl bg-emerald-500/15 text-emerald-300 border border-emerald-500/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                        <div class="mt-4 relative z-10">
                            <span class="text-2xl sm:text-3xl font-black text-emerald-400 font-mono">
                                {{ $affiliate->formatted_balance }}
                            </span>
                            <p class="text-[11px] text-slate-400 mt-1">Mínimo para saque: R$ 50,00</p>
                        </div>
                    </div>

                    <!-- Total Histórico Ganho -->
                    <div class="p-6 rounded-2xl bg-slate-900/80 backdrop-blur-md border border-slate-800/80 shadow-lg relative overflow-hidden group hover:border-slate-700 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Faturado</span>
                            <div class="p-2 rounded-xl bg-purple-500/10 text-purple-400 border border-purple-500/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="text-2xl sm:text-3xl font-black text-white font-mono">
                                {{ $affiliate->formatted_total_earned }}
                            </span>
                            <p class="text-[11px] text-slate-500 mt-1">Já resgatado: {{ $affiliate->formatted_total_withdrawn }}</p>
                        </div>
                    </div>
                </div>

                <!-- Seção em 2 Colunas: Configuração da Chave PIX & Solicitação de Saque -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Card 1: Chave PIX Cadastrada -->
                    <div class="rounded-2xl bg-slate-900/80 backdrop-blur-md border border-slate-800/80 p-6 shadow-xl space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                            <div class="flex items-center gap-2.5">
                                <div class="p-2 rounded-xl bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <h4 class="text-base font-bold text-white">Chave PIX para Depósito</h4>
                            </div>
                            @if($affiliate->pix_key)
                                <span class="text-[11px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded-md">
                                    Cadastrada
                                </span>
                            @else
                                <span class="text-[11px] font-bold text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2 py-0.5 rounded-md">
                                    Pendente
                                </span>
                            @endif
                        </div>

                        <form action="{{ route('affiliates.update-pix') }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1">Tipo de Chave</label>
                                    <select name="pix_key_type" class="w-full rounded-xl bg-slate-950/80 border border-slate-800 text-xs text-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                                        <option value="cpf" {{ $affiliate->pix_key_type === 'cpf' ? 'selected' : '' }}>CPF</option>
                                        <option value="cnpj" {{ $affiliate->pix_key_type === 'cnpj' ? 'selected' : '' }}>CNPJ</option>
                                        <option value="email" {{ $affiliate->pix_key_type === 'email' ? 'selected' : '' }}>E-mail</option>
                                        <option value="phone" {{ $affiliate->pix_key_type === 'phone' ? 'selected' : '' }}>Telefone</option>
                                        <option value="random" {{ $affiliate->pix_key_type === 'random' ? 'selected' : '' }}>Chave Aleatória</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-semibold text-slate-400 mb-1">Sua Chave PIX</label>
                                    <input type="text" 
                                           name="pix_key" 
                                           value="{{ old('pix_key', $affiliate->pix_key) }}" 
                                           placeholder="Ex: 123.456.789-00 ou pix@empresa.com"
                                           class="w-full rounded-xl bg-slate-950/80 border border-slate-800 text-xs text-slate-200 focus:border-emerald-500 focus:ring-emerald-500 font-mono">
                                </div>
                            </div>

                            <button type="submit" 
                                    class="w-full py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white text-xs font-bold transition border border-slate-700 flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Salvar Chave PIX
                            </button>
                        </form>
                    </div>

                    <!-- Card 2: Solicitar Saque PIX -->
                    <div class="rounded-2xl bg-slate-900/80 backdrop-blur-md border border-slate-800/80 p-6 shadow-xl space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                            <div class="flex items-center gap-2.5">
                                <div class="p-2 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                </div>
                                <h4 class="text-base font-bold text-white">Solicitar Resgate de Comissões</h4>
                            </div>
                            <span class="text-xs font-mono font-bold text-emerald-400">
                                {{ $affiliate->formatted_balance }}
                            </span>
                        </div>

                        <form action="{{ route('affiliates.withdraw') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1">
                                    Valor a Resgatar (Mínimo R$ 50,00)
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-slate-500">R$</span>
                                    <input type="number" 
                                           step="0.01" 
                                           min="50" 
                                           max="{{ $affiliate->balance_cents / 100 }}"
                                           name="amount" 
                                           value="{{ $affiliate->balance_cents >= 5000 ? number_format($affiliate->balance_cents / 100, 2, '.', '') : '' }}" 
                                           placeholder="50.00"
                                           class="w-full pl-9 rounded-xl bg-slate-950/80 border border-slate-800 text-xs text-slate-200 focus:border-emerald-500 focus:ring-emerald-500 font-mono">
                                </div>
                            </div>

                            @if($affiliate->balance_cents >= 5000 && !empty($affiliate->pix_key))
                                <button type="submit" 
                                        class="w-full py-2.5 rounded-xl bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400 hover:text-emerald-300 text-xs font-bold transition border border-emerald-500/30 shadow-lg shadow-emerald-950/30 flex items-center justify-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    Confirmar Saque Instantâneo via PIX
                                </button>
                            @else
                                <button type="button" disabled 
                                        class="w-full py-2.5 rounded-xl bg-slate-800/50 text-slate-500 text-xs font-bold border border-slate-800 cursor-not-allowed">
                                    @if(empty($affiliate->pix_key))
                                        Cadastre uma Chave PIX primeiro
                                    @else
                                        Saldo insuficiente (Mínimo R$ 50,00)
                                    @endif
                                </button>
                            @endif
                        </form>
                    </div>
                </div>

                <!-- Tabela de Comissões Recentes -->
                <div class="rounded-2xl bg-slate-900/80 backdrop-blur-md border border-slate-800/80 p-6 shadow-xl space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-base font-bold text-white flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            Comissões Recentes Recebidas
                        </h4>
                        <span class="text-xs text-slate-500">Últimas 20 comissões</span>
                    </div>

                    @if($affiliate->commissions->isEmpty())
                        <div class="py-10 text-center space-y-2">
                            <p class="text-xs text-slate-400">Nenhuma comissão gerada ainda.</p>
                            <p class="text-[11px] text-slate-500">Compartilhe seu link de indicação para começar a receber!</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs text-slate-400">
                                <thead class="border-b border-slate-800 text-[11px] uppercase font-bold text-slate-500 font-mono">
                                    <tr>
                                        <th class="py-3 px-3">Data</th>
                                        <th class="py-3 px-3">Descrição / Fatura</th>
                                        <th class="py-3 px-3">Cliente</th>
                                        <th class="py-3 px-3 text-right">Valor da Fatura</th>
                                        <th class="py-3 px-3 text-right">Comissão (15%)</th>
                                        <th class="py-3 px-3 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800/60 font-mono">
                                    @foreach($affiliate->commissions as $comm)
                                        <tr class="hover:bg-slate-800/30 transition">
                                            <td class="py-3 px-3 text-slate-300">
                                                {{ $comm->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="py-3 px-3 text-slate-200 font-sans">
                                                {{ $comm->description }}
                                            </td>
                                            <td class="py-3 px-3 text-slate-400 font-sans">
                                                {{ $comm->referredUser?->name ?? 'Cliente Indicado' }}
                                            </td>
                                            <td class="py-3 px-3 text-right text-slate-400">
                                                {{ $comm->formatted_order_amount }}
                                            </td>
                                            <td class="py-3 px-3 text-right text-emerald-400 font-bold">
                                                + {{ $comm->formatted_commission }}
                                            </td>
                                            <td class="py-3 px-3 text-center">
                                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold {{ $comm->status_badge }}">
                                                    {{ $comm->status_label }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Histórico de Saques PIX -->
                @if($affiliate->withdrawals->isNotEmpty())
                    <div class="rounded-2xl bg-slate-900/80 backdrop-blur-md border border-slate-800/80 p-6 shadow-xl space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-base font-bold text-white flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
                                Histórico de Solicitações de Saque PIX
                            </h4>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs text-slate-400">
                                <thead class="border-b border-slate-800 text-[11px] uppercase font-bold text-slate-500 font-mono">
                                    <tr>
                                        <th class="py-3 px-3">Data</th>
                                        <th class="py-3 px-3">Valor</th>
                                        <th class="py-3 px-3">Chave PIX</th>
                                        <th class="py-3 px-3">Tipo</th>
                                        <th class="py-3 px-3 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800/60 font-mono">
                                    @foreach($affiliate->withdrawals as $with)
                                        <tr class="hover:bg-slate-800/30 transition">
                                            <td class="py-3 px-3 text-slate-300">
                                                {{ $with->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="py-3 px-3 text-emerald-400 font-bold">
                                                {{ $with->formatted_amount }}
                                            </td>
                                            <td class="py-3 px-3 text-slate-300">
                                                {{ $with->pix_key }}
                                            </td>
                                            <td class="py-3 px-3 text-slate-400 uppercase">
                                                {{ $with->pix_key_type }}
                                            </td>
                                            <td class="py-3 px-3 text-center">
                                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold {{ $with->status_badge }}">
                                                    {{ $with->status_label }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>
