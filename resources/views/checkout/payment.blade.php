@extends('layouts.checkout')

@section('content')
<div class="min-h-screen bg-[#05080e] text-slate-100 py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
    <div class="max-w-xl w-full space-y-6">

        <!-- Card Central de Pagamento -->
        <div class="p-6 sm:p-10 rounded-3xl bg-[#090d16] border border-slate-800 shadow-2xl space-y-6 text-center relative overflow-hidden">
            
            <!-- Glow Decorativo de Fundo -->
            <div class="absolute -top-24 -left-24 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Status do Pedido -->
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-mono uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Aguardando Pagamento
            </div>

            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-white">
                    Finalize sua <span class="bg-gradient-to-r from-emerald-400 via-teal-300 to-sky-400 bg-clip-text text-transparent">Assinatura</span>
                </h1>
                <p class="text-xs text-slate-400 mt-1">
                    Fatura <span class="font-mono text-slate-300">{{ $invoice->invoice_number }}</span> • Liberação automática após aprovação
                </p>
            </div>

            <!-- Valor em Destaque -->
            <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 inline-block w-full max-w-sm mx-auto">
                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Total a Pagar</span>
                <div class="text-3xl sm:text-4xl font-black text-white mt-1">
                    <span class="text-lg font-semibold text-emerald-400">R$</span>
                    {{ number_format($invoice->amount_cents / 100, 2, ',', '.') }}
                </div>
            </div>

            <!-- SELETOR DE MÉTODO DE PAGAMENTO (PIX vs CARTÃO MERCADO PAGO) -->
            <div class="grid grid-cols-2 gap-2 p-1.5 rounded-2xl bg-slate-900/90 border border-slate-800">
                <button type="button" id="tab-pix-btn" onclick="switchTab('pix')"
                        class="py-3 px-4 rounded-xl font-bold text-xs uppercase tracking-wider transition flex items-center justify-center gap-2 bg-emerald-500 text-black shadow-lg shadow-emerald-500/20">
                    <span>⚡ PIX Instantâneo</span>
                </button>
                <button type="button" id="tab-card-btn" onclick="switchTab('card')"
                        class="py-3 px-4 rounded-xl font-bold text-xs uppercase tracking-wider transition flex items-center justify-center gap-2 text-slate-400 hover:text-white">
                    <span>💳 Cartão de Crédito / Débito</span>
                </button>
            </div>

            <!-- ABA 1: PIX INSTANTÂNEO -->
            <div id="content-pix" class="space-y-6">
                <!-- QR Code do PIX -->
                <div class="space-y-3">
                    <div class="inline-block p-4 rounded-2xl bg-white shadow-xl shadow-emerald-500/5 border border-slate-200">
                        @if(!empty($invoice->pix_qr_code_base64))
                            <img src="data:image/png;base64,{{ $invoice->pix_qr_code_base64 }}" 
                                 alt="QR Code PIX" 
                                 class="w-48 h-48 sm:w-56 sm:h-56 mx-auto object-contain">
                        @elseif(!empty($invoice->pix_copy_paste))
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=240x240&data={{ urlencode($invoice->pix_copy_paste) }}" 
                                 alt="QR Code PIX" 
                                 class="w-48 h-48 sm:w-56 sm:h-56 mx-auto object-contain">
                        @else
                            <div class="w-48 h-48 sm:w-56 sm:h-56 flex items-center justify-center bg-slate-100 text-slate-500 text-xs">
                                Gerando QR Code...
                            </div>
                        @endif
                    </div>
                    <p class="text-xs text-slate-400">
                        Abra o app do seu banco e aponte a câmera para o QR Code acima.
                    </p>
                </div>

                <!-- Código Copia e Cola -->
                @if(!empty($invoice->pix_copy_paste))
                <div class="space-y-2 text-left">
                    <label class="block text-xs font-mono uppercase tracking-wider text-slate-400">
                        Ou use o PIX Copia e Cola:
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="text" id="pix-code" readonly 
                               value="{{ $invoice->pix_copy_paste }}"
                               class="w-full px-3.5 py-3 rounded-xl bg-slate-900 border border-slate-700 text-slate-300 font-mono text-xs focus:outline-none select-all truncate">
                        <button type="button" onclick="copyPixCode()" id="btn-copy"
                                class="px-4 py-3 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-black font-black text-xs uppercase tracking-wider transition whitespace-nowrap flex items-center gap-1.5 shadow-lg shadow-emerald-500/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <span id="btn-copy-text">Copiar</span>
                        </button>
                    </div>
                </div>
                @endif
            </div>

            <!-- ABA 2: CARTÃO DE CRÉDITO OU DÉBITO (MERCADO PAGO) -->
            <div id="content-card" class="space-y-6 hidden">
                <div class="p-6 rounded-2xl bg-gradient-to-b from-slate-900/90 to-slate-950/90 border border-sky-500/30 text-left space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-sky-400">Checkout Mercado Pago</span>
                        <span class="text-[11px] px-2.5 py-0.5 rounded-full bg-sky-500/10 text-sky-300 border border-sky-500/20 font-semibold">
                            Até 12x no Cartão
                        </span>
                    </div>

                    <h3 class="text-base font-bold text-white">
                        Pague com Cartão de Crédito ou Débito com Segurança
                    </h3>

                    <p class="text-xs text-slate-300 leading-relaxed">
                        Aceitamos as principais bandeiras com aprovação instantânea pelo Mercado Pago:
                    </p>

                    <!-- Bandeiras de Cartão -->
                    <div class="flex flex-wrap items-center gap-2 pt-1 pb-2">
                        <span class="px-3 py-1 rounded-lg bg-slate-800 border border-slate-700 text-[11px] font-bold text-slate-200">💳 Visa</span>
                        <span class="px-3 py-1 rounded-lg bg-slate-800 border border-slate-700 text-[11px] font-bold text-slate-200">💳 Mastercard</span>
                        <span class="px-3 py-1 rounded-lg bg-slate-800 border border-slate-700 text-[11px] font-bold text-slate-200">💳 Elo</span>
                        <span class="px-3 py-1 rounded-lg bg-slate-800 border border-slate-700 text-[11px] font-bold text-slate-200">💳 Hipercard</span>
                        <span class="px-3 py-1 rounded-lg bg-slate-800 border border-slate-700 text-[11px] font-bold text-slate-200">💳 Débito Caixa / Bancos</span>
                    </div>

                    @if(!empty($preferenceUrl))
                    <a href="{{ $preferenceUrl }}" 
                       class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 hover:from-sky-400 hover:to-blue-500 text-white font-black text-sm uppercase tracking-wider shadow-xl shadow-blue-500/25 transition flex items-center justify-center gap-2 group">
                        <span>Pagar com Cartão no Mercado Pago &rarr;</span>
                    </a>
                    @else
                    <p class="text-xs text-amber-400">
                        Carregando link seguro do Mercado Pago...
                    </p>
                    @endif

                    <div class="text-[11px] text-slate-400 flex items-center gap-1.5 pt-1">
                        <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        <span>Ambiente 100% protegido com certificação PCI-DSS do Mercado Pago. Retorno e ativação automáticos após aprovação.</span>
                    </div>
                </div>
            </div>

            <!-- Informações e Ativação Imediata -->
            <div class="pt-4 border-t border-slate-800/80 space-y-4">
                <div class="flex items-center justify-center gap-2 text-xs text-slate-400">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>Aguardando pagamento... O status atualiza automaticamente</span>
                </div>

                <!-- Botão de Confirmação Manual / Teste -->
                <form action="{{ route('checkout.confirm', $invoice) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="w-full py-3 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 hover:text-white font-bold text-xs uppercase tracking-wider transition flex items-center justify-center gap-2">
                        <span>⚡ Já paguei / Ativar hospedagem agora &rarr;</span>
                    </button>
                </form>
            </div>

            <!-- Footer de Segurança -->
            <div class="text-[11px] text-slate-500 pt-1">
                🔒 Assim que aprovado pelo Mercado Pago, você receberá instantaneamente um e-mail HTML com seus servidores DNS, dados de FTP, painel de controle e senhas de acesso.
            </div>

        </div>

    </div>
