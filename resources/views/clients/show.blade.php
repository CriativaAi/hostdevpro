<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('clients.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 transition">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar
                </a>
                <span class="text-gray-300">/</span>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detalhes do Cliente
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar Cliente
                </a>
                <form action="{{ route('clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir o cliente {{ $client->name }}? Esta ação pode ser revertida via suporte.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-200 shadow-sm text-sm font-medium rounded-lg text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Header Profile Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="h-16 w-16 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-bold text-2xl shadow-md shadow-indigo-100">
                            {{ $client->initials }}
                        </div>
                        <div>
                            <div class="flex items-center gap-3">
                                <h1 class="text-2xl font-bold text-gray-900">{{ $client->name }}</h1>
                                <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $client->status_badge_classes }}">
                                    {{ $client->status_label }}
                                </span>
                            </div>
                            @if($client->company)
                                <p class="text-sm font-medium text-gray-600 mt-1 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    {{ $client->company }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="text-xs text-gray-500 space-y-1 sm:text-right">
                        <p>Cliente cadastrado em <strong class="text-gray-700">{{ $client->created_at->format('d/m/Y \à\s H:i') }}</strong></p>
                        <p>Última atualização em <strong class="text-gray-700">{{ $client->updated_at->format('d/m/Y \à\s H:i') }}</strong></p>
                    </div>
                </div>
            </div>

            <!-- Dados de Contato e Informações -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informações de Contato -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Canais de Contato
                    </h3>

                    <dl class="space-y-4 text-sm">
                        <div>
                            <dt class="text-xs font-semibold uppercase text-gray-400">E-mail</dt>
                            <dd class="mt-1 flex items-center justify-between">
                                <span class="font-medium text-gray-800">{{ $client->email }}</span>
                                <a href="mailto:{{ $client->email }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline">
                                    Enviar E-mail &rarr;
                                </a>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold uppercase text-gray-400">Telefone / WhatsApp</dt>
                            <dd class="mt-1 flex items-center justify-between">
                                <span class="font-medium text-gray-800">{{ $client->phone ?: 'Não informado' }}</span>
                                @if($client->phone)
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $client->phone);
                                    @endphp
                                    <a href="https://wa.me/55{{ $cleanPhone }}" target="_blank" rel="noopener noreferrer" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800 hover:underline">
                                        Abrir WhatsApp &rarr;
                                    </a>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold uppercase text-gray-400">Empresa / Razão Social</dt>
                            <dd class="mt-1 font-medium text-gray-800">
                                {{ $client->company ?: 'Pessoa Física / Não informado' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Observações e Notas -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col">
                    <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Observações e Notas Internas
                    </h3>

                    <div class="flex-1">
                        @if($client->notes)
                            <div class="bg-slate-50 border border-slate-100 rounded-lg p-4 text-sm text-gray-700 whitespace-pre-line leading-relaxed">
                                {{ $client->notes }}
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-400 text-sm">
                                <p>Nenhuma observação registrada para este cliente.</p>
                                <a href="{{ route('clients.edit', $client) }}" class="mt-2 inline-block text-xs font-semibold text-indigo-600 hover:underline">
                                    + Adicionar anotações
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Seção de Projetos Deste Cliente -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4 mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Aplicações & Projetos Vinculados ({{ $client->projects->count() }})
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Sistemas, websites e ferramentas contratados por este cliente.</p>
                    </div>
                    <a href="{{ route('projects.create', ['client_id' => $client->id]) }}" class="inline-flex items-center px-3.5 py-2 border border-transparent text-xs font-semibold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition gap-1.5 self-start sm:self-auto shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Novo Projeto para este Cliente
                    </a>
                </div>

                @if($client->projects->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($client->projects as $proj)
                            <div class="rounded-xl border border-gray-100 p-4 hover:border-indigo-200 hover:shadow-sm transition flex flex-col justify-between bg-slate-50/50">
                                <div>
                                    <div class="flex items-center justify-between gap-2 mb-2">
                                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $proj->status_badge_classes }}">
                                            {{ $proj->status_label }}
                                        </span>
                                        <span class="text-xs text-gray-400 font-medium">
                                            {{ $proj->type_label }}
                                        </span>
                                    </div>
                                    <h4 class="font-bold text-gray-900 text-sm hover:text-indigo-600 transition">
                                        <a href="{{ route('projects.show', $proj) }}">{{ $proj->name }}</a>
                                    </h4>
                                    @if($proj->description)
                                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $proj->description }}</p>
                                    @endif
                                </div>

                                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-2">
                                        @if($proj->production_url)
                                            <a href="{{ $proj->production_url }}" target="_blank" rel="noopener noreferrer" class="text-emerald-700 hover:underline font-semibold" title="Acessar Produção">
                                                Prod &rarr;
                                            </a>
                                        @endif
                                        @if($proj->repository_url)
                                            <a href="{{ $proj->repository_url }}" target="_blank" rel="noopener noreferrer" class="text-gray-600 hover:underline" title="Ver Repositório">
                                                Repo
                                            </a>
                                        @endif
                                    </div>
                                    <a href="{{ route('projects.show', $proj) }}" class="font-semibold text-indigo-600 hover:text-indigo-800">
                                        Detalhes &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400 text-sm">
                        <p>Nenhum projeto cadastrado para este cliente até o momento.</p>
                        <a href="{{ route('projects.create', ['client_id' => $client->id]) }}" class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:underline">
                            + Criar primeiro projeto agora
                        </a>
                    </div>
                @endif
            </div>

            <!-- Seção de Hospedagens & Domínios Deste Cliente -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4 mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-darkolive" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                            Contas de Hospedagem & Domínios ({{ $client->hostingAccounts->count() }})
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Planos web gerenciados, domínios e alocação de servidores para este cliente.</p>
                    </div>
                    <a href="{{ route('hosting.create', ['client_id' => $client->id]) }}" class="inline-flex items-center px-3.5 py-2 border border-transparent text-xs font-semibold rounded-lg text-white bg-brand-darkolive hover:bg-brand-russet transition gap-1.5 self-start sm:self-auto shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Nova Hospedagem para este Cliente
                    </a>
                </div>

                @if($client->hostingAccounts->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($client->hostingAccounts as $account)
                            <div class="rounded-xl border border-gray-100 p-4 hover:border-brand-darkolive/40 hover:shadow-sm transition flex flex-col justify-between bg-slate-50/50">
                                <div>
                                    <div class="flex items-center justify-between gap-2 mb-2">
                                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $account->status_badge_classes }}">
                                            {{ $account->status_label }}
                                        </span>
                                        <span class="text-xs text-brand-russet font-semibold bg-brand-cornsilk px-2 py-0.5 rounded">
                                            {{ $account->plan_label }}
                                        </span>
                                    </div>
                                    <h4 class="font-bold text-gray-900 text-sm hover:text-brand-darkolive transition">
                                        <a href="{{ route('hosting.show', $account) }}" class="flex items-center gap-1.5">
                                            <span>{{ $account->domain }}</span>
                                            @if($account->ssl_status === \App\Models\HostingAccount::SSL_ACTIVE)
                                                <svg class="w-3.5 h-3.5 text-emerald-600 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="SSL Let's Encrypt Ativo">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                </svg>
                                            @endif
                                        </a>
                                    </h4>
                                    <div class="mt-2 space-y-1 text-xs text-gray-600">
                                        <p class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                                            </svg>
                                            <span>Servidor: <strong class="text-gray-800">{{ $account->server->name }}</strong></span>
                                        </p>
                                        <p class="flex items-center gap-1">
                                            <span class="text-gray-400 font-mono">SSD:</span>
                                            <span>{{ $account->disk_limit_mb >= 1024 ? round($account->disk_limit_mb / 1024, 1) . ' GB' : $account->disk_limit_mb . ' MB' }}</span>
                                            <span class="text-gray-300">|</span>
                                            <span class="text-gray-400 font-mono">Tráfego:</span>
                                            <span>{{ $account->bandwidth_limit_gb }} GB/mês</span>
                                        </p>
                                        @if($account->php_version)
                                            <p class="text-[11px] text-gray-400 font-mono">
                                                PHP {{ $account->php_version }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                                    <a href="https://{{ $account->domain }}" target="_blank" rel="noopener noreferrer" class="text-emerald-700 hover:underline font-semibold flex items-center gap-1" title="Visitar Domínio">
                                        <span>Visitar</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('hosting.show', $account) }}" class="font-semibold text-brand-darkolive hover:text-brand-russet">
                                        Gerenciar &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400 text-sm">
                        <p>Nenhuma conta de hospedagem vinculada a este cliente.</p>
                        <a href="{{ route('hosting.create', ['client_id' => $client->id]) }}" class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-brand-darkolive hover:underline">
                            + Vincular primeira conta de hospedagem agora
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
