<x-guest-layout>
    <div class="max-w-4xl w-full bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl shadow-slate-300/70 overflow-hidden border border-slate-200/90 grid grid-cols-1 md:grid-cols-12 my-6">
        
        <!-- Painel Esquerdo: Brand & Boas-Vindas Institucional -->
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

            <!-- Centro: Mensagem de Boas-Vindas -->
            <div class="my-6 md:my-auto relative z-10 space-y-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-100 text-orange-800 text-xs font-bold font-mono uppercase tracking-wider border border-orange-200 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                    Ativação Imediata &bull; SSL Incluso
                </span>
                
                <h2 class="text-2xl sm:text-3xl font-display font-black text-slate-900 tracking-tight leading-tight">
                    Crie sua conta
                </h2>
                
                <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                    Comece a hospedar suas aplicações, gerenciar servidores dedicados e usufruir da ultra-performance dos discos NVMe Gen5 no Brasil.
                </p>

                <!-- Destaques -->
                <div class="space-y-2 pt-2 text-xs font-medium text-slate-700">
                    <div class="flex items-center gap-2 p-2 rounded-xl bg-white border border-slate-200/80 shadow-sm">
                        <span class="text-base">🚀</span>
                        <span>Setup Instantâneo de Instâncias</span>
                    </div>
                    <div class="flex items-center gap-2 p-2 rounded-xl bg-white border border-slate-200/80 shadow-sm">
                        <span class="text-base">🛡️</span>
                        <span>Proteção Anti-DDoS 2.4 Tbps</span>
                    </div>
                </div>

                <!-- Botão Já Tenho Conta -->
                <div class="pt-4">
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center justify-center w-full px-6 py-3.5 rounded-xl border-2 border-slate-950 text-slate-950 hover:bg-slate-950 hover:text-white font-black text-xs tracking-wider uppercase transition-all duration-200 shadow-sm">
                        JÁ TENHO CONTA (ENTRAR) &rarr;
                    </a>
                </div>
            </div>

            <!-- Rodapé do Painel Esquerdo -->
            <div class="relative z-10 pt-4 border-t border-slate-200/80 flex items-center justify-between text-[11px] text-slate-500 font-mono">
                <span class="flex items-center gap-1.5 text-emerald-700 font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Setup Automático
                </span>
                <span>Criptografia 256-bit</span>
            </div>
        </div>

        <!-- Painel Direito: Formulário de Cadastro -->
        <div class="md:col-span-7 p-8 md:p-12 flex flex-col justify-between bg-white relative">
            <div>
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-display font-black text-slate-900 tracking-tight">
                            Primeiro Acesso
                        </h1>
                        <p class="text-xs md:text-sm text-slate-500 font-medium mt-1">
                            Preencha seus dados para criar seu acesso imediato.
                        </p>
                    </div>
                    <img src="{{ asset('brand/icons/HDP-icon-64.png') }}" alt="HDP" class="h-10 w-10 object-contain drop-shadow-sm hidden sm:block">
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1.5">
                            Nome Completo
                        </label>
                        <input id="name" 
                               type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus 
                               autocomplete="name" 
                               placeholder="Seu nome ou Razão Social"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-900 bg-slate-50/50 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 text-sm font-medium transition outline-none shadow-sm @error('name') border-rose-500 @enderror">
                        <x-input-error :messages="$errors->get('name')" class="mt-1 text-rose-600 text-xs font-semibold" />
                    </div>

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
                               autocomplete="username" 
                               placeholder="seunome@empresa.com.br"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-900 bg-slate-50/50 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 text-sm font-medium transition outline-none shadow-sm @error('email') border-rose-500 @enderror">
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-rose-600 text-xs font-semibold" />
                    </div>

                    <!-- Password with Eye Toggle -->
                    <div x-data="{ showPass: false }">
                        <label for="password" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1.5">
                            Senha de Acesso
                        </label>
                        <div class="relative">
                            <input id="password" 
                                   :type="showPass ? 'text' : 'password'" 
                                   name="password" 
                                   required 
                                   autocomplete="new-password" 
                                   placeholder="Mínimo de 8 caracteres"
                                   class="w-full px-4 py-2.5 pe-11 rounded-xl border border-slate-300 text-slate-900 bg-slate-50/50 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 text-sm font-medium transition outline-none shadow-sm @error('password') border-rose-500 @enderror">
                            <button type="button" 
                                    @click="showPass = !showPass" 
                                    class="absolute inset-y-0 right-0 pe-3.5 flex items-center text-slate-400 hover:text-slate-800 transition focus:outline-none"
                                    :title="showPass ? 'Ocultar senha' : 'Ver senha'">
                                <svg x-show="!showPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPass" style="display: none;" class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-rose-600 text-xs font-semibold" />
                    </div>

                    <!-- Confirm Password with Eye Toggle -->
                    <div x-data="{ showConfirmPass: false }">
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1.5">
                            Confirmar Senha
                        </label>
                        <div class="relative">
                            <input id="password_confirmation" 
                                   :type="showConfirmPass ? 'text' : 'password'" 
                                   name="password_confirmation" 
                                   required 
                                   autocomplete="new-password" 
                                   placeholder="Repita sua senha"
                                   class="w-full px-4 py-2.5 pe-11 rounded-xl border border-slate-300 text-slate-900 bg-slate-50/50 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 text-sm font-medium transition outline-none shadow-sm @error('password_confirmation') border-rose-500 @enderror">
                            <button type="button" 
                                    @click="showConfirmPass = !showConfirmPass" 
                                    class="absolute inset-y-0 right-0 pe-3.5 flex items-center text-slate-400 hover:text-slate-800 transition focus:outline-none"
                                    :title="showConfirmPass ? 'Ocultar senha' : 'Ver senha'">
                                <svg x-show="!showConfirmPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showConfirmPass" style="display: none;" class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-rose-600 text-xs font-semibold" />
                    </div>

                    <!-- Termos de Aceite -->
                    <div class="pt-2">
                        <label class="flex items-start cursor-pointer text-xs text-slate-700 leading-relaxed">
                            <input type="checkbox" 
                                   name="terms" 
                                   required 
                                   class="mt-0.5 rounded border-slate-300 text-orange-500 shadow-sm focus:ring-orange-500">
                            <span class="ms-2">
                                Li e concordo com o 
                                <a href="{{ route('terms.hosting') }}" target="_blank" class="text-orange-600 font-bold hover:underline">Contrato de Hospedagem</a> 
                                e com o 
                                <a href="{{ route('terms.vps') }}" target="_blank" class="text-orange-600 font-bold hover:underline">Contrato de VPS</a>.
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full py-4 px-6 rounded-xl bg-slate-950 hover:bg-emerald-600 text-white font-black text-xs md:text-sm tracking-wider uppercase shadow-xl shadow-slate-950/20 hover:shadow-emerald-600/30 transition-all duration-200 flex items-center justify-center gap-2 group hover:scale-[1.01] active:scale-[0.99]">
                            <span>FINALIZAR CADASTRO</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform text-orange-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Rodapé -->
            <div class="pt-6 mt-4 border-t border-slate-100 text-center">
                <p class="text-[11px] text-slate-400">
                    HostDevPro Cloud &copy; {{ date('Y') }} &bull; Gestão Avançada de Servidores & Aplicações
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
