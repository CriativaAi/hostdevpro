<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-white tracking-tight leading-tight flex items-center gap-2.5">
                    <span>Gestão de Clientes</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 font-bold">
                        {{ number_format($metrics['total']) }} Clientes
                    </span>
                </h2>
                <p class="text-xs text-slate-400 mt-1">Gerencie clientes, empresas parceiras e dados de contato do HostDevPro Cloud.</p>
            </div>
            <div>
                <a href="{{ route('clients.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs uppercase tracking-wider transition shadow-lg shadow-emerald-500/20 gap-2 transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Novo Cliente</span>
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
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 truncate">Total de Clientes</p>
                        <p class="text-2xl sm:text-3xl font-black text-white mt-1.5 tracking-tight">{{ number_format($metrics['total']) }}</p>
                    </div>
                    <div class="h-11 w-11 rounded-xl bg-purple-500/10 border border-purple-500/30 text-purple-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Ativos -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl flex items-center justify-between min-w-0 overflow-hidden transition">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-emerald-400 truncate">Clientes Ativos</p>
                        <p class="text-2xl sm:text-3xl font-black text-emerald-400 mt-1.5 tracking-tight">{{ number_format($metrics['active']) }}</p>
                    </div>
                    <div class="h-11 w-11 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Pendentes -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl flex items-center justify-between min-w-0 overflow-hidden transition">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-amber-400 truncate">Pendentes</p>
                        <p class="text-2xl sm:text-3xl font-black text-amber-400 mt-1.5 tracking-tight">{{ number_format($metrics['pending']) }}</p>
                    </div>
                    <div class="h-11 w-11 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Inativos -->
                <div class="p-6 rounded-2xl bg-white/[0.06] hover:bg-white/[0.10] backdrop-blur-2xl border border-white/15 shadow-xl flex items-center justify-between min-w-0 overflow-hidden transition">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 truncate">Inativos</p>
                        <p class="text-2xl sm:text-3xl font-black text-slate-400 mt-1.5 tracking-tight">{{ number_format($metrics['inactive']) }}</p>
                    </div>
                    <div class="h-11 w-11 rounded-xl bg-white/[0.05] border border-white/10 text-slate-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Filtros e Busca (Dark Frosted Glass, rounded-2xl) -->
            <div class="p-6 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-xl">
                <form action="{{ route('clients.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-center justify-between">
                    <div class="flex-1 w-full sm:max-w-md relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nome, e-mail, empresa ou telefone..." class="block w-full pl-10 pr-4 py-2.5 bg-black/40 border border-white/10 rounded-xl text-xs text-white placeholder-slate-500 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none">
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <select name="status" class="block w-full sm:w-44 py-2.5 px-3 bg-black/40 border border-white/10 rounded-xl text-xs text-slate-200 focus:border-emerald-500 outline-none transition">
                            <option value="" class="bg-slate-900 text-slate-300">Todos os Status</option>
                            <option value="active" {{ $status === 'active' ? 'selected' : '' }} class="bg-slate-900 text-emerald-400">🟢 Ativos</option>
                            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }} class="bg-slate-900 text-amber-400">🟡 Pendentes</option>
                            <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }} class="bg-slate-900 text-slate-400">⚪ Inativos</option>
                        </select>

                        <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-sm whitespace-nowrap">
                            Filtrar
                        </button>

                        @if($search || $status)
                            <a href="{{ route('clients.index') }}" class="inline-flex items-center px-3 py-2 text-xs font-semibold rounded-xl text-slate-400 hover:text-white transition whitespace-nowrap" title="Limpar filtros">
                                Limpar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabela de Clientes (Dark Frosted Glass, rounded-2xl) -->
            <div class="bg-white/[0.06] backdrop-blur-2xl rounded-2xl border border-white/15 shadow-2xl overflow-hidden">
                @if($clients->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-white/10 bg-white/[0.03] text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                    <th scope="col" class="py-4 px-6">Cliente / Empresa</th>
                                    <th scope="col" class="py-4 px-6">Contato</th>
                                    <th scope="col" class="py-4 px-6">Status</th>
                                    <th scope="col" class="py-4 px-6">Cadastro</th>
                                    <th scope="col" class="py-4 px-6 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 text-sm">
                                @foreach($clients as $client)
                                    <tr class="hover:bg-white/[0.04] transition">
                                        <!-- Cliente / Avatar -->
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3.5">
                                                <div class="h-10 w-10 rounded-xl bg-purple-500/10 border border-purple-500/30 text-purple-400 flex items-center justify-center font-black text-xs flex-shrink-0">
                                                    {{ $client->initials }}
                                                </div>
                                                <div class="min-w-0">
                                                    <a href="{{ route('clients.show', $client) }}" class="font-bold text-white hover:text-emerald-400 transition block truncate">
                                                        {{ $client->name }}
                                                    </a>
                                                    <span class="text-xs text-slate-400 block truncate">
                                                        {{ $client->company ?: 'Pessoa Física' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Contato -->
                                        <td class="py-4 px-6 text-xs text-slate-300">
                                            <div class="font-medium text-white">{{ $client->email }}</div>
                                            <div class="text-slate-400 mt-0.5">{{ $client->phone ?: 'Sem telefone' }}</div>
                                        </td>

                                        <!-- Status -->
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border {{ $client->status_classes }}">
                                                @if($client->status === 'active')
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                                @endif
                                                {{ $client->status_label }}
                                            </span>
                                        </td>

                                        <!-- Data -->
                                        <td class="py-4 px-6 text-xs text-slate-400">
                                            {{ $client->created_at->format('d/m/Y') }}
                                        </td>

                                        <!-- Ações -->
                                        <td class="py-4 px-6 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('clients.show', $client) }}" class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/[0.08] transition" title="Ver Detalhes">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </a>
                                                <a href="{{ route('clients.edit', $client) }}" class="p-2 rounded-lg text-slate-400 hover:text-amber-400 hover:bg-amber-500/10 transition" title="Editar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($clients->hasPages())
                        <div class="p-6 border-t border-white/10 bg-white/[0.02]">
                            {{ $clients->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-purple-500/10 border border-purple-500/30 text-purple-400 mx-auto flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-white">Nenhum cliente cadastrado</h3>
                        <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                            Cadastre clientes para vincular serviços de hospedagem, servidores e cobranças.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('clients.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-400 text-slate-950 font-black text-xs uppercase tracking-wider rounded-xl shadow-lg transition">
                                Novo Cliente
                            </a>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
