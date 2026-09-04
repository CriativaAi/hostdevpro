<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Ticket;
use App\Models\TicketReply;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    protected string $apiUrl;
    protected string $instance;
    protected string $token;
    protected ?string $n8nWebhook;

    public function __construct()
    {
        $this->apiUrl = rtrim(config('services.evolution.url', 'http://127.0.0.1:8080'), '/');
        $this->instance = config('services.evolution.instance', 'HostDevPro');
        $this->token = config('services.evolution.token', 'E530B747A900-469A-BB8E-453FFC6032C2');
        $this->n8nWebhook = config('services.n8n.webhook_url');
    }

    /**
     * Higieniza e formata o telefone para o padrão internacional (E.164 sem o +)
     * Exemplo: (11) 92138-1308 -> 5511921381308
     */
    public function formatPhone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $phone);

        if (empty($digits)) {
            return null;
        }

        // Se tem 10 ou 11 dígitos (DDD + número), adiciona DDI 55 do Brasil
        if (strlen($digits) === 10 || strlen($digits) === 11) {
            $digits = '55' . $digits;
        }

        return $digits;
    }

    /**
     * Envia mensagem de texto via Evolution API.
     */
    public function sendMessage(string $phone, string $text): array
    {
        $formattedPhone = $this->formatPhone($phone);
        if (!$formattedPhone) {
            return ['success' => false, 'message' => 'Telefone inválido ou não informado.'];
        }

        try {
            $endpoint = "{$this->apiUrl}/message/sendText/{$this->instance}";

            $response = Http::timeout(10)
                ->withHeaders([
                    'apikey' => $this->token,
                    'Content-Type' => 'application/json',
                ])
                ->post($endpoint, [
                    'number' => $formattedPhone,
                    'text' => $text,
                ]);

            if ($response->successful()) {
                Log::info("WhatsApp enviado com sucesso para {$formattedPhone}", [
                    'instance' => $this->instance,
                    'status' => $response->status(),
                ]);
                return ['success' => true, 'data' => $response->json()];
            }

            Log::warning("Falha ao enviar WhatsApp para {$formattedPhone}", [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return ['success' => false, 'message' => $response->body()];
        } catch (\Throwable $e) {
            Log::error("Erro de conexão ao enviar WhatsApp: {$e->getMessage()}", [
                'phone' => $formattedPhone,
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Envia imagem ou mídia em base64.
     */
    public function sendMedia(string $phone, string $base64, string $fileName, string $caption = ''): array
    {
        $formattedPhone = $this->formatPhone($phone);
        if (!$formattedPhone) {
            return ['success' => false, 'message' => 'Telefone inválido.'];
        }

        try {
            $endpoint = "{$this->apiUrl}/message/sendMedia/{$this->instance}";

            // Limpa o cabeçalho data:image/... se presente
            if (str_contains($base64, ',')) {
                $parts = explode(',', $base64, 2);
                $base64 = $parts[1];
            }

            $response = Http::timeout(15)
                ->withHeaders([
                    'apikey' => $this->token,
                    'Content-Type' => 'application/json',
                ])
                ->post($endpoint, [
                    'number' => $formattedPhone,
                    'mediatype' => 'image',
                    'mimetype' => 'image/png',
                    'caption' => $caption,
                    'media' => $base64,
                    'fileName' => $fileName,
                ]);

            return ['success' => $response->successful(), 'data' => $response->json()];
        } catch (\Throwable $e) {
            Log::error("Erro ao enviar mídia WhatsApp: {$e->getMessage()}");
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Notifica o cliente sobre uma nova fatura gerada com detalhes e PIX.
     */
    public function sendInvoiceCreated(Invoice $invoice): bool
    {
        $invoice->loadMissing(['client']);
        $client = $invoice->client;

        if (!$client || empty($client->phone)) {
            return false;
        }

        $dueDate = $invoice->due_date ? Carbon::parse($invoice->due_date)->format('d/m/Y') : 'À vista';
        $amount = $invoice->amount_formatted ?? ('R$ ' . number_format($invoice->amount_cents / 100, 2, ',', '.'));
        $invoiceUrl = "https://app.hostdevpro.app.br/invoices/{$invoice->id}";

        $msg = "⚡ *HostDevPro — Nova Fatura Gerada*\n\n";
        $msg .= "Olá, *{$client->name}*!\n";
        $msg .= "Sua fatura foi gerada e já está disponível para pagamento.\n\n";
        $msg .= "📄 *Fatura:* `{$invoice->invoice_number}`\n";
        $msg .= "💰 *Valor:* *{$amount}*\n";
        $msg .= "📅 *Vencimento:* {$dueDate}\n\n";

        if (!empty($invoice->pix_copy_paste)) {
            $msg .= "🔑 *PIX Copia e Cola:*\n";
            $msg .= "```{$invoice->pix_copy_paste}```\n\n";
            $msg .= "_Copie o código acima e cole na opção PIX Copia e Cola do seu banco._\n\n";
        }

        $msg .= "🔗 *Acesse sua fatura completa:*\n";
        $msg .= "{$invoiceUrl}\n\n";
        $msg .= "⚡ _HostDevPro Cloud & Hosting Services_";

        $res = $this->sendMessage($client->phone, $msg);

        $this->notifyN8n('invoice.created', [
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'amount_cents' => $invoice->amount_cents,
            'due_date' => $invoice->due_date,
            'client_name' => $client->name,
            'client_phone' => $client->phone,
        ]);

        return $res['success'] ?? false;
    }

    /**
     * Notifica o cliente sobre a confirmação do pagamento.
     */
    public function sendInvoicePaid(Invoice $invoice): bool
    {
        $invoice->loadMissing(['client']);
        $client = $invoice->client;

        if (!$client || empty($client->phone)) {
            return false;
        }

        $paidAt = $invoice->paid_at ? Carbon::parse($invoice->paid_at)->format('d/m/Y H:i') : Carbon::now()->format('d/m/Y H:i');
        $amount = $invoice->amount_formatted ?? ('R$ ' . number_format($invoice->amount_cents / 100, 2, ',', '.'));

        $msg = "✅ *HostDevPro — Pagamento Confirmado!*\n\n";
        $msg .= "Olá, *{$client->name}*!\n";
        $msg .= "Recebemos com sucesso a confirmação do pagamento da sua fatura:\n\n";
        $msg .= "📄 *Fatura:* `{$invoice->invoice_number}`\n";
        $msg .= "💰 *Valor Pago:* {$amount}\n";
        $msg .= "🕒 *Data:* {$paidAt}\n";
        $msg .= "✨ *Status:* Pago & Confirmado\n\n";
        $msg .= "Seus serviços e hospedagens vinculados permanecem 100% ativos e renovados.\n";
        $msg .= "Agradecemos pela parceria e confiança na *HostDevPro*!\n\n";
        $msg .= "⚡ _HostDevPro Cloud & Hosting Services_";

        $res = $this->sendMessage($client->phone, $msg);

        $this->notifyN8n('invoice.paid', [
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'amount_cents' => $invoice->amount_cents,
            'paid_at' => $invoice->paid_at,
            'client_name' => $client->name,
            'client_phone' => $client->phone,
        ]);

        return $res['success'] ?? false;
    }

    /**
     * Notifica lembretes de fatura (vence hoje, em breve ou vencida).
     */
    public function sendInvoiceReminder(Invoice $invoice, string $type = 'due_soon'): bool
    {
        $invoice->loadMissing(['client']);
        $client = $invoice->client;

        if (!$client || empty($client->phone) || $invoice->status === Invoice::STATUS_PAID) {
            return false;
        }

        $dueDate = $invoice->due_date ? Carbon::parse($invoice->due_date)->format('d/m/Y') : 'Imediato';
        $amount = $invoice->amount_formatted ?? ('R$ ' . number_format($invoice->amount_cents / 100, 2, ',', '.'));
        $invoiceUrl = "https://app.hostdevpro.app.br/invoices/{$invoice->id}";

        if ($type === 'due_today') {
            $msg = "⏰ *HostDevPro — Lembrete de Vencimento Hoje*\n\n";
            $msg .= "Olá, *{$client->name}*!\n";
            $msg .= "Lembramos que sua fatura vence *HOJE* ({$dueDate}).\n\n";
        } elseif ($type === 'overdue') {
            $msg = "⚠️ *HostDevPro — Fatura em Atraso*\n\n";
            $msg .= "Olá, *{$client->name}*!\n";
            $msg .= "Notamos que a fatura `{$invoice->invoice_number}` com vencimento em {$dueDate} ainda está pendente.\n\n";
        } else {
            $msg = "📅 *HostDevPro — Aviso de Fatura a Vencer*\n\n";
            $msg .= "Olá, *{$client->name}*!\n";
            $msg .= "Lembramos que sua fatura vence em breve ({$dueDate}).\n\n";
        }

        $msg .= "📄 *Fatura:* `{$invoice->invoice_number}`\n";
        $msg .= "💰 *Valor:* *{$amount}*\n\n";

        if (!empty($invoice->pix_copy_paste)) {
            $msg .= "🔑 *PIX Copia e Cola:*\n";
            $msg .= "```{$invoice->pix_copy_paste}```\n\n";
        }

        $msg .= "🔗 *Acesse para pagar ou emitir 2ª via:*\n";
        $msg .= "{$invoiceUrl}\n\n";
        $msg .= "_Se você já realizou o pagamento nas últimas horas, por favor desconsidere este aviso._\n\n";
        $msg .= "⚡ _HostDevPro Cloud & Hosting Services_";

        $res = $this->sendMessage($client->phone, $msg);

        return $res['success'] ?? false;
    }

    /**
     * Envia evento para o webhook do n8n de forma segura.
     */
    protected function notifyN8n(string $event, array $payload): void
    {
        if (empty($this->n8nWebhook)) {
            return;
        }

        try {
            Http::timeout(3)->post($this->n8nWebhook, [
                'event' => $event,
                'timestamp' => Carbon::now()->toIso8601String(),
                'payload' => $payload,
            ]);
        } catch (\Throwable $e) {
            Log::debug("n8n webhook silenciado ou indisponível: {$e->getMessage()}");
        }
    }
}
