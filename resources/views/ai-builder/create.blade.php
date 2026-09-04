<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-2xl text-white tracking-tight leading-tight flex items-center gap-2.5">
                    <span>Novo Site com IA</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-purple-500/20 text-purple-300 border border-purple-500/30 font-bold">
                        Gemini 3.6 Flash
                    </span>
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Responda as informações básicas abaixo para a inteligência artificial desenhar sua landing page completa.
                </p>
            </div>
            <a href="{{ route('ai-builder.index') }}" 
               class="px-4 py-2 rounded-xl bg-white/[0.06] hover:bg-white/[0.12] border border-white/15 text-slate-300 hover:text-white font-bold text-xs uppercase tracking-wider transition">
                &larr; Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-950/40 border border-rose-500/40 text-rose-300 text-xs shadow-xl backdrop-blur-xl space-y-1">
                    <div class="font-bold flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Atenção: verifique os campos abaixo</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-rose-300/90 pl-2">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('ai-builder.store') }}" id="ai-generator-form" class="space-y-6">
                @csrf

                <!-- Bloco 1: Identidade do Negócio -->
                <div class="p-6 sm:p-8 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-xl space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-white/10">
                        <div class="w-8 h-8 rounded-xl bg-purple-500/20 border border-purple-500/40 text-purple-400 flex items-center justify-center font-bold text-sm">
                            1
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">Identidade da Empresa</h3>
                            <p class="text-xs text-slate-400">Qual é o nome do seu projeto e área de especialidade?</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Nome da Empresa -->
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                                Nome da Empresa / Marca *
                            </label>
                            <input type="text" 
                                   name="business_name" 
                                   id="business_name"
                                   required
                                   value="{{ old('business_name') }}" 
                                   placeholder="Ex: Oficina Mecânica Silva, Dra. Carla Odonto..." 
                                   class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white placeholder-slate-500 text-sm focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 outline-none transition">
                        </div>

                        <!-- Nicho / Ramo -->
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                                Nicho de Atuação *
                            </label>
                            <input type="text" 
                                   name="niche" 
                                   id="niche"
                                   required
                                   value="{{ old('niche') }}" 
                                   placeholder="Ex: Barbearia Moderna, Advocacia Previdenciária..." 
                                   class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white placeholder-slate-500 text-sm focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 outline-none transition">
                        </div>
                    </div>

                    <!-- Presets Rápidos de Nicho -->
                    <div>
                        <span class="text-[11px] text-slate-400 font-semibold block mb-2">Ou selecione um modelo pronto de nicho:</span>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $presets = [
                                    ['label' => '💈 Barbearia & Salão', 'niche' => 'Barbearia e Estética Masculina', 'desc' => 'Cortes na tesoura e máquina, barba com toalha quente, pigmentação, cerveja artesanal e agendamento online.'],
                                    ['label' => '⚖️ Advocacia & Jurídico', 'niche' => 'Escritório de Advocacia', 'desc' => 'Especialistas em direito civil, trabalhista e previdenciário com atendimento consultivo e ágil.'],
                                    ['label' => '🍕 Restaurante & Delivery', 'niche' => 'Restaurante e Gastronomia', 'desc' => 'Pratos artesanais, ingredientes frescos selecionados, ambiente acolhedor e entrega rápida.'],
                                    ['label' => '🚗 Oficina & Auto Elétrica', 'niche' => 'Oficina Mecânica e Auto Center', 'desc' => 'Revisão automotiva preventiva, freios, suspensão, injeção eletrônica e diagnósticos computadorizados.'],
                                    ['label' => '🩺 Clínica & Saúde', 'niche' => 'Clínica Médica e Odontologia', 'desc' => 'Consultas especializadas, exames modernos, ambiente confortável e profissionais certificados.'],
                                    ['label' => '💻 Agência & Software Dev', 'niche' => 'Agência de Desenvolvimento e Marketing', 'desc' => 'Criação de aplicativos, websites de alta performance, tráfego pago e automações em nuvem.']
                                ];
                            @endphp
                            @foreach ($presets as $p)
                                <button type="button" 
                                        onclick="applyPreset('{{ $p['niche'] }}', '{{ addslashes($p['desc']) }}')"
                                        class="px-3 py-1.5 rounded-lg bg-white/[0.04] hover:bg-purple-500/20 border border-white/10 hover:border-purple-500/40 text-slate-300 hover:text-purple-300 text-xs transition">
                                    {{ $p['label'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Bloco 2: Diferenciais & Contato -->
                <div class="p-6 sm:p-8 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-xl space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-white/10">
                        <div class="w-8 h-8 rounded-xl bg-purple-500/20 border border-purple-500/40 text-purple-400 flex items-center justify-center font-bold text-sm">
                            2
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">Serviços e Contato de Conversão</h3>
                            <p class="text-xs text-slate-400">Conte o que você oferece e onde os clientes vão te chamar.</p>
                        </div>
                    </div>

                    <!-- Descrição dos Diferenciais -->
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                            Diferenciais, Serviços e Ofertas
                        </label>
                        <textarea name="description" 
                                  id="description"
                                  rows="3" 
                                  placeholder="Descreva brevemente os produtos/serviços, anos de experiência, diferenciais ou garantias..." 
                                  class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white placeholder-slate-500 text-sm focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 outline-none transition">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- WhatsApp -->
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                                WhatsApp para Vendas / Agendamento *
                            </label>
                            <input type="text" 
                                   name="whatsapp" 
                                   value="{{ old('whatsapp', '11921381308') }}" 
                                   placeholder="Ex: 11 92138-1308" 
                                   class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white placeholder-slate-500 text-sm focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 outline-none transition font-mono">
                            <span class="text-[11px] text-slate-400 mt-1 block">A IA criará botões de WhatsApp de alta conversão diretamente para este número.</span>
                        </div>

                        <!-- Hospedagem Vinculada (Opcional) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                                Vincular à Conta de Hospedagem (Opcional)
                            </label>
                            <select name="hosting_account_id" class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-slate-200 outline-none focus:border-purple-500">
                                <option value="" class="bg-slate-900 text-slate-400">Nenhuma (Gerar avulso para download)</option>
                                @foreach ($hostingAccounts as $acc)
                                    <option value="{{ $acc->id }}" 
                                            @selected(old('hosting_account_id', $selectedHostingId) == $acc->id)
                                            class="bg-slate-900 text-white">
                                        {{ $acc->domain }} ({{ $acc->client->name }})
                                    </option>
                                @endforeach
                            </select>
                            <span class="text-[11px] text-slate-400 mt-1 block">Selecione o domínio do cliente caso queira publicar com 1 clique no futuro.</span>
                        </div>
                    </div>
                </div>

                <!-- Bloco 3: Estilo Visual & Seções -->
                <div class="p-6 sm:p-8 rounded-2xl bg-white/[0.06] backdrop-blur-2xl border border-white/15 shadow-xl space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-white/10">
                        <div class="w-8 h-8 rounded-xl bg-purple-500/20 border border-purple-500/40 text-purple-400 flex items-center justify-center font-bold text-sm">
                            3
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">Estilo Visual & Layout</h3>
                            <p class="text-xs text-slate-400">Escolha a paleta de cores e a identidade estética do site.</p>
                        </div>
                    </div>

                    <!-- Seletor de Estilos -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" x-data="{ selectedStyle: '{{ old('style', 'dark_frosted') }}' }">
                        
                        <!-- Dark Frosted -->
                        <label @click="selectedStyle = 'dark_frosted'" 
                               :class="selectedStyle === 'dark_frosted' ? 'border-purple-500 bg-purple-500/10 ring-2 ring-purple-500/30' : 'border-white/10 bg-black/30 hover:border-white/20'"
                               class="p-4 rounded-xl border cursor-pointer transition flex flex-col justify-between">
                            <input type="radio" name="style" value="dark_frosted" class="sr-only" x-model="selectedStyle">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold text-white">Dark Frosted</span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                                </div>
                                <p class="text-[11px] text-slate-400">Visual HostDevPro com vidro escuro fosco, bordas neon e alta modernidade.</p>
                            </div>
                            <div class="mt-3 flex gap-1.5">
                                <span class="w-4 h-4 rounded-full bg-slate-950 border border-slate-700"></span>
                                <span class="w-4 h-4 rounded-full bg-emerald-500"></span>
                                <span class="w-4 h-4 rounded-full bg-cyan-400"></span>
                            </div>
                        </label>

                        <!-- Clean Minimal -->
                        <label @click="selectedStyle = 'clean_minimal'" 
                               :class="selectedStyle === 'clean_minimal' ? 'border-purple-500 bg-purple-500/10 ring-2 ring-purple-500/30' : 'border-white/10 bg-black/30 hover:border-white/20'"
                               class="p-4 rounded-xl border cursor-pointer transition flex flex-col justify-between">
                            <input type="radio" name="style" value="clean_minimal" class="sr-only" x-model="selectedStyle">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold text-white">Clean & Minimal</span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-blue-400"></span>
                                </div>
                                <p class="text-[11px] text-slate-400">Fundo claro, visual arejado, linhas elegantes e máxima clareza.</p>
                            </div>
                            <div class="mt-3 flex gap-1.5">
                                <span class="w-4 h-4 rounded-full bg-slate-100 border border-slate-300"></span>
                                <span class="w-4 h-4 rounded-full bg-blue-600"></span>
                                <span class="w-4 h-4 rounded-full bg-slate-800"></span>
                            </div>
                        </label>

                        <!-- Corporate Blue -->
                        <label @click="selectedStyle = 'corporate_blue'" 
                               :class="selectedStyle === 'corporate_blue' ? 'border-purple-500 bg-purple-500/10 ring-2 ring-purple-500/30' : 'border-white/10 bg-black/30 hover:border-white/20'"
                               class="p-4 rounded-xl border cursor-pointer transition flex flex-col justify-between">
                            <input type="radio" name="style" value="corporate_blue" class="sr-only" x-model="selectedStyle">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold text-white">Corporativo & Tech</span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-cyan-400"></span>
                                </div>
                                <p class="text-[11px] text-slate-400">Azul marinho profundo, detalhes em ciano e alta autoridade B2B.</p>
                            </div>
                            <div class="mt-3 flex gap-1.5">
                                <span class="w-4 h-4 rounded-full bg-slate-900 border border-blue-900"></span>
                                <span class="w-4 h-4 rounded-full bg-cyan-400"></span>
                                <span class="w-4 h-4 rounded-full bg-blue-500"></span>
                            </div>
                        </label>

                        <!-- Luxury Gold -->
                        <label @click="selectedStyle = 'luxury_gold'" 
                               :class="selectedStyle === 'luxury_gold' ? 'border-purple-500 bg-purple-500/10 ring-2 ring-purple-500/30' : 'border-white/10 bg-black/30 hover:border-white/20'"
                               class="p-4 rounded-xl border cursor-pointer transition flex flex-col justify-between">
                            <input type="radio" name="style" value="luxury_gold" class="sr-only" x-model="selectedStyle">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold text-white">Elegante & Premium</span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                                </div>
                                <p class="text-[11px] text-slate-400">Preto ônix com detalhes em dourado refinado, estilo boutique e luxo.</p>
                            </div>
                            <div class="mt-3 flex gap-1.5">
                                <span class="w-4 h-4 rounded-full bg-neutral-950 border border-amber-900/50"></span>
                                <span class="w-4 h-4 rounded-full bg-amber-400"></span>
                                <span class="w-4 h-4 rounded-full bg-amber-600"></span>
                            </div>
                        </label>

                        <!-- Vibrant Modern -->
                        <label @click="selectedStyle = 'vibrant_modern'" 
                               :class="selectedStyle === 'vibrant_modern' ? 'border-purple-500 bg-purple-500/10 ring-2 ring-purple-500/30' : 'border-white/10 bg-black/30 hover:border-white/20'"
                               class="p-4 rounded-xl border cursor-pointer transition flex flex-col justify-between">
                            <input type="radio" name="style" value="vibrant_modern" class="sr-only" x-model="selectedStyle">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold text-white">Vibrante & Criativo</span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-pink-500"></span>
                                </div>
                                <p class="text-[11px] text-slate-400">Gradientes dinâmicos de púrpura, rosa neon e modernidade startup.</p>
                            </div>
                            <div class="mt-3 flex gap-1.5">
                                <span class="w-4 h-4 rounded-full bg-slate-950"></span>
                                <span class="w-4 h-4 rounded-full bg-purple-500"></span>
                                <span class="w-4 h-4 rounded-full bg-pink-500"></span>
                            </div>
                        </label>
                    </div>

                    <!-- Seções a incluir -->
                    <div class="pt-4 border-t border-white/10">
                        <span class="text-xs font-bold text-slate-300 uppercase tracking-wider block mb-3">Seções da Landing Page:</span>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                            <label class="flex items-center gap-2 text-slate-300 cursor-pointer">
                                <input type="checkbox" name="sections[]" value="hero" checked class="rounded bg-black/40 border-white/20 text-purple-600 focus:ring-purple-500">
                                <span>Hero Banner & CTA</span>
                            </label>
                            <label class="flex items-center gap-2 text-slate-300 cursor-pointer">
                                <input type="checkbox" name="sections[]" value="benefits" checked class="rounded bg-black/40 border-white/20 text-purple-600 focus:ring-purple-500">
                                <span>Benefícios / Diferenciais</span>
                            </label>
                            <label class="flex items-center gap-2 text-slate-300 cursor-pointer">
                                <input type="checkbox" name="sections[]" value="services" checked class="rounded bg-black/40 border-white/20 text-purple-600 focus:ring-purple-500">
                                <span>Grade de Serviços</span>
                            </label>
                            <label class="flex items-center gap-2 text-slate-300 cursor-pointer">
                                <input type="checkbox" name="sections[]" value="testimonials" checked class="rounded bg-black/40 border-white/20 text-purple-600 focus:ring-purple-500">
                                <span>Depoimentos Reais</span>
                            </label>
                            <label class="flex items-center gap-2 text-slate-300 cursor-pointer">
                                <input type="checkbox" name="sections[]" value="faq" checked class="rounded bg-black/40 border-white/20 text-purple-600 focus:ring-purple-500">
                                <span>Perguntas Frequentes (FAQ)</span>
                            </label>
                            <label class="flex items-center gap-2 text-slate-300 cursor-pointer">
                                <input type="checkbox" name="sections[]" value="contact" checked class="rounded bg-black/40 border-white/20 text-purple-600 focus:ring-purple-500">
                                <span>WhatsApp & Contato</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Botão de Ação Principal -->
                <div class="pt-2">
                    <button type="submit" 
                            id="submit-btn"
                            class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-purple-600 via-pink-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-black text-sm uppercase tracking-wider shadow-2xl shadow-purple-500/30 hover:shadow-purple-500/50 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-3">
                        <svg class="w-5 h-5 animate-spin hidden" id="spinner-icon" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span id="btn-text">✨ Gerar Website com IA Gemini (Levará ~10 segundos)</span>
                    </button>
                    <p class="text-[11px] text-center text-slate-400 mt-2">
                        Utilizando modelo de última geração Google Gemini 3.6 Flash. O site gerado poderá ser ajustado livremente no Studio.
                    </p>
                </div>
            </form>

        </div>
    </div>

    <script>
        function applyPreset(niche, desc) {
            document.getElementById('niche').value = niche;
            document.getElementById('description').value = desc;
            if (!document.getElementById('business_name').value) {
                document.getElementById('business_name').focus();
            }
        }

        document.getElementById('ai-generator-form').addEventListener('submit', function() {
            var btn = document.getElementById('submit-btn');
            var spinner = document.getElementById('spinner-icon');
            var text = document.getElementById('btn-text');

            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
            spinner.classList.remove('hidden');
            
            var messages = [
                'Consultando o Google Gemini 3.6 Flash...',
                'Escrevendo código HTML5 e Tailwind CSS...',
                'Criando seções de alta conversão e depoimentos...',
                'Configurando botões de WhatsApp e responsividade...',
                'Finalizando o Studio de visualização...'
            ];
            var i = 0;
            text.innerText = messages[0];
            setInterval(function() {
                i = (i + 1) % messages.length;
                text.innerText = messages[i];
            }, 2500);
        });
    </script>
</x-app-layout>
