<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('Gestão de Projetos & Aplicações') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Acompanhe o ciclo de vida, repositórios e ambientes de deploy das aplicações.</p>
            </div>
            <div>
                <a href="{{ route('projects.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Novo Projeto
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Message -->
            @if(session('success'))
                <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 shadow-sm flex items-center justify-between" role="alert">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 bg-emerald-100 rounded-lg p-1.5 text-emerald-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- KPI Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total -->
                <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total de Projetos</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($metrics['total']) }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </div>

                <!-- Produção -->
                <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Em Produção</p>
                        <p class="text-2xl font-bold text-emerald-600 mt-1">{{ number_format($metrics['production']) }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                <!-- Desenvolvimento -->
                <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Em Desenvolvimento</p>
                        <p class="text-2xl font-bold text-indigo-600 mt-1">{{ number_format($metrics['development']) }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                    </div>
                </div>

                <!-- Homologação / Manutenção -->
                <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Homologação / Manutenção</p>
                        <p class="text-2xl font-bold text-amber-600 mt-1">{{ number_format($metrics['staging'] + $metrics['maintenance']) }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Barra de Filtros e Busca -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 sm:p-5">
                <form action="{{ route('projects.index') }}" method="GET" class="flex flex-col lg:flex-row gap-3 items-center justify-between">
                    <div class="flex-1 w-full relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por projeto, cliente, stack ou URLs..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto">
                        <!-- Filtro por Cliente -->
                        <select name="client_id" class="block py-2 px-3 border border-gray-300 bg-white rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <option value="">Todos os Clientes</option>
                            @foreach($clients as $clientOpt)
                                <option value="{{ $clientOpt->id }}" {{ (string)$clientId === (string)$clientOpt->id ? 'selected' : '' }}>
                                    {{ $clientOpt->name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Filtro por Status -->
                        <select name="status" class="block py-2 px-3 border border-gray-300 bg-white rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <option value="">Todos os Status</option>
                            <option value="planning" {{ $status === 'planning' ? 'selected' : '' }}>Planejamento</option>
                            <option value="development" {{ $status === 'development' ? 'selected' : '' }}>Desenvolvimento</option>
                            <option value="staging" {{ $status === 'staging' ? 'selected' : '' }}>Homologação</option>
                            <option value="production" {{ $status === 'production' ? 'selected' : '' }}>Produção</option>
                            <option value="maintenance" {{ $status === 'maintenance' ? 'selected' : '' }}>Manutenção</option>
                        </select>

                        <!-- Filtro por Tipo -->
                        <select name="type" class="block py-2 px-3 border border-gray-300 bg-white rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <option value="">Todos os Tipos</option>
                            <option value="saas" {{ $type === 'saas' ? 'selected' : '' }}>SaaS / Web App</option>
                            <option value="website" {{ $type === 'website' ? 'selected' : '' }}>Website</option>
                            <option value="ecommerce" {{ $type === 'ecommerce' ? 'selected' : '' }}>E-commerce</option>
                            <option value="api" {{ $type === 'api' ? 'selected' : '' }}>API</option>
                            <option value="landing_page" {{ $type === 'landing_page' ? 'selected' : '' }}>Landing Page</option>
                            <option value="mobile_app" {{ $type === 'mobile_app' ? 'selected' : '' }}>Mobile</option>
                        </select>

                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl text-white bg-gray-900 hover:bg-gray-800 transition shadow-sm">
                            Filtrar
                        </button>

                        @if($search || $status || $type || $clientId)
                            <a href="{{ route('projects.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-xl text-gray-600 bg-white hover:bg-gray-50 transition" title="Limpar filtros">
                                Limpar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabela de Projetos -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                @if($projects->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-left">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-6 pr-3 text-xs font-bold uppercase tracking-wider text-gray-500">Projeto & Tipo</th>
                                    <th scope="col" class="px-3 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500">Cliente</th>
                                    <th scope="col" class="px-3 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500">Stack & Ambientes</th>
                                    <th scope="col" class="px-3 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500">Status</th>
                                    <th scope="col" class="px-3 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500">Cadastro</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-6 text-right text-xs font-bold uppercase tracking-wider text-gray-500">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach($projects as $proj)
                                    <tr class="hover:bg-slate-50/75 transition">
                                        <!-- Projeto / Tipo -->
                                        <td class="whitespace-nowrap py-4 pl-6 pr-3">
                                            <div class="flex items-center gap-3">
                                                <div class="h-10 w-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs uppercase">
                                                    {{ mb_substr($proj->type, 0, 3) }}
                                                </div>
                                                <div>
                                                    <a href="{{ route('projects.show', $proj) }}" class="font-semibold text-gray-900 hover:text-indigo-600 transition block">
                                                        {{ $proj->name }}
                                                    </a>
                                                    <span class="text-xs text-gray-400">
                                                        {{ $proj->type_label }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Cliente -->
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            <a href="{{ route('clients.show', $proj->client) }}" class="font-medium text-gray-900 hover:text-indigo-600 transition block">
                                                {{ $proj->client->name }}
                                            </a>
                                            <span class="text-xs text-gray-500">
                                                {{ $proj->client->company ?: 'Pessoa Física' }}
                                            </span>
                                        </td>

                                        <!-- Stack e Ambientes -->
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            <div class="flex items-center gap-2 mb-1">
                                                @if($proj->production_url)
                                                    <a href="{{ $proj->production_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-xs text-emerald-700 hover:underline font-semibold gap-1" title="Acessar Produção">
                                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span> Prod &rarr;
                                                    </a>
                                                @endif
                                                @if($proj->staging_url)
                                                    <a href="{{ $proj->staging_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-xs text-amber-700 hover:underline font-semibold gap-1" title="Acessar Homologação">
                                                        <span class="h-2 w-2 rounded-full bg-amber-500"></span> Stg &rarr;
                                                    </a>
                                                @endif
                                                @if($proj->repository_url)
                                                    <a href="{{ $proj->repository_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-xs text-gray-600 hover:underline font-semibold gap-1" title="Ver Repositório">
                                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"></path></svg> Repo
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500 truncate max-w-xs">
                                                {{ $proj->tech_stack ?: 'Sem stack informada' }}
                                            </div>
                                        </td>

                                        <!-- Status Badge -->
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $proj->status_badge_classes }}">
                                                {{ $proj->status_label }}
                                            </span>
                                        </td>

                                        <!-- Data -->
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $proj->created_at->format('d/m/Y') }}
                                        </td>

                                        <!-- Ações -->
                                        <td class="whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-2">
                                                <!-- Visualizar -->
                                                <a href="{{ route('projects.show', $proj) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 transition" title="Visualizar Detalhes">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>

                                                <!-- Editar -->
                                                <a href="{{ route('projects.edit', $proj) }}" class="p-1.5 text-gray-400 hover:text-emerald-600 rounded-lg hover:bg-emerald-50 transition" title="Editar Projeto">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>

                                                <!-- Excluir -->
                                                <form action="{{ route('projects.destroy', $proj) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir o projeto {{ $proj->name }}?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition" title="Excluir">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    @if($projects->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100 bg-slate-50/50">
                            {{ $projects->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-16 px-4">
                        <div class="mx-auto h-16 w-16 text-gray-300 flex items-center justify-center rounded-2xl bg-gray-50 border border-gray-200">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-base font-bold text-gray-900">Nenhum projeto encontrado</h3>
                        <p class="mt-1 text-sm text-gray-500 max-w-sm mx-auto">
                            @if($search || $status || $type || $clientId)
                                Não encontramos nenhuma aplicação com os filtros selecionados. Tente ajustar os termos.
                            @else
                                Comece cadastrando o primeiro projeto ou aplicação para seus clientes.
                            @endif
                        </p>
                        <div class="mt-6">
                            @if($search || $status || $type || $clientId)
                                <a href="{{ route('projects.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                                    Limpar Filtros
                                </a>
                            @else
                                <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-sm gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Cadastrar Primeiro Projeto
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
