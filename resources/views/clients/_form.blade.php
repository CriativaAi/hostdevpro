<div class="space-y-6">
    <!-- Grid Nome e Empresa -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div>
            <label for="name" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                Nome do Cliente / Responsável *
            </label>
            <input id="name" name="name" type="text" 
                   class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/15 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition outline-none shadow-inner" 
                   value="{{ old('name', $client->name ?? '') }}" required autofocus placeholder="Ex: João Silva ou Tech Solutions Ltda" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="company" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                Empresa / Nome Fantasia
            </label>
            <input id="company" name="company" type="text" 
                   class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/15 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition outline-none shadow-inner" 
                   value="{{ old('company', $client->company ?? '') }}" placeholder="Ex: Acme Corporation" />
            <x-input-error class="mt-2" :messages="$errors->get('company')" />
        </div>
    </div>

    <!-- Grid E-mail e Telefone -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div>
            <label for="email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                E-mail Comercial *
            </label>
            <input id="email" name="email" type="email" 
                   class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/15 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition outline-none shadow-inner font-mono" 
                   value="{{ old('email', $client->email ?? '') }}" required placeholder="cliente@empresa.com.br" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <label for="phone" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                Telefone / WhatsApp
            </label>
            <input id="phone" name="phone" type="text" 
                   class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/15 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition outline-none shadow-inner" 
                   value="{{ old('phone', $client->phone ?? '') }}" placeholder="(11) 98765-4321" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>
    </div>

    <!-- Status -->
    <div>
        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
            Status da Conta *
        </label>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            @php
                $selectedStatus = old('status', $client->status ?? 'active');
            @endphp
            <label class="relative flex items-center justify-between p-3.5 border rounded-xl cursor-pointer transition {{ $selectedStatus === 'active' ? 'border-emerald-500 bg-emerald-500/10 ring-1 ring-emerald-500/50' : 'border-white/15 bg-slate-900/60 hover:bg-white/[0.04]' }}">
                <div class="flex items-center gap-2.5">
                    <input type="radio" name="status" value="active" {{ $selectedStatus === 'active' ? 'checked' : '' }} class="rounded-full border-white/20 bg-slate-900 text-emerald-500 focus:ring-emerald-400">
                    <div>
                        <span class="block text-sm font-bold text-white">Ativo</span>
                        <span class="block text-xs text-slate-400">Acesso liberado e ativo</span>
                    </div>
                </div>
                <span class="inline-flex items-center rounded-full bg-emerald-500/20 px-2 py-0.5 text-xs font-bold text-emerald-400 border border-emerald-500/30">
                    Ativo
                </span>
            </label>

            <label class="relative flex items-center justify-between p-3.5 border rounded-xl cursor-pointer transition {{ $selectedStatus === 'pending' ? 'border-amber-500 bg-amber-500/10 ring-1 ring-amber-500/50' : 'border-white/15 bg-slate-900/60 hover:bg-white/[0.04]' }}">
                <div class="flex items-center gap-2.5">
                    <input type="radio" name="status" value="pending" {{ $selectedStatus === 'pending' ? 'checked' : '' }} class="rounded-full border-white/20 bg-slate-900 text-amber-500 focus:ring-amber-400">
                    <div>
                        <span class="block text-sm font-bold text-white">Pendente</span>
                        <span class="block text-xs text-slate-400">Aguardando onboarding</span>
                    </div>
                </div>
                <span class="inline-flex items-center rounded-full bg-amber-500/20 px-2 py-0.5 text-xs font-bold text-amber-400 border border-amber-500/30">
                    Pendente
                </span>
            </label>

            <label class="relative flex items-center justify-between p-3.5 border rounded-xl cursor-pointer transition {{ $selectedStatus === 'inactive' ? 'border-slate-500 bg-slate-500/10 ring-1 ring-slate-500/50' : 'border-white/15 bg-slate-900/60 hover:bg-white/[0.04]' }}">
                <div class="flex items-center gap-2.5">
                    <input type="radio" name="status" value="inactive" {{ $selectedStatus === 'inactive' ? 'checked' : '' }} class="rounded-full border-white/20 bg-slate-900 text-slate-500 focus:ring-slate-400">
                    <div>
                        <span class="block text-sm font-bold text-white">Inativo</span>
                        <span class="block text-xs text-slate-400">Acesso suspenso</span>
                    </div>
                </div>
                <span class="inline-flex items-center rounded-full bg-slate-500/20 px-2 py-0.5 text-xs font-bold text-slate-400 border border-slate-500/30">
                    Inativo
                </span>
            </label>
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('status')" />
    </div>

    <!-- Observações -->
    <div>
        <label for="notes" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
            Observações e Detalhes
        </label>
        <textarea id="notes" name="notes" rows="4" 
                  class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/15 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition outline-none shadow-inner" 
                  placeholder="Adicione observações internas sobre este cliente, preferências técnicas, etc.">{{ old('notes', $client->notes ?? '') }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
    </div>
</div>
