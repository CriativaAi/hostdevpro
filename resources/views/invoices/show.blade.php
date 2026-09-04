<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="font-bold text-2xl text-[#783D19] leading-tight">
                        Fatura {{ $invoice->invoice_number }}
                    </h2>
                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $invoice->status_badge_classes }}">
                        {{ $invoice->status_label }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    Vencimento em: <strong class="{{ $invoice->is_overdue ? 'text-rose-600' : 'text-gray-700' }}">{{ $invoice->due_date->format('d/m/Y') }}</strong>
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('invoices.index') }}" 
                   class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 font-semibold text-xs uppercase tracking-wider hover:bg-gray-50 transition">
                    &larr; Minhas Faturas
                </a>
                <button type="button" onclick="window.print()" 
                        class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs uppercase tracking-wider transition">
                    🖨️ Imprimir
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Mensagens Flash -->
            @if (session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2.5 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Banner se estiver Vencida -->
            @if ($invoice->is_overdue)
                <div class="p-5 rounded-3xl bg-rose-50 border border-rose-200 text-rose-900 flex items-center justify-between gap-4 shadow-sm">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">⚠️</span>
                        <div>
                            <span class="font-extrabold text-sm block">Esta fatura está vencida</span>
                            <p class="text-xs text-rose-700 mt-0.5">
                                Realize o pagamento imediato via PIX para reativar ou evitar a suspensão dos seus serviços na nuvem.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Card da Fatura -->
            <div class="bg-white rounded-3xl border border-gray-200/80 shadow-sm p-6 sm:p-8 space-y-8">
                
                <!-- Cabeçalho Institucional da Fatura -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 border-b border-gray-100 pb-6">
                    <div>
                        <img src="{{ asset('brand/logos/dark/HostDevPro-horizontal-white.webp') }}" 
                             alt="HostDevPro" 
                             class="h-8 w-auto filter invert brightness-0">
                        <span class="text-[11px] text-gray-400 block mt-2">
                            HostDevPro Cloud Soluções de Hospedagem & VPS<br>
                            CNPJ: 00.000.000/0001-00 &bull; São Paulo / Brasil
                        </span>
                    </div>

                    <div class="text-left sm:text-right">
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Fatura</span>
                        <span class="font-mono font-extrabold text-xl text-gray-900 block mt-0.5">
                            {{ $invoice->invoice_number }}
                        </span>
                        <div class="text-xs text-gray-500 mt-1 font-mono">
                            Data de Emissão: {{ $invoice->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                </div>

                <!-- Dados do Pagador (Cliente) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                    <div class="p-4 rounded-2xl bg-gray-50/70 border border-gray-100 space-y-1">
                        <span class="font-bold text-gray-400 uppercase tracking-wider block mb-1">Cobrado a:</span>
                        <span class="font-extrabold text-sm text-gray-900 block">{{ $invoice->client->name }}</span>
                        <span class="text-gray-600 block">{{ $invoice->client->company ?? 'Pessoa Física' }}</span>
                        <span class="text-gray-500 font-mono block">{{ $invoice->client->email }}</span>
                        @if ($invoice->client->phone)
                            <span class="text-gray-500 block">{{ $invoice->client->phone }}</span>
                        @endif
                    </div>

                    <div class="p-4 rounded-2xl bg-gray-50/70 border border-gray-100 space-y-1 sm:text-right">
                        <span class="font-bold text-gray-400 uppercase tracking-wider block mb-1">Resumo da Cobrança:</span>
                        <div class="text-sm font-bold text-gray-800">
                            {{ $invoice->hostingAccount ? $invoice->hostingAccount->domain : 'Serviços HostDevPro' }}
                        </div>
                        <div class="text-xs text-gray-500 font-mono">
                            Vencimento: {{ $invoice->due_date->format('d/m/Y') }}
                        </div>
                        @if ($invoice->paid_at)
                            <div class="text-xs text-emerald-600 font-bold font-mono">
                                Pago em: {{ $invoice->paid_at->format('d/m/Y H:i') }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tabela de Itens -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-bold border-b border-gray-100">
                            <tr>
                                <th class="py-3 px-4">Descrição do Serviço</th>
                                <th class="py-3 px-4 text-center">Qtd</th>
                                <th class="py-3 px-4 text-right">Valor Unitário</th>
                                <th class="py-3 px-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($invoice->items as $item)
                                <tr>
                                    <td class="py-4 px-4 font-medium text-gray-900">
                                        {{ $item->description }}
                                    </td>
                                    <td class="py-4 px-4 text-center font-mono text-gray-600">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="py-4 px-4 text-right font-mono text-gray-600">
                                        {{ $item->amount_formatted }}
                                    </td>
                                    <td class="py-4 px-4 text-right font-mono font-bold text-gray-900">
                                        {{ $item->total_formatted }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="py-4 px-4 font-medium text-gray-900">
                                        {{ $invoice->notes ?? 'Serviços de Hospedagem & Cloud' }}
                                    </td>
                                    <td class="py-4 px-4 text-center font-mono text-gray-600">1</td>
                                    <td class="py-4 px-4 text-right font-mono text-gray-600">{{ $invoice->amount_formatted }}</td>
                                    <td class="py-4 px-4 text-right font-mono font-bold text-gray-900">{{ $invoice->amount_formatted }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-gray-200">
                                <td colspan="3" class="py-4 px-4 text-right font-extrabold uppercase tracking-wider text-gray-700 text-sm">
                                    Total a Pagar:
                                </td>
                                <td class="py-4 px-4 text-right font-extrabold font-mono text-2xl text-blue-600">
                                    {{ $invoice->amount_formatted }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Seção de Pagamento (Se a fatura estiver em aberto) -->
                @if ($invoice->status !== \App\Models\Invoice::STATUS_PAID)
                    <div class="p-6 rounded-3xl bg-blue-50/60 border border-blue-200/80 space-y-6" x-data="{
                        activeTab: 'pix',
                        copiedPix: false,
                        copyPix(text) {
                            navigator.clipboard.writeText(text).then(() => {
                                this.copiedPix = true;
                                setTimeout(() => this.copiedPix = false, 3000);
                            });
                        }
                    }">
                        <div class="flex items-center justify-between border-b border-blue-200/60 pb-4">
                            <span class="font-bold text-sm text-blue-950 flex items-center gap-2">
                                <span>💳</span> Opções de Pagamento Instantâneo
                            </span>
                            
                            <!-- Seletor de Abas -->
                            <div class="flex items-center gap-2 bg-white/80 p-1 rounded-xl border border-blue-200">
                                <button type="button" 
                                        @click="activeTab = 'pix'"
                                        :class="activeTab === 'pix' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                                        class="px-3 py-1 rounded-lg text-xs font-bold transition">
                                    PIX Mercado Pago
                                </button>
                                <button type="button" 
                                        @click="activeTab = 'card'"
                                        :class="activeTab === 'card' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                                        class="px-3 py-1 rounded-lg text-xs font-bold transition">
                                    Cartão Stripe
                                </button>
                            </div>
                        </div>

                        <!-- Aba 1: PIX Instantâneo -->
                        <div x-show="activeTab === 'pix'" class="space-y-4">
                            <div class="flex flex-col sm:flex-row items-center gap-6">
                                <!-- QR Code Box -->
                                <div class="w-36 h-36 bg-white p-3 rounded-2xl border border-blue-200 shadow-sm flex flex-col items-center justify-center text-center flex-shrink-0">
                                    @if ($invoice->pix_qr_code_base64)
                                        <img src="data:image/png;base64,{{ $invoice->pix_qr_code_base64 }}" alt="QR Code PIX" class="w-full h-full object-contain">
                                    @else
                                        <!-- Mock SVG QR Code Elegante -->
                                        <svg class="w-24 h-24 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M2 2h8v8H2V2zm2 2v4h4V4H4zm10-2h8v8h-8V2zm2 2v4h4V4h-4zM2 14h8v8H2v-8zm2 2v4h4v-4H4zm8-2h2v2h-2v-2zm4 0h2v2h-2v-2zm-2 2h2v2h-2v-2zm4 0h2v2h-2v-2zm-4 2h2v2h-2v-2zm2 2h2v2h-2v-2zm2-2h2v2h-2v-2zm0 2h2v2h-2v-2z"/>
                                        </svg>
                                        <span class="text-[9px] font-bold text-gray-500 uppercase mt-1">PIX Instantâneo</span>
                                    @endif
                                </div>

                                <div class="space-y-3 flex-1 w-full">
                                    <div>
                                        <span class="font-bold text-xs text-blue-900 block">PIX Copia e Cola</span>
                                        <span class="text-[11px] text-gray-600">Copie o código abaixo e cole na opção "PIX Copia e Cola" do seu aplicativo bancário:</span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <input type="text" 
                                               readonly 
                                               value="{{ $invoice->pix_copy_paste }}"
                                               class="w-full px-3 py-2 rounded-xl bg-white border border-blue-200 text-gray-700 font-mono text-xs select-all outline-none">
                                        
                                        <button type="button" 
                                                @click="copyPix('{{ $invoice->pix_copy_paste }}')"
                                                class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider flex-shrink-0 transition shadow-sm">
                                            <span x-show="!copiedPix">Copiar</span>
                                            <span x-show="copiedPix" style="display: none;">Copiado!</span>
                                        </button>
                                    </div>
                                    <span class="text-[11px] text-emerald-700 font-medium block">
                                        ✓ Liberação automática dos serviços em menos de 1 minuto após o pagamento.
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Aba 2: Cartão de Crédito Stripe -->
                        <div x-show="activeTab === 'card'" style="display: none;" class="space-y-4">
                            <div class="p-4 rounded-2xl bg-white border border-blue-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <span class="font-bold text-sm text-gray-900 block">Cartão de Crédito Internacional ou Nacional</span>
                                    <span class="text-xs text-gray-500 mt-0.5 block">Processamento seguro criptografado com certificação PCI-DSS via Stripe.</span>
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

                        <!-- Ação de Simulação / Baixa Manual para Teste -->
                        <div class="pt-4 border-t border-blue-200/60 flex items-center justify-between text-xs text-gray-500">
                            <span>Ambiente de Teste / Homologação</span>
                            <form method="POST" action="{{ route('invoices.mark-paid', $invoice) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-emerald-700 font-bold hover:underline" onclick="return confirm('Simular confirmação de pagamento para esta fatura?');">
                                    [Simular Baixa / Confirmar Pagamento]
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Recibo de Fatura Liquidada -->
                    <div class="p-6 rounded-3xl bg-emerald-50 border border-emerald-200 text-center space-y-2">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 mx-auto flex items-center justify-center font-bold text-xl">
                            ✓
                        </div>
                        <h4 class="font-bold text-base text-emerald-900">Fatura Liquidada com Sucesso</h4>
                        <p class="text-xs text-emerald-700">
                            Pagamento registrado em {{ $invoice->paid_at?->format('d/m/Y \à\s H:i') ?? 'Hoje' }} via {{ strtoupper($invoice->payment_method ?? 'PIX') }}. Seus serviços estão totalmente ativos.
                        </p>
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>
