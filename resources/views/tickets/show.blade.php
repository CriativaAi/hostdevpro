<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('tickets.index') }}" class="inline-flex items-center text-xs font-bold uppercase tracking-wider text-gray-500 hover:text-[#783D19] transition">
                    &larr; Voltar
                </a>
                <span class="text-gray-300">/</span>
                <span class="font-mono text-sm font-bold text-[#783D19] bg-[#FEFAE0] px-2.5 py-1 rounded-lg border border-[#B99470]/30">
                    {{ $ticket->ticket_number }}
                </span>
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold ring-1 ring-inset {{ $ticket->status_badge_classes }}">
                    {{ $ticket->status_label }}
                </span>
            </div>

            <!-- Ações Rápidas no Topo -->
            <div class="flex items-center gap-2">
                @if ($ticket->status !== \App\Models\Ticket::STATUS_CLOSED)
                    <form method="POST" action="{{ route('tickets.update-status', $ticket) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="closed">
                        <button type="submit" class="px-3.5 py-2 rounded-xl border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-bold text-xs uppercase tracking-wider transition shadow-sm flex items-center gap-1.5">
                            <span>✓</span>
                            <span>Fechar Chamado</span>
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('tickets.update-status', $ticket) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="open">
                        <button type="submit" class="px-3.5 py-2 rounded-xl border border-emerald-300 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs uppercase tracking-wider transition shadow-sm flex items-center gap-1.5">
                            <span>↺</span>
                            <span>Reabrir Chamado</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Mensagem Flash -->
            @if (session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2.5 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Layout em Grade: Timeline de Conversas (8 colunas) + Sidebar de Contexto (4 colunas) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Coluna Principal: Assunto, Timeline e Resposta -->
                <div class="lg:col-span-8 space-y-6">
                    <!-- Cabeçalho do Chamado -->
                    <div class="bg-white rounded-3xl p-6 border border-[#B99470]/25 shadow-sm">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <span class="text-xs font-bold text-[#5F6F52] uppercase tracking-wider block mb-1">
                                    {{ $ticket->department_label }}
                                </span>
                                <h1 class="text-xl md:text-2xl font-bold text-gray-900 leading-snug">
                                    {{ $ticket->subject }}
                                </h1>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold ring-1 ring-inset {{ $ticket->priority_badge_classes }} shrink-0">
                                Prioridade {{ $ticket->priority_label }}
                            </span>
                        </div>
                    </div>

                    <!-- Linha do Tempo de Mensagens -->
                    <div class="space-y-4">
                        @foreach ($ticket->replies as $reply)
                            @if ($reply->is_internal_note)
                                <!-- Nota Interna da Equipe (Destaque Amarelo/Ouro) -->
                                <div class="bg-amber-50/80 rounded-3xl p-6 border-2 border-amber-200 shadow-sm relative overflow-hidden">
                                    <div class="flex items-center justify-between border-b border-amber-200 pb-3 mb-4">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 rounded bg-amber-200 text-amber-900 text-[11px] font-bold uppercase tracking-wider">
                                                🔒 Nota Interna
                                            </span>
                                            <span class="font-bold text-xs text-amber-950">{{ $reply->author_name }}</span>
                                        </div>
                                        <span class="text-xs text-amber-800">{{ $reply->created_at->format('d/m/Y \à\s H:i') }} ({{ $reply->created_at->diffForHumans() }})</span>
                                    </div>
                                    <div class="text-sm text-amber-900 leading-relaxed whitespace-pre-line font-sans">
                                        {{ $reply->message }}
                                    </div>
                                    <p class="text-[10px] text-amber-700 mt-3 font-semibold uppercase tracking-wider">
                                        ⚠️ Esta mensagem é visível exclusivamente pela equipe da HostDevPro.
                                    </p>
                                </div>
                            @elseif ($reply->is_staff)
                                <!-- Resposta do Staff / Suporte (Verde Dark Olive) -->
                                <div class="bg-white rounded-3xl p-6 border-l-4 border-l-[#5F6F52] border border-[#B99470]/25 shadow-sm">
                                    <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-full bg-[#5F6F52] text-white flex items-center justify-center text-xs font-bold">
                                                🛡️
                                            </div>
                                            <div>
                                                <span class="font-bold text-xs text-gray-900 block">{{ $reply->author_name }}</span>
                                                <span class="text-[11px] text-[#5F6F52] font-semibold">Equipe HostDevPro</span>
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-400">{{ $reply->created_at->format('d/m/Y \à\s H:i') }} ({{ $reply->created_at->diffForHumans() }})</span>
                                    </div>
                                    <div class="text-sm text-gray-800 leading-relaxed whitespace-pre-line">
                                        {{ $reply->message }}
                                    </div>
                                </div>
                            @else
                                <!-- Mensagem do Cliente Titular (Tom Bege Claro / Cornsilk) -->
                                <div class="bg-white rounded-3xl p-6 border-l-4 border-l-[#C4661F] border border-[#B99470]/25 shadow-sm">
                                    <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-full bg-[#FEFAE0] text-[#783D19] border border-[#B99470]/40 flex items-center justify-center text-xs font-bold">
                                                👤
                                            </div>
                                            <div>
                                                <span class="font-bold text-xs text-gray-900 block">{{ $reply->author_name }}</span>
                                                <span class="text-[11px] text-gray-500">Cliente Titular</span>
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-400">{{ $reply->created_at->format('d/m/Y \à\s H:i') }} ({{ $reply->created_at->diffForHumans() }})</span>
                                    </div>
                                    <div class="text-sm text-gray-800 leading-relaxed whitespace-pre-line">
                                        {{ $reply->message }}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Formulário de Nova Resposta / Nota Interna -->
                    @if ($ticket->status !== \App\Models\Ticket::STATUS_CLOSED)
                        <div class="bg-white rounded-3xl p-6 md:p-8 border border-[#B99470]/25 shadow-md">
                            <h3 class="text-base font-bold text-[#783D19] mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#5F6F52]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                <span>Responder ao Chamado</span>
                            </h3>

                            <form method="POST" action="{{ route('tickets.reply', $ticket) }}" class="space-y-4">
                                @csrf
                                <div>
                                    <textarea name="message" 
                                              rows="5" 
                                              required
                                              placeholder="Digite sua resposta técnica ou orientação para o cliente..."
                                              class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-[#5F6F52] focus:ring-[#5F6F52] text-sm">{{ old('message') }}</textarea>
                                    @error('message')
                                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
                                    <!-- Checkbox Nota Interna -->
                                    <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-gray-700 select-none">
                                        <input type="checkbox" 
                                               name="is_internal_note" 
                                               value="1" 
                                               class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                        <span>🔒 Registrar como Nota Interna (Privado da equipe)</span>
                                    </label>

                                    <!-- Botão de Envio -->
                                    <button type="submit" 
                                            class="px-6 py-2.5 rounded-xl bg-[#5F6F52] hover:bg-[#783D19] text-white font-bold text-xs uppercase tracking-wider transition-all duration-200 shadow-md">
                                        Enviar Mensagem
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-3xl p-6 border border-gray-200 text-center">
                            <span class="text-2xl block mb-2">🔒</span>
                            <h4 class="font-bold text-sm text-gray-800">Este chamado está fechado</h4>
                            <p class="text-xs text-gray-500 mt-1">
                                Resolvido em {{ $ticket->closed_at ? $ticket->closed_at->format('d/m/Y \à\s H:i') : 'Data não informada' }}. Para reabrir e enviar novas mensagens, clique em Reabrir Chamado no topo.
                            </p>
                        </div>
                    @endif

                </div>

                <!-- Coluna Lateral: Metadados do Chamado e Serviços Vinculados -->
                <div class="lg:col-span-4 space-y-6">

                    <!-- Gestão de Status e Prioridade Rápida -->
                    <div class="bg-white rounded-3xl p-6 border border-[#B99470]/25 shadow-sm">
                        <h4 class="text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#5F6F52]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                            <span>Gestão do Atendimento</span>
                        </h4>

                        <form method="POST" action="{{ route('tickets.update-status', $ticket) }}" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <!-- Status -->
                            <div>
                                <label for="update_status" class="block text-xs font-semibold text-gray-600 mb-1">Status da Fila</label>
                                <select name="status" id="update_status" class="w-full rounded-xl border-gray-300 text-xs focus:border-[#5F6F52] focus:ring-[#5F6F52]">
                                    <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Aberto</option>
                                    <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>Em Atendimento</option>
                                    <option value="answered" {{ $ticket->status === 'answered' ? 'selected' : '' }}>Respondido pelo Suporte</option>
                                    <option value="customer_reply" {{ $ticket->status === 'customer_reply' ? 'selected' : '' }}>Resposta do Cliente</option>
                                    <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Fechado</option>
                                </select>
                            </div>

                            <!-- Prioridade -->
                            <div>
                                <label for="update_priority" class="block text-xs font-semibold text-gray-600 mb-1">Prioridade</label>
                                <select name="priority" id="update_priority" class="w-full rounded-xl border-gray-300 text-xs focus:border-[#5F6F52] focus:ring-[#5F6F52]">
                                    <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>Baixa</option>
                                    <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>Média</option>
                                    <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>Alta</option>
                                    <option value="urgent" {{ $ticket->priority === 'urgent' ? 'selected' : '' }}>Crítica / Urgente</option>
                                </select>
                            </div>

                            <!-- Atendente Responsável -->
                            <div>
                                <label for="update_user" class="block text-xs font-semibold text-gray-600 mb-1">Atendente Designado</label>
                                <select name="user_id" id="update_user" class="w-full rounded-xl border-gray-300 text-xs focus:border-[#5F6F52] focus:ring-[#5F6F52]">
                                    <option value="">Não atribuído</option>
                                    @foreach ($users as $u)
                                        <option value="{{ $u->id }}" {{ $ticket->user_id == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="w-full py-2 px-4 rounded-xl bg-[#783D19] hover:bg-[#C4661F] text-white font-bold text-xs uppercase tracking-wider transition shadow-sm">
                                Atualizar Atendimento
                            </button>
                        </form>
                    </div>

                    <!-- Cliente Proprietário -->
                    <div class="bg-white rounded-3xl p-6 border border-[#B99470]/25 shadow-sm">
                        <h4 class="text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#5F6F52]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>Cliente Titular</span>
                        </h4>

                        <div class="space-y-2 text-xs">
                            <a href="{{ route('clients.show', $ticket->client) }}" class="font-bold text-sm text-[#783D19] hover:text-[#C4661F] transition block">
                                {{ $ticket->client->name }}
                            </a>
                            <p class="text-gray-500">{{ $ticket->client->company ?? 'Pessoa Física' }}</p>
                            <p class="font-mono text-gray-600">{{ $ticket->client->email }}</p>
                            @if ($ticket->client->phone)
                                <div class="pt-1">
                                    <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $ticket->client->phone) }}" target="_blank" class="text-emerald-700 font-bold hover:underline inline-flex items-center gap-1">
                                        <span>💬 WhatsApp: {{ $ticket->client->phone }}</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Serviço Vinculado (Se houver) -->
                    @if ($ticket->hostingAccount || $ticket->server || $ticket->project)
                        <div class="bg-white rounded-3xl p-6 border border-[#B99470]/25 shadow-sm">
                            <h4 class="text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#5F6F52]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                <span>Serviço Afetado</span>
                            </h4>

                            <div class="space-y-3 text-xs">
                                @if ($ticket->hostingAccount)
                                    <div class="p-3 rounded-xl bg-gray-50 border border-gray-200">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase block">Conta de Hospedagem</span>
                                        <a href="{{ route('hosting.show', $ticket->hostingAccount) }}" class="font-bold text-[#5F6F52] hover:underline block mt-0.5 font-mono">
                                            🌐 {{ $ticket->hostingAccount->domain }}
                                        </a>
                                        <span class="text-gray-500 block text-[11px] mt-1">Plano {{ $ticket->hostingAccount->plan_label }}</span>
                                    </div>
                                @endif

                                @if ($ticket->server)
                                    <div class="p-3 rounded-xl bg-gray-50 border border-gray-200">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase block">Servidor VPS</span>
                                        <a href="{{ route('servers.show', $ticket->server) }}" class="font-bold text-[#5F6F52] hover:underline block mt-0.5 font-mono">
                                            🖥️ {{ $ticket->server->name }}
                                        </a>
                                        <span class="text-gray-500 block text-[11px] mt-1">IP: {{ $ticket->server->ip_address }}</span>
                                    </div>
                                @endif

                                @if ($ticket->project)
                                    <div class="p-3 rounded-xl bg-gray-50 border border-gray-200">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase block">Projeto / Software</span>
                                        <a href="{{ route('projects.show', $ticket->project) }}" class="font-bold text-[#5F6F52] hover:underline block mt-0.5">
                                            📦 {{ $ticket->project->name }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Exclusão do Chamado -->
                    <div class="p-6 bg-red-50/50 rounded-3xl border border-red-200 text-center">
                        <form method="POST" action="{{ route('tickets.destroy', $ticket) }}" onsubmit="return confirm('Tem certeza que deseja excluir o chamado {{ $ticket->ticket_number }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-800 hover:underline">
                                🗑️ Excluir este Chamado
                            </button>
                        </form>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
