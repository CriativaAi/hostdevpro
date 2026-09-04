<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StripeService
{
    protected ?string $secretKey;
    protected ?string $publicKey;

    public function __construct()
    {
        $this->secretKey = config('services.stripe.secret');
        $this->publicKey = config('services.stripe.key');
    }

    /**
     * Cria uma sessão de checkout do Stripe para a fatura.
     */
    public function createCheckoutSession(Invoice $invoice, string $successUrl, string $cancelUrl): array
    {
        if (!$this->secretKey) {
            return [
                'success' => false,
                'message' => 'Stripe secret key not configured.',
            ];
        }

        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->asForm()
                ->post('https://api.stripe.com/v1/checkout/sessions', [
                    'payment_method_types' => ['card'],
                    'line_items' => [
                        [
                            'price_data' => [
                                'currency' => 'brl',
                                'product_data' => [
                                    'name' => "Fatura {$invoice->invoice_number} - HostDevPro Cloud",
                                    'description' => $invoice->notes ?? 'Serviços de Infraestrutura e Hospedagem',
                                ],
                                'unit_amount' => $invoice->amount_cents,
                            ],
                            'quantity' => 1,
                        ],
                    ],
                    'mode' => 'payment',
                    'success_url' => $successUrl,
                    'cancel_url' => $cancelUrl,
                    'client_reference_id' => (string) $invoice->id,
                    'customer_email' => $invoice->client->email ?? null,
                ]);

            if ($response->successful()) {
                $session = $response->json();
                $invoice->update([
                    'payment_method' => Invoice::PAYMENT_CREDIT_CARD,
                    'payment_gateway' => Invoice::GATEWAY_STRIPE,
                    'gateway_transaction_id' => $session['id'] ?? null,
                ]);

                return [
                    'success' => true,
                    'checkout_url' => $session['url'] ?? null,
                    'session_id' => $session['id'] ?? null,
                ];
            }

            Log::warning('Stripe API error: ' . $response->body());
            return [
                'success' => false,
                'message' => $response->json()['error']['message'] ?? 'Erro ao conectar ao Stripe.',
            ];
        } catch (\Exception $e) {
            Log::error('Stripe exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
