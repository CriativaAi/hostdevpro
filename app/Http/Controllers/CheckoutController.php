<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;
use App\Services\HostingProvisioningService;
use App\Services\MercadoPagoService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Tela inicial de contratação do plano de hospedagem.
     */
    public function index(Request $request): View
    {
        $selectedPlan = $request->query('plan', 'basic');
        if (!in_array($selectedPlan, ['basic', 'premium'])) {
            $selectedPlan = 'basic';
        }

        $selectedPeriod = $request->query('period', 'monthly');
        if (!in_array($selectedPeriod, ['monthly', 'annual'])) {
            $selectedPeriod = 'monthly';
        }

        $prefilledDomain = $request->query('domain', '');

        $plansInfo = [
            'basic' => [
                'name' => 'Plano Basic NVMe',
                'badge' => 'Sites & Portfólios',
                'color' => 'emerald',
                'monthly_price' => '19,90',
                'annual_price' => '199,00',
                'specs' => [
                    '30 GB NVMe Gen5 Dedicado',
                    '2 vCPU Dedicado + 4 GB RAM Dedicada',
                    '10 Contas de E-mail Profissional',
                    'Bancos de Dados MySQL & PostgreSQL',
                    'Certificado SSL Grátis Let\'s Encrypt',
                    'PHP 8.2, 8.3, 8.4 e 8.5 Nativo',
                    'Proteção Anti-DDoS & Firewall Ativo',
                ],
            ],
            'premium' => [
                'name' => 'Plano Premium NVMe',
                'badge' => 'Sistemas, APIs & Alta Frequência',
                'color' => 'rose',
                'monthly_price' => '49,90',
                'annual_price' => '499,00',
                'specs' => [
                    '100 GB NVMe Gen5 Dedicado',
                    '4 vCPU Dedicado + 8 GB RAM Dedicada',
                    'Redis Cache Nativo em Memória',
                    'E-mails & Bancos MySQL/Postgres Ilimitados',
                    'Suporte Nativo a Laravel, Node.js & Python',
                    'Git Deploy, Webhooks CI/CD & SSH',
                    'Certificado SSL Wildcard Grátis',
                ],
            ],
        ];

        return view('checkout.index', [
            'selectedPlan' => $selectedPlan,
            'selectedPeriod' => $selectedPeriod,
            'prefilledDomain' => $prefilledDomain,
            'plansInfo' => $plansInfo,
            'user' => auth()->user(),
        ]);
    }

    /**
     * Processa a assinatura, cria fatura e gera o PIX e preferência Mercado Pago.
     */
    public function process(Request $request, MercadoPagoService $mpService): RedirectResponse
    {
        $request->validate([
            'plan' => ['required', 'in:basic,premium'],
            'period' => ['required', 'in:monthly,annual'],
            'domain' => ['required', 'string', 'min:3', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'min:10', 'max:20'],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'domain.required' => 'Informe o domínio que deseja hospedar (ex: seusite.com.br).',
            'name.required' => 'Informe seu nome completo.',
            'email.required' => 'Informe seu e-mail para receber as credenciais.',
            'phone.required' => 'Informe seu número de WhatsApp com DDD.',
        ]);

        // Sanitiza domínio
        $cleanDomain = strtolower(trim($request->domain));
        $cleanDomain = preg_replace('#^https?://#', '', $cleanDomain);
        $cleanDomain = preg_replace('#^www\.#', '', $cleanDomain);
        $cleanDomain = rtrim($cleanDomain, '/');

        // Cria ou localiza Usuário
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password ?? Str::random(12)),
            ]);
        }

        // Cria ou localiza Cliente
        $client = Client::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'phone' => $request->phone,
                'status' => Client::STATUS_ACTIVE,
            ]
        );

        if ($client->phone !== $request->phone || $client->name !== $request->name) {
            $client->update([
                'name' => $request->name,
                'phone' => $request->phone,
            ]);
        }

        // Calcula valor em centavos
        $plan = $request->plan;
        $period = $request->period;
        if ($plan === 'basic') {
            $amountCents = ($period === 'annual') ? 19900 : 1990;
        } else {
            $amountCents = ($period === 'annual') ? 49900 : 4990;
        }

        $invoiceNumber = 'HDP-' . strtoupper(Str::random(6));

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'client_id' => $client->id,
            'amount_cents' => $amountCents,
            'status' => Invoice::STATUS_UNPAID,
            'due_date' => Carbon::now()->addDays(3),
            'payment_method' => Invoice::PAYMENT_PIX,
            'notes' => "Assinatura Hospedagem HostDevPro\nPlano: " . strtoupper($plan) . " (" . ($period === 'annual' ? 'Anual' : 'Mensal') . ")\nDomínio: {$cleanDomain}",
        ]);

        // Gera cobrança PIX via Mercado Pago
        $mpService->createPixPayment($invoice);

        // Autentica o usuário na sessão se não estiver logado
        if (!Auth::check()) {
            Auth::login($user);
        }

        return redirect()->route('checkout.payment', $invoice);
    }

    /**
     * Tela de pagamento com PIX, Cartão de Crédito e Débito via Mercado Pago.
     */
    public function payment(Invoice $invoice, MercadoPagoService $mpService): View
    {
        $invoice->load(['client']);
        $preference = $mpService->createPreference($invoice);

        return view('checkout.payment', [
            'invoice' => $invoice,
            'preferenceUrl' => $preference['init_point'] ?? null,
        ]);
    }

    /**
     * Endpoint para verificação de status em tempo real (polling via JS).
     */
    public function checkStatus(Invoice $invoice): JsonResponse
    {
        return response()->json([
            'status' => $invoice->status,
            'paid' => $invoice->status === Invoice::STATUS_PAID,
            'redirect_url' => route('checkout.success', $invoice),
        ]);
    }

    /**
     * Confirmação / Ativação de Pagamento com disparo do provisionamento.
     */
    public function confirmPayment(Invoice $invoice, HostingProvisioningService $provisioner): RedirectResponse
    {
        if ($invoice->status === Invoice::STATUS_PAID && $invoice->hosting_account_id) {
            return redirect()->route('checkout.success', $invoice);
        }

        $result = $provisioner->provisionAccountForInvoice($invoice);

        if ($result['success']) {
            return redirect()->route('checkout.success', $invoice)->with('success', 'Hospedagem provisionada com sucesso! As credenciais foram enviadas para seu e-mail.');
        }

        return redirect()->route('checkout.payment', $invoice)->with('error', 'Houve um imprevisto no provisionamento: ' . ($result['message'] ?? 'Tente novamente.'));
    }

    /**
     * Tela de Sucesso e Boas-Vindas.
     */
    public function success(Invoice $invoice): View
    {
        $invoice->load(['client', 'hostingAccount.server']);

        return view('checkout.success', [
            'invoice' => $invoice,
            'account' => $invoice->hostingAccount,
        ]);
    }
}
