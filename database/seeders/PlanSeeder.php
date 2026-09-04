<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Revenda NVMe Basic Ilimitado EUA Plesk',
                'slug' => 'revenda-nvme-basic-ilimitado-eua-plesk',
                'category' => Plan::CATEGORY_RESELLER,
                'price_cents' => 5999, // R$ 59,99
                'billing_cycle' => Plan::CYCLE_MONTHLY,
                'disk_quota_mb' => 102400, // 100 GB
                'bandwidth_quota_mb' => 1000000, // 1 TB
                'description' => 'Plano oficial de revenda Plesk Obsidian em cluster de alta disponibilidade.',
                'features' => [
                    'Painel Plesk Obsidian Avançado',
                    'Armazenamento NVMe de Alta Velocidade',
                    'Caixas Postais & Webmail com Relay MailBaby',
                    'Subdomínios e Bancos MySQL Ilimitados',
                    'SSL Grátis Let\'s Encrypt Automático',
                    'Proteção Anti-DDoS e Firewall Ativo',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Hospedagem Cloud Pro',
                'slug' => 'hospedagem-cloud-pro',
                'category' => Plan::CATEGORY_HOSTING,
                'price_cents' => 3990, // R$ 39,90
                'billing_cycle' => Plan::CYCLE_MONTHLY,
                'disk_quota_mb' => 25600, // 25 GB
                'bandwidth_quota_mb' => 500000, // 500 GB
                'description' => 'Ideal para sites e aplicações Laravel de alto desempenho.',
                'features' => [
                    'PHP 8.2, 8.3, 8.4 e 8.5',
                    'Servidor OpenResty com Cache HTTP',
                    'Contêineres Isolados',
                    'SSL Automático com Renovação',
                    'Webmail Roundcube Integrado',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'VPS Dedicado NVMe Starter',
                'slug' => 'vps-dedicado-nvme-starter',
                'category' => Plan::CATEGORY_VPS,
                'price_cents' => 12900, // R$ 129,00
                'billing_cycle' => Plan::CYCLE_MONTHLY,
                'disk_quota_mb' => 81920, // 80 GB
                'bandwidth_quota_mb' => 2000000, // 2 TB
                'description' => 'Instância VPS privada para máxima autonomia e performance.',
                'features' => [
                    '2 vCPU Cores Intel Xeon',
                    '4 GB RAM Dedicada',
                    'IP Dedicado IPv4 e IPv6',
                    'Acesso Total Root / SSH',
                    'Datacenter Brasil com Baixa Latência',
                ],
                'is_active' => true,
            ],
        ];

        foreach ($plans as $planData) {
            Plan::updateOrCreate(
                ['slug' => $planData['slug']],
                $planData
            );
        }
    }
}
