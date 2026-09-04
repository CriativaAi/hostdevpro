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
        </div>
    </div>
</x-app-layout>
