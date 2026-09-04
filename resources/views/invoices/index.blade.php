<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-[#783D19] leading-tight">
                    Minhas Faturas
                </h2>
                <p class="text-xs text-gray-500 mt-1">
                    Histórico de pagamentos, cobranças ativas e faturamento da nuvem HostDevPro.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" 
                   class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 font-semibold text-xs uppercase tracking-wider hover:bg-gray-50 transition">
                    &larr; Voltar ao Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- KPIs de Faturamento -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-white rounded-3xl p-5 border border-gray-200/80 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Total Pendente</span>
                        <span class="text-2xl font-extrabold text-amber-600 mt-1 block">
                            R$ {{ number_format($totalUnpaid / 100, 2, ',', '.') }}
                        </span>
                    </div>
                    <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">
                        ⏳
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-gray-200/80 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Faturas Vencidas</span>
                        <span class="text-2xl font-extrabold text-rose-600 mt-1 block">
                            {{ $overdueCount }}
                        </span>
                    </div>
                    <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-lg">
                        ⚠️
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-gray-200/80 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Total Liquidado</span>
                        <span class="text-2xl font-extrabold text-emerald-600 mt-1 block">
                            R$ {{ number_format($totalPaid / 100, 2, ',', '.') }}
                        </span>
                    </div>
                    <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                        ✓
                    </div>
                </div>
            </div>

            <!-- Tabela de Faturas -->
            <div class="bg-white rounded-3xl border border-gray-200/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <h3 class="font-bold text-sm text-gray-900">
                        Histórico de Cobranças
                    </h3>
                    <!-- Filtros -->
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('invoices.index') }}" 
                           class="px-3 py-1.5 rounded-xl text-xs font-bold {{ !request('status') ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition">
                            Todas
                        </a>
                        <a href="{{ route('invoices.index', ['status' => 'unpaid']) }}" 
                           class="px-3 py-1.5 rounded-xl text-xs font-bold {{ request('status') === 'unpaid' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }} transition">
                            Pendentes
                        </a>
                        <a href="{{ route('invoices.index', ['status' => 'paid']) }}" 
                           class="px-3 py-1.5 rounded-xl text-xs font-bold {{ request('status') === 'paid' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }} transition">
                            Pagas
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50/70 text-gray-400 font-bold uppercase tracking-wider border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Fatura #</th>
                                <th class="px-6 py-4">Cliente / Serviço</th>
                                <th class="px-6 py-4">Vencimento</th>
                                <th class="px-6 py-4">Total</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($invoices as $inv)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="px-6 py-4 font-mono font-bold text-gray-900">
                                        <a href="{{ route('invoices.show', $inv) }}" class="hover:text-blue-600">
                                            {{ $inv->invoice_number }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-gray-800 block">{{ $inv->client->name }}</span>
                                        <span class="text-[11px] text-gray-400 block truncate max-w-xs">{{ $inv->notes ?? 'Serviços Cloud' }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-mono">
                                        <span class="{{ $inv->is_overdue ? 'text-rose-600 font-bold' : 'text-gray-600' }}">
                                            {{ $inv->due_date->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-bold font-mono text-gray-900 text-sm">
                                        {{ $inv->amount_formatted }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $inv->status_badge_classes }}">
                                            {{ $inv->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('invoices.show', $inv) }}" 
                                           class="px-3 py-1.5 rounded-xl {{ $inv->status === 'paid' ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-blue-600 hover:bg-blue-700 text-white' }} font-bold text-[11px] uppercase tracking-wider transition shadow-sm inline-block">
                                            {{ $inv->status === 'paid' ? 'Ver Recibo' : 'Pagar' }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                        Nenhuma fatura encontrada com os filtros selecionados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($invoices->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $invoices->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
