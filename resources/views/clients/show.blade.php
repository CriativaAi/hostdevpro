<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('clients.index') }}" class="inline-flex items-center text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-white transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar
                </a>
                <span class="text-slate-600">/</span>
                <h2 class="font-black text-xl text-white leading-tight">
                    Detalhes do Cliente
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center px-4 py-2 bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-lg">
                    <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar Cliente
                </a>
                <form action="{{ route('clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir o cliente {{ $client->name }}? Esta ação pode ser revertida via suporte.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 text-red-400 font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-lg">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Excluir
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Header Profile Card -->
            <div class="bg-white/[0.06] backdrop-blur-xl rounded-2xl border border-white/15 shadow-xl p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-slate-950 font-black text-2xl shadow-lg shadow-emerald-500/20">
                            {{ $client->initials }}
                        </div>
                        <div>
                            <div class="flex items-center gap-3">
                                <h1 class="text-2xl font-black text-white">{{ $client->name }}</h1>
                                <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $client->status_badge_classes }}">
                                    {{ $client->status_label }}
                                </span>
                            </div>
                            @if($client->company)
                                <p class="text-sm font-medium text-slate-400 mt-1 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    {{ $client->company }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="text-xs text-slate-400 space-y-1 sm:text-right">
                        <p>Cliente cadastrado em <strong class="text-slate-200">{{ $client->created_at->format('d/m/Y \à\s H:i') }}</strong></p>
                        <p>Última atualização em <strong class="text-slate-200">{{ $client->updated_at->format('d/m/Y \à\s H:i') }}</strong></p>
                    </div>
                </div>
            </div>

            <!-- Dados de Contato e Informações -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informações de Contato -->
                <div class="bg-white/[0.06] backdrop-blur-xl rounded-2xl border border-white/15 shadow-xl p-6">
                    <h3 class="text-base font-bold text-white border-b border-white/10 pb-3 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Canais de Contato
                    </h3>

                    <dl class="space-y-4 text-sm">
                        <div>
                            <dt class="text-xs font-semibold uppercase text-slate-400">E-mail</dt>
                            <dd class="mt-1 flex items-center justify-between">
                                <span class="font-medium text-slate-200 font-mono">{{ $client->email }}</span>
                                <a href="mailto:{{ $client->email }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 hover:underline">
                                    Enviar E-mail &rarr;
                                </a>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold uppercase text-slate-400">Telefone / WhatsApp</dt>
                            <dd class="mt-1 flex items-center justify-between">
                                <span class="font-medium text-slate-200">{{ $client->phone ?: 'Não informado' }}</span>
                                @if($client->phone)
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $client->phone);
                                    @endphp
                                    <a href="https://wa.me/55{{ $cleanPhone }}" target="_blank" rel="noopener noreferrer" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 hover:underline inline-flex items-center gap-1">
                                        <span>💬 Abrir WhatsApp &rarr;</span>
                                    </a>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold uppercase text-slate-400">Empresa / Razão Social</dt>
                            <dd class="mt-1 font-medium text-slate-200">
                                {{ $client->company ?: 'Pessoa Física / Não informado' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Observações e Notas -->
                <div class="bg-white/[0.06] backdrop-blur-xl rounded-2xl border border-white/15 shadow-xl p-6 flex flex-col">
                    <h3 class="text-base font-bold text-white border-b border-white/10 pb-3 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Observações e Notas Internas
                    </h3>

                    <div class="flex-1">
                        @if($client->notes)
                            <div class="bg-slate-900/80 border border-white/10 rounded-xl p-4 text-sm text-slate-300 whitespace-pre-line leading-relaxed">
                                {{ $client->notes }}
                            </div>
                        @else
                            <div class="text-center py-8 text-slate-400 text-sm">
                                <p>Nenhuma observação registrada para este cliente.</p>
                                <a href="{{ route('clients.edit', $client) }}" class="mt-2 inline-block text-xs font-semibold text-emerald-400 hover:underline">
                                    + Adicionar anotações
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Seção de Projetos Deste Cliente -->
            <div class="bg-white/[0.06] backdrop-blur-xl rounded-2xl border border-white/15 shadow-xl p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-4 mb-6">
                    <div>
                        <h3 class="text-lg font-black text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Aplicações & Projetos Vinculados ({{ $client->projects->count() }})
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Sistemas, websites e ferramentas contratados por este cliente.</p>
                    </div>
                    <a href="{{ route('projects.create', ['client_id' => $client->id]) }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 text-slate-950 font-black text-xs uppercase tracking-wider transition gap-1.5 shadow-lg shadow-emerald-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Novo Projeto para este Cliente
                    </a>
                </div>

                @if($client->projects->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($client->projects as $proj)
                            <div class="rounded-xl border border-white/10 p-4 bg-slate-900/80 hover:border-emerald-500/30 transition flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between gap-2 mb-2">
                                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $proj->status_badge_classes }}">
                                            {{ $proj->status_label }}
                                        </span>
                                        <span class="text-xs text-slate-400 font-medium">
                                            {{ $proj->type_label }}
                                        </span>
                                    </div>
                                    <h4 class="font-bold text-white text-sm hover:text-emerald-400 transition">
                                        <a href="{{ route('projects.show', $proj) }}">{{ $proj->name }}</a>
                                    </h4>
                                    @if($proj->description)
                                        <p class="text-xs text-slate-400 mt-1 line-clamp-2">{{ $proj->description }}</p>
                                    @endif
                                </div>

                                <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-2">
                                        @if($proj->production_url)
                                            <a href="{{ $proj->production_url }}" target="_blank" rel="noopener noreferrer" class="text-emerald-400 hover:underline font-semibold" title="Acessar Produção">
                                                Prod &rarr;
                                            </a>
                                        @endif
                                        @if($proj->repository_url)
                                            <a href="{{ $proj->repository_url }}" target="_blank" rel="noopener noreferrer" class="text-slate-400 hover:text-white hover:underline" title="Ver Repositório">
                                                Repo
                                            </a>
                                        @endif
                                    </div>
                                    <a href="{{ route('projects.show', $proj) }}" class="font-semibold text-cyan-400 hover:text-cyan-300">
                                        Detalhes &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-slate-400 text-sm">
                        <p>Nenhum projeto cadastrado para este cliente até o momento.</p>
                        <a href="{{ route('projects.create', ['client_id' => $client->id]) }}" class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-emerald-400 hover:underline">
                            + Criar primeiro projeto agora
                        </a>
                    </div>
                @endif
            </div>

            <!-- Seção de Hospedagens & Domínios Deste Cliente -->
            <div class="bg-white/[0.06] backdrop-blur-xl rounded-2xl border border-white/15 shadow-xl p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-4 mb-6">
                    <div>
                        <h3 class="text-lg font-black text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                            Contas de Hospedagem & Domínios ({{ $client->hostingAccounts->count() }})
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Planos web gerenciados, domínios e alocação de servidores para este cliente.</p>
                    </div>
                    <a href="{{ route('hosting.create', ['client_id' => $client->id]) }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 text-slate-950 font-black text-xs uppercase tracking-wider transition gap-1.5 shadow-lg shadow-emerald-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Nova Hospedagem para este Cliente
                    </a>
                </div>

                @if($client->hostingAccounts->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($client->hostingAccounts as $account)
                            <div class="rounded-xl border border-white/10 p-4 bg-slate-900/80 hover:border-emerald-500/30 transition flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between gap-2 mb-2">
                                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $account->status_badge_classes }}">
                                            {{ $account->status_label }}
                                        </span>
                                        <span class="text-xs text-emerald-400 font-semibold bg-emerald-500/10 border border-emerald-500/30 px-2 py-0.5 rounded">
                                            {{ $account->plan_label }}
                                        </span>
                                    </div>
                                    <h4 class="font-bold text-white text-sm hover:text-cyan-400 transition font-mono">
                                        <a href="{{ route('hosting.show', $account) }}" class="flex items-center gap-1.5">
                                            <span>{{ $account->domain }}</span>
                                            @if($account->ssl_status === \App\Models\HostingAccount::SSL_ACTIVE)
                                                <svg class="w-3.5 h-3.5 text-emerald-400 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="SSL Let's Encrypt Ativo">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                </svg>
                                            @endif
                                        </a>
                                    </h4>
                                    <div class="mt-2 space-y-1 text-xs text-slate-400">
                                        <p class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                                            </svg>
                                            <span>Servidor: <strong class="text-slate-200">{{ $account->server->name }}</strong></span>
                                        </p>
                                        <p class="flex items-center gap-1">
                                            <span class="text-slate-500 font-mono">SSD:</span>
                                            <span>{{ $account->disk_limit_mb >= 1024 ? round($account->disk_limit_mb / 1024, 1) . ' GB' : $account->disk_limit_mb . ' MB' }}</span>
                                            <span class="text-slate-600">|</span>
                                            <span class="text-slate-500 font-mono">Tráfego:</span>
                                            <span>{{ $account->bandwidth_limit_gb }} GB/mês</span>
                                        </p>
                                        @if($account->php_version)
                                            <p class="text-[11px] text-emerald-400 font-mono">
                                                PHP {{ $account->php_version }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs">
                                    <a href="https://{{ $account->domain }}" target="_blank" rel="noopener noreferrer" class="text-emerald-400 hover:text-emerald-300 hover:underline font-semibold flex items-center gap-1" title="Visitar Domínio">
                                        <span>Visitar</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('hosting.show', $account) }}" class="font-semibold text-cyan-400 hover:text-cyan-300">
                                        Gerenciar &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-slate-400 text-sm">
                        <p>Nenhuma conta de hospedagem vinculada a este cliente.</p>
                        <a href="{{ route('hosting.create', ['client_id' => $client->id]) }}" class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-emerald-400 hover:underline">
                            + Vincular primeira conta de hospedagem agora
                        </a>
                    </div>
                @endif
            </div>

            <!-- Seção de Chamados de Suporte Deste Cliente -->
            <div class="bg-white/[0.06] backdrop-blur-xl rounded-2xl border border-white/15 shadow-xl p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-4 mb-6">
                    <div>
                        <h3 class="text-lg font-black text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            Chamados de Suporte Recentes ({{ $client->tickets->count() }})
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Histórico de tickets técnicos, solicitações de infraestrutura e faturamento deste cliente.</p>
                    </div>
                    <a href="{{ route('tickets.create', ['client_id' => $client->id]) }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 text-slate-950 font-black text-xs uppercase tracking-wider transition gap-1.5 shadow-lg shadow-emerald-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Novo Chamado para este Cliente
                    </a>
                </div>

                @if($client->tickets->count() > 0)
                    <div class="divide-y divide-white/5">
                        @foreach($client->tickets as $t)
                            <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2 hover:bg-white/[0.04] p-2 rounded-xl transition">
                                <div class="flex items-start sm:items-center gap-3">
                                    <span class="font-mono text-xs font-bold text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/30 shrink-0">
                                        {{ $t->ticket_number }}
                                    </span>
                                    <div>
                                        <a href="{{ route('tickets.show', $t) }}" class="font-bold text-sm text-white hover:text-emerald-400 transition">
                                            {{ $t->subject }}
                                        </a>
                                        <div class="flex items-center gap-2 mt-0.5 text-xs text-slate-400">
                                            <span>{{ $t->department_label }}</span>
                                            <span>•</span>
                                            <span>Atualizado {{ $t->last_reply_at ? $t->last_reply_at->diffForHumans() : $t->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2.5 self-end sm:self-auto">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold ring-1 ring-inset {{ $t->priority_badge_classes }}">
                                        {{ $t->priority_label }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold ring-1 ring-inset {{ $t->status_badge_classes }}">
                                        {{ $t->status_label }}
                                    </span>
                                    <a href="{{ route('tickets.show', $t) }}" class="text-xs font-semibold text-emerald-400 hover:underline ml-2">
                                        Ver &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-slate-400 text-sm">
                        <p>Nenhum chamado de suporte aberto para este cliente.</p>
                        <a href="{{ route('tickets.create', ['client_id' => $client->id]) }}" class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-emerald-400 hover:underline">
                            + Abrir chamado agora
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
