<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-white tracking-tight leading-tight flex items-center gap-2.5">
                    <span>Gestão de Projetos & Aplicações</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 font-bold">
                        {{ number_format($metrics['total']) }} Projetos
                    </span>
                </h2>
                <p class="text-xs text-slate-400 mt-1">Acompanhe o ciclo de vida, repositórios e ambientes de deploy das aplicações na nuvem.</p>
            </div>
            <div>
                <a href="{{ route('projects.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs uppercase tracking-wider transition shadow-lg shadow-emerald-500/20 gap-2 transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Novo Projeto</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Message -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-950/40 border border-emerald-500/40 text-emerald-300 text-xs flex items-center justify-between shadow-xl backdrop-blur-xl" role="alert">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 bg-emerald-500/20 rounded-lg p-1.5 text-emerald-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <p class="text-xs font-semibold">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- KPI Metric Cards (Dark Frosted Glass, rounded-2xl) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Total -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl flex items-center justify-between min-w-0 overflow-hidden transition">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 truncate">Total de Projetos</p>
                        <p class="text-2xl sm:text-3xl font-black text-white mt-1.5 tracking-tight">{{ number_format($metrics['total']) }}</p>
                    </div>
                    <div class="h-11 w-11 rounded-xl bg-purple-500/10 border border-purple-500/30 text-purple-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </div>

                <!-- Produção -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl flex items-center justify-between min-w-0 overflow-hidden transition">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-emerald-400 truncate">Em Produção</p>
                        <p class="text-2xl sm:text-3xl font-black text-emerald-400 mt-1.5 tracking-tight">{{ number_format($metrics['production']) }}</p>
                    </div>
                    <div class="h-11 w-11 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Desenvolvimento -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl flex items-center justify-between min-w-0 overflow-hidden transition">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-cyan-400 truncate">Em Desenvolvimento</p>
                        <p class="text-2xl sm:text-3xl font-black text-cyan-400 mt-1.5 tracking-tight">{{ number_format($metrics['development'] ?? 0) }}</p>
                    </div>
                    <div class="h-11 w-11 rounded-xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                    </div>
                </div>

                <!-- Homologação / Manutenção -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl flex items-center justify-between min-w-0 overflow-hidden transition">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-amber-400 truncate">Staging / Testes</p>
                        <p class="text-2xl sm:text-3xl font-black text-amber-400 mt-1.5 tracking-tight">{{ number_format(($metrics['staging'] ?? 0) + ($metrics['maintenance'] ?? 0)) }}</p>
                    </div>
                    <div class="h-11 w-11 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Barra de Filtros e Busca (Dark Frosted Glass, rounded-2xl) -->
            <div class="p-6 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-xl">
                <form action="{{ route('projects.index') }}" method="GET" class="flex flex-col lg:flex-row gap-3 items-center justify-between">
                    <div class="flex-1 w-full relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por projeto, cliente, stack ou URLs..." class="block w-full pl-10 pr-4 py-2.5 bg-black/40 border border-white/10 rounded-xl text-xs text-white placeholder-slate-500 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none">
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto">
                        <!-- Filtro por Cliente -->
                        <select name="client_id" class="block py-2.5 px-3 bg-black/40 border border-white/10 rounded-xl text-xs text-slate-200 focus:border-emerald-500 outline-none transition">
                            <option value="" class="bg-slate-900 text-slate-300">Todos os Clientes</option>
                            @foreach($clients as $clientOpt)
                                <option value="{{ $clientOpt->id }}" {{ (string)$clientId === (string)$clientOpt->id ? 'selected' : '' }} class="bg-slate-900 text-white">
                                    {{ $clientOpt->name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Filtro por Status -->
                        <select name="status" class="block py-2.5 px-3 bg-black/40 border border-white/10 rounded-xl text-xs text-slate-200 focus:border-emerald-500 outline-none transition">
                            <option value="" class="bg-slate-900 text-slate-300">Todos os Status</option>
                            <option value="planning" {{ $status === 'planning' ? 'selected' : '' }} class="bg-slate-900 text-slate-300">Planejamento</option>
                            <option value="development" {{ $status === 'development' ? 'selected' : '' }} class="bg-slate-900 text-cyan-400">Desenvolvimento</option>
                            <option value="staging" {{ $status === 'staging' ? 'selected' : '' }} class="bg-slate-900 text-amber-400">Homologação</option>
                            <option value="production" {{ $status === 'production' ? 'selected' : '' }} class="bg-slate-900 text-emerald-400">Produção</option>
                            <option value="maintenance" {{ $status === 'maintenance' ? 'selected' : '' }} class="bg-slate-900 text-rose-400">Manutenção</option>
                        </select>

                        <!-- Filtro por Tipo -->
                        <select name="type" class="block py-2.5 px-3 bg-black/40 border border-white/10 rounded-xl text-xs text-slate-200 focus:border-emerald-500 outline-none transition">
                            <option value="" class="bg-slate-900 text-slate-300">Todos os Tipos</option>
                            <option value="saas" {{ $type === 'saas' ? 'selected' : '' }} class="bg-slate-900 text-white">SaaS / Web App</option>
                            <option value="website" {{ $type === 'website' ? 'selected' : '' }} class="bg-slate-900 text-white">Website</option>
                            <option value="ecommerce" {{ $type === 'ecommerce' ? 'selected' : '' }} class="bg-slate-900 text-white">E-commerce</option>
                            <option value="api" {{ $type === 'api' ? 'selected' : '' }} class="bg-slate-900 text-white">API</option>
                            <option value="landing_page" {{ $type === 'landing_page' ? 'selected' : '' }} class="bg-slate-900 text-white">Landing Page</option>
                            <option value="mobile_app" {{ $type === 'mobile_app' ? 'selected' : '' }} class="bg-slate-900 text-white">Mobile</option>
                        </select>

                        <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-sm whitespace-nowrap">
                            Filtrar
                        </button>

                        @if($search || $status || $type || $clientId)
                            <a href="{{ route('projects.index') }}" class="inline-flex items-center px-3 py-2 text-xs font-semibold rounded-xl text-slate-400 hover:text-white transition whitespace-nowrap" title="Limpar filtros">
                                Limpar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabela de Projetos (Dark Frosted Glass, rounded-2xl) -->
            <div class="bg-white/[0.06] backdrop-blur-2xl rounded-2xl border border-white/15 shadow-2xl overflow-hidden">
                @if($projects->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-white/10 bg-white/[0.03] text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                    <th scope="col" class="py-4 px-6">Projeto & Tipo</th>
                                    <th scope="col" class="py-4 px-6">Cliente</th>
                                    <th scope="col" class="py-4 px-6">Stack & Repositório</th>
                                    <th scope="col" class="py-4 px-6">Status</th>
                                    <th scope="col" class="py-4 px-6">Cadastro</th>
                                    <th scope="col" class="py-4 px-6 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 text-sm">
                                @foreach($projects as $proj)
                                    <tr class="hover:bg-white/[0.04] transition">
                                        <!-- Projeto / Tipo -->
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3.5">
                                                <div class="h-10 w-10 rounded-xl bg-purple-500/10 border border-purple-500/30 text-purple-400 flex items-center justify-center font-black text-xs uppercase flex-shrink-0">
                                                    {{ mb_substr($proj->type, 0, 3) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <a href="{{ route('projects.show', $proj) }}" class="font-bold text-white hover:text-emerald-400 transition block truncate">
                                                        {{ $proj->name }}
                                                    </a>
                                                    <span class="text-xs text-slate-400 block truncate">
                                                        {{ $proj->type_label }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Cliente -->
                                        <td class="py-4 px-6 text-xs text-slate-300">
                                            <a href="{{ route('clients.show', $proj->client) }}" class="font-semibold text-white hover:text-emerald-400 hover:underline block truncate">
                                                {{ $proj->client->name }}
                                            </a>
                                            <span class="text-slate-400 block truncate">{{ $proj->client->company ?? 'Pessoa Física' }}</span>
                                        </td>

                                        <!-- Stack & Repo -->
                                        <td class="py-4 px-6 text-xs text-slate-300">
                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                @if($proj->stack)
                                                    <span class="font-mono text-emerald-400 font-bold">{{ $proj->stack }}</span>
                                                @else
                                                    <span class="text-slate-500">Stack padrão</span>
                                                @endif
                                            </div>
                                            @if($proj->repository_url)
                                                <a href="{{ $proj->repository_url }}" target="_blank" rel="noopener noreferrer" class="text-[11px] text-cyan-400 hover:underline flex items-center gap-1 mt-0.5 font-mono">
                                                    <span>Repo &nearr;</span>
                                                </a>
                                            @endif
                                        </td>

                                        <!-- Status -->
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border {{ $proj->status_badge_classes }}">
                                                @if($proj->status === 'production')
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                                @endif
                                                {{ $proj->status_label }}
                                            </span>
                                        </td>

                                        <!-- Data -->
                                        <td class="py-4 px-6 text-xs text-slate-400">
                                            {{ $proj->created_at->format('d/m/Y') }}
                                        </td>

                                        <!-- Ações -->
                                        <td class="py-4 px-6 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('projects.show', $proj) }}" class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/[0.08] transition" title="Ver Detalhes">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </a>
                                                <a href="{{ route('projects.edit', $proj) }}" class="p-2 rounded-lg text-slate-400 hover:text-amber-400 hover:bg-amber-500/10 transition" title="Editar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($projects->hasPages())
                        <div class="p-6 border-t border-white/10 bg-white/[0.02]">
                            {{ $projects->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-purple-500/10 border border-purple-500/30 text-purple-400 mx-auto flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-base font-bold text-white">Nenhum projeto cadastrado</h3>
                        <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                            Cadastre aplicações e associe repositórios e clientes ao painel HostDevPro.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-400 text-slate-950 font-black text-xs uppercase tracking-wider rounded-xl shadow-lg transition">
                                Novo Projeto
                            </a>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
