<x-guest-layout>
    <div class="max-w-4xl w-full bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl shadow-slate-300/70 overflow-hidden border border-slate-200/90 grid grid-cols-1 md:grid-cols-12 my-6">
        
        <!-- Painel Esquerdo: Identidade Visual Vibrante em Fundo Claro -->
        <div class="md:col-span-5 bg-gradient-to-b from-slate-50 via-white to-orange-50/40 p-8 md:p-10 flex flex-col justify-between border-b md:border-b-0 md:border-r border-slate-200/80 relative overflow-hidden">
            <!-- Glows Sutis de Fundo -->
            <div class="absolute -top-12 -left-12 w-48 h-48 bg-orange-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -right-12 w-48 h-48 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <!-- Topo: Logotipo Oficial para Fundo Claro em Destaque GRANDE com Animação PULSAR -->
            <div class="relative z-10 pt-2 pb-6">
                <a href="https://hostdevpro.app.br" class="inline-block">
                    <img src="{{ asset('brand/logos/light/HostDevPro-horizontal-gradient.webp') }}" 
                         alt="HostDevPro Cloud" 
                         class="h-14 sm:h-16 w-auto object-contain animate-logo-pulsar">
                </a>
            </div>

            <!-- Centro: Mensagem de Boas-Vindas e Badges Tecnológicos -->
            <div class="my-6 md:my-auto relative z-10 space-y-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100/80 text-emerald-800 text-xs font-bold font-mono uppercase tracking-wider border border-emerald-200 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Área do Cliente &bull; HostDevPro
                </span>
                
                <h2 class="text-2xl sm:text-3xl font-display font-black text-slate-900 tracking-tight leading-tight">
                    Olá, bem-vindo de volta! 👋
                </h2>
                
                <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                    Acesse seu painel exclusivo para gerenciar seus servidores, automações n8n, hospedagem e faturas PIX instantâneas.
                </p>

                <!-- Destaques Tecnológicos -->
                <div class="space-y-2 pt-2 text-xs font-medium text-slate-700">
                    <div class="flex items-center gap-2 p-2 rounded-xl bg-white border border-slate-200/80 shadow-sm">
                        <span class="text-base">⚡</span>
                        <span>Datacenter SP3 Brasil &bull; NVMe Gen5 Array</span>
                    </div>
                    <div class="flex items-center gap-2 p-2 rounded-xl bg-white border border-slate-200/80 shadow-sm">
                        <span class="text-base">🔒</span>
                        <span>Criptografia Ponta a Ponta TLS 1.3</span>
                    </div>
                </div>

                <!-- Botão Primeiro Acesso -->
                <div class="pt-4">
                    <a href="{{ route('register') }}" 
                       class="inline-flex items-center justify-center w-full px-6 py-3.5 rounded-xl bg-gradient-to-r from-orange-500 via-rose-500 to-amber-500 hover:from-orange-600 hover:to-rose-600 text-white font-black text-xs tracking-wider uppercase transition-all duration-200 shadow-lg shadow-orange-500/25 hover:scale-[1.02] active:scale-[0.98]">
                        <span>PRIMEIRO ACESSO (CRIAR CONTA) &rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Rodapé do Painel Esquerdo -->
            <div class="relative z-10 pt-4 border-t border-slate-200/80 flex items-center justify-between text-[11px] text-slate-500 font-mono">
                <span class="flex items-center gap-1.5 text-emerald-700 font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sistemas 100% Online
                </span>
                <span>SP3 Brasil</span>
            </div>
        </div>

        <!-- Painel Direito: Formulário de Autenticação -->
        <div class="md:col-span-7 p-8 md:p-12 flex flex-col justify-between bg-white relative">
            <div>
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-display font-black text-slate-900 tracking-tight">
                            Portal do Cliente
                        </h1>
                        <p class="text-xs md:text-sm text-slate-500 font-medium mt-1">
                            Informe seu e-mail e senha cadastrados para entrar.
                        </p>
                    </div>
                    <img src="{{ asset('brand/icons/HDP-icon-64.png') }}" alt="HDP" class="h-10 w-10 object-contain drop-shadow-sm hidden sm:block">
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1.5">
                            E-mail Corporativo
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username" 
                               placeholder="seunome@empresa.com.br"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 bg-slate-50/50 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 text-sm font-medium transition outline-none shadow-sm @error('email') border-rose-500 @enderror">
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-rose-600 text-xs font-semibold" />
                    </div>

                    <!-- Password with Eye Toggle -->
                    <div x-data="{ showPassword: false }">
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold text-slate-800 uppercase tracking-wider">
                                Senha de Acesso
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs text-rose-600 hover:text-rose-700 font-bold hover:underline transition">
                                    Esqueceu a senha?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <input id="password" 
                                   :type="showPassword ? 'text' : 'password'" 
                                   name="password" 
                                   required 
                                   autocomplete="current-password" 
                                   placeholder="••••••••"
                                   class="w-full px-4 py-3 pe-11 rounded-xl border border-slate-300 text-slate-900 bg-slate-50/50 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 text-sm font-medium transition outline-none shadow-sm @error('password') border-rose-500 @enderror">
                            <button type="button" 
                                    @click="showPassword = !showPassword" 
                                    class="absolute inset-y-0 right-0 pe-3.5 flex items-center text-slate-400 hover:text-slate-800 transition focus:outline-none"
                                    :title="showPassword ? 'Ocultar senha' : 'Ver senha'">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" style="display: none;" class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-rose-600 text-xs font-semibold" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-1">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" 
                                   type="checkbox" 
                                   name="remember" 
                                   class="rounded border-slate-300 text-orange-500 shadow-sm focus:ring-orange-500">
                            <span class="ms-2 text-xs font-medium text-slate-700 select-none">Lembrar neste dispositivo</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full py-4 px-6 rounded-xl bg-slate-950 hover:bg-emerald-600 text-white font-black text-xs md:text-sm tracking-wider uppercase shadow-xl shadow-slate-950/20 hover:shadow-emerald-600/30 transition-all duration-200 flex items-center justify-center gap-2 group hover:scale-[1.01] active:scale-[0.99]">
                            <span>ENTRAR NA PLATAFORMA</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform text-orange-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Rodapé e Links para Contratos Legais -->
            <div class="pt-8 mt-6 border-t border-slate-100 text-center space-y-2">
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    Ao continuar, você concorda com os termos do 
                    <a href="{{ route('terms.hosting') }}" target="_blank" class="text-slate-800 hover:text-orange-600 font-bold hover:underline">Contrato de Hospedagem</a> 
                    e do 
                    <a href="{{ route('terms.vps') }}" target="_blank" class="text-slate-800 hover:text-orange-600 font-bold hover:underline">Contrato de VPS</a>.
                </p>
                <p class="text-[11px] text-slate-400">
                    HostDevPro Cloud &copy; {{ date('Y') }} &bull; Todos os direitos reservados.
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
