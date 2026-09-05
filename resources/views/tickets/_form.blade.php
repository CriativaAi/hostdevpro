@csrf

<div class="space-y-6">
    <!-- Bloco 1: Cliente e Serviço Afetado -->
    <div class="border-b border-white/10 pb-6">
        <h3 class="text-xs font-black text-emerald-400 uppercase tracking-wider mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs font-mono">1</span>
            <span>Identificação do Cliente & Contexto</span>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Cliente -->
            <div>
                <label for="client_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                    Cliente Titular <span class="text-rose-500">*</span>
                </label>
                <select name="client_id" 
                        id="client_id" 
                        required
                        class="w-full rounded-xl bg-black/40 border border-white/10 text-white text-xs py-2.5 px-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                    <option value="" class="bg-slate-900 text-slate-400">Selecione o cliente...</option>
                    @foreach ($clients as $c)
                        <option value="{{ $c->id }}" {{ old('client_id', $ticket->client_id) == $c->id ? 'selected' : '' }} class="bg-slate-900 text-white">
                            {{ $c->name }} ({{ $c->company ?: $c->email }})
                        </option>
                    @endforeach
                </select>
                @error('client_id')
                    <p class="text-xs text-rose-400 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Servidor VPS Vinculado (Opcional) -->
            <div>
                <label for="server_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                    Servidor VPS (Opcional)
                </label>
                <select name="server_id" 
                        id="server_id" 
                        class="w-full rounded-xl bg-black/40 border border-white/10 text-white text-xs py-2.5 px-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                    <option value="" class="bg-slate-900 text-slate-400">Nenhum servidor específico vinculado</option>
                    @foreach ($servers as $srv)
                        <option value="{{ $srv->id }}" {{ old('server_id', $ticket->server_id) == $srv->id ? 'selected' : '' }} class="bg-slate-900 text-white">
                            {{ $srv->name }} ({{ $srv->ip_address }})
                        </option>
                    @endforeach
                </select>
                @error('server_id')
                    <p class="text-xs text-rose-400 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Bloco 2: Classificação do Chamado -->
    <div class="border-b border-white/10 pb-6">
        <h3 class="text-xs font-black text-emerald-400 uppercase tracking-wider mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs font-mono">2</span>
            <span>Classificação & Fila de Atendimento</span>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Departamento -->
            <div>
                <label for="department" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                    Departamento <span class="text-rose-500">*</span>
                </label>
                <select name="department" 
                        id="department" 
                        required
                        class="w-full rounded-xl bg-black/40 border border-white/10 text-white text-xs py-2.5 px-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                    <option value="technical" {{ old('department', $ticket->department) === 'technical' ? 'selected' : '' }} class="bg-slate-900 text-white">
                        🛠️ Suporte Técnico (Aplicações, PHP, E-mails)
                    </option>
                    <option value="devops" {{ old('department', $ticket->department) === 'devops' ? 'selected' : '' }} class="bg-slate-900 text-white">
                        🚀 DevOps & Migrações (VPS, Docker, OpenResty, SSL, Migração)
                    </option>
                    <option value="financial" {{ old('department', $ticket->department) === 'financial' ? 'selected' : '' }} class="bg-slate-900 text-white">
                        💳 Financeiro & Faturamento (Faturas, PIX, Recibos)
                    </option>
                    <option value="commercial" {{ old('department', $ticket->department) === 'commercial' ? 'selected' : '' }} class="bg-slate-900 text-white">
                        🤝 Comercial & Planos (Upgrades, Consultoria)
                    </option>
                </select>
                @error('department')
                    <p class="text-xs text-rose-400 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Prioridade -->
            <div>
                <label for="priority" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                    Prioridade / Urgência <span class="text-rose-500">*</span>
                </label>
                <select name="priority" 
                        id="priority" 
                        required
                        class="w-full rounded-xl bg-black/40 border border-white/10 text-white text-xs py-2.5 px-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                    <option value="low" {{ old('priority', $ticket->priority) === 'low' ? 'selected' : '' }} class="bg-slate-900 text-slate-300">
                        🟢 Baixa (Dúvidas gerais, configurações)
                    </option>
                    <option value="medium" {{ old('priority', $ticket->priority) === 'medium' ? 'selected' : '' }} class="bg-slate-900 text-cyan-400">
                        🟡 Média (Solicitações normais, migrações)
                    </option>
                    <option value="high" {{ old('priority', $ticket->priority) === 'high' ? 'selected' : '' }} class="bg-slate-900 text-amber-400">
                        🟠 Alta (Serviço instável)
                    </option>
                    <option value="urgent" {{ old('priority', $ticket->priority) === 'urgent' ? 'selected' : '' }} class="bg-slate-900 text-rose-400">
                        🔴 Crítica / Urgente (Interrupção total)
                    </option>
                </select>
                @error('priority')
                    <p class="text-xs text-rose-400 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Bloco 3: Assunto e Descrição -->
    <div class="space-y-6">
        <h3 class="text-xs font-black text-emerald-400 uppercase tracking-wider mb-2 flex items-center gap-2">
            <span class="w-6 h-6 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs font-mono">3</span>
            <span>Mensagem do Chamado</span>
        </h3>

        <!-- Assunto -->
        <div>
            <label for="subject" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                Assunto do Chamado <span class="text-rose-500">*</span>
            </label>
            <input type="text" 
                   name="subject" 
                   id="subject" 
                   value="{{ old('subject', $ticket->subject) }}" 
                   placeholder="Ex: Solicitação de Migração Gratuita de Websites"
                   required
                   class="w-full px-4 py-2.5 rounded-xl bg-black/40 border border-white/10 text-white placeholder-slate-500 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
            @error('subject')
                <p class="text-xs text-rose-400 mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Mensagem Inicial -->
        <div>
            <label for="message" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                Descrição Detalhada / Mensagem Inicial <span class="text-rose-500">*</span>
            </label>
            <textarea name="message" 
                      id="message" 
                      rows="6" 
                      required
                      placeholder="Descreva a solicitação com o máximo de detalhes (ex: dados de acesso do servidor anterior se for migração)..."
                      class="w-full p-4 rounded-xl bg-black/40 border border-white/10 text-white placeholder-slate-500 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">{{ old('message') }}</textarea>
            @error('message')
                <p class="text-xs text-rose-400 mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Botões de Ação -->
    <div class="pt-6 border-t border-white/10 flex items-center justify-end gap-3">
        <a href="{{ route('tickets.index') }}" 
           class="px-5 py-2.5 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider transition">
            Cancelar
        </a>
        <button type="submit" 
                class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs uppercase tracking-wider transition-all duration-200 shadow-lg shadow-emerald-500/20">
            Abrir Chamado de Suporte
        </button>
    </div>
</div>
