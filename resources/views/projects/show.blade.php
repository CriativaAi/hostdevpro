<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 transition">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar
                </a>
                <span class="text-gray-300">/</span>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detalhes da Aplicação
                </h2>
            </div>
            <div class="flex items-center gap-3">
                @if($project->production_url)
                    <a href="{{ $project->production_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-3.5 py-2 border border-emerald-300 text-sm font-medium rounded-lg text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        Acessar Produção
                    </a>
                @endif
                <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
                <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir o projeto {{ $project->name }}?');">
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

            <!-- Header Card do Projeto -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div>
                        <div class="flex flex-wrap items-center gap-2.5 mb-2">
                            <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $project->status_badge_classes }}">
                                {{ $project->status_label }}
                            </span>
                            <span class="inline-flex items-center rounded-md bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700">
                                {{ $project->type_label }}
                            </span>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $project->name }}</h1>
                        <p class="text-sm text-gray-500 mt-1 flex items-center gap-1.5">
                            Pertence a:
                            <a href="{{ route('clients.show', $project->client) }}" class="font-semibold text-indigo-600 hover:text-indigo-800 hover:underline">
                                {{ $project->client->name }} {{ $project->client->company ? "({$project->client->company})" : '' }}
                            </a>
                        </p>
                    </div>

                    <!-- Tech Stack Tags -->
                    @if(!empty($project->tech_stack_array))
                        <div class="lg:text-right">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Stack Tecnológica</p>
                            <div class="flex flex-wrap lg:justify-end gap-1.5">
                                @foreach($project->tech_stack_array as $tech)
                                    <span class="inline-flex items-center rounded-lg bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 border border-gray-200">
                                        {{ $tech }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Ambientes e Links de Deploy (2 colunas) -->
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                            Ambientes & URLs do Projeto
                        </h3>

                        <div class="space-y-4">
                            <!-- Produção -->
                            <div class="flex items-center justify-between p-4 rounded-xl border border-gray-100 bg-slate-50/50">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">
                                        PR
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase text-gray-400">Ambiente de Produção</p>
                                        @if($project->production_url)
                                            <a href="{{ $project->production_url }}" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold text-gray-900 hover:text-indigo-600 transition break-all">
                                                {{ $project->production_url }}
                                            </a>
                                        @else
                                            <span class="text-sm text-gray-400">Nenhuma URL de produção configurada</span>
                                        @endif
                                    </div>
                                </div>
                                @if($project->production_url)
                                    <a href="{{ $project->production_url }}" target="_blank" rel="noopener noreferrer" class="p-2 text-gray-400 hover:text-indigo-600 transition" title="Abrir link">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                @endif
                            </div>

                            <!-- Homologação -->
                            <div class="flex items-center justify-between p-4 rounded-xl border border-gray-100 bg-slate-50/50">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm">
                                        HM
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase text-gray-400">Ambiente de Homologação (Staging)</p>
                                        @if($project->staging_url)
                                            <a href="{{ $project->staging_url }}" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold text-gray-900 hover:text-indigo-600 transition break-all">
                                                {{ $project->staging_url }}
                                            </a>
                                        @else
                                            <span class="text-sm text-gray-400">Nenhuma URL de homologação configurada</span>
                                        @endif
                                    </div>
                                </div>
                                @if($project->staging_url)
                                    <a href="{{ $project->staging_url }}" target="_blank" rel="noopener noreferrer" class="p-2 text-gray-400 hover:text-indigo-600 transition" title="Abrir link">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                @endif
                            </div>

                            <!-- Repositório Git -->
                            <div class="flex items-center justify-between p-4 rounded-xl border border-gray-100 bg-slate-50/50">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-lg bg-gray-900 text-white flex items-center justify-center font-bold text-sm">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase text-gray-400">Repositório de Código</p>
                                        @if($project->repository_url)
                                            <a href="{{ $project->repository_url }}" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold text-gray-900 hover:text-indigo-600 transition break-all">
                                                {{ $project->repository_url }}
                                            </a>
                                        @else
                                            <span class="text-sm text-gray-400">Nenhum repositório Git vinculado</span>
                                        @endif
                                    </div>
                                </div>
                                @if($project->repository_url)
                                    <a href="{{ $project->repository_url }}" target="_blank" rel="noopener noreferrer" class="p-2 text-gray-400 hover:text-indigo-600 transition" title="Abrir repositório">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Escopo e Descrição -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Descrição e Escopo
                        </h3>

                        @if($project->description)
                            <div class="bg-slate-50 border border-slate-100 rounded-lg p-4 text-sm text-gray-700 whitespace-pre-line leading-relaxed">
                                {{ $project->description }}
                            </div>
                        @else
                            <p class="text-sm text-gray-400 italic">Nenhuma descrição cadastrada para este projeto.</p>
                        @endif
                    </div>
                </div>

                <!-- Painel Lateral: Cliente & Auditoria (1 coluna) -->
                <div class="space-y-6">
                    <!-- Card do Cliente -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">
                            Dados do Cliente
                        </h3>

                        <div class="flex items-center gap-3.5 mb-4">
                            <div class="h-11 w-11 rounded-xl bg-indigo-50 text-indigo-700 font-bold flex items-center justify-center text-sm border border-indigo-100">
                                {{ $project->client->initials }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">{{ $project->client->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $project->client->company ?: 'Pessoa Física' }}</p>
                            </div>
                        </div>

                        <dl class="space-y-3 text-xs">
                            <div>
                                <dt class="font-semibold text-gray-400 uppercase">E-mail</dt>
                                <dd class="mt-0.5 text-gray-800 font-medium break-all">{{ $project->client->email }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-gray-400 uppercase">Telefone</dt>
                                <dd class="mt-0.5 text-gray-800 font-medium">{{ $project->client->phone ?: 'Não informado' }}</dd>
                            </div>
                        </dl>

                        <div class="mt-5 pt-4 border-t border-gray-100">
                            <a href="{{ route('clients.show', $project->client) }}" class="inline-flex items-center text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline">
                                Ver ficha completa do cliente &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- Card de Metadados -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-xs text-gray-500 space-y-2">
                        <p>Criado em <strong class="text-gray-700">{{ $project->created_at->format('d/m/Y \à\s H:i') }}</strong></p>
                        <p>Última alteração em <strong class="text-gray-700">{{ $project->updated_at->format('d/m/Y \à\s H:i') }}</strong></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
