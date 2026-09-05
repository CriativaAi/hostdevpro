@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Nome do Servidor -->
    <div class="md:col-span-2">
        <label for="name" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
            Identificação do Servidor *
        </label>
        <input type="text" 
               name="name" 
               id="name" 
               value="{{ old('name', $server->name ?? '') }}" 
               required
               placeholder="Ex: VPS Integrator Master 01, Cloud Hetzner Node 02"
               class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/15 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition outline-none shadow-inner @error('name') border-red-500 @enderror">
        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>

    <!-- IP Address -->
    <div>
        <label for="ip_address" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
            Endereço IP (IPv4) *
        </label>
        <input type="text" 
               name="ip_address" 
               id="ip_address" 
               value="{{ old('ip_address', $server->ip_address ?? '') }}" 
               required
               placeholder="Ex: 209.50.245.45"
               class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/15 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition outline-none shadow-inner font-mono @error('ip_address') border-red-500 @enderror">
        <x-input-error :messages="$errors->get('ip_address')" class="mt-1" />
    </div>

    <!-- Hostname -->
    <div>
        <label for="hostname" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
            Hostname
        </label>
        <input type="text" 
               name="hostname" 
               id="hostname" 
               value="{{ old('hostname', $server->hostname ?? '') }}" 
               placeholder="Ex: app.hostdevpro.app.br"
               class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/15 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition outline-none shadow-inner font-mono @error('hostname') border-red-500 @enderror">
        <x-input-error :messages="$errors->get('hostname')" class="mt-1" />
    </div>

    <!-- Provider -->
    <div>
        <label for="provider" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
            Provedor de Hospedagem / Cloud
        </label>
        <input type="text" 
               name="provider" 
               id="provider" 
               value="{{ old('provider', $server->provider ?? 'Integrator Host') }}" 
               placeholder="Ex: Integrator Host, Hetzner, AWS, DigitalOcean"
               class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/15 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition outline-none shadow-inner @error('provider') border-red-500 @enderror">
        <x-input-error :messages="$errors->get('provider')" class="mt-1" />
    </div>

    <!-- Datacenter Location -->
    <div>
        <label for="datacenter_location" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
            Localização do Datacenter
        </label>
        <input type="text" 
               name="datacenter_location" 
               id="datacenter_location" 
               value="{{ old('datacenter_location', $server->datacenter_location ?? 'São Paulo - Brasil (BR)') }}" 
               placeholder="Ex: São Paulo - Brasil (BR), Falkenstein - DE"
               class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/15 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition outline-none shadow-inner @error('datacenter_location') border-red-500 @enderror">
        <x-input-error :messages="$errors->get('datacenter_location')" class="mt-1" />
    </div>

    <!-- OS -->
    <div>
        <label for="os" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
            Sistema Operacional
        </label>
        <input type="text" 
               name="os" 
               id="os" 
               value="{{ old('os', $server->os ?? 'Ubuntu 24.04 LTS (Docker/OpenResty)') }}" 
               placeholder="Ex: Ubuntu 24.04 LTS, Debian 12, Alpine Linux"
               class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/15 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition outline-none shadow-inner @error('os') border-red-500 @enderror">
        <x-input-error :messages="$errors->get('os')" class="mt-1" />
    </div>

    <!-- SSH Port -->
    <div>
        <label for="ssh_port" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
            Porta SSH *
        </label>
        <input type="number" 
               name="ssh_port" 
               id="ssh_port" 
               value="{{ old('ssh_port', $server->ssh_port ?? 22) }}" 
               required
               class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/15 text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition outline-none shadow-inner @error('ssh_port') border-red-500 @enderror">
        <x-input-error :messages="$errors->get('ssh_port')" class="mt-1" />
    </div>

    <!-- CPU Cores -->
    <div>
        <label for="cpu_cores" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
            vCPU Cores *
        </label>
        <input type="number" 
               name="cpu_cores" 
               id="cpu_cores" 
               value="{{ old('cpu_cores', $server->cpu_cores ?? 4) }}" 
               required
               min="1" max="128"
               class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/15 text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition outline-none shadow-inner @error('cpu_cores') border-red-500 @enderror">
        <x-input-error :messages="$errors->get('cpu_cores')" class="mt-1" />
    </div>

    <!-- RAM (MB) -->
    <div>
        <label for="ram_mb" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
            Memória RAM (MB) *
        </label>
        <div class="relative">
            <input type="number" 
                   name="ram_mb" 
                   id="ram_mb" 
                   value="{{ old('ram_mb', $server->ram_mb ?? 8192) }}" 
                   required
                   min="512" step="512"
                   class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/15 text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition outline-none shadow-inner @error('ram_mb') border-red-500 @enderror">
            <span class="absolute right-4 top-2.5 text-xs font-bold text-slate-400">MB</span>
        </div>
        <p class="text-[11px] text-slate-400 mt-1">4096 = 4GB, 8192 = 8GB, 16384 = 16GB</p>
        <x-input-error :messages="$errors->get('ram_mb')" class="mt-1" />
    </div>

    <!-- Disk (GB) -->
    <div>
        <label for="disk_gb" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
            Armazenamento NVMe/SSD (GB) *
        </label>
        <div class="relative">
            <input type="number" 
                   name="disk_gb" 
                   id="disk_gb" 
                   value="{{ old('disk_gb', $server->disk_gb ?? 160) }}" 
                   required
                   min="10"
                   class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/15 text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition outline-none shadow-inner @error('disk_gb') border-red-500 @enderror">
            <span class="absolute right-4 top-2.5 text-xs font-bold text-slate-400">GB</span>
        </div>
        <x-input-error :messages="$errors->get('disk_gb')" class="mt-1" />
    </div>

    <!-- Status -->
    <div>
        <label for="status" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
            Status Operacional *
        </label>
        <select name="status" 
                id="status" 
                required
                class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/15 text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition outline-none shadow-inner @error('status') border-red-500 @enderror">
            <option value="{{ \App\Models\Server::STATUS_ONLINE }}" class="bg-slate-900 text-white" @selected(old('status', $server->status ?? '') === \App\Models\Server::STATUS_ONLINE)>🟢 Online (Operacional)</option>
            <option value="{{ \App\Models\Server::STATUS_MAINTENANCE }}" class="bg-slate-900 text-white" @selected(old('status', $server->status ?? '') === \App\Models\Server::STATUS_MAINTENANCE)>🟡 Em Manutenção Programada</option>
            <option value="{{ \App\Models\Server::STATUS_OFFLINE }}" class="bg-slate-900 text-white" @selected(old('status', $server->status ?? '') === \App\Models\Server::STATUS_OFFLINE)>🔴 Offline (Indisponível)</option>
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-1" />
    </div>

    <!-- Observações / Anotações Internas -->
    <div class="md:col-span-2">
        <label for="notes" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
            Notas Técnicas & Detalhes da Instância
        </label>
        <textarea name="notes" 
                  id="notes" 
                  rows="3" 
                  placeholder="Informações de configuração, contêineres instalados, credenciais de acesso ou políticas específicas deste nó..."
                  class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/15 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition outline-none shadow-inner @error('notes') border-red-500 @enderror">{{ old('notes', $server->notes ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('notes')" class="mt-1" />
    </div>
</div>

<div class="mt-8 pt-6 border-t border-white/10 flex items-center justify-end gap-3">
    <a href="{{ route('servers.index') }}" 
       class="px-5 py-2.5 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider transition">
        Cancelar
    </a>
    <button type="submit" 
            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 transition flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span>Salvar Servidor</span>
    </button>
</div>
