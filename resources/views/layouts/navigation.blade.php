<nav x-data="{ open: false }" class="bg-[#020617]/95 backdrop-blur-md border-b border-slate-800/80 sticky top-0 z-40 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="focus:outline-none">
                        <x-application-logo variant="dark-white" class="block h-8 w-auto hover:opacity-90 transition-opacity" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-8 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('clients.index')" :active="request()->routeIs('clients.*')">
                        {{ __('Clientes') }}
                    </x-nav-link>
                    <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')">
                        {{ __('Projetos') }}
                    </x-nav-link>
                    <x-nav-link :href="route('servers.index')" :active="request()->routeIs('servers.*')">
                        {{ __('Servidores') }}
                    </x-nav-link>
                    <x-nav-link :href="route('hosting.index')" :active="request()->routeIs('hosting.*')">
                        {{ __('Hospedagens') }}
                    </x-nav-link>
                    <x-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.*')">
                        {{ __('Suporte') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Botão de Acesso Rápido ao Webmail (Verde Neon Moderno) -->
            <div class="hidden sm:flex sm:items-center sm:ms-auto gap-3">
                <a href="https://webmail.hostdevpro.app.br" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-emerald-500/30 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 hover:text-emerald-300 text-xs font-bold transition shadow-[0_0_12px_rgba(16,185,129,0.15)] group" 
                   title="Acessar Webmail Roundcube HostDevPro">
                    <svg class="w-3.5 h-3.5 text-emerald-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Webmail</span>
                    <svg class="w-3 h-3 text-emerald-500/70 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-4">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-slate-800 text-xs font-semibold rounded-xl text-slate-200 bg-slate-900/90 hover:text-white hover:bg-slate-800 hover:border-slate-700 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span>{{ Auth::user()->name }}</span>
                            </div>

                            <div class="ms-2">
                                <svg class="fill-current h-3.5 w-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Meu Perfil') }}
                        </x-dropdown-link>

                        <x-dropdown-link href="https://webmail.hostdevpro.app.br" target="_blank" class="text-emerald-400 hover:text-emerald-300">
                            ✉️ {{ __('Acessar Webmail') }}
                        </x-dropdown-link>

                        <!-- Links para Termos & Contratos -->
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
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="text-red-400 hover:text-red-300">
                                {{ __('Sair da Conta') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-900 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-950/95 border-b border-slate-800/80 backdrop-blur-md">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('clients.index')" :active="request()->routeIs('clients.*')">
                {{ __('Clientes') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')">
                {{ __('Projetos') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('servers.index')" :active="request()->routeIs('servers.*')">
                {{ __('Servidores') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('hosting.index')" :active="request()->routeIs('hosting.*')">
                {{ __('Hospedagens') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.*')">
                {{ __('Suporte') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-2 border-t border-slate-800">
            <div class="px-4">
                <div class="font-bold text-base text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>{{ Auth::user()->name }}</span>
                </div>
                <div class="font-mono text-xs text-slate-400 mt-0.5">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Meu Perfil') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="https://webmail.hostdevpro.app.br" target="_blank" class="text-emerald-400 font-bold">
                    ✉️ {{ __('Acessar Webmail') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('terms.vps')" target="_blank">
                    {{ __('Contrato de VPS') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('terms.hosting')" target="_blank">
                    {{ __('Contrato de Hospedagem') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="text-red-400">
                        {{ __('Sair da Conta') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