</div>

<script>
    function switchTab(tab) {
        const btnPix = document.getElementById('tab-pix-btn');
        const btnCard = document.getElementById('tab-card-btn');
        const contentPix = document.getElementById('content-pix');
        const contentCard = document.getElementById('content-card');

        if (tab === 'pix') {
            btnPix.className = 'py-3 px-4 rounded-xl font-bold text-xs uppercase tracking-wider transition flex items-center justify-center gap-2 bg-emerald-500 text-black shadow-lg shadow-emerald-500/20';
            btnCard.className = 'py-3 px-4 rounded-xl font-bold text-xs uppercase tracking-wider transition flex items-center justify-center gap-2 text-slate-400 hover:text-white';
            contentPix.classList.remove('hidden');
            contentCard.classList.add('hidden');
        } else {
            btnCard.className = 'py-3 px-4 rounded-xl font-bold text-xs uppercase tracking-wider transition flex items-center justify-center gap-2 bg-sky-500 text-white shadow-lg shadow-sky-500/20';
            btnPix.className = 'py-3 px-4 rounded-xl font-bold text-xs uppercase tracking-wider transition flex items-center justify-center gap-2 text-slate-400 hover:text-white';
            contentCard.classList.remove('hidden');
            contentPix.classList.add('hidden');
        }
    }

    function copyPixCode() {
        const input = document.getElementById('pix-code');
        if (!input) return;
        input.select();
        input.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(input.value);

        const btnText = document.getElementById('btn-copy-text');
        btnText.innerText = 'Copiado!';
        setTimeout(() => {
            btnText.innerText = 'Copiar';
        }, 2500);
    }

    // Polling contínuo a cada 3 segundos
    setInterval(function() {
        fetch("{{ route('checkout.status', $invoice) }}")
            .then(res => res.json())
            .then(data => {
                if (data.paid && data.redirect_url) {
                    window.location.href = data.redirect_url;
                }
            })
            .catch(err => console.error("Polling error:", err));
    }, 3000);
</script>
@endsection
