@csrf

<div class="space-y-6">
    <!-- Bloco 1: Cliente e Serviço Afetado -->
    <div class="border-b border-gray-100 pb-6">
        <h3 class="text-sm font-bold text-[#783D19] uppercase tracking-wider mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-[#FEFAE0] text-[#783D19] flex items-center justify-center text-xs">1</span>
            <span>Identificação do Cliente & Contexto</span>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Cliente -->
            <div>
                <label for="client_id" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-2">
                    Cliente Titular <span class="text-red-500">*</span>
                </label>
                <select name="client_id" 
                        id="client_id" 
                        required
                        class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-[#5F6F52] focus:ring-[#5F6F52] text-sm">
                    <option value="">Selecione o cliente...</option>
                    @foreach ($clients as $c)
                        <option value="{{ $c->id }}" {{ old('client_id', $ticket->client_id) == $c->id ? 'selected' : '' }}>
                            {{ $c->name }} ({{ $c->company ?: $c->email }})
                        </option>
                    @endforeach
                </select>
                @error('client_id')
                    <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Servidor VPS Vinculado (Opcional) -->
            <div>
                <label for="server_id" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-2">
                    Servidor VPS (Opcional)
                </label>
                <select name="server_id" 
                        id="server_id" 
                        class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-[#5F6F52] focus:ring-[#5F6F52] text-sm">
                    <option value="">Nenhum servidor específico vinculado</option>
                    @foreach ($servers as $srv)
                        <option value="{{ $srv->id }}" {{ old('server_id', $ticket->server_id) == $srv->id ? 'selected' : '' }}>
                            {{ $srv->name }} ({{ $srv->ip_address }})
                        </option>
                    @endforeach
                </select>
                @error('server_id')
                    <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Bloco 2: Classificação do Chamado -->
    <div class="border-b border-gray-100 pb-6">
        <h3 class="text-sm font-bold text-[#783D19] uppercase tracking-wider mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-[#FEFAE0] text-[#783D19] flex items-center justify-center text-xs">2</span>
            <span>Classificação & Roteamento de Fila</span>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Departamento -->
            <div>
                <label for="department" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-2">
                    Departamento de Atendimento <span class="text-red-500">*</span>
                </label>
                <select name="department" 
                        id="department" 
                        required
                        class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-[#5F6F52] focus:ring-[#5F6F52] text-sm">
                    <option value="technical" {{ old('department', $ticket->department) === 'technical' ? 'selected' : '' }}>
                        🛠️ Suporte Técnico (Aplicações, PHP, E-mails)
                    </option>
                    <option value="devops" {{ old('department', $ticket->department) === 'devops' ? 'selected' : '' }}>
                        🚀 DevOps & Infraestrutura (VPS, Docker, OpenResty, SSL)
                    </option>
                    <option value="financial" {{ old('department', $ticket->department) === 'financial' ? 'selected' : '' }}>
                        💳 Financeiro & Faturamento (Boletos, Faturas, Notas Fiscais)
                    </option>
                    <option value="commercial" {{ old('department', $ticket->department) === 'commercial' ? 'selected' : '' }}>
                        🤝 Comercial & Vendas (Novos planos, Upgrades, Consultoria)
                    </option>
                </select>
                @error('department')
                    <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Prioridade -->
            <div>
                <label for="priority" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-2">
                    Nível de Prioridade / Urgência <span class="text-red-500">*</span>
                </label>
                <select name="priority" 
                        id="priority" 
                        required
                        class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-[#5F6F52] focus:ring-[#5F6F52] text-sm">
                    <option value="low" {{ old('priority', $ticket->priority) === 'low' ? 'selected' : '' }}>
                        🟢 Baixa (Dúvidas gerais, configurações menores)
                    </option>
                    <option value="medium" {{ old('priority', $ticket->priority) === 'medium' ? 'selected' : '' }}>
                        🟡 Média (Funcionamento parcial, solicitações normais)
                    </option>
                    <option value="high" {{ old('priority', $ticket->priority) === 'high' ? 'selected' : '' }}>
                        🟠 Alta (Serviço instável, impacto no negócio)
                    </option>
                    <option value="urgent" {{ old('priority', $ticket->priority) === 'urgent' ? 'selected' : '' }}>
                        🔴 Crítica / Urgente (Servidor fora do ar, interrupção total)
                    </option>
                </select>
                @error('priority')
                    <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Bloco 3: Assunto e Descrição -->
    <div class="space-y-6">
        <h3 class="text-sm font-bold text-[#783D19] uppercase tracking-wider mb-2 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-[#FEFAE0] text-[#783D19] flex items-center justify-center text-xs">3</span>
            <span>Mensagem do Chamado</span>
        </h3>

        <!-- Assunto -->
        <div>
            <label for="subject" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-2">
                Assunto do Chamado <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="subject" 
                   id="subject" 
                   value="{{ old('subject', $ticket->subject) }}" 
                   placeholder="Ex: Instalação de certificado SSL Let's Encrypt para meu domínio"
                   required
                   class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-[#5F6F52] focus:ring-[#5F6F52] text-sm">
            @error('subject')
                <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Mensagem Inicial -->
        <div>
            <label for="message" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-2">
                Descrição Detalhada / Mensagem Inicial <span class="text-red-500">*</span>
            </label>
            <textarea name="message" 
                      id="message" 
                      rows="6" 
                      required
                      placeholder="Descreva o problema ou solicitação com o máximo de detalhes para agilizar o atendimento..."
                      class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-[#5F6F52] focus:ring-[#5F6F52] text-sm">{{ old('message') }}</textarea>
            @error('message')
                <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Botões de Ação -->
    <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
        <a href="{{ route('tickets.index') }}" 
           class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-semibold text-xs uppercase tracking-wider hover:bg-gray-50 transition">
            Cancelar
        </a>
        <button type="submit" 
                class="px-6 py-2.5 rounded-xl bg-[#5F6F52] hover:bg-[#783D19] text-white font-bold text-xs uppercase tracking-wider transition-all duration-200 shadow-md">
            Abrir Chamado de Suporte
        </button>
    </div>
</div>
