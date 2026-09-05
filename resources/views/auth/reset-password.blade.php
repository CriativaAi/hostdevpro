<x-guest-layout>
    <div class="max-w-4xl w-full bg-white rounded-3xl shadow-2xl shadow-slate-200/70 overflow-hidden border border-slate-200/90 grid grid-cols-1 md:grid-cols-12 my-4">
        
        <!-- Painel Esquerdo: Brand & Boas-Vindas Institucional -->
        <div class="md:col-span-5 bg-gradient-to-br from-slate-950 via-slate-900 to-[#081219] p-8 md:p-10 flex flex-col justify-between relative overflow-hidden text-white">
            <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-emerald-500/15 blur-2xl pointer-events-none"></div>
            <div class="absolute -left-16 -bottom-16 w-56 h-56 rounded-full bg-rose-500/15 blur-3xl pointer-events-none"></div>

            <div class="relative z-10">
                <a href="https://hostdevpro.app.br" class="inline-block transition-transform hover:scale-105 duration-200">
                    <img src="{{ asset('brand/logos/dark/HostDevPro-horizontal-white.webp') }}" 
                         alt="HostDevPro Cloud" 
                         class="h-9 md:h-10 w-auto drop-shadow-md">
                </a>
            </div>

            <div class="my-8 md:my-auto relative z-10">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-emerald-300 text-[11px] font-semibold tracking-wider uppercase mb-4 border border-white/15">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Segurança & Credenciais
                </span>
                <h2 class="text-2xl md:text-3xl font-display font-black tracking-tight text-white leading-tight">
                    Nova Senha
                </h2>
                <p class="text-slate-300 text-xs md:text-sm mt-3 leading-relaxed">
                    Defina uma nova senha forte com pelo menos 8 caracteres para restabelecer seu acesso seguro.
                </p>
            </div>

            <div class="relative z-10 pt-4 border-t border-white/10 flex items-center gap-2 text-[11px] text-slate-300 font-mono">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Criptografia Ponta a Ponta</span>
            </div>
        </div>

        <!-- Painel Direito: Formulário de Redefinição -->
        <div class="md:col-span-7 p-8 md:p-12 flex flex-col justify-between bg-white">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-display font-black text-slate-900 tracking-tight">
                            Redefinir Senha
                        </h1>
                        <p class="text-xs md:text-sm text-slate-500 font-medium mt-1">
                            Escolha sua nova senha de acesso à plataforma.
                        </p>
                    </div>
                    <img src="{{ asset('brand/icons/HDP-icon-64.webp') }}" alt="HDP" class="h-10 w-10 opacity-90 hidden sm:block">
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            E-mail
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email', $request->email) }}" 
                               required 
                               autofocus 
                               autocomplete="username" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-900 bg-slate-50/60 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition outline-none shadow-sm @error('email') border-rose-500 @enderror">
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-rose-500 text-xs" />
                    </div>

                    <!-- Password -->
                    <div x-data="{ showPass: false }">
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nova Senha
                        </label>
                        <div class="relative">
                            <input id="password" 
                                   :type="showPass ? 'text' : 'password'" 
                                   name="password" 
                                   required 
                                   autocomplete="new-password" 
                                   placeholder="Mínimo de 8 caracteres"
                                   class="w-full px-4 py-2.5 pe-11 rounded-xl border border-slate-200 text-slate-900 bg-slate-50/60 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition outline-none shadow-sm @error('password') border-rose-500 @enderror">
                            <button type="button" 
                                    @click="showPass = !showPass" 
                                    class="absolute inset-y-0 right-0 pe-3.5 flex items-center text-slate-400 hover:text-slate-700 transition focus:outline-none"
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
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-rose-500 text-xs" />
                    </div>

                    <!-- Confirm Password -->
                    <div x-data="{ showConfirmPass: false }">
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Confirmar Nova Senha
                        </label>
                        <div class="relative">
                            <input id="password_confirmation" 
                                   :type="showConfirmPass ? 'text' : 'password'" 
                                   name="password_confirmation" 
                                   required 
                                   autocomplete="new-password" 
                                   placeholder="Repita sua nova senha"
                                   class="w-full px-4 py-2.5 pe-11 rounded-xl border border-slate-200 text-slate-900 bg-slate-50/60 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition outline-none shadow-sm @error('password_confirmation') border-rose-500 @enderror">
                            <button type="button" 
                                    @click="showConfirmPass = !showConfirmPass" 
                                    class="absolute inset-y-0 right-0 pe-3.5 flex items-center text-slate-400 hover:text-slate-700 transition focus:outline-none"
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
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-rose-500 text-xs" />
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full py-3.5 px-6 rounded-xl bg-slate-900 hover:bg-emerald-600 text-white font-bold text-xs md:text-sm tracking-wider uppercase shadow-lg shadow-slate-900/10 hover:shadow-emerald-600/25 transition-all duration-200 flex items-center justify-center gap-2 group">
                            <span>DEFINIR NOVA SENHA</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform text-emerald-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <div class="pt-6 mt-4 border-t border-slate-100 text-center">
                <p class="text-[11px] text-slate-400">
                    HostDevPro Cloud &copy; {{ date('Y') }} &bull; Gestão Avançada de Servidores & Aplicações
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
