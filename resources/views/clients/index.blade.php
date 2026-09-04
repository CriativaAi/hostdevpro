<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('Gestão de Clientes') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Gerencie clientes, empresas parceiras e dados de contato do HostDevPro.</p>
            </div>
            <div>
                <a href="{{ route('clients.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Novo Cliente
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
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total de Clientes</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($metrics['total']) }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Ativos -->
                <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Clientes Ativos</p>
                        <p class="text-2xl font-bold text-emerald-600 mt-1">{{ number_format($metrics['active']) }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Pendentes -->
                <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Pendentes</p>
                        <p class="text-2xl font-bold text-amber-600 mt-1">{{ number_format($metrics['pending']) }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Inativos -->
                <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Inativos</p>
                        <p class="text-2xl font-bold text-gray-600 mt-1">{{ number_format($metrics['inactive']) }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-gray-100 text-gray-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Filtros e Busca -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 sm:p-5">
                <form action="{{ route('clients.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-center justify-between">
                    <div class="flex-1 w-full sm:max-w-md relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nome, e-mail, empresa ou telefone..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <select name="status" class="block w-full sm:w-44 py-2 px-3 border border-gray-300 bg-white rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <option value="">Todos os Status</option>
                            <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Ativos</option>
                            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pendentes</option>
                            <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inativos</option>
                        </select>

                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl text-white bg-gray-900 hover:bg-gray-800 transition shadow-sm">
                            Filtrar
                        </button>

                        @if($search || $status)
                            <a href="{{ route('clients.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-xl text-gray-600 bg-white hover:bg-gray-50 transition" title="Limpar filtros">
                                Limpar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabela de Clientes -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                @if($clients->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-left">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-6 pr-3 text-xs font-bold uppercase tracking-wider text-gray-500">Cliente / Empresa</th>
                                    <th scope="col" class="px-3 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500">Contato</th>
                                    <th scope="col" class="px-3 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500">Status</th>
                                    <th scope="col" class="px-3 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-500">Cadastro</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-6 text-right text-xs font-bold uppercase tracking-wider text-gray-500">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach($clients as $client)
                                    <tr class="hover:bg-slate-50/75 transition">
                                        <!-- Cliente / Avatar -->
                                        <td class="whitespace-nowrap py-4 pl-6 pr-3">
                                            <div class="flex items-center gap-3.5">
                                                <div class="h-10 w-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">
                                                    {{ $client->initials }}
                                                </div>
                                                <div>
                                                    <a href="{{ route('clients.show', $client) }}" class="font-semibold text-gray-900 hover:text-indigo-600 transition block">
                                                        {{ $client->name }}
                                                    </a>
                                                    <span class="text-xs text-gray-500">
                                                        {{ $client->company ?: 'Pessoa Física' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Contato -->
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            <div class="text-gray-900 font-medium">{{ $client->email }}</div>
                                            <div class="text-xs text-gray-500">{{ $client->phone ?: 'Sem telefone' }}</div>
                                        </td>

                                        <!-- Status Badge -->
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $client->status_badge_classes }}">
                                                {{ $client->status_label }}
                                            </span>
                                        </td>

                                        <!-- Data -->
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $client->created_at->format('d/m/Y') }}
                                        </td>

                                        <!-- Ações -->
                                        <td class="whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-2">
                                                <!-- Visualizar -->
                                                <a href="{{ route('clients.show', $client) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 transition" title="Visualizar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>

                                                <!-- Editar -->
                                                <a href="{{ route('clients.edit', $client) }}" class="p-1.5 text-gray-400 hover:text-emerald-600 rounded-lg hover:bg-emerald-50 transition" title="Editar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>

                                                <!-- Excluir -->
                                                <form action="{{ route('clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir o cliente {{ $client->name }}?');" class="inline">
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
                    @if($clients->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100 bg-slate-50/50">
                            {{ $clients->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-16 px-4">
                        <div class="mx-auto h-16 w-16 text-gray-300 flex items-center justify-center rounded-2xl bg-gray-50 border border-gray-200">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-base font-bold text-gray-900">Nenhum cliente encontrado</h3>
                        <p class="mt-1 text-sm text-gray-500 max-w-sm mx-auto">
                            @if($search || $status)
                                Não encontramos nenhum cliente com os filtros aplicados. Tente ajustar o termo de busca.
                            @else
                                Comece cadastrando o primeiro cliente ou empresa parceira no HostDevPro.
                            @endif
                        </p>
                        <div class="mt-6">
                            @if($search || $status)
                                <a href="{{ route('clients.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                                    Limpar Filtros
                                </a>
                            @else
                                <a href="{{ route('clients.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-sm gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Cadastrar Primeiro Cliente
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
