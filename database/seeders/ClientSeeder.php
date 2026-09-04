<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'name' => 'Carlos Eduardo Menezes',
                'email' => 'carlos.menezes@alphadev.com.br',
                'phone' => '(11) 98765-4321',
                'company' => 'Alpha Dev Soluções Digitais',
                'status' => Client::STATUS_ACTIVE,
                'notes' => 'Cliente corporativo com contrato de hospedagem dedicada e gestão de instâncias AWS.',
            ],
            [
                'name' => 'Mariana Silveira',
                'email' => 'mariana@nexustecnologia.io',
                'phone' => '(21) 99887-1122',
                'company' => 'Nexus Cloud & Tech',
                'status' => Client::STATUS_ACTIVE,
                'notes' => 'Plano Enterprise. Integração contínua e clusters Kubernetes gerenciados.',
            ],
            [
                'name' => 'Rodrigo Fagundes',
                'email' => 'rodrigo@fagundesconsultoria.com.br',
                'phone' => '(31) 98223-9988',
                'company' => 'Fagundes & Associados',
                'status' => Client::STATUS_PENDING,
                'notes' => 'Aguardando validação de domínio e liberação do gateway de pagamento.',
            ],
            [
                'name' => 'Beatriz Vasconcelos',
                'email' => 'b.vasconcelos@startuppro.com.br',
                'phone' => '(41) 97112-4455',
                'company' => 'Startup Pro Hub',
                'status' => Client::STATUS_ACTIVE,
                'notes' => 'Onboarding concluído em Agosto. Servidor VPS NVMe ativo.',
            ],
            [
                'name' => 'Fernando Alcantara',
                'email' => 'fernando@legacytech.com.br',
                'phone' => '(19) 98334-7766',
                'company' => 'Legacy Systems Ltda',
                'status' => Client::STATUS_INACTIVE,
                'notes' => 'Contrato encerrado no trimestre anterior. Manter dados em conformidade LGPD.',
            ],
        ];

        foreach ($clients as $clientData) {
            Client::firstOrCreate(
                ['email' => $clientData['email']],
                $clientData
            );
        }
    }
}
