@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Cliente Proprietário -->
    <div>
        <label for="client_id" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-2">
            Cliente Proprietário *
        </label>
        <select name="client_id" 
                id="client_id" 
                required
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 bg-gray-50/50 focus:bg-white focus:border-[#C4661F] focus:ring-2 focus:ring-[#C4661F]/20 text-sm transition outline-none shadow-sm @error('client_id') border-red-500 @enderror">
            <option value="">Selecione um cliente...</option>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}" @selected(old('client_id', $hosting->client_id ?? ($selectedClientId ?? '')) == $client->id)>
                    {{ $client->name }} ({{ $client->company ?? 'Pessoa Física' }})
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('client_id')" class="mt-1" />
    </div>

    <!-- Servidor VPS -->
    <div>
        <label for="server_id" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-2">
            Servidor / Nó de Hospedagem *
        </label>
        <select name="server_id" 
                id="server_id" 
                required
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 bg-gray-50/50 focus:bg-white focus:border-[#C4661F] focus:ring-2 focus:ring-[#C4661F]/20 text-sm transition outline-none shadow-sm @error('server_id') border-red-500 @enderror">
            <option value="">Selecione o servidor...</option>
            @foreach ($servers as $srv)
                <option value="{{ $srv->id }}" @selected(old('server_id', $hosting->server_id ?? ($selectedServerId ?? '')) == $srv->id)>
                    {{ $srv->name }} ({{ $srv->ip_address }} • {{ $srv->datacenter_location }})
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('server_id')" class="mt-1" />
    </div>

    <!-- Domínio -->
    <div>
        <label for="domain" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-2">
            Domínio Principal *
        </label>
        <input type="text" 
               name="domain" 
               id="domain" 
               value="{{ old('domain', $hosting->domain ?? '') }}" 
               required
               placeholder="Ex: seudominio.com.br"
               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 bg-gray-50/50 focus:bg-white focus:border-[#C4661F] focus:ring-2 focus:ring-[#C4661F]/20 text-sm transition outline-none shadow-sm font-mono @error('domain') border-red-500 @enderror">
        <x-input-error :messages="$errors->get('domain')" class="mt-1" />
    </div>

    <!-- Username de Sistema / SFTP -->
    <div>
        <label for="username" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-2">
            Usuário de Sistema (SFTP / Linux)
        </label>
        <input type="text" 
               name="username" 
               id="username" 
               value="{{ old('username', $hosting->username ?? '') }}" 
               placeholder="Ex: usr_dominio"
               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 bg-gray-50/50 focus:bg-white focus:border-[#C4661F] focus:ring-2 focus:ring-[#C4661F]/20 text-sm transition outline-none shadow-sm font-mono @error('username') border-red-500 @enderror">
        <x-input-error :messages="$errors->get('username')" class="mt-1" />
    </div>

    <!-- Plano de Hospedagem -->
    <div>
        <label for="plan" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-2">
            Plano de Recursos *
        </label>
        <select name="plan" 
                id="plan" 
                required
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 bg-gray-50/50 focus:bg-white focus:border-[#C4661F] focus:ring-2 focus:ring-[#C4661F]/20 text-sm transition outline-none shadow-sm @error('plan') border-red-500 @enderror">
            <option value="{{ \App\Models\HostingAccount::PLAN_BASIC }}" @selected(old('plan', $hosting->plan ?? '') === \App\Models\HostingAccount::PLAN_BASIC)>Start (5 GB NVMe • 30 GB Tráfego)</option>
            <option value="{{ \App\Models\HostingAccount::PLAN_PRO }}" @selected(old('plan', $hosting->plan ?? 'pro') === \App\Models\HostingAccount::PLAN_PRO)>Pro (15 GB NVMe • 100 GB Tráfego)</option>
            <option value="{{ \App\Models\HostingAccount::PLAN_ENTERPRISE }}" @selected(old('plan', $hosting->plan ?? '') === \App\Models\HostingAccount::PLAN_ENTERPRISE)>Enterprise (50 GB NVMe • 500 GB Tráfego)</option>
        </select>
        <x-input-error :messages="$errors->get('plan')" class="mt-1" />
    </div>

    <!-- Versão do PHP -->
    <div>
        <label for="php_version" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-2">
            Versão do Runtime PHP *
        </label>
        <select name="php_version" 
                id="php_version" 
                required
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 bg-gray-50/50 focus:bg-white focus:border-[#C4661F] focus:ring-2 focus:ring-[#C4661F]/20 text-sm transition outline-none shadow-sm font-mono @error('php_version') border-red-500 @enderror">
            <option value="8.5" @selected(old('php_version', $hosting->php_version ?? '8.5') === '8.5')>PHP 8.5 (Recomendado - Mais Recente)</option>
            <option value="8.4" @selected(old('php_version', $hosting->php_version ?? '') === '8.4')>PHP 8.4 (LTS)</option>
            <option value="8.3" @selected(old('php_version', $hosting->php_version ?? '') === '8.3')>PHP 8.3</option>
            <option value="8.2" @selected(old('php_version', $hosting->php_version ?? '') === '8.2')>PHP 8.2</option>
        </select>
        <x-input-error :messages="$errors->get('php_version')" class="mt-1" />
    </div>

    <!-- Cota de Disco (MB) -->
    <div>
        <label for="disk_quota_mb" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-2">
            Cota de Disco (MB) *
        </label>
        <input type="number" 
               name="disk_quota_mb" 
               id="disk_quota_mb" 
               value="{{ old('disk_quota_mb', $hosting->disk_quota_mb ?? 15360) }}" 
               required
               min="512"
               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 bg-gray-50/50 focus:bg-white focus:border-[#C4661F] focus:ring-2 focus:ring-[#C4661F]/20 text-sm transition outline-none shadow-sm font-mono @error('disk_quota_mb') border-red-500 @enderror">
        <p class="text-[11px] text-gray-400 mt-1">Ex: 5120 = 5GB, 15360 = 15GB, 51200 = 50GB</p>
        <x-input-error :messages="$errors->get('disk_quota_mb')" class="mt-1" />
    </div>

    <!-- Tráfego Mensal (MB) -->
    <div>
        <label for="bandwidth_quota_mb" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-2">
            Cota de Tráfego Mensal (MB) *
        </label>
        <input type="number" 
               name="bandwidth_quota_mb" 
               id="bandwidth_quota_mb" 
               value="{{ old('bandwidth_quota_mb', $hosting->bandwidth_quota_mb ?? 100000) }}" 
               required
               min="1000"
               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 bg-gray-50/50 focus:bg-white focus:border-[#C4661F] focus:ring-2 focus:ring-[#C4661F]/20 text-sm transition outline-none shadow-sm font-mono @error('bandwidth_quota_mb') border-red-500 @enderror">
        <x-input-error :messages="$errors->get('bandwidth_quota_mb')" class="mt-1" />
    </div>

    <!-- Status do Certificado SSL -->
    <div>
        <label for="ssl_status" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-2">
            Certificado SSL Let's Encrypt *
        </label>
        <select name="ssl_status" 
                id="ssl_status" 
                required
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 bg-gray-50/50 focus:bg-white focus:border-[#C4661F] focus:ring-2 focus:ring-[#C4661F]/20 text-sm transition outline-none shadow-sm @error('ssl_status') border-red-500 @enderror">
            <option value="{{ \App\Models\HostingAccount::SSL_ACTIVE }}" @selected(old('ssl_status', $hosting->ssl_status ?? 'active') === \App\Models\HostingAccount::SSL_ACTIVE)>🔒 Ativo e Renovado</option>
            <option value="{{ \App\Models\HostingAccount::SSL_PENDING }}" @selected(old('ssl_status', $hosting->ssl_status ?? '') === \App\Models\HostingAccount::SSL_PENDING)>⏳ Pendente (Aguardando DNS)</option>
            <option value="{{ \App\Models\HostingAccount::SSL_EXPIRED }}" @selected(old('ssl_status', $hosting->ssl_status ?? '') === \App\Models\HostingAccount::SSL_EXPIRED)>⚠️ Expirado</option>
            <option value="{{ \App\Models\HostingAccount::SSL_NONE }}" @selected(old('ssl_status', $hosting->ssl_status ?? '') === \App\Models\HostingAccount::SSL_NONE)>❌ Desativado</option>
        </select>
        <x-input-error :messages="$errors->get('ssl_status')" class="mt-1" />
    </div>

    <!-- Status da Conta de Hospedagem -->
    <div>
        <label for="status" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-2">
            Status da Conta *
        </label>
        <select name="status" 
                id="status" 
                required
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 bg-gray-50/50 focus:bg-white focus:border-[#C4661F] focus:ring-2 focus:ring-[#C4661F]/20 text-sm transition outline-none shadow-sm @error('status') border-red-500 @enderror">
            <option value="{{ \App\Models\HostingAccount::STATUS_ACTIVE }}" @selected(old('status', $hosting->status ?? 'active') === \App\Models\HostingAccount::STATUS_ACTIVE)>🟢 Ativa</option>
            <option value="{{ \App\Models\HostingAccount::STATUS_SUSPENDED }}" @selected(old('status', $hosting->status ?? '') === \App\Models\HostingAccount::STATUS_SUSPENDED)>🔴 Suspensa</option>
            <option value="{{ \App\Models\HostingAccount::STATUS_PENDING }}" @selected(old('status', $hosting->status ?? '') === \App\Models\HostingAccount::STATUS_PENDING)>🟡 Pendente de Configuração</option>
            <option value="{{ \App\Models\HostingAccount::STATUS_TERMINATED }}" @selected(old('status', $hosting->status ?? '') === \App\Models\HostingAccount::STATUS_TERMINATED)>⚪ Cancelada</option>
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-1" />
    </div>

    <!-- Motivo da Suspensão -->
    <div class="md:col-span-2">
        <label for="suspended_reason" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-2">
            Motivo da Suspensão (Se aplicável)
        </label>
        <input type="text" 
               name="suspended_reason" 
               id="suspended_reason" 
               value="{{ old('suspended_reason', $hosting->suspended_reason ?? '') }}" 
               placeholder="Ex: Inadimplência da fatura #103, violação de termos de uso..."
               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 bg-gray-50/50 focus:bg-white focus:border-[#C4661F] focus:ring-2 focus:ring-[#C4661F]/20 text-sm transition outline-none shadow-sm @error('suspended_reason') border-red-500 @enderror">
        <x-input-error :messages="$errors->get('suspended_reason')" class="mt-1" />
    </div>

    <!-- Notas Internas -->
    <div class="md:col-span-2">
        <label for="notes" class="block text-xs font-bold text-[#5F6F52] uppercase tracking-wider mb-2">
            Notas Técnicas & Informações do Domínio
        </label>
        <textarea name="notes" 
                  id="notes" 
                  rows="3" 
                  placeholder="Anotações de apontamento DNS, MX na ValueHost, banco de dados ou observações de atendimento..."
                  class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 bg-gray-50/50 focus:bg-white focus:border-[#C4661F] focus:ring-2 focus:ring-[#C4661F]/20 text-sm transition outline-none shadow-sm @error('notes') border-red-500 @enderror">{{ old('notes', $hosting->notes ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('notes')" class="mt-1" />
    </div>
</div>

<div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
    <a href="{{ route('hosting.index') }}" 
       class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-semibold text-xs uppercase tracking-wider hover:bg-gray-50 transition">
        Cancelar
    </a>
    <button type="submit" 
            class="px-6 py-2.5 rounded-xl bg-[#5F6F52] hover:bg-[#48563e] text-white font-bold text-xs uppercase tracking-wider shadow-md hover:shadow-lg transition flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span>Salvar Conta</span>
    </button>
</div>
