<x-guest-layout>
    <div class="max-w-4xl w-full bg-white rounded-3xl shadow-2xl shadow-slate-200/70 overflow-hidden border border-slate-200/90 grid grid-cols-1 md:grid-cols-12 my-4">
        
        <!-- Painel Esquerdo: Identidade Visual Tecnológica -->
        <div class="md:col-span-5 bg-gradient-to-br from-slate-950 via-slate-900 to-[#081219] p-8 md:p-10 flex flex-col justify-between text-white relative overflow-hidden">
            <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-emerald-500/15 blur-2xl pointer-events-none"></div>
            <div class="absolute -left-16 -bottom-16 w-56 h-56 rounded-full bg-rose-500/15 blur-3xl pointer-events-none"></div>

            <div class="relative z-10">
                <a href="https://hostdevpro.app.br" class="inline-block transition-transform hover:scale-105 duration-200">
                    <img src="{{ asset('brand/logos/dark/HostDevPro-horizontal-white.webp') }}" 
                         alt="HostDevPro Cloud" 
                         class="h-9 md:h-10 w-auto drop-shadow-md">
                </a>
            </div>

            <div class="my-10 md:my-auto relative z-10">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-rose-300 text-[11px] font-semibold tracking-wider uppercase mb-4 border border-white/15">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-400 animate-pulse"></span>
                    Segurança da Conta
                </span>
                <h2 class="text-2xl md:text-3xl font-display font-black tracking-tight text-white leading-tight">
                    Recuperar Senha
                </h2>
                <p class="text-slate-300 text-xs md:text-sm mt-3 leading-relaxed">
                    Esqueceu sua senha? Informe seu e-mail cadastrado e enviaremos um link seguro para você redefinir sua credencial.
                </p>

                <div class="mt-8">
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center justify-center w-full sm:w-auto px-6 py-2.5 rounded-xl border border-emerald-400/80 text-emerald-300 hover:bg-emerald-500 hover:text-slate-950 font-bold text-xs tracking-wider uppercase transition-all duration-200 shadow-sm shadow-emerald-500/20">
                        VOLTAR AO LOGIN
                    </a>
                </div>
            </div>

            <div class="relative z-10 pt-4 border-t border-white/10 flex items-center gap-2 text-[11px] text-slate-300 font-mono">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Link com Token Exclusivo e Seguro</span>
            </div>
        </div>

        <!-- Painel Direito: Formulário -->
        <div class="md:col-span-7 p-8 md:p-12 flex flex-col justify-between bg-white">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-display font-black text-slate-900 tracking-tight">
                            Redefinição de Senha
                        </h1>
                        <p class="text-xs md:text-sm text-slate-500 font-medium mt-1">
                            Digite o e-mail associado à sua conta HostDevPro.
                        </p>
                    </div>
                    <img src="{{ asset('brand/icons/HDP-icon-64.webp') }}" alt="HDP" class="h-10 w-10 opacity-90 hidden sm:block">
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            E-mail Cadastrado
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username" 
                               placeholder="seunome@empresa.com.br"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-900 bg-slate-50/60 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition outline-none shadow-sm @error('email') border-rose-500 @enderror">
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-rose-500 text-xs" />
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full py-3.5 px-6 rounded-xl bg-slate-900 hover:bg-emerald-600 text-white font-bold text-xs md:text-sm tracking-wider uppercase shadow-lg shadow-slate-900/10 hover:shadow-emerald-600/25 transition-all duration-200 flex items-center justify-center gap-2 group">
                            <span>ENVIAR LINK DE RECUPERAÇÃO</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform text-emerald-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <div class="pt-8 mt-6 border-t border-slate-100 text-center">
                <p class="text-[11px] text-slate-400">
                    HostDevPro Cloud &copy; {{ date('Y') }} &bull; Proteção de Identidade e Acesso Seguro
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
