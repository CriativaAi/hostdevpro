<x-guest-layout>
    <div class="max-w-4xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-[#B99470]/30 grid grid-cols-1 md:grid-cols-12 my-4">
        
        <!-- Painel Esquerdo: Identidade Visual -->
        <div class="md:col-span-5 bg-gradient-to-br from-[#5F6F52] via-[#48563e] to-[#783D19] p-8 md:p-10 flex flex-col justify-between text-white relative overflow-hidden">
            <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-white/5 blur-2xl pointer-events-none"></div>
            <div class="absolute -left-16 -bottom-16 w-56 h-56 rounded-full bg-[#C4661F]/20 blur-3xl pointer-events-none"></div>

            <!-- Topo: Logo Oficial Dark -->
            <div class="relative z-10">
                <a href="/" class="inline-block transition-transform hover:scale-105 duration-200">
                    <img src="{{ asset('brand/logos/dark/HostDevPro-horizontal-gradient.webp') }}" 
                         alt="HostDevPro" 
                         class="h-8 md:h-9 w-auto drop-shadow-md">
                </a>
            </div>

            <!-- Centro -->
            <div class="my-10 md:my-auto relative z-10">
                <span class="inline-block px-3 py-1 rounded-full bg-white/10 text-white/90 text-xs font-semibold tracking-wider uppercase mb-4 border border-white/15">
                    Segurança da Conta
                </span>
                <h2 class="text-2xl md:text-3xl font-bold tracking-tight text-white">
                    Recuperar Senha
                </h2>
                <p class="text-white/80 text-sm mt-3 leading-relaxed">
                    Esqueceu sua senha? Não se preocupe. Informe seu e-mail cadastrado e enviaremos um link seguro para você redefinir sua senha de acesso.
                </p>

                <div class="mt-8">
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center justify-center w-full sm:w-auto px-6 py-2.5 rounded-xl border-2 border-white text-white font-bold text-xs tracking-wider uppercase hover:bg-white hover:text-[#5F6F52] transition-all duration-200 shadow-sm">
                        VOLTAR AO LOGIN
                    </a>
                </div>
            </div>

            <!-- Rodapé -->
            <div class="relative z-10 pt-4 border-t border-white/10 flex items-center gap-2 text-xs text-white/70">
                <span class="w-2 h-2 rounded-full bg-[#A9B388] animate-pulse"></span>
                <span>Link com expiração de segurança</span>
            </div>
        </div>

        <!-- Painel Direito: Formulário -->
        <div class="md:col-span-7 p-8 md:p-12 flex flex-col justify-between bg-white">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-extrabold text-[#783D19] tracking-tight">
                            Redefinição de Acesso
                        </h1>
                        <p class="text-xs md:text-sm text-[#5F6F52] font-medium mt-1">
                            Informe seu e-mail de cadastro
                        </p>
                    </div>
                    <img src="{{ asset('brand/icons/HDP-icon-64.webp') }}" alt="HDP" class="h-10 w-10 opacity-90 hidden sm:block">
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-1.5">
                            E-mail de Cadastro
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               placeholder="seunome@empresa.com.br"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 text-gray-900 bg-gray-50/50 focus:bg-white focus:border-[#C4661F] focus:ring-2 focus:ring-[#C4661F]/20 text-sm transition outline-none shadow-sm @error('email') border-red-500 @enderror">
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full py-3.5 px-6 rounded-xl bg-[#5F6F52] hover:bg-[#48563e] text-white font-bold text-xs md:text-sm tracking-wider uppercase shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2 group">
                            <span>ENVIAR LINK DE REDEFINIÇÃO</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Rodapé -->
            <div class="pt-8 mt-6 border-t border-gray-100 text-center">
                <p class="text-[11px] text-gray-400">
                    HostDevPro &copy; {{ date('Y') }} • Central de Segurança
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
