<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PleskService
{
    protected string $host;
    protected string $username;
    protected string $password;

    public function __construct()
    {
        $this->host = rtrim(config('services.plesk.host', 'https://us163-pl.valueserver.net:8443'), '/');
        $this->username = config('services.plesk.username', 'alexcla1');
        $this->password = config('services.plesk.password', '');
    }

    /**
     * Cria ou localiza um cliente no Plesk Obsidian.
     */
    public function findOrCreateClient(string $name, string $email, string $login, string $password): array
    {
        if (empty($this->password)) {
            Log::warning('PleskService: Senha do Plesk não configurada. Operando em modo simulado.');
            return [
                'success' => true,
                'client_id' => 9999,
                'login' => $login,
                'simulated' => true,
            ];
        }

        // 1. Tenta listar clientes para ver se já existe pelo e-mail ou login
        try {
            $response = Http::withoutVerifying()
                ->withBasicAuth($this->username, $this->password)
                ->timeout(15)
                ->get($this->host . '/api/v2/clients');

            if ($response->successful()) {
                $clients = $response->json();
                if (is_array($clients)) {
                    foreach ($clients as $client) {
                        if (($client['email'] ?? '') === $email || ($client['login'] ?? '') === $login) {
                            return [
                                'success' => true,
                                'client_id' => $client['id'],
                                'login' => $client['login'],
                                'simulated' => false,
                            ];
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('PleskService: Erro ao listar clientes: ' . $e->getMessage());
        }

        // 2. Cria o novo cliente
        try {
            $cleanLogin = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $login));
            if (strlen($cleanLogin) < 3) {
                $cleanLogin = 'cli' . substr(md5($email), 0, 6);
            }
            if (strlen($cleanLogin) > 16) {
                $cleanLogin = substr($cleanLogin, 0, 16);
            }

            $response = Http::withoutVerifying()
                ->withBasicAuth($this->username, $this->password)
                ->timeout(20)
                ->post($this->host . '/api/v2/clients', [
                    'name' => $name,
                    'company' => '',
                    'login' => $cleanLogin,
                    'email' => $email,
                    'password' => $password,
                    'type' => 'customer',
                    'locale' => 'pt-BR',
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'client_id' => $data['id'] ?? null,
                    'login' => $cleanLogin,
                    'simulated' => false,
                ];
            }

            Log::warning('PleskService: Resposta ao criar cliente: ' . $response->body());

            return [
                'success' => true,
                'client_id' => null,
                'login' => $cleanLogin,
                'simulated' => true,
                'raw_error' => $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('PleskService: Exceção ao criar cliente: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'simulated' => true,
            ];
        }
    }

    /**
     * Cria um domínio/assinatura de hospedagem no Plesk.
     */
    public function createDomain(
        string $domain,
        int $clientId,
        string $ftpLogin,
        string $ftpPassword,
        ?string $planName = null
    ): array {
        if (empty($this->password)) {
            return [
                'success' => true,
                'domain_id' => 9999,
                'domain' => $domain,
                'simulated' => true,
            ];
        }

        try {
            $cleanFtpLogin = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $ftpLogin));
            if (strlen($cleanFtpLogin) < 3) {
                $cleanFtpLogin = 'ftp' . substr(md5($domain), 0, 5);
            }
            if (strlen($cleanFtpLogin) > 16) {
                $cleanFtpLogin = substr($cleanFtpLogin, 0, 16);
            }

            $payload = [
                'name' => strtolower(trim($domain)),
                'description' => 'Hospedagem HostDevPro Cloud - ' . ($planName ?? 'Plano Pro'),
                'hosting_type' => 'virtual',
                'hosting_settings' => [
                    'ftp_login' => $cleanFtpLogin,
                    'ftp_password' => $ftpPassword,
                ],
            ];

            if ($clientId > 0) {
                $payload['owner_client'] = [
                    'id' => $clientId,
                ];
            }

            $response = Http::withoutVerifying()
                ->withBasicAuth($this->username, $this->password)
                ->timeout(30)
                ->post($this->host . '/api/v2/domains', $payload);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'domain_id' => $data['id'] ?? null,
                    'guid' => $data['guid'] ?? null,
                    'domain' => $domain,
                    'ftp_login' => $cleanFtpLogin,
                    'simulated' => false,
                ];
            }

            Log::warning('PleskService: Resposta ao criar domínio: ' . $response->body());

            // Se o domínio já existir no Plesk, consideramos sucesso reutilizando
            if (str_contains($response->body(), 'already exists') || str_contains($response->body(), 'Duplicate')) {
                return [
                    'success' => true,
                    'domain_id' => null,
                    'domain' => $domain,
                    'ftp_login' => $cleanFtpLogin,
                    'existing' => true,
                ];
            }

            return [
                'success' => false,
                'domain' => $domain,
                'ftp_login' => $cleanFtpLogin,
                'error' => $response->body(),
                'simulated' => true,
            ];
        } catch (\Exception $e) {
            Log::error('PleskService: Exceção ao criar domínio: ' . $e->getMessage());
            return [
                'success' => false,
                'domain' => $domain,
                'error' => $e->getMessage(),
                'simulated' => true,
            ];
        }
    }
}
