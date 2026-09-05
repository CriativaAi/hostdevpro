<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="font-black text-2xl text-white tracking-tight leading-tight">
                        Fatura {{ $invoice->invoice_number }}
                    </h2>
                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $invoice->status_badge_classes }}">
                        {{ $invoice->status_label }}
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-1">
                    Vencimento em: <strong class="{{ $invoice->is_overdue ? 'text-rose-400 font-bold' : 'text-slate-300' }}">{{ $invoice->due_date->format('d/m/Y') }}</strong>
                </p>
            </div>
            <div class="flex items-center gap-2">
                @if ($invoice->client && $invoice->client->phone)
                    <form method="POST" action="{{ route('invoices.send-whatsapp', $invoice) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                title="Enviar notificação e código PIX para o WhatsApp do cliente"
                                class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs uppercase tracking-wider transition flex items-center gap-1.5 shadow-lg shadow-emerald-600/20">
                            <span>📲</span> Enviar WhatsApp
                        </button>
                    </form>
                @endif
                <a href="{{ route('invoices.index') }}" 
                   class="px-4 py-2 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider transition">
                    &larr; Minhas Faturas
                </a>
                <button type="button" onclick="window.print()" 
                        class="px-4 py-2 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider transition">
                    🖨️ Imprimir
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Mensagens Flash -->
            @if (session('success'))
                <div class="p-4 rounded-2xl bg-emerald-950/40 border border-emerald-500/40 text-emerald-300 text-xs flex items-center gap-2.5 shadow-xl backdrop-blur-xl">
                    <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Banner se estiver Vencida -->
            @if ($invoice->is_overdue)
                <div class="p-5 rounded-2xl bg-rose-950/40 border border-rose-500/40 text-rose-200 flex items-center justify-between gap-4 shadow-xl backdrop-blur-xl">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">⚠️</span>
                        <div>
                            <span class="font-black text-sm text-white block">Esta fatura está vencida</span>
                            <p class="text-xs text-rose-200/90 mt-0.5">
                                Realize o pagamento imediato via PIX para evitar a suspensão dos seus serviços na nuvem.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Card da Fatura (Dark Frosted Glass) -->
            <div class="bg-white/[0.06] backdrop-blur-2xl rounded-2xl border border-white/15 shadow-2xl p-6 sm:p-8 space-y-8 text-white">
                
                <!-- Cabeçalho Institucional da Fatura -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 border-b border-white/10 pb-6">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-black text-xl text-white tracking-tight">HOST<span class="text-emerald-400">DEV</span>PRO</span>
                            <span class="text-xs px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 font-bold">CLOUD</span>
                        </div>
                        <span class="text-[11px] text-slate-400 block mt-2 leading-relaxed">
                            HostDevPro Cloud Soluções de Hospedagem & VPS<br>
                            São Paulo &bull; Brasil &bull; Suporte: +55 (11) 92138-1308
                        </span>
                    </div>

                    <div class="text-left sm:text-right">
                        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Fatura</span>
                        <span class="font-mono font-black text-xl text-white block mt-0.5">
                            {{ $invoice->invoice_number }}
                        </span>
                        <div class="text-xs text-slate-400 mt-1 font-mono">
                            Data de Emissão: {{ $invoice->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                </div>

                <!-- Dados do Pagador (Cliente) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                    <div class="p-4 rounded-xl bg-black/40 border border-white/10 space-y-1">
                        <span class="font-bold text-slate-400 uppercase tracking-wider block mb-1">Cobrado a:</span>
                        <span class="font-black text-sm text-white block">{{ $invoice->client->name }}</span>
                        <span class="text-slate-300 block">{{ $invoice->client->company ?? 'Pessoa Física' }}</span>
                        <span class="text-slate-400 font-mono block">{{ $invoice->client->email }}</span>
                        @if ($invoice->client->phone)
                            <span class="text-emerald-400 font-mono block">{{ $invoice->client->phone }}</span>
                        @endif
                    </div>

                    <div class="p-4 rounded-xl bg-black/40 border border-white/10 space-y-1 sm:text-right">
                        <span class="font-bold text-slate-400 uppercase tracking-wider block mb-1">Resumo da Cobrança:</span>
                        <div class="text-sm font-black text-white">
                            {{ $invoice->hostingAccount ? $invoice->hostingAccount->domain : 'Serviços HostDevPro Cloud' }}
                        </div>
                        <div class="text-xs text-slate-400 font-mono">
                            Vencimento: <span class="{{ $invoice->is_overdue ? 'text-rose-400 font-bold' : 'text-slate-300' }}">{{ $invoice->due_date->format('d/m/Y') }}</span>
                        </div>
                        @if ($invoice->paid_at)
                            <div class="text-xs text-emerald-400 font-bold font-mono">
                                ✓ Pago em: {{ $invoice->paid_at->format('d/m/Y H:i') }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tabela de Itens -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-white/[0.03] text-slate-400 uppercase tracking-wider font-bold border-b border-white/10">
                            <tr>
                                <th class="py-3 px-4">Descrição do Serviço</th>
                                <th class="py-3 px-4 text-center">Qtd</th>
                                <th class="py-3 px-4 text-right">Valor Unitário</th>
                                <th class="py-3 px-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse ($invoice->items as $item)
                                <tr>
                                    <td class="py-4 px-4 font-bold text-white">
                                        {{ $item->description }}
                                    </td>
                                    <td class="py-4 px-4 text-center font-mono text-slate-300">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="py-4 px-4 text-right font-mono text-slate-300">
                                        {{ $item->amount_formatted }}
                                    </td>
                                    <td class="py-4 px-4 text-right font-mono font-bold text-emerald-400">
                                        {{ $item->total_formatted }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="py-4 px-4 font-bold text-white">
                                        {{ $invoice->notes ?? 'Serviços de Hospedagem & Cloud NVMe' }}
                                    </td>
                                    <td class="py-4 px-4 text-center font-mono text-slate-300">1</td>
                                    <td class="py-4 px-4 text-right font-mono text-slate-300">{{ $invoice->amount_formatted }}</td>
                                    <td class="py-4 px-4 text-right font-mono font-bold text-emerald-400">{{ $invoice->amount_formatted }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="border-t border-white/15">
                                <td colspan="3" class="py-4 px-4 text-right font-bold uppercase tracking-wider text-slate-400 text-xs">
                                    Total a Pagar:
                                </td>
                                <td class="py-4 px-4 text-right font-black font-mono text-2xl text-emerald-400">
                                    {{ $invoice->amount_formatted }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Seção de Pagamento (Se a fatura estiver em aberto) -->
                @if ($invoice->status !== \App\Models\Invoice::STATUS_PAID)
                    <div class="p-6 rounded-2xl bg-black/40 border border-emerald-500/30 space-y-6" x-data="{
                        activeTab: 'pix',
                        copiedPix: false,
                        copyPix(text) {
                            navigator.clipboard.writeText(text).then(() => {
                                this.copiedPix = true;
                                setTimeout(() => this.copiedPix = false, 3000);
                            });
                        }
                    }">
                        <div class="flex items-center justify-between border-b border-white/10 pb-4">
                            <span class="font-bold text-sm text-white flex items-center gap-2">
                                <span>💳</span> Opções de Pagamento Instantâneo
                            </span>
                            
                            <!-- Seletor de Abas -->
                            <div class="flex items-center gap-2 bg-white/[0.05] p-1 rounded-xl border border-white/10">
                                <button type="button" 
                                        @click="activeTab = 'pix'"
                                        :class="activeTab === 'pix' ? 'bg-emerald-500 text-slate-950 font-black shadow-sm' : 'text-slate-300 hover:text-white'"
                                        class="px-3 py-1 rounded-lg text-xs font-bold transition">
                                    PIX Instantâneo
                                </button>
                                <button type="button" 
                                        @click="activeTab = 'card'"
                                        :class="activeTab === 'card' ? 'bg-emerald-500 text-slate-950 font-black shadow-sm' : 'text-slate-300 hover:text-white'"
                                        class="px-3 py-1 rounded-lg text-xs font-bold transition">
                                    Cartão Stripe
                                </button>
                            </div>
                        </div>

                        <!-- Aba 1: PIX Instantâneo -->
                        <div x-show="activeTab === 'pix'" class="space-y-4">
                            <div class="flex flex-col sm:flex-row items-center gap-6">
                                <!-- QR Code Box -->
                                <div class="w-36 h-36 bg-white p-2.5 rounded-2xl border border-white/20 shadow-lg flex flex-col items-center justify-center text-center flex-shrink-0">
                                    @if ($invoice->pix_qr_code_base64)
                                        <img src="data:image/png;base64,{{ $invoice->pix_qr_code_base64 }}" alt="QR Code PIX" class="w-full h-full object-contain">
                                    @else
                                        <svg class="w-24 h-24 text-slate-900" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M2 2h8v8H2V2zm2 2v4h4V4H4zm10-2h8v8h-8V2zm2 2v4h4V4h-4zM2 14h8v8H2v-8zm2 2v4h4v-4H4zm8-2h2v2h-2v-2zm4 0h2v2h-2v-2zm-2 2h2v2h-2v-2zm4 0h2v2h-2v-2zm-4 2h2v2h-2v-2zm2 2h2v2h-2v-2zm2-2h2v2h-2v-2zm0 2h2v2h-2v-2z"/>
                                        </svg>
                                        <span class="text-[9px] font-black text-slate-800 uppercase mt-0.5">PIX OFICIAL</span>
                                    @endif
                                </div>

                                <div class="space-y-3 flex-1 w-full">
                                    <div>
                                        <span class="font-bold text-xs text-emerald-400 block">Código PIX Copia e Cola</span>
                                        <span class="text-[11px] text-slate-400">Copie o código abaixo e cole no seu aplicativo bancário ou internet banking:</span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <input type="text" 
                                               readonly 
                                               value="{{ $invoice->pix_copy_paste }}"
                                               class="w-full px-3 py-2 rounded-xl bg-black/60 border border-white/15 text-white font-mono text-xs select-all outline-none">
                                        
                                        <button type="button" 
                                                @click="copyPix('{{ $invoice->pix_copy_paste }}')"
                                                class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs uppercase tracking-wider flex-shrink-0 transition shadow-lg shadow-emerald-500/20">
                                            <span x-show="!copiedPix">Copiar</span>
                                            <span x-show="copiedPix" style="display: none;">Copiado!</span>
                                        </button>
                                    </div>
                                    <span class="text-[11px] text-emerald-400 font-medium block">
                                        ✓ Liberação e ativação automática do servidor em menos de 1 minuto após o pagamento.
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Aba 2: Cartão de Crédito Stripe -->
                        <div x-show="activeTab === 'card'" style="display: none;" class="space-y-4">
                            <div class="p-4 rounded-xl bg-white/[0.04] border border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <span class="font-bold text-sm text-white block">Cartão de Crédito Internacional ou Nacional</span>
                                    <span class="text-xs text-slate-400 mt-0.5 block">Processamento seguro criptografado com certificação PCI-DSS via Stripe.</span>
                                </div>

                                <form method="POST" action="{{ route('invoices.pay-stripe', $invoice) }}">
                                    @csrf
                                    <button type="submit" 
                                            class="px-6 py-2.5 rounded-xl bg-[#635BFF] hover:bg-[#5249ea] text-white font-bold text-xs uppercase tracking-wider shadow-sm transition">
                                        Pagar com Stripe &rarr;
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Ação de Baixa Manual para Teste / Administrador -->
                        @if (auth()->user()?->id === 1)
                            <div class="pt-4 border-t border-white/10 flex items-center justify-between text-xs text-slate-500">
                                <span>Painel Administrativo HostDevPro</span>
                                <form method="POST" action="{{ route('invoices.mark-paid', $invoice) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-emerald-400 font-bold hover:underline" onclick="return confirm('Confirmar pagamento manual desta fatura?');">
                                        [Confirmar Pagamento Manual]
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @else
                    <!-- Recibo de Fatura Liquidada -->
                    <div class="p-6 rounded-2xl bg-emerald-950/40 border border-emerald-500/40 text-center space-y-2">
                        <div class="w-12 h-12 rounded-full bg-emerald-500/20 text-emerald-400 mx-auto flex items-center justify-center font-black text-xl border border-emerald-500/30">
                            ✓
                        </div>
                        <h4 class="font-black text-base text-white">Fatura Liquidada com Sucesso</h4>
                        <p class="text-xs text-emerald-300">
                            Pagamento registrado em {{ $invoice->paid_at?->format('d/m/Y \à\s H:i') ?? 'Hoje' }} via {{ strtoupper($invoice->payment_method ?? 'PIX') }}. Seus serviços estão totalmente ativos.
                        </p>
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>
