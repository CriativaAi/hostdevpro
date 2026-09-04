<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Services\MercadoPagoService;
use App\Services\StripeService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    /**
     * Lista de Faturas (Minhas Faturas).
     */
    public function index(Request $request): View
    {
        $query = Invoice::with(['client', 'hostingAccount'])->latest('due_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $invoices = $query->paginate(15)->withQueryString();

        // Totais e KPIs
        $totalUnpaid = Invoice::where('status', Invoice::STATUS_UNPAID)->sum('amount_cents');
        $totalPaid = Invoice::where('status', Invoice::STATUS_PAID)->sum('amount_cents');
        $overdueCount = Invoice::where('status', '!=', Invoice::STATUS_PAID)
            ->where('due_date', '<', Carbon::today())
            ->count();

        return view('invoices.index', compact(
            'invoices',
            'totalUnpaid',
            'totalPaid',
            'overdueCount'
        ));
    }

    /**
     * Visualização detalhada e tela de pagamento da Fatura.
     */
    public function show(Invoice $invoice, MercadoPagoService $mpService): View
    {
        $invoice->load(['client', 'hostingAccount', 'items']);

        // Se for fatura pendente e ainda não tiver código PIX gerado, gera via Mercado Pago
        if ($invoice->status !== Invoice::STATUS_PAID && empty($invoice->pix_copy_paste)) {
            $mpService->createPixPayment($invoice);
            $invoice->refresh();
        }

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Processamento de pagamento com Cartão de Crédito via Stripe.
     */
    public function payStripe(Invoice $invoice, StripeService $stripeService): RedirectResponse
    {
        $successUrl = route('invoices.show', ['invoice' => $invoice, 'paid' => 1]);
        $cancelUrl = route('invoices.show', $invoice);

        $result = $stripeService->createCheckoutSession($invoice, $successUrl, $cancelUrl);

        if ($result['success'] && !empty($result['checkout_url'])) {
            return redirect()->away($result['checkout_url']);
        }

        return back()->with('error', $result['message'] ?? 'Não foi possível iniciar o checkout Stripe no momento.');
    }

    /**
     * Confirmação / baixa manual de pagamento de fatura.
     */
    public function markAsPaid(Invoice $invoice): RedirectResponse
    {
        $invoice->update([
            'status' => Invoice::STATUS_PAID,
            'paid_at' => Carbon::now(),
        ]);

        return back()->with('success', "Fatura {$invoice->invoice_number} confirmada como Paga com sucesso!");
    }
}
