<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\HostingAccount;
use App\Models\Server;
use Illuminate\Database\Seeder;

class HostingAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $server = Server::where('ip_address', '209.50.245.45')->first() ?? Server::first();

        $carlos = Client::where('email', 'carlos.menezes@alphadev.com.br')->first();
        $mariana = Client::where('email', 'mariana@nexustecnologia.io')->first();
        $rodrigo = Client::where('email', 'rodrigo@fagundesconsultoria.com.br')->first();
        $beatriz = Client::where('email', 'b.vasconcelos@startuppro.com.br')->first();

        $defaultClientId = Client::first()?->id;
        $serverId = $server?->id;

        if (!$serverId || !$defaultClientId) {
            return;
        }

        $accounts = [
            [
                'client_id' => $carlos ? $carlos->id : $defaultClientId,
                'server_id' => $serverId,
                'domain' => 'alphadev.com.br',
                'username' => 'usr_alphadev',
                'plan' => HostingAccount::PLAN_PRO,
                'php_version' => '8.5',
                'disk_quota_mb' => 15360,
                'disk_used_mb' => 4820,
                'bandwidth_quota_mb' => 100000,
                'ssl_status' => HostingAccount::SSL_ACTIVE,
                'status' => HostingAccount::STATUS_ACTIVE,
                'suspended_reason' => null,
                'notes' => 'Portal corporativo e API Laravel em produção. SSL Let\'s Encrypt renovado.',
            ],
            [
                'client_id' => $mariana ? $mariana->id : $defaultClientId,
                'server_id' => $serverId,
                'domain' => 'nexuscloud.io',
                'username' => 'usr_nexuscloud',
                'plan' => HostingAccount::PLAN_ENTERPRISE,
                'php_version' => '8.5',
                'disk_quota_mb' => 51200,
                'disk_used_mb' => 18450,
                'bandwidth_quota_mb' => 500000,
                'ssl_status' => HostingAccount::SSL_ACTIVE,
                'status' => HostingAccount::STATUS_ACTIVE,
                'suspended_reason' => null,
                'notes' => 'Cluster corporativo Enterprise com alto tráfego de microsserviços.',
            ],
            [
                'client_id' => $rodrigo ? $rodrigo->id : $defaultClientId,
                'server_id' => $serverId,
                'domain' => 'fagundesconsultoria.com.br',
                'username' => 'usr_fagundes',
                'plan' => HostingAccount::PLAN_BASIC,
                'php_version' => '8.4',
                'disk_quota_mb' => 5120,
                'disk_used_mb' => 1240,
                'bandwidth_quota_mb' => 30000,
                'ssl_status' => HostingAccount::SSL_ACTIVE,
                'status' => HostingAccount::STATUS_ACTIVE,
                'suspended_reason' => null,
                'notes' => 'Website institucional responsivo com blog e agendamento.',
            ],
            [
                'client_id' => $beatriz ? $beatriz->id : $defaultClientId,
                'server_id' => $serverId,
                'domain' => 'startuppro.com.br',
                'username' => 'usr_startuppro',
                'plan' => HostingAccount::PLAN_PRO,
                'php_version' => '8.5',
                'disk_quota_mb' => 15360,
                'disk_used_mb' => 3120,
                'bandwidth_quota_mb' => 80000,
                'ssl_status' => HostingAccount::SSL_ACTIVE,
                'status' => HostingAccount::STATUS_ACTIVE,
                'suspended_reason' => null,
                'notes' => 'Ambiente da comunidade de mentoria para startups aceleradas.',
            ],
        ];

        foreach ($accounts as $accountData) {
            $account = HostingAccount::withTrashed()->where('domain', $accountData['domain'])->first();
            if ($account) {
                if ($account->trashed()) {
                    $account->restore();
                }
                $account->update($accountData);
            } else {
                HostingAccount::create($accountData);
            }
        }
    }
}
