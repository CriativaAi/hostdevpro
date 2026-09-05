<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Ticket;
use App\Models\TicketReply;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiSupportService
{
    protected ?string $apiKey;
    protected array $models = [
        'gemini-2.5-flash',
        'gemini-1.5-flash',
        'gemini-flash-latest',
        'gemini-2.0-flash',
    ];

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
    }

    /**
     * Executa diagnóstico em tempo real de infraestrutura para o chamado.
     */
    public function diagnoseTicket(Ticket $ticket): array
    {
        $ticket->loadMissing(['client', 'hostingAccount.server', 'server', 'replies']);

        $domain = $this->extractDomain($ticket);
        $dnsData = ['ns' => [], 'a' => [], 'is_hostdevpro_ns' => false, 'is_resolved' => false];
        $httpData = ['status' => null, 'time_ms' => null, 'ssl_valid' => false, 'online' => false];

        if ($domain) {
            $dnsData = $this->checkDns($domain);
            $httpData = $this->checkHttpAndSsl($domain);
        }

        // Checagem financeira do cliente
        $client = $ticket->client;
        $overdueInvoices = [];
        if ($client) {
            $overdueInvoices = Invoice::where('client_id', $client->id)
                ->where('status', '!=', Invoice::STATUS_PAID)
                ->where('due_date', '<', Carbon::today())
                ->get(['id', 'invoice_number', 'amount_cents', 'due_date'])
                ->toArray();
        }

        $server = $ticket->server ?? $ticket->hostingAccount?->server;

        return [
            'domain' => $domain,
            'dns' => $dnsData,
            'http' => $httpData,
            'server_name' => $server?->name ?? 'ValueHost Cluster (us163-pl)',
            'server_ip' => $server?->ip_address ?? '177.136.254.37',
            'overdue_invoices' => $overdueInvoices,
            'has_financial_block' => !empty($overdueInvoices),
            'timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * Gera resposta técnica inteligente usando o Google Gemini alimentado com a telemetria do chamado.
     */
    public function generateReply(Ticket $ticket, ?string $instructions = null): array
    {
        $diagnostics = $this->diagnoseTicket($ticket);
        $ticket->loadMissing(['client', 'replies']);

        $conversationHistory = "";
        foreach ($ticket->replies as $reply) {
            $role = $reply->is_internal_note ? "[NOTA INTERNA - PRIVADA]" : ($reply->is_staff ? "[SUPORTE HUMANO]" : ($reply->is_ai ? "[IA GEMINI]" : "[CLIENTE]"));
            $conversationHistory .= "{$role} {$reply->author_name} ({$reply->created_at->format('d/m/Y H:i')}):\n{$reply->message}\n\n";
        }

        $domain = $diagnostics['domain'] ?? 'Não especificado';
        $dnsStatus = $diagnostics['dns']['is_hostdevpro_ns'] ? "Correto (apontando para ns1/ns2.valueserver.net)" : "Atenção: Nameservers atuais (" . implode(', ', $diagnostics['dns']['ns'] ?? ['Nenhum']) . ")";
        $ipStatus = implode(', ', $diagnostics['dns']['a'] ?? ['Nenhum IP resolvido']);
        $httpStatus = $diagnostics['http']['online'] ? "Online (HTTP {$diagnostics['http']['status']}, {$diagnostics['http']['time_ms']}ms)" : "Inacessível ou não responde";
        $sslStatus = $diagnostics['http']['ssl_valid'] ? "Válido / Ativo" : "Não detectado ou pendente de ativação";
        $financeStatus = $diagnostics['has_financial_block'] ? "Constam faturas vencidas no financeiro" : "Em dia (Sem bloqueios)";

        $prompt = <<<EOT
Você é o **HostDevPro AI Copilot**, o Engenheiro Sênior de Suporte Técnico & Nuvem da **HostDevPro Cloud** (app.hostdevpro.app.br).
Sua missão é responder ao cliente titular com clareza exemplar, empatia, precisão técnica cirúrgica e orientações práticas passo a passo.

### DADOS OFICIAIS DA PLATAFORMA HOSTDEVPRO:
- **DNS Primário Oficial:** `ns1.valueserver.net` (IP: `177.136.254.37`)
- **DNS Secundário Oficial:** `ns2.valueserver.net` (IP: `177.136.254.38`)
- **Painel de Hospedagem Plesk:** `https://us163-pl.valueserver.net:8443`
- **Webmail Oficial:** `https://webmail.hostdevpro.app.br` (Roundcube)
- **Portas de E-mail:** IMAP: 993 (SSL/TLS), SMTP: 465 (SSL/TLS) ou 587 (STARTTLS), POP3: 995 (SSL/TLS)
- **Servidor do Cliente:** {$diagnostics['server_name']} (IP: {$diagnostics['server_ip']})
- **WhatsApp de Suporte:** +55 (11) 92138-1308

### TELEMETRIA REAL DO DOMÍNIO EM TEMPO REAL:
- **Domínio Analisado:** {$domain}
- **Status DNS (NS):** {$dnsStatus}
- **Apontamento A:** {$ipStatus}
- **Conectividade Web:** {$httpStatus}
- **Certificado SSL:** {$sslStatus}
- **Situação Financeira:** {$financeStatus}

### DADOS DO CHAMADO #{$ticket->ticket_number}:
- **Assunto:** {$ticket->subject}
- **Departamento:** {$ticket->department_label}
- **Prioridade:** {$ticket->priority_label}
- **Cliente:** {$ticket->client?->name} ({$ticket->client?->email})

### HISTÓRICO DA CONVERSA:
{$conversationHistory}

### DIRETRIZES DA RESPOSTA:
1. Responda em Português do Brasil de forma polida, ágil e resolutiva.
2. Formate sua resposta em Markdown rico (use títulos curtos, tópicos, listas numeradas e blocos de código `code` para dados de configuração).
3. Use os dados reais da telemetria acima. Se o DNS estiver incorreto, informe exatamente os DNS que ele deve colocar no Registro.br ou onde comprou o domínio (`ns1.valueserver.net` e `ns2.valueserver.net`).
4. Se for problema de e-mail, liste as portas e servidores exatos.
5. Se for dúvida de certificado SSL, explique que o Let's Encrypt é gratuito e pode ser ativado com 1 clique no painel Plesk.
6. Se o cliente relatar falha crítica que exige intervenção física no servidor (como reinicialização de hardware ou migração assistida de banco), assegure que o time sênior já foi alertado.
7. Finalize sempre com uma saudação cordial de encerramento da HostDevPro Cloud.

{$instructions}
EOT;

        $replyText = $this->callGeminiApi($prompt);

        return [
            'success' => true,
            'message' => $replyText,
            'diagnostics' => $diagnostics,
        ];
    }

    /**
     * Executa resposta automática para o chamado e notifica o cliente via WhatsApp se configurado.
     */
    public function autoReplyAndNotify(Ticket $ticket, ?string $instructions = null): ?TicketReply
    {
        try {
            $response = $this->generateReply($ticket, $instructions);

            if (empty($response['message'])) {
                return null;
            }

            // Cria mensagem oficial da IA no chamado
            $reply = TicketReply::create([
                'ticket_id' => $ticket->id,
                'user_id' => null,
                'client_id' => null,
                'author_name' => 'HostDevPro AI Copilot',
                'author_type' => TicketReply::AUTHOR_TYPE_AI,
                'message' => $response['message'],
                'is_internal_note' => false,
            ]);

            // Atualiza status do chamado para Respondido
            $ticket->update([
                'status' => Ticket::STATUS_ANSWERED,
                'last_reply_at' => now(),
            ]);

            // Notifica o cliente via WhatsApp se houver telefone
            $whatsappService = app(WhatsAppNotificationService::class);
            $whatsappService->sendTicketAiReply($ticket, $response['message']);

            return $reply;
        } catch (\Throwable $e) {
            Log::error("Erro no auto-atendimento Gemini para ticket #{$ticket->ticket_number}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Extrai domínio do ticket a partir da conta vinculada ou do texto.
     */
    protected function extractDomain(Ticket $ticket): ?string
    {
        if ($ticket->hostingAccount && !empty($ticket->hostingAccount->domain)) {
            return strtolower(trim($ticket->hostingAccount->domain));
        }

        // Tenta regex no assunto e mensagens
        $corpus = $ticket->subject . " " . ($ticket->replies->first()?->message ?? '');
        if (preg_match('/([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+(com\.br|app\.br|dev\.br|com|net|org|io|cloud|site|online|tech)/i', $corpus, $matches)) {
            return strtolower(trim($matches[0]));
        }

        return null;
    }

    /**
     * Realiza checagem de DNS do domínio.
     */
    protected function checkDns(string $domain): array
    {
        $nsRecords = [];
        $aRecords = [];
        $isHostDevPro = false;

        try {
            $ns = @dns_get_record($domain, DNS_NS);
            if (is_array($ns)) {
                foreach ($ns as $record) {
                    if (isset($record['target'])) {
                        $target = strtolower($record['target']);
                        $nsRecords[] = $target;
                        if (str_contains($target, 'valueserver.net') || str_contains($target, 'hostdevpro')) {
                            $isHostDevPro = true;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::debug("DNS NS lookup failed for {$domain}: {$e->getMessage()}");
        }

        try {
            $a = @dns_get_record($domain, DNS_A);
            if (is_array($a)) {
                foreach ($a as $record) {
                    if (isset($record['ip'])) {
                        $aRecords[] = $record['ip'];
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::debug("DNS A lookup failed for {$domain}: {$e->getMessage()}");
        }

        return [
            'ns' => array_unique($nsRecords),
            'a' => array_unique($aRecords),
            'is_hostdevpro_ns' => $isHostDevPro,
            'is_resolved' => !empty($aRecords),
        ];
    }

    /**
     * Testa resposta HTTP e validade do SSL do domínio.
     */
    protected function checkHttpAndSsl(string $domain): array
    {
        $status = null;
        $timeMs = null;
        $sslValid = false;
        $online = false;

        $start = microtime(true);
        try {
            $response = Http::timeout(4)
                ->withoutVerifying()
                ->get("https://{$domain}");

            $timeMs = (int) round((microtime(true) - $start) * 1000);
            $status = $response->status();
            $online = ($status >= 200 && $status < 400);
        } catch (\Throwable $e) {
            // Tenta fallback HTTP simples
            try {
                $response = Http::timeout(3)->get("http://{$domain}");
                $status = $response->status();
                $online = ($status >= 200 && $status < 400);
            } catch (\Throwable $e2) {
                // Inacessível
            }
        }

        // Checagem de certificado SSL via stream socket
        try {
            $g = stream_context_create([
                "ssl" => [
                    "capture_peer_cert" => true,
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ],
            ]);
            $r = @stream_socket_client("ssl://{$domain}:443", $errno, $errstr, 3, STREAM_CLIENT_CONNECT, $g);
            if ($r) {
                $cont = stream_context_get_params($r);
                if (isset($cont["options"]["ssl"]["peer_certificate"])) {
                    $cert = openssl_x509_parse($cont["options"]["ssl"]["peer_certificate"]);
                    if ($cert && isset($cert['validTo_time_t']) && $cert['validTo_time_t'] > time()) {
                        $sslValid = true;
                    }
                }
                fclose($r);
            }
        } catch (\Throwable $e) {
            Log::debug("SSL socket check error: {$e->getMessage()}");
        }

        return [
            'status' => $status,
            'time_ms' => $timeMs,
            'ssl_valid' => $sslValid,
            'online' => $online,
        ];
    }

    /**
     * Envia prompt para o Google Gemini com fallback automático entre modelos.
     */
    protected function callGeminiApi(string $prompt): string
    {
        if (empty($this->apiKey)) {
            throw new \RuntimeException('A chave GEMINI_API_KEY não está configurada no ambiente.');
        }

        $lastError = null;

        foreach ($this->models as $model) {
            try {
                $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$this->apiKey}";

                $response = Http::timeout(25)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post($url, [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $prompt]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => 0.4,
                            'maxOutputTokens' => 1500,
                        ],
                    ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $text = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    if (!empty($text)) {
                        return trim($text);
                    }
                }

                $lastError = "Modelo {$model} retornou status {$response->status()}: " . $response->body();
                Log::warning("Gemini modelo {$model} retornou falha, tentando próximo...", ['error' => $lastError]);
            } catch (\Throwable $e) {
                $lastError = $e->getMessage();
                Log::warning("Exceção ao chamar Gemini {$model}: {$e->getMessage()}");
            }
        }

        throw new \RuntimeException('Falha ao comunicar com a API do Google Gemini: ' . ($lastError ?? 'Nenhum modelo respondeu.'));
    }
}
