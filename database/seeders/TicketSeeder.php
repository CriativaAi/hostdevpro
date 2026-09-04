<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\HostingAccount;
use App\Models\Server;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $creativaClient = Client::where('email', 'contato@creativaai.com.br')->first() ?? Client::first();

        if (! $creativaClient) {
            return;
        }

        $hostingAccount = HostingAccount::where('domain', 'creativaai.com.br')->first() ?? HostingAccount::first();
        $server = Server::where('ip_address', '209.50.245.45')->first() ?? Server::first();

        $ticketsData = [
            [
                'ticket_number' => 'HDP-2026-0001',
                'client_id' => $creativaClient->id,
                'user_id' => $user?->id,
                'hosting_account_id' => $hostingAccount?->id,
                'server_id' => $server?->id,
                'department' => Ticket::DEPARTMENT_DEVOPS,
                'priority' => Ticket::PRIORITY_HIGH,
                'status' => Ticket::STATUS_IN_PROGRESS,
                'subject' => 'Otimização de rotas Nginx / OpenResty e compressão Gzip para Web',
                'last_reply_at' => now()->subHours(2),
                'replies' => [
                    [
                        'author_name' => $creativaClient->name,
                        'author_type' => TicketReply::AUTHOR_TYPE_CLIENT,
                        'client_id' => $creativaClient->id,
                        'user_id' => null,
                        'message' => "Olá equipe HostDevPro,\n\nNotamos que algumas requisições de arquivos estáticos podem ser aceleradas com o módulo gzip/brotli do OpenResty. Poderiam validar se a compressão está ativa para nosso domínio?",
                        'is_internal_note' => false,
                        'created_at' => now()->subHours(4),
                    ],
                    [
                        'author_name' => 'Equipe de Infraestrutura',
                        'author_type' => TicketReply::AUTHOR_TYPE_STAFF,
                        'client_id' => null,
                        'user_id' => $user?->id,
                        'message' => "Analisando o arquivo vhost em /etc/icontainer/apps/openresty/openresty/conf/vhost/app.conf. O gzip já está ativo nos tipos MIME text/css e application/javascript.",
                        'is_internal_note' => true,
                        'created_at' => now()->subHours(3),
                    ],
                    [
                        'author_name' => $user?->name ?? 'Suporte HostDevPro',
                        'author_type' => TicketReply::AUTHOR_TYPE_STAFF,
                        'client_id' => null,
                        'user_id' => $user?->id,
                        'message' => "Olá! Confirmamos que os módulos de compressão de assets estáticos e buffers de proxy foram otimizados no VPS. Os tempos de resposta diminuíram em média 35% nos testes de carga.",
                        'is_internal_note' => false,
                        'created_at' => now()->subHours(2),
                    ],
                ],
            ],
            [
                'ticket_number' => 'HDP-2026-0002',
                'client_id' => $creativaClient->id,
                'user_id' => $user?->id,
                'hosting_account_id' => $hostingAccount?->id,
                'server_id' => $server?->id,
                'department' => Ticket::DEPARTMENT_TECHNICAL,
                'priority' => Ticket::PRIORITY_MEDIUM,
                'status' => Ticket::STATUS_OPEN,
                'subject' => 'Instalação de certificado SSL Let\'s Encrypt para novo subdomínio',
                'last_reply_at' => now()->subMinutes(45),
                'replies' => [
                    [
                        'author_name' => $creativaClient->name,
                        'author_type' => TicketReply::AUTHOR_TYPE_CLIENT,
                        'client_id' => $creativaClient->id,
                        'user_id' => null,
                        'message' => "Boa tarde! Criamos o subdomínio api.creativaai.com.br e gostaríamos de solicitar a emissão do certificado SSL automatizado com renovação de 90 dias.",
                        'is_internal_note' => false,
                        'created_at' => now()->subMinutes(45),
                    ],
                ],
            ],
            [
                'ticket_number' => 'HDP-2026-0003',
                'client_id' => $creativaClient->id,
                'user_id' => $user?->id,
                'hosting_account_id' => null,
                'server_id' => null,
                'department' => Ticket::DEPARTMENT_FINANCIAL,
                'priority' => Ticket::PRIORITY_LOW,
                'status' => Ticket::STATUS_ANSWERED,
                'subject' => 'Consulta sobre emissão de Nota Fiscal referente aos serviços de VPS',
                'last_reply_at' => now()->subDay(),
                'replies' => [
                    [
                        'author_name' => $creativaClient->name,
                        'author_type' => TicketReply::AUTHOR_TYPE_CLIENT,
                        'client_id' => $creativaClient->id,
                        'user_id' => null,
                        'message' => "Gostaria de confirmar se a NFS-e é enviada automaticamente para o nosso departamento financeiro no dia do vencimento.",
                        'is_internal_note' => false,
                        'created_at' => now()->subDays(2),
                    ],
                    [
                        'author_name' => $user?->name ?? 'Financeiro HostDevPro',
                        'author_type' => TicketReply::AUTHOR_TYPE_STAFF,
                        'client_id' => null,
                        'user_id' => $user?->id,
                        'message' => "Olá! Sim, todas as NFS-e são emitidas automaticamente pela prefeitura após a compensação bancária e enviadas em PDF e XML para o e-mail cadastrado.",
                        'is_internal_note' => false,
                        'created_at' => now()->subDay(),
                    ],
                ],
            ],
        ];

        foreach ($ticketsData as $data) {
            $replies = $data['replies'] ?? [];
            unset($data['replies']);

            $ticket = Ticket::withTrashed()->where('ticket_number', $data['ticket_number'])->first();

            if ($ticket) {
                if ($ticket->trashed()) {
                    $ticket->restore();
                }
                $ticket->update($data);
            } else {
                $ticket = Ticket::create($data);
            }

            foreach ($replies as $rData) {
                TicketReply::withTrashed()
                    ->where('ticket_id', $ticket->id)
                    ->where('message', $rData['message'])
                    ->firstOrCreate([
                        'ticket_id' => $ticket->id,
                        'author_name' => $rData['author_name'],
                        'author_type' => $rData['author_type'],
                        'client_id' => $rData['client_id'],
                        'user_id' => $rData['user_id'],
                        'message' => $rData['message'],
                        'is_internal_note' => $rData['is_internal_note'],
                    ]);
            }
        }
    }
}
