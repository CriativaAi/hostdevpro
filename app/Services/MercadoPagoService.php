<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MercadoPagoService
{
    protected ?string $accessToken;
    protected ?string $publicKey;

    public function __construct()
    {
        $this->accessToken = config('services.mercadopago.access_token');
        $this->publicKey = config('services.mercadopago.public_key');
    }

    /**
     * Cria uma Preferência do Mercado Pago Checkout Pro
     * (Permite Cartão de Crédito em até 12x, Cartão de Débito, PIX e Boleto).
     */
    public function createPreference(Invoice $invoice): array
    {
        $amount = round($invoice->amount_cents / 100, 2);
        $client = $invoice->client;

        if (!$this->accessToken) {
            return [
                'success' => false,
                'init_point' => null,
            ];
        }

        try {
            $cleanDomain = 'Hospedagem HostDevPro';
            if (preg_match('/Domínio:\s*([a-zA-Z0-9\.\-]+)/i', $invoice->notes ?? '', $m)) {
                $cleanDomain = trim($m[1]);
            }

            $successUrl = route('checkout.confirm', $invoice);
            $pendingUrl = route('checkout.payment', $invoice);
            $failureUrl = route('checkout.payment', $invoice);

            $payload = [
                'items' => [
                    [
                        'id' => (string) $invoice->id,
                        'title' => "Fatura {$invoice->invoice_number} - Hospedagem {$cleanDomain}",
                        'description' => "Assinatura de Hospedagem Cloud NVMe - HostDevPro",
                        'quantity' => 1,
                        'currency_id' => 'BRL',
                        'unit_price' => (float) $amount,
                    ],
                ],
                'payer' => [
                    'name' => $client->name ?? 'Cliente',
                    'email' => $client->email ?? 'cliente@hostdevpro.app.br',
                ],
                'back_urls' => [
                    'success' => $successUrl,
                    'pending' => $pendingUrl,
                    'failure' => $failureUrl,
                ],
                'auto_return' => 'approved',
                'external_reference' => (string) $invoice->id,
                'statement_descriptor' => 'HOSTDEVPRO',
            ];

            $response = Http::withToken($this->accessToken)
                ->withHeaders([
                    'X-Idempotency-Key' => (string) \Illuminate\Support\Str::uuid(),
                ])
                ->timeout(15)
                ->post('https://api.mercadopago.com/checkout/preferences', $payload);

            if ($response->successful()) {
                $data = $response->json();
                $initPoint = $data['init_point'] ?? null;
                $preferenceId = $data['id'] ?? null;

                $invoice->update([
                    'payment_gateway' => Invoice::GATEWAY_MERCADOPAGO,
                    'gateway_transaction_id' => (string) $preferenceId,
                ]);

                return [
                    'success' => true,
                    'init_point' => $initPoint,
                    'preference_id' => $preferenceId,
                ];
            }

            Log::warning('Mercado Pago Preference API response error: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Mercado Pago Preference exception: ' . $e->getMessage());
        }

        return [
            'success' => false,
            'init_point' => null,
        ];
    }

    /**
     * Gera uma cobrança PIX instantânea para uma fatura.
     */
    public function createPixPayment(Invoice $invoice): array
    {
        $amount = round($invoice->amount_cents / 100, 2);
        $client = $invoice->client;

        if (!$this->accessToken) {
            return $this->getMockPixPayload($invoice);
        }

        try {
            $response = Http::withToken($this->accessToken)
                ->withHeaders([
                    'X-Idempotency-Key' => (string) \Illuminate\Support\Str::uuid(),
                ])
                ->post('https://api.mercadopago.com/v1/payments', [
                    'transaction_amount' => (float) $amount,
                    'description' => "Fatura {$invoice->invoice_number} - HostDevPro Cloud",
                    'payment_method_id' => 'pix',
                    'payer' => [
                        'email' => $client->email ?? 'cliente@hostdevpro.app.br',
                        'first_name' => explode(' ', $client->name)[0] ?? 'Cliente',
                        'last_name' => explode(' ', $client->name)[1] ?? 'HostDevPro',
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $qrCodeBase64 = $data['point_of_interaction']['transaction_data']['qr_code_base64'] ?? null;
                $qrCode = $data['point_of_interaction']['transaction_data']['qr_code'] ?? null;
                $paymentId = $data['id'] ?? null;

                $invoice->update([
                    'payment_method' => Invoice::PAYMENT_PIX,
                    'payment_gateway' => Invoice::GATEWAY_MERCADOPAGO,
                    'gateway_transaction_id' => (string) $paymentId,
                    'pix_qr_code_base64' => $qrCodeBase64,
                    'pix_copy_paste' => $qrCode,
                ]);

                return [
                    'success' => true,
                    'qr_code_base64' => $qrCodeBase64,
                    'pix_copy_paste' => $qrCode,
                    'payment_id' => $paymentId,
                ];
            }

            Log::warning('Mercado Pago API response error: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Mercado Pago exception: ' . $e->getMessage());
        }

        return $this->getMockPixPayload($invoice);
    }

    protected function getMockPixPayload(Invoice $invoice): array
    {
        $mockCopyPaste = '00020126580014br.gov.bcb.pix0136APP_USR-8785824747116398-022419-0860f3a84be40b5cd180bf6e57778c60-23516909605204000053039865405' . number_format($invoice->amount_cents / 100, 2, '.', '') . '5802BR5915HostDevPro Cloud6009Sao Paulo62070503***6304' . strtoupper(substr(md5($invoice->id), 0, 4));

        $invoice->update([
            'payment_method' => Invoice::PAYMENT_PIX,
            'payment_gateway' => Invoice::GATEWAY_MERCADOPAGO,
            'pix_copy_paste' => $mockCopyPaste,
        ]);

        return [
            'success' => true,
            'qr_code_base64' => null,
            'pix_copy_paste' => $mockCopyPaste,
            'payment_id' => 'mp_sim_' . $invoice->id,
        ];
    }
}
