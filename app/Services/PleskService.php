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

    /**
     * Consulta registros DNS ativos no cluster Plesk.
     */
    public function getDnsRecords(string $domain): array
    {
        if (empty($this->password)) {
            return [];
        }

        try {
            $response = Http::withoutVerifying()
                ->withBasicAuth($this->username, $this->password)
                ->timeout(15)
                ->get($this->host . '/api/v2/dns/records', [
                    'domain' => strtolower(trim($domain)),
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return is_array($data) ? $data : [];
            }

            Log::warning("PleskService: Erro ao listar DNS para {$domain}: " . $response->body());
            return [];
        } catch (\Exception $e) {
            Log::error("PleskService: Exceção ao listar DNS: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Adiciona um novo registro DNS no Plesk.
     */
    public function addDnsRecord(string $domain, string $type, string $host, string $value, ?string $opt = null): array
    {
        if (empty($this->password)) {
            return [
                'success' => false,
                'message' => 'Serviço Plesk não configurado.',
            ];
        }

        try {
            $payload = [
                'domain' => strtolower(trim($domain)),
                'type' => strtoupper(trim($type)),
                'host' => trim($host),
                'value' => trim($value),
            ];

            if (!empty($opt)) {
                $payload['opt'] = trim($opt);
            }

            $response = Http::withoutVerifying()
                ->withBasicAuth($this->username, $this->password)
                ->timeout(15)
                ->post($this->host . '/api/v2/dns/records', $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => $response->body(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Remove um registro DNS pelo ID no Plesk.
     */
    public function deleteDnsRecord(int $recordId): bool
    {
        if (empty($this->password)) {
            return false;
        }

        try {
            $response = Http::withoutVerifying()
                ->withBasicAuth($this->username, $this->password)
                ->timeout(15)
                ->delete($this->host . "/api/v2/dns/records/{$recordId}");

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("PleskService: Exceção ao excluir DNS {$recordId}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lista bancos de dados MySQL associados a um domínio ou conta.
     */
    public function getDatabases(?string $domain = null): array
    {
        if (empty($this->password)) {
            return [];
        }

        try {
            $query = [];
            if ($domain) {
                // Tenta buscar o domain_id primeiro se necessário
                $domainInfo = $this->getDomainInfo($domain);
                if (!empty($domainInfo['id'])) {
                    $query['domain_id'] = $domainInfo['id'];
                }
            }

            $response = Http::withoutVerifying()
                ->withBasicAuth($this->username, $this->password)
                ->timeout(15)
                ->get($this->host . '/api/v2/databases', $query);

            if ($response->successful()) {
                $data = $response->json();
                return is_array($data) ? $data : [];
            }

            return [];
        } catch (\Exception $e) {
            Log::error("PleskService: Exceção ao listar databases: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Cria um novo banco de dados MySQL e usuário associado no Plesk.
     */
    public function createDatabase(string $domain, string $dbName, string $username, string $password): array
    {
        if (empty($this->password)) {
            return [
                'success' => false,
                'message' => 'Serviço Plesk não configurado.',
            ];
        }

        try {
            $domainInfo = $this->getDomainInfo($domain);
            if (empty($domainInfo['id'])) {
                return [
                    'success' => false,
                    'message' => "Domínio {$domain} não localizado no Plesk.",
                ];
            }

            $domainId = $domainInfo['id'];

            // 1. Cria a base de dados
            $dbRes = Http::withoutVerifying()
                ->withBasicAuth($this->username, $this->password)
                ->timeout(20)
                ->post($this->host . '/api/v2/databases', [
                    'name' => strtolower(trim($dbName)),
                    'type' => 'mysql',
                    'parent_domain' => [
                        'id' => $domainId,
                    ],
                ]);

            if (!$dbRes->successful()) {
                return [
                    'success' => false,
                    'message' => 'Falha ao criar banco: ' . $dbRes->body(),
                ];
            }

            $dbData = $dbRes->json();
            $newDbId = $dbData['id'] ?? null;

            // 2. Cria o usuário com acesso à base de dados
            if ($newDbId && !empty($username) && !empty($password)) {
                Http::withoutVerifying()
                    ->withBasicAuth($this->username, $this->password)
                    ->timeout(20)
                    ->post($this->host . '/api/v2/database-users', [
                        'name' => strtolower(trim($username)),
                        'password' => $password,
                        'database_id' => $newDbId,
                    ]);
            }

            return [
                'success' => true,
                'database' => $dbData,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Retorna detalhes de um domínio no Plesk.
     */
    public function getDomainInfo(string $domain): ?array
    {
        if (empty($this->password)) {
            return null;
        }

        try {
            $response = Http::withoutVerifying()
                ->withBasicAuth($this->username, $this->password)
                ->timeout(15)
                ->get($this->host . '/api/v2/domains');

            if ($response->successful()) {
                $domains = $response->json();
                if (is_array($domains)) {
                    foreach ($domains as $d) {
                        if (strcasecmp($d['name'] ?? '', trim($domain)) === 0) {
                            return $d;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("PleskService: Erro ao obter info do domínio: " . $e->getMessage());
        }

        return null;
    }
}

