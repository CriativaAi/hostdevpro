<x-guest-layout>
    <div class="max-w-4xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-[#B99470]/30 grid grid-cols-1 md:grid-cols-12 my-4">
        
        <!-- Painel Esquerdo: Identidade Visual e Primeiro Acesso -->
        <div class="md:col-span-5 bg-gradient-to-br from-[#5F6F52] via-[#48563e] to-[#783D19] p-8 md:p-10 flex flex-col justify-between text-white relative overflow-hidden">
            <!-- Efeito decorativo sutil de fundo -->
            <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-white/5 blur-2xl pointer-events-none"></div>
            <div class="absolute -left-16 -bottom-16 w-56 h-56 rounded-full bg-[#C4661F]/20 blur-3xl pointer-events-none"></div>

            <!-- Topo: Logo Oficial Branco para Fundos Escuros -->
            <div class="relative z-10">
                <a href="/" class="inline-block transition-transform hover:scale-105 duration-200">
                    <img src="{{ asset('brand/logos/dark/HostDevPro-horizontal-white.webp') }}" 
                         alt="HostDevPro" 
                         class="h-9 md:h-10 w-auto drop-shadow-md">
                </a>
            </div>

            <!-- Centro: Mensagem de Boas-Vindas -->
            <div class="my-10 md:my-auto relative z-10">
                <span class="inline-block px-3 py-1 rounded-full bg-white/10 text-white/90 text-xs font-semibold tracking-wider uppercase mb-4 border border-white/15">
                    Infraestrutura Cloud & DevOps
                </span>
                <h2 class="text-2xl md:text-3xl font-bold tracking-tight text-white">
                    Olá, bem-vindo!
                </h2>
                <p class="text-white/80 text-sm mt-3 leading-relaxed">
                    Acesse seu painel para gerenciar serviços, projetos, instâncias VPS e suporte. Se ainda não tem acesso, faça seu primeiro cadastro.
                </p>

                <div class="mt-8">
                    <a href="{{ route('register') }}" 
                       class="inline-flex items-center justify-center w-full sm:w-auto px-6 py-2.5 rounded-xl border-2 border-white text-white font-bold text-xs tracking-wider uppercase hover:bg-white hover:text-[#5F6F52] transition-all duration-200 shadow-sm">
                        PRIMEIRO ACESSO
                    </a>
                </div>
            </div>

            <!-- Rodapé do Painel Esquerdo -->
            <div class="relative z-10 pt-4 border-t border-white/10 flex items-center gap-2 text-xs text-white/70">
                <span class="w-2 h-2 rounded-full bg-[#A9B388] animate-pulse"></span>
                <span>Datacenter Brasil • Alta Conectividade</span>
            </div>
        </div>

        <!-- Painel Direito: Formulário de Autenticação -->
        <div class="md:col-span-7 p-8 md:p-12 flex flex-col justify-between bg-white">
            <div>
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-extrabold text-[#783D19] tracking-tight">
                            Portal do Cliente
                        </h1>
                        <p class="text-xs md:text-sm text-[#5F6F52] font-medium mt-1">
                            Use seu e-mail e senha para continuar
                        </p>
                    </div>
                    <img src="{{ asset('brand/icons/HDP-icon-64.webp') }}" alt="HDP" class="h-10 w-10 opacity-90 hidden sm:block">
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-1.5">
                            E-mail
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username" 
                               placeholder="seunome@email.com"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 text-gray-900 bg-gray-50/50 focus:bg-white focus:border-[#C4661F] focus:ring-2 focus:ring-[#C4661F]/20 text-sm transition outline-none shadow-sm @error('email') border-red-500 @enderror">
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- Password with Eye Toggle ("Olhinho") -->
                    <div x-data="{ showPassword: false }">
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider">
                                Senha
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs text-[#C4661F] hover:text-[#783D19] font-medium hover:underline transition">
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
                                   class="w-full px-4 py-3 pe-11 rounded-xl border border-gray-200 text-gray-900 bg-gray-50/50 focus:bg-white focus:border-[#C4661F] focus:ring-2 focus:ring-[#C4661F]/20 text-sm transition outline-none shadow-sm @error('password') border-red-500 @enderror">
                            <button type="button" 
                                    @click="showPassword = !showPassword" 
                                    class="absolute inset-y-0 right-0 pe-3.5 flex items-center text-gray-400 hover:text-[#C4661F] transition focus:outline-none"
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
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-1">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" 
                                   type="checkbox" 
                                   name="remember" 
                                   class="rounded border-gray-300 text-[#5F6F52] shadow-sm focus:ring-[#C4661F]">
                            <span class="ms-2 text-xs text-gray-600 select-none">Lembrar neste dispositivo</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full py-3.5 px-6 rounded-xl bg-[#5F6F52] hover:bg-[#48563e] text-white font-bold text-xs md:text-sm tracking-wider uppercase shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2 group">
                            <span>ENTRAR</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Rodapé e Links para Contratos Legais -->
            <div class="pt-8 mt-6 border-t border-gray-100 text-center space-y-2">
                <p class="text-[11px] text-gray-500 leading-relaxed">
                    Ao continuar, você concorda com os termos do 
                    <a href="{{ route('terms.hosting') }}" target="_blank" class="text-[#C4661F] font-semibold hover:underline">Contrato de Hospedagem</a> 
                    e do 
                    <a href="{{ route('terms.vps') }}" target="_blank" class="text-[#C4661F] font-semibold hover:underline">Contrato de VPS</a>.
                </p>
                <p class="text-[11px] text-gray-400">
                    HostDevPro &copy; {{ date('Y') }} • Todos os direitos reservados.
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
