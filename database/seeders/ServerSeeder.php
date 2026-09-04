<?php

namespace Database\Seeders;

use App\Models\Server;
use Illuminate\Database\Seeder;

class ServerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $servers = [
            [
                'name' => 'VPS Integrator Master 01',
                'hostname' => 'app.hostdevpro.app.br',
                'ip_address' => '209.50.245.45',
                'provider' => 'Integrator Host',
                'datacenter_location' => 'São Paulo - Brasil (BR)',
                'os' => 'Ubuntu Linux 24.04 LTS (Docker / OpenResty)',
                'cpu_cores' => 4,
                'ram_mb' => 8192,
                'disk_gb' => 160,
                'ssh_port' => 22,
                'status' => Server::STATUS_ONLINE,
                'notes' => 'Servidor mestre de produção com containers Docker, OpenResty e MySQL 8.',
            ],
            [
                'name' => 'Cloud Hetzner Backup & Staging',
                'hostname' => 'staging.hostdevpro.app.br',
                'ip_address' => '159.69.120.88',
                'provider' => 'Hetzner Cloud',
                'datacenter_location' => 'Falkenstein - Alemanha (EU)',
                'os' => 'Ubuntu Linux 24.04 LTS',
                'cpu_cores' => 2,
                'ram_mb' => 4096,
                'disk_gb' => 80,
                'ssh_port' => 22,
                'status' => Server::STATUS_ONLINE,
                'notes' => 'Instância secundária para homologação, testes de carga e redundância.',
            ],
            [
                'name' => 'ValueHost Cluster (us163-pl)',
                'hostname' => 'us163-pl.valueserver.net',
                'ip_address' => '177.136.254.37',
                'provider' => 'ValueHost',
                'datacenter_location' => 'Brasil / São Paulo',
                'os' => 'Linux (Plesk Obsidian)',
                'cpu_cores' => 8,
                'ram_mb' => 16384,
                'disk_gb' => 500,
                'ssh_port' => 22,
                'status' => Server::STATUS_ONLINE,
                'notes' => "Servidores DNS:\nns1.valueserver.net (177.93.111.32)\nns2.valueserver.net (187.45.181.114)\nns3.valueserver.net (51.81.81.61)\nns4.valueserver.net (51.222.29.124)\nCluster de Revenda Plesk e E-mails Corporativos.",
            ],
        ];

        foreach ($servers as $serverData) {
            $server = Server::withTrashed()->where('ip_address', $serverData['ip_address'])->first();
            if ($server) {
                if ($server->trashed()) {
                    $server->restore();
                }
                $server->update($serverData);
            } else {
                Server::create($serverData);
            }
        }
    }
}
