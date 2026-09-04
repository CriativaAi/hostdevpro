<div class="space-y-6">
    <!-- Grid Nome e Empresa -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div>
            <x-input-label for="name" :value="__('Nome do Cliente / Responsável *')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $client->name ?? '')" required autofocus placeholder="Ex: João Silva ou Tech Solutions Ltda" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="company" :value="__('Empresa / Nome Fantasia')" />
            <x-text-input id="company" name="company" type="text" class="mt-1 block w-full" :value="old('company', $client->company ?? '')" placeholder="Ex: Acme Corporation" />
            <x-input-error class="mt-2" :messages="$errors->get('company')" />
        </div>
    </div>

    <!-- Grid E-mail e Telefone -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div>
            <x-input-label for="email" :value="__('E-mail Comercial *')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $client->email ?? '')" required placeholder="cliente@empresa.com.br" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="phone" :value="__('Telefone / WhatsApp')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $client->phone ?? '')" placeholder="(11) 98765-4321" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>
    </div>

    <!-- Status -->
    <div>
        <x-input-label :value="__('Status da Conta *')" class="mb-2" />
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            @php
                $selectedStatus = old('status', $client->status ?? 'active');
            @endphp
            <label class="relative flex items-center justify-between p-3.5 border rounded-xl cursor-pointer hover:bg-slate-50 transition {{ $selectedStatus === 'active' ? 'border-emerald-500 bg-emerald-50/40 ring-1 ring-emerald-500' : 'border-gray-200' }}">
                <div class="flex items-center gap-2.5">
                    <input type="radio" name="status" value="active" {{ $selectedStatus === 'active' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                    <div>
                        <span class="block text-sm font-semibold text-gray-900">Ativo</span>
                        <span class="block text-xs text-gray-500">Acesso liberado e ativo</span>
                    </div>
                </div>
                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-800">
                    Ativo
                </span>
            </label>

            <label class="relative flex items-center justify-between p-3.5 border rounded-xl cursor-pointer hover:bg-slate-50 transition {{ $selectedStatus === 'pending' ? 'border-amber-500 bg-amber-50/40 ring-1 ring-amber-500' : 'border-gray-200' }}">
                <div class="flex items-center gap-2.5">
                    <input type="radio" name="status" value="pending" {{ $selectedStatus === 'pending' ? 'checked' : '' }} class="text-amber-600 focus:ring-amber-500">
                    <div>
                        <span class="block text-sm font-semibold text-gray-900">Pendente</span>
                        <span class="block text-xs text-gray-500">Aguardando onboarding</span>
                    </div>
                </div>
                <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800">
                    Pendente
                </span>
            </label>

            <label class="relative flex items-center justify-between p-3.5 border rounded-xl cursor-pointer hover:bg-slate-50 transition {{ $selectedStatus === 'inactive' ? 'border-gray-400 bg-gray-50 ring-1 ring-gray-400' : 'border-gray-200' }}">
                <div class="flex items-center gap-2.5">
                    <input type="radio" name="status" value="inactive" {{ $selectedStatus === 'inactive' ? 'checked' : '' }} class="text-gray-600 focus:ring-gray-500">
                    <div>
                        <span class="block text-sm font-semibold text-gray-900">Inativo</span>
                        <span class="block text-xs text-gray-500">Acesso suspenso</span>
                    </div>
                </div>
                <span class="inline-flex items-center rounded-full bg-gray-200 px-2 py-0.5 text-xs font-medium text-gray-800">
                    Inativo
                </span>
            </label>
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('status')" />
    </div>

    <!-- Observações -->
    <div>
        <x-input-label for="notes" :value="__('Observações e Detalhes')" />
        <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Adicione observações internas sobre este cliente, preferências técnicas, etc.">{{ old('notes', $client->notes ?? '') }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
    </div>
</div>
