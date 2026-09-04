<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-white tracking-tight leading-tight">
                    Minhas Faturas
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Histórico de pagamentos, cobranças ativas e faturamento da nuvem HostDevPro.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" 
                   class="px-4 py-2 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] border border-white/15 text-white font-bold text-xs uppercase tracking-wider transition">
                    &larr; Voltar ao Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- KPIs de Faturamento (rounded-xl, 80% Transparência no Branco / Frosted Glass) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-white/[0.06] backdrop-blur-2xl rounded-xl p-5 border border-white/15 shadow-xl flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Pendente</span>
                        <span class="text-2xl font-black text-amber-400 mt-1 block font-mono">
                            R$ {{ number_format($totalUnpaid / 100, 2, ',', '.') }}
                        </span>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 flex items-center justify-center font-bold text-lg">
                        ⏳
                    </div>
                </div>

                <div class="bg-white/[0.06] backdrop-blur-2xl rounded-xl p-5 border border-white/15 shadow-xl flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Faturas Vencidas</span>
                        <span class="text-2xl font-black text-rose-400 mt-1 block font-mono">
                            {{ $overdueCount }}
                        </span>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 flex items-center justify-center font-bold text-lg">
                        ⚠️
                    </div>
                </div>

                <div class="bg-white/[0.06] backdrop-blur-2xl rounded-xl p-5 border border-white/15 shadow-xl flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Liquidado</span>
                        <span class="text-2xl font-black text-emerald-400 mt-1 block font-mono">
                            R$ {{ number_format($totalPaid / 100, 2, ',', '.') }}
                        </span>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-center font-bold text-lg">
                        ✓
                    </div>
                </div>
            </div>

            <!-- Tabela de Faturas (rounded-2xl, Frosted Glass) -->
            <div class="bg-white/[0.06] backdrop-blur-2xl rounded-2xl border border-white/15 shadow-2xl overflow-hidden">
                <div class="p-6 border-b border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <h3 class="font-black text-sm text-white">
                        Histórico de Cobranças
                    </h3>
                    <!-- Filtros -->
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('invoices.index') }}" 
                           class="px-3 py-1.5 rounded-lg text-xs font-bold {{ !request('status') ? 'bg-emerald-500 text-slate-950 font-black' : 'bg-white/[0.06] text-slate-300 hover:bg-white/[0.12]' }} transition">
                            Todas
                        </a>
                        <a href="{{ route('invoices.index', ['status' => 'unpaid']) }}" 
                           class="px-3 py-1.5 rounded-lg text-xs font-bold {{ request('status') === 'unpaid' ? 'bg-amber-500 text-slate-950 font-black' : 'bg-white/[0.06] text-slate-300 hover:bg-white/[0.12]' }} transition">
                            Pendentes
                        </a>
                        <a href="{{ route('invoices.index', ['status' => 'paid']) }}" 
                           class="px-3 py-1.5 rounded-lg text-xs font-bold {{ request('status') === 'paid' ? 'bg-emerald-500 text-slate-950 font-black' : 'bg-white/[0.06] text-slate-300 hover:bg-white/[0.12]' }} transition">
                            Pagas
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-white/[0.03] text-slate-400 font-bold uppercase tracking-wider border-b border-white/10">
                            <tr>
                                <th class="px-6 py-4">Fatura #</th>
                                <th class="px-6 py-4">Cliente / Serviço</th>
                                <th class="px-6 py-4">Vencimento</th>
                                <th class="px-6 py-4">Total</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @forelse ($invoices as $inv)
                                <tr class="hover:bg-white/[0.04] transition">
                                    <td class="px-6 py-4 font-mono font-bold text-white">
                                        <a href="{{ route('invoices.show', $inv) }}" class="hover:text-emerald-400 transition">
                                            {{ $inv->invoice_number }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-white block">{{ $inv->client->name }}</span>
                                        <span class="text-[11px] text-slate-400 block truncate max-w-xs">{{ $inv->notes ?? 'Serviços Cloud' }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-mono">
                                        <span class="{{ $inv->is_overdue ? 'text-rose-400 font-bold' : 'text-slate-300' }}">
                                            {{ $inv->due_date->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-bold font-mono text-emerald-400 text-sm">
                                        {{ $inv->amount_formatted }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $inv->status_badge_classes }}">
                                            {{ $inv->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('invoices.show', $inv) }}" 
                                           class="px-3.5 py-1.5 rounded-lg {{ $inv->status === 'paid' ? 'bg-white/[0.08] hover:bg-white/[0.14] text-slate-200' : 'bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black' }} font-bold text-[11px] uppercase tracking-wider transition shadow-sm inline-block">
                                            {{ $inv->status === 'paid' ? 'Ver Recibo' : 'Pagar' }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                        Nenhuma fatura encontrada com os filtros selecionados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($invoices->hasPages())
                    <div class="p-4 border-t border-white/10">
                        {{ $invoices->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
