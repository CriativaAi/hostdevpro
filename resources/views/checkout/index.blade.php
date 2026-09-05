@extends('layouts.checkout')

@section('content')
<div class="min-h-screen bg-[#05080e] text-slate-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-8">
        
        <!-- Header -->
        <div class="text-center space-y-3">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-mono uppercase tracking-widest">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                Checkout Seguro & Ativação Imediata
            </div>
            <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight">
                Assinar <span class="bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400 bg-clip-text text-transparent">Hospedagem Cloud NVMe</span>
            </h1>
            <p class="text-sm text-slate-400 max-w-xl mx-auto">
                Configure seu domínio, escolha o ciclo de pagamento e receba as credenciais do servidor instantaneamente em seu e-mail.
            </p>
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            @csrf

            <!-- Coluna Principal (Dados & Domínio) -->
            <div class="lg:col-span-7 space-y-6">

                <!-- 1. Seleção de Plano & Ciclo -->
                <div class="p-6 sm:p-8 rounded-3xl bg-[#090d16] border border-slate-800 shadow-xl space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-sm">1</span>
                            <h2 class="text-lg font-bold text-white">Escolha o Plano & Ciclo</h2>
                        </div>
                        <div class="flex items-center gap-2 p-1 rounded-xl bg-slate-900 border border-slate-800 text-xs">
                            <button type="button" id="toggle-monthly" 
                                    class="px-3 py-1.5 rounded-lg font-bold transition {{ $selectedPeriod === 'monthly' ? 'bg-emerald-500 text-black shadow-md' : 'text-slate-400 hover:text-white' }}"
                                    onclick="setPeriod('monthly')">
                                Mensal
                            </button>
                            <button type="button" id="toggle-annual" 
                                    class="px-3 py-1.5 rounded-lg font-bold transition flex items-center gap-1 {{ $selectedPeriod === 'annual' ? 'bg-emerald-500 text-black shadow-md' : 'text-slate-400 hover:text-white' }}"
                                    onclick="setPeriod('annual')">
                                <span>Anual</span>
                                <span class="px-1.5 py-0.5 rounded-md bg-amber-400 text-black text-[10px] font-black uppercase">-2 Meses</span>
                            </button>
                        </div>
                    </div>

                    <input type="hidden" name="period" id="input-period" value="{{ $selectedPeriod }}">
                    <input type="hidden" name="plan" id="input-plan" value="{{ $selectedPlan }}">

                    <!-- Cards de Plano -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Card Basic -->
                        <div class="p-5 rounded-2xl border-2 transition cursor-pointer relative {{ $selectedPlan === 'basic' ? 'border-emerald-500 bg-emerald-950/20 ring-2 ring-emerald-500/20' : 'border-slate-800 bg-slate-900/40 hover:border-slate-700' }}"
                             onclick="selectPlan('basic')">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">Sites & Portfólios</span>
                                <input type="radio" name="plan_radio" value="basic" {{ $selectedPlan === 'basic' ? 'checked' : '' }} class="accent-emerald-500">
                            </div>
                            <h3 class="text-xl font-black text-white">Plano Basic</h3>
                            <div class="mt-3 flex items-baseline gap-1">
                                <span class="text-xs text-slate-400">R$</span>
                                <span class="text-3xl font-black text-white" id="price-basic">{{ $selectedPeriod === 'annual' ? '199,00' : '19,90' }}</span>
                                <span class="text-xs text-slate-400" id="cycle-basic">{{ $selectedPeriod === 'annual' ? '/ano' : '/mês' }}</span>
                            </div>
                            <ul class="mt-4 space-y-1.5 text-xs text-slate-300">
                                <li>✓ <strong>30 GB NVMe Gen5</strong></li>
                                <li>✓ 2 vCPU + 4 GB RAM</li>
                                <li>✓ 10 E-mails Corporativos</li>
                                <li>✓ SSL Let's Encrypt Grátis</li>
                            </ul>
                        </div>

                        <!-- Card Premium -->
                        <div class="p-5 rounded-2xl border-2 transition cursor-pointer relative {{ $selectedPlan === 'premium' ? 'border-rose-500 bg-rose-950/20 ring-2 ring-rose-500/20' : 'border-slate-800 bg-slate-900/40 hover:border-slate-700' }}"
                             onclick="selectPlan('premium')">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-rose-400">Alta Frequência</span>
                                <input type="radio" name="plan_radio" value="premium" {{ $selectedPlan === 'premium' ? 'checked' : '' }} class="accent-rose-500">
                            </div>
                            <h3 class="text-xl font-black text-white">Plano Premium</h3>
                            <div class="mt-3 flex items-baseline gap-1">
                                <span class="text-xs text-slate-400">R$</span>
                                <span class="text-3xl font-black text-white" id="price-premium">{{ $selectedPeriod === 'annual' ? '499,00' : '49,90' }}</span>
                                <span class="text-xs text-slate-400" id="cycle-premium">{{ $selectedPeriod === 'annual' ? '/ano' : '/mês' }}</span>
                            </div>
                            <ul class="mt-4 space-y-1.5 text-xs text-slate-300">
                                <li>✓ <strong>100 GB NVMe Gen5</strong></li>
                                <li>✓ 4 vCPU + 8 GB RAM</li>
                                <li>✓ Redis Cache Nativo</li>
                                <li>✓ E-mails & Bancos Ilimitados</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 2. Configuração do Domínio -->
                <div class="p-6 sm:p-8 rounded-3xl bg-[#090d16] border border-slate-800 shadow-xl space-y-6">
                    <div class="flex items-center gap-3 border-b border-slate-800 pb-4">
                        <span class="w-8 h-8 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold text-sm">2</span>
                        <div>
                            <h2 class="text-lg font-bold text-white">Qual domínio você quer hospedar?</h2>
                            <p class="text-xs text-slate-400">Você pode usar um domínio que já possui no Registro.br, GoDaddy ou criar um novo.</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="domain" class="block text-xs font-mono uppercase tracking-wider text-slate-400">
                            Nome do Domínio <span class="text-emerald-400">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 text-sm font-mono">
                                https://
                            </span>
                            <input type="text" name="domain" id="domain" required
                                   value="{{ old('domain', $prefilledDomain) }}"
                                   placeholder="seusite.com.br"
                                   class="w-full pl-24 pr-4 py-3.5 rounded-2xl bg-slate-900/90 border border-slate-700 text-white font-mono text-sm placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition">
                        </div>
                        <p class="text-[11px] text-slate-500">
                            Após a ativação, você receberá os servidores DNS para apontar seu domínio com 1 clique.
                        </p>
                        @error('domain')
                            <p class="text-xs text-rose-400 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- 3. Dados do Cliente / Acesso -->
                <div class="p-6 sm:p-8 rounded-3xl bg-[#090d16] border border-slate-800 shadow-xl space-y-6">
                    <div class="flex items-center gap-3 border-b border-slate-800 pb-4">
                        <span class="w-8 h-8 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold text-sm">3</span>
                        <div>
                            <h2 class="text-lg font-bold text-white">Seus Dados de Contato</h2>
                            <p class="text-xs text-slate-400">Para envio do e-mail com senhas, DNS e recibo.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2 space-y-1.5">
                            <label for="name" class="block text-xs font-semibold text-slate-400">Nome Completo</label>
                            <input type="text" name="name" id="name" required
                                   value="{{ old('name', $user?->name) }}"
                                   placeholder="Alexandre Silva"
                                   class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700 text-white text-sm focus:border-emerald-500 transition">
                            @error('name') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label for="email" class="block text-xs font-semibold text-slate-400">E-mail (Para receber as credenciais)</label>
                            <input type="email" name="email" id="email" required
                                   value="{{ old('email', $user?->email) }}"
                                   placeholder="voce@empresa.com.br"
                                   class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700 text-white text-sm focus:border-emerald-500 transition">
                            @error('email') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label for="phone" class="block text-xs font-semibold text-slate-400">WhatsApp (com DDD)</label>
                            <input type="text" name="phone" id="phone" required
                                   value="{{ old('phone') }}"
                                   placeholder="(11) 92138-1308"
                                   class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700 text-white text-sm focus:border-emerald-500 transition">
                            @error('phone') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
                        </div>

                        @guest
                        <div class="sm:col-span-2 space-y-1.5">
                            <label for="password" class="block text-xs font-semibold text-slate-400">Crie uma Senha para o Painel HostDevPro</label>
                            <input type="password" name="password" id="password" required
                                   placeholder="Mínimo 6 caracteres"
                                   class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700 text-white text-sm focus:border-emerald-500 transition">
                            @error('password') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
                        </div>
                        @endguest
                    </div>
                </div>

            </div>

            <!-- Coluna Lateral (Resumo do Pedido & CTA) -->
            <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-8">
                <div class="p-6 sm:p-8 rounded-3xl bg-[#090d16] border border-slate-800 shadow-2xl space-y-6">
                    <h3 class="text-lg font-bold text-white border-b border-slate-800 pb-4 flex items-center justify-between">
                        <span>Resumo do Pedido</span>
                        <span class="text-xs px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 font-mono" id="summary-badge">BASIC NVMe</span>
                    </h3>

                    <div class="space-y-3 text-sm text-slate-300">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Plano Selecionado:</span>
                            <span class="font-bold text-white" id="summary-plan-name">Plano Basic NVMe</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Ciclo de Cobrança:</span>
                            <span class="font-bold text-emerald-400" id="summary-period">Mensal</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">IP Dedicado & SSL:</span>
                            <span class="font-bold text-white">Incluso (Grátis)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Taxa de Instalação:</span>
                            <span class="font-bold text-emerald-400">R$ 0,00</span>
                        </div>
                    </div>

                    <div class="border-t border-slate-800 pt-4 flex items-baseline justify-between">
                        <div>
                            <span class="text-xs text-slate-400 block">Total a Pagar:</span>
                            <span class="text-xs text-slate-500" id="summary-recurrent">Renovação no mesmo valor</span>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-black text-white">
                                <span class="text-sm font-semibold text-slate-400">R$</span>
                                <span id="summary-total">19,90</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 space-y-2">
                        <div class="flex flex-wrap items-center gap-2 text-xs font-bold">
                            <span class="text-emerald-400">⚡ PIX</span>
                            <span class="text-slate-600">•</span>
                            <span class="text-sky-400">💳 Cartão de Crédito (até 12x)</span>
                            <span class="text-slate-600">•</span>
                            <span class="text-teal-400">💳 Débito</span>
                        </div>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Pague com QR Code PIX ou parcele em até 12x no cartão com a segurança oficial do Mercado Pago.
                        </p>
                    </div>

                    <button type="submit" 
                            class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-black font-black text-sm uppercase tracking-wider shadow-lg shadow-emerald-500/25 transition transform active:scale-95 flex items-center justify-center gap-2">
                        <span>Ir para Pagamento (PIX / Cartão) &rarr;</span>
                    </button>

                    <div class="text-center space-y-2 pt-2">
                        <p class="text-[11px] text-slate-500">
                            🔒 Conexão Criptografada SSL 256-bit • Ativação 100% Automática
                        </p>
                        <div class="flex items-center justify-center gap-4 text-[11px] text-slate-400">
                            <span>Garantia de 7 dias</span>
                            <span>•</span>
                            <span>Suporte 24/7 WhatsApp</span>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    const prices = {
        basic: { monthly: '19,90', annual: '199,00', name: 'Plano Basic NVMe', badge: 'BASIC NVMe' },
        premium: { monthly: '49,90', annual: '499,00', name: 'Plano Premium NVMe', badge: 'PREMIUM NVMe' }
    };

    let currentPlan = '{{ $selectedPlan }}';
    let currentPeriod = '{{ $selectedPeriod }}';

    function setPeriod(period) {
        currentPeriod = period;
        document.getElementById('input-period').value = period;

        // Estilos dos botões
        const btnM = document.getElementById('toggle-monthly');
        const btnA = document.getElementById('toggle-annual');

        if (period === 'monthly') {
            btnM.className = 'px-3 py-1.5 rounded-lg font-bold transition bg-emerald-500 text-black shadow-md';
            btnA.className = 'px-3 py-1.5 rounded-lg font-bold transition flex items-center gap-1 text-slate-400 hover:text-white';
            document.getElementById('cycle-basic').innerText = '/mês';
            document.getElementById('cycle-premium').innerText = '/mês';
            document.getElementById('summary-period').innerText = 'Mensal';
        } else {
            btnM.className = 'px-3 py-1.5 rounded-lg font-bold transition text-slate-400 hover:text-white';
            btnA.className = 'px-3 py-1.5 rounded-lg font-bold transition flex items-center gap-1 bg-emerald-500 text-black shadow-md';
            document.getElementById('cycle-basic').innerText = '/ano';
            document.getElementById('cycle-premium').innerText = '/ano';
            document.getElementById('summary-period').innerText = 'Anual (2 Meses Off)';
        }

        document.getElementById('price-basic').innerText = prices.basic[period];
        document.getElementById('price-premium').innerText = prices.premium[period];
        updateSummary();
    }

    function selectPlan(plan) {
        currentPlan = plan;
        document.getElementById('input-plan').value = plan;

        // Atualiza radio buttons
        document.querySelectorAll('input[name="plan_radio"]').forEach(radio => {
            radio.checked = (radio.value === plan);
        });

        updateSummary();
    }

    function updateSummary() {
        const p = prices[currentPlan];
        document.getElementById('summary-badge').innerText = p.badge;
        document.getElementById('summary-plan-name').innerText = p.name;
        document.getElementById('summary-total').innerText = p[currentPeriod];
    }
</script>
@endsection
