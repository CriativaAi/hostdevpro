<nav x-data="{ open: false, servicesOpen: false, billingOpen: false, supportOpen: false }" class="bg-[#020617]/95 backdrop-blur-md border-b border-slate-800/80 sticky top-0 z-40 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center me-6">
                    <a href="{{ route('dashboard') }}" class="focus:outline-none">
                        <x-application-logo variant="dark-white" class="block h-8 w-auto hover:opacity-90 transition-opacity" />
                    </a>
                </div>

                <!-- Navigation Links & Dropdowns (Estilo ValueHost / WHMCS) -->
                <div class="hidden sm:flex sm:items-center space-x-1 md:space-x-2">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Dropdown Serviços -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                        <button @click="open = !open" 
                                class="inline-flex items-center px-3 py-2 text-xs font-semibold rounded-xl text-slate-300 hover:text-white hover:bg-slate-800/60 focus:outline-none transition {{ request()->routeIs('hosting.*', 'servers.*', 'ai-builder.*', 'projects.*', 'affiliates.*') ? 'text-emerald-400 bg-slate-800/50' : '' }}">
                            <span>Serviços</span>
                            <svg class="ms-1.5 h-3.5 w-3.5 fill-current opacity-70" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" style="display: none;" 
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             class="absolute left-0 mt-2 w-60 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl py-2 z-50">
                            <div class="px-4 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">Hospedagem & Infra</div>
                            <a href="{{ route('hosting.index') }}" class="block px-4 py-2 text-xs text-slate-200 hover:bg-slate-800 hover:text-white transition">
                                🖥️ Meus Serviços
                            </a>
                            <a href="{{ route('hosting.create') }}" class="block px-4 py-2 text-xs text-slate-200 hover:bg-slate-800 hover:text-white transition">
                                ➕ Contratar / Ativar Novo
                            </a>
                            <a href="{{ route('servers.index') }}" class="block px-4 py-2 text-xs text-slate-200 hover:bg-slate-800 hover:text-white transition">
                                🗄️ Servidores & Nós VPS
                            </a>

                            <div class="border-t border-slate-800/80 my-1.5"></div>
                            <div class="px-4 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">Recursos & Soluções</div>

                            <a href="{{ route('ai-builder.index') }}" class="flex items-center justify-between px-4 py-2 text-xs text-purple-300 hover:bg-slate-800 hover:text-purple-200 transition font-medium">
                                <span class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-pulse"></span>
                                    <span>✨ Criador de Sites IA</span>
                                </span>
                                <span class="text-[9px] px-1.5 py-0.5 rounded bg-purple-500/20 text-purple-300 border border-purple-500/30">Gemini</span>
                            </a>
                            <a href="{{ route('projects.index') }}" class="block px-4 py-2 text-xs text-slate-200 hover:bg-slate-800 hover:text-white transition">
                                📂 Projetos & Domínios
                            </a>
                            <a href="{{ route('affiliates.index') }}" class="flex items-center justify-between px-4 py-2 text-xs text-emerald-300 hover:bg-slate-800 hover:text-emerald-200 transition font-medium">
                                <span class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    <span>🤝 Programa de Afiliados</span>
                                </span>
                                <span class="text-[9px] px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">15%</span>
                            </a>
                        </div>
                    </div>

                    <!-- Dropdown Faturamento -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                        <button @click="open = !open" 
                                class="inline-flex items-center px-3 py-2 text-xs font-semibold rounded-xl text-slate-300 hover:text-white hover:bg-slate-800/60 focus:outline-none transition {{ request()->routeIs('invoices.*') ? 'text-emerald-400 bg-slate-800/50' : '' }}">
                            <span>Faturamento</span>
                            <svg class="ms-1.5 h-3.5 w-3.5 fill-current opacity-70" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" style="display: none;" 
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             class="absolute left-0 mt-2 w-52 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl py-2 z-50">
                            <a href="{{ route('invoices.index') }}" class="block px-4 py-2 text-xs text-slate-200 hover:bg-slate-800 hover:text-white transition">
                                📄 Minhas Faturas
                            </a>
                            <a href="{{ route('invoices.index', ['status' => 'unpaid']) }}" class="block px-4 py-2 text-xs text-amber-400 hover:bg-slate-800 transition">
                                ⏳ Faturas Pendentes
                            </a>
                            <a href="{{ route('invoices.index', ['status' => 'paid']) }}" class="block px-4 py-2 text-xs text-emerald-400 hover:bg-slate-800 transition">
                                ✓ Faturas Pagas
                            </a>
                        </div>
                    </div>

                    <!-- Dropdown Suporte -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                        <button @click="open = !open" 
                                class="inline-flex items-center px-3 py-2 text-xs font-semibold rounded-xl text-slate-300 hover:text-white hover:bg-slate-800/60 focus:outline-none transition {{ request()->routeIs('tickets.*') ? 'text-emerald-400 bg-slate-800/50' : '' }}">
                            <span>Suporte</span>
                            <svg class="ms-1.5 h-3.5 w-3.5 fill-current opacity-70" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" style="display: none;" 
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             class="absolute left-0 mt-2 w-48 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl py-2 z-50">
                            <a href="{{ route('tickets.index') }}" class="block px-4 py-2 text-xs text-slate-200 hover:bg-slate-800 hover:text-white transition">
                                💬 Meus Chamados
                            </a>
                            <a href="{{ route('tickets.create') }}" class="block px-4 py-2 text-xs text-slate-200 hover:bg-slate-800 hover:text-white transition">
                                🎧 Abrir Novo Ticket
                            </a>
                        </div>
                    </div>

                    <!-- Clientes (Gestão) -->
                    <x-nav-link :href="route('clients.index')" :active="request()->routeIs('clients.*')">
                        {{ __('Clientes') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Topo Direito: Ícones, Webmail e Perfil -->
            <div class="hidden sm:flex sm:items-center gap-3">
                <!-- Notificações 🔔 com badge -->
                <a href="{{ route('invoices.index', ['status' => 'unpaid']) }}" 
                   class="relative p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 transition" 
                   title="Notificações & Faturas Pendentes">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span class="absolute top-1.5 right-1.5 w-4 h-4 rounded-full bg-rose-600 text-white text-[9px] font-extrabold flex items-center justify-center">
                        2
                    </span>
                </a>

                <!-- Webmail -->
                <a href="https://webmail.hostdevpro.app.br" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-emerald-500/30 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 hover:text-emerald-300 text-xs font-bold transition shadow-[0_0_12px_rgba(16,185,129,0.15)] group" 
                   title="Acessar Webmail Roundcube HostDevPro">
                    <svg class="w-3.5 h-3.5 text-emerald-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Webmail</span>
                </a>

                <!-- Botão Rápido: + Contratar -->
                <a href="{{ route('hosting.create') }}" 
                   class="px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider shadow-sm transition">
                    + Contratar
                </a>

                <!-- Settings Dropdown -->
                <div class="ms-2">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-slate-800 text-xs font-semibold rounded-xl text-slate-200 bg-slate-900/90 hover:text-white hover:bg-slate-800 hover:border-slate-700 focus:outline-none transition shadow-sm">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span>Olá, {{ explode(' ', Auth::user()->name)[0] }} !</span>
                                </div>
                                <svg class="fill-current h-3.5 w-3.5 ms-2 text-slate-400" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('dashboard')">
                                🏠 {{ __('Área do Cliente') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('affiliates.index')">
                                🤝 {{ __('Central de Afiliados (15%)') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('invoices.index')">
                                💳 {{ __('Minhas Faturas') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('profile.edit')">
                                ⚙️ {{ __('Meu Perfil') }}
                            </x-dropdown-link>

                            <x-dropdown-link href="https://webmail.hostdevpro.app.br" target="_blank" class="text-emerald-400 hover:text-emerald-300">
                                ✉️ {{ __('Acessar Webmail') }}
                            </x-dropdown-link>

                            <div class="border-t border-slate-800/80 my-1"></div>
                            <x-dropdown-link :href="route('terms.vps')" target="_blank">
                                {{ __('Contrato de VPS') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('terms.hosting')" target="_blank">
                                {{ __('Contrato de Hospedagem') }}
                            </x-dropdown-link>

                            <div class="border-t border-slate-800/80 my-1"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-red-400 hover:text-red-300">
                                    {{ __('Sair da Conta') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-slate-800/80 bg-[#020617] px-4 pt-2 pb-6 space-y-2">
        <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard / Área do Cliente') }}
        </x-responsive-nav-link>

        <div class="py-1 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Serviços & Ferramentas</div>
        <x-responsive-nav-link :href="route('hosting.index')" :active="request()->routeIs('hosting.*')">
            {{ __('🖥️ Meus Serviços / Hospedagens') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('servers.index')" :active="request()->routeIs('servers.*')">
            {{ __('🗄️ Servidores VPS') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('ai-builder.index')" :active="request()->routeIs('ai-builder.*')">
            <span class="text-purple-400 font-bold">{{ __('✨ Criador de Sites IA (Gemini)') }}</span>
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')">
            {{ __('📂 Meus Projetos') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('affiliates.index')" :active="request()->routeIs('affiliates.*')">
            <span class="text-emerald-400 font-bold">{{ __('🤝 Programa de Afiliados (15%)') }}</span>
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('clients.index')" :active="request()->routeIs('clients.*')">
            {{ __('👥 Gestão de Clientes') }}
        </x-responsive-nav-link>

        <div class="py-1 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 pt-3 border-t border-slate-800/60">Financeiro & Suporte</div>
        <x-responsive-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')">
            {{ __('📄 Minhas Faturas') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.*')">
            {{ __('💬 Suporte & Chamados') }}
        </x-responsive-nav-link>

        <div class="pt-4 border-t border-slate-800/80">
            <a href="https://webmail.hostdevpro.app.br" target="_blank" class="flex items-center gap-2 px-3 py-2 text-xs font-bold text-emerald-400">
                ✉️ Acessar Webmail Oficial
            </a>
            <div class="mt-2 text-xs font-medium text-slate-400 px-3">
                {{ Auth::user()->name }} ({{ Auth::user()->email }})
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-400">
                    {{ __('Sair da Conta') }}
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>
