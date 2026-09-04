<div class="space-y-6">
    <!-- Cliente & Nome do Projeto -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div>
            <x-input-label for="client_id" :value="__('Cliente / Empresa Proprietária *')" />
            <select id="client_id" name="client_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                <option value="">Selecione um cliente...</option>
                @foreach($clients as $clientItem)
                    <option value="{{ $clientItem->id }}" {{ (string)old('client_id', $project->client_id ?? $clientId ?? '') === (string)$clientItem->id ? 'selected' : '' }}>
                        {{ $clientItem->name }} {{ $clientItem->company ? "({$clientItem->company})" : '' }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('client_id')" />
        </div>

        <div>
            <x-input-label for="name" :value="__('Nome do Projeto / Aplicação *')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $project->name ?? '')" required placeholder="Ex: Portal do Aluno ou E-commerce V1" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
    </div>

    <!-- Tipo de Projeto -->
    <div>
        <x-input-label :value="__('Tipo de Aplicação *')" class="mb-2" />
        @php
            $selectedType = old('type', $project->type ?? 'saas');
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            <label class="flex items-center gap-2 p-3 border rounded-xl cursor-pointer hover:bg-slate-50 transition {{ $selectedType === 'saas' ? 'border-indigo-500 bg-indigo-50/40 ring-1 ring-indigo-500' : 'border-gray-200' }}">
                <input type="radio" name="type" value="saas" {{ $selectedType === 'saas' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                <span class="text-xs font-semibold text-gray-800">SaaS / Web App</span>
            </label>

            <label class="flex items-center gap-2 p-3 border rounded-xl cursor-pointer hover:bg-slate-50 transition {{ $selectedType === 'website' ? 'border-indigo-500 bg-indigo-50/40 ring-1 ring-indigo-500' : 'border-gray-200' }}">
                <input type="radio" name="type" value="website" {{ $selectedType === 'website' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                <span class="text-xs font-semibold text-gray-800">Website Institucional</span>
            </label>

            <label class="flex items-center gap-2 p-3 border rounded-xl cursor-pointer hover:bg-slate-50 transition {{ $selectedType === 'ecommerce' ? 'border-indigo-500 bg-indigo-50/40 ring-1 ring-indigo-500' : 'border-gray-200' }}">
                <input type="radio" name="type" value="ecommerce" {{ $selectedType === 'ecommerce' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                <span class="text-xs font-semibold text-gray-800">E-commerce / Loja</span>
            </label>

            <label class="flex items-center gap-2 p-3 border rounded-xl cursor-pointer hover:bg-slate-50 transition {{ $selectedType === 'api' ? 'border-indigo-500 bg-indigo-50/40 ring-1 ring-indigo-500' : 'border-gray-200' }}">
                <input type="radio" name="type" value="api" {{ $selectedType === 'api' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                <span class="text-xs font-semibold text-gray-800">API / Microserviço</span>
            </label>

            <label class="flex items-center gap-2 p-3 border rounded-xl cursor-pointer hover:bg-slate-50 transition {{ $selectedType === 'landing_page' ? 'border-indigo-500 bg-indigo-50/40 ring-1 ring-indigo-500' : 'border-gray-200' }}">
                <input type="radio" name="type" value="landing_page" {{ $selectedType === 'landing_page' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                <span class="text-xs font-semibold text-gray-800">Landing Page</span>
            </label>

            <label class="flex items-center gap-2 p-3 border rounded-xl cursor-pointer hover:bg-slate-50 transition {{ $selectedType === 'mobile_app' ? 'border-indigo-500 bg-indigo-50/40 ring-1 ring-indigo-500' : 'border-gray-200' }}">
                <input type="radio" name="type" value="mobile_app" {{ $selectedType === 'mobile_app' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                <span class="text-xs font-semibold text-gray-800">Aplicativo Mobile</span>
            </label>
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('type')" />
    </div>

    <!-- Status do Projeto -->
    <div>
        <x-input-label :value="__('Status do Projeto *')" class="mb-2" />
        @php
            $selectedStatus = old('status', $project->status ?? 'development');
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5">
            <label class="flex items-center gap-2 p-2.5 border rounded-xl cursor-pointer hover:bg-slate-50 transition {{ $selectedStatus === 'planning' ? 'border-sky-500 bg-sky-50/40 ring-1 ring-sky-500' : 'border-gray-200' }}">
                <input type="radio" name="status" value="planning" {{ $selectedStatus === 'planning' ? 'checked' : '' }} class="text-sky-600 focus:ring-sky-500">
                <span class="text-xs font-semibold text-gray-800">Planejamento</span>
            </label>

            <label class="flex items-center gap-2 p-2.5 border rounded-xl cursor-pointer hover:bg-slate-50 transition {{ $selectedStatus === 'development' ? 'border-indigo-500 bg-indigo-50/40 ring-1 ring-indigo-500' : 'border-gray-200' }}">
                <input type="radio" name="status" value="development" {{ $selectedStatus === 'development' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                <span class="text-xs font-semibold text-gray-800">Desenvolvimento</span>
            </label>

            <label class="flex items-center gap-2 p-2.5 border rounded-xl cursor-pointer hover:bg-slate-50 transition {{ $selectedStatus === 'staging' ? 'border-amber-500 bg-amber-50/40 ring-1 ring-amber-500' : 'border-gray-200' }}">
                <input type="radio" name="status" value="staging" {{ $selectedStatus === 'staging' ? 'checked' : '' }} class="text-amber-600 focus:ring-amber-500">
                <span class="text-xs font-semibold text-gray-800">Homologação</span>
            </label>

            <label class="flex items-center gap-2 p-2.5 border rounded-xl cursor-pointer hover:bg-slate-50 transition {{ $selectedStatus === 'production' ? 'border-emerald-500 bg-emerald-50/40 ring-1 ring-emerald-500' : 'border-gray-200' }}">
                <input type="radio" name="status" value="production" {{ $selectedStatus === 'production' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                <span class="text-xs font-semibold text-gray-800">Produção</span>
            </label>

            <label class="flex items-center gap-2 p-2.5 border rounded-xl cursor-pointer hover:bg-slate-50 transition {{ $selectedStatus === 'maintenance' ? 'border-purple-500 bg-purple-50/40 ring-1 ring-purple-500' : 'border-gray-200' }}">
                <input type="radio" name="status" value="maintenance" {{ $selectedStatus === 'maintenance' ? 'checked' : '' }} class="text-purple-600 focus:ring-purple-500">
                <span class="text-xs font-semibold text-gray-800">Manutenção</span>
            </label>
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('status')" />
    </div>

    <!-- URLs: Repositório, Produção e Homologação -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
        <div>
            <x-input-label for="repository_url" :value="__('Repositório Git (GitHub / GitLab)')" />
            <x-text-input id="repository_url" name="repository_url" type="url" class="mt-1 block w-full text-sm" :value="old('repository_url', $project->repository_url ?? '')" placeholder="https://github.com/empresa/repo" />
            <x-input-error class="mt-2" :messages="$errors->get('repository_url')" />
        </div>

        <div>
            <x-input-label for="production_url" :value="__('URL de Produção (Online)')" />
            <x-text-input id="production_url" name="production_url" type="url" class="mt-1 block w-full text-sm" :value="old('production_url', $project->production_url ?? '')" placeholder="https://app.cliente.com.br" />
            <x-input-error class="mt-2" :messages="$errors->get('production_url')" />
        </div>

        <div>
            <x-input-label for="staging_url" :value="__('URL de Homologação / Staging')" />
            <x-text-input id="staging_url" name="staging_url" type="url" class="mt-1 block w-full text-sm" :value="old('staging_url', $project->staging_url ?? '')" placeholder="https://staging.cliente.com.br" />
            <x-input-error class="mt-2" :messages="$errors->get('staging_url')" />
        </div>
    </div>

    <!-- Stack Tecnológica -->
    <div>
        <x-input-label for="tech_stack" :value="__('Stack Tecnológica (separar por vírgula)')" />
        <x-text-input id="tech_stack" name="tech_stack" type="text" class="mt-1 block w-full text-sm" :value="old('tech_stack', $project->tech_stack ?? '')" placeholder="Ex: Laravel 13, Vue.js, Tailwind CSS, PostgreSQL, Docker, Redis" />
        <p class="mt-1 text-xs text-gray-500">As tecnologias serão exibidas em formato de tags no painel.</p>
        <x-input-error class="mt-2" :messages="$errors->get('tech_stack')" />
    </div>

    <!-- Descrição / Escopo -->
    <div>
        <x-input-label for="description" :value="__('Descrição e Escopo do Projeto')" />
        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Descreva os objetivos do projeto, integrações necessárias, escopo e requisitos técnicos principais.">{{ old('description', $project->description ?? '') }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>
</div>
