<?php

namespace App\Services;

use App\Mail\HostingAccountWelcomeMail;
use App\Models\Client;
use App\Models\HostingAccount;
use App\Models\Invoice;
use App\Models\Server;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class HostingProvisioningService
{
    protected PleskService $plesk;
    protected WhatsAppNotificationService $whatsapp;

    public function __construct(PleskService $plesk, WhatsAppNotificationService $whatsapp)
    {
        $this->plesk = $plesk;
        $this->whatsapp = $whatsapp;
    }

    /**
     * Provisiona uma conta de hospedagem completa após confirmação de pagamento.
     */
    public function provisionAccountForInvoice(Invoice $invoice, ?string $domain = null, ?string $planKey = 'basic'): array
    {
        $client = $invoice->client;
        if (!$client) {
            return [
                'success' => false,
                'message' => 'Fatura não possui cliente vinculado.',
            ];
        }

        // Tenta extrair o domínio das notas da fatura se não foi passado explicitamente
        if (empty($domain)) {
            if (preg_match('/Domínio:\s*([a-zA-Z0-9\.\-]+)/i', $invoice->notes ?? '', $matches)) {
                $domain = trim($matches[1]);
            } else {
                $domain = 'site-' . substr(md5($client->email), 0, 6) . '.hostdevpro.app.br';
            }
        }

        $domain = strtolower(trim($domain));

        // Determina plano
        if (empty($planKey)) {
            if (str_contains(strtolower($invoice->notes ?? ''), 'premium')) {
                $planKey = 'premium';
            } else {
                $planKey = 'basic';
            }
        }

        $diskQuota = ($planKey === 'premium') ? 102400 : 30720; // 100GB ou 30GB
        $bandwidthQuota = ($planKey === 'premium') ? 1000000 : 100000;

        // Localiza servidor ValueHost ou o primeiro servidor ativo
        $server = Server::where('ip_address', '177.136.254.37')
            ->orWhere('hostname', 'like', '%valueserver%')
            ->first() ?? Server::first();

        if (!$server) {
            $server = Server::create([
                'name' => 'ValueHost Cluster (us163-pl)',
                'hostname' => 'us163-pl.valueserver.net',
                'ip_address' => '177.136.254.37',
                'provider' => 'ValueHost',
                'datacenter_location' => 'São Paulo / Brasil',
                'os' => 'Linux (Plesk Obsidian)',
                'cpu_cores' => 8,
                'ram_mb' => 16384,
                'disk_gb' => 500,
                'ssh_port' => 22,
                'status' => 'online',
                'notes' => "Cluster Plesk Obsidian com DNS ns1/ns2.valueserver.net",
            ]);
        }

        // Gera credenciais seguras
        $cleanDomainPrefix = preg_replace('/[^a-z0-9]/', '', explode('.', $domain)[0] ?? 'hdp');
        $username = substr($cleanDomainPrefix, 0, 10);
        if (strlen($username) < 3) {
            $username = 'usr' . substr(md5($domain), 0, 6);
        }

        // Garante que o username não colida com outro no banco
        $existingCount = HostingAccount::where('username', 'like', "{$username}%")->count();
        if ($existingCount > 0) {
            $username = substr($username, 0, 8) . ($existingCount + 1);
        }

        $plainPassword = 'Hdp' . Str::random(5) . '!' . rand(10, 99);

        // 1. Cria ou localiza no Plesk
        $pleskClient = $this->plesk->findOrCreateClient(
            $client->name,
            $client->email,
            $username,
            $plainPassword
        );

        $pleskClientId = $pleskClient['client_id'] ?? 0;

        // 2. Cria domínio no Plesk
        $pleskDomain = $this->plesk->createDomain(
            $domain,
            $pleskClientId,
            $username,
            $plainPassword,
            $planKey
        );

        // 3. Salva ou atualiza a HostingAccount no banco de dados local
        $hostingAccount = HostingAccount::updateOrCreate(
            ['domain' => $domain],
            [
                'client_id' => $client->id,
                'server_id' => $server->id,
                'username' => $username,
                'plan' => $planKey,
                'php_version' => '8.4',
                'disk_quota_mb' => $diskQuota,
                'disk_used_mb' => 0,
                'bandwidth_quota_mb' => $bandwidthQuota,
                'ssl_status' => HostingAccount::SSL_ACTIVE,
                'status' => HostingAccount::STATUS_ACTIVE,
                'notes' => "Provisionamento automatizado HostDevPro via Checkout.\nPlesk Client ID: {$pleskClientId}\nSenha Inicial: {$plainPassword}",
            ]
        );

        // 4. Atualiza a fatura
        $invoice->update([
            'hosting_account_id' => $hostingAccount->id,
            'status' => Invoice::STATUS_PAID,
            'paid_at' => Carbon::now(),
        ]);

        $serverDetails = [
            'ip' => $server->ip_address,
            'hostname' => $server->hostname,
            'location' => $server->datacenter_location,
        ];

        $dnsDetails = [
            'ns1' => 'ns1.valueserver.net',
            'ns2' => 'ns2.valueserver.net',
            'ns3' => 'ns3.valueserver.net',
            'ns4' => 'ns4.valueserver.net',
            'ip' => $server->ip_address,
        ];

        // 5. Envia E-mail HTML de Boas-Vindas com dados completos
        try {
            Mail::to($client->email)->send(
                new HostingAccountWelcomeMail(
                    $client,
                    $hostingAccount,
                    $plainPassword,
                    $dnsDetails,
                    $serverDetails
                )
            );
            Log::info("E-mail de boas-vindas da hospedagem enviado com sucesso para {$client->email}");
        } catch (\Exception $e) {
            Log::error("Falha ao enviar e-mail de boas-vindas: " . $e->getMessage());
        }

        // 6. Envia notificação no WhatsApp do cliente
        if (!empty($client->phone)) {
            $this->sendHostingWelcomeWhatsApp($client, $hostingAccount);
        }

        return [
            'success' => true,
            'account' => $hostingAccount,
            'plain_password' => $plainPassword,
            'plesk_result' => $pleskDomain,
        ];
    }

    /**
     * Envia mensagem de boas-vindas pelo WhatsApp via Evolution API.
     */
    protected function sendHostingWelcomeWhatsApp(Client $client, HostingAccount $account): void
    {
        try {
            $msg = "🚀 *HostDevPro Cloud - Hospedagem Ativada!*\n\n";
            $msg .= "Olá, *{$client->name}*!\n\n";
            $msg .= "Sua conta de hospedagem para o domínio *{$account->domain}* acaba de ser ativada com sucesso em nossos servidores NVMe de alta performance!\n\n";
            $msg .= "🌐 *IP do Servidor:* `177.136.254.37`\n";
            $msg .= "🔑 *Usuário do Painel:* `{$account->username}`\n";
            $msg .= "⚡ *Servidores DNS:*\n";
            $msg .= "• `ns1.valueserver.net`\n";
            $msg .= "• `ns2.valueserver.net`\n\n";
            $msg .= "✉️ Enviamos todos os dados completos de acesso (senhas, FTP e Webmail) para o seu e-mail: *{$client->email}*.\n\n";
            $msg .= "👉 Acesse seu painel: https://app.hostdevpro.app.br/dashboard\n\n";
            $msg .= "_HostDevPro - Velocidade Extrema e Suporte Dedicado._";

            $cleanPhone = preg_replace('/\D/', '', $client->phone);
            if (strlen($cleanPhone) <= 11 && !str_starts_with($cleanPhone, '55')) {
                $cleanPhone = '55' . $cleanPhone;
            }

            // Utiliza o cliente HTTP direto para o endpoint Evolution
            $url = rtrim(config('services.evolution.url', 'http://127.0.0.1:8080'), '/');
            $instance = config('services.evolution.instance', 'HostDevPro');
            $token = config('services.evolution.token', '');

            \Illuminate\Support\Facades\Http::withHeaders([
                'apikey' => $token,
            ])->timeout(10)->post("{$url}/message/sendText/{$instance}", [
                'number' => $cleanPhone,
                'text' => $msg,
            ]);
        } catch (\Exception $e) {
            Log::warning("WhatsApp welcome alert failed: " . $e->getMessage());
        }
    }
}
