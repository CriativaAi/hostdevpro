<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\HostingAccount;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Garantir que o cliente "Ale (Actual Agency)" exista
        $ale = Client::firstOrCreate(
            ['email' => 'alex@actualagency.com.br'],
            [
                'name' => 'Alexandre (Actual Agency)',
                'phone' => '(11) 99876-5432',
                'company' => 'Actual Agency Comunicação',
                'status' => Client::STATUS_ACTIVE,
                'notes' => 'Cliente titular da revenda ValueHost e aplicações.',
            ]
        );

        // Vincular a conta actualagency.com.br a este cliente se existir
        $actualAccount = HostingAccount::where('domain', 'actualagency.com.br')->first();
        if ($actualAccount) {
            $actualAccount->update(['client_id' => $ale->id]);
        }

        // 2. Garantir que o ticket aguardando resposta exista (#CFI-073726)
        Ticket::updateOrCreate(
            ['ticket_number' => 'CFI-073726'],
            [
                'client_id' => $ale->id,
                'hosting_account_id' => $actualAccount?->id,
                'department' => Ticket::DEPARTMENT_TECHNICAL,
                'priority' => Ticket::PRIORITY_HIGH,
                'status' => Ticket::STATUS_ANSWERED, // Aguarda resposta do cliente!
                'subject' => 'Domínio www.evolutionlocacoes.com.br',
                'last_reply_at' => Carbon::now()->subHours(2),
            ]
        );

        // 3. Criar a Fatura Vencida de R$ 59,99 (idêntica à imagem da ValueHost)
        $dueDate = Carbon::yesterday(); // Ontem para ser vencida!
        
        $invoiceAle = Invoice::updateOrCreate(
            ['invoice_number' => 'FAT-2026-0873'],
            [
                'client_id' => $ale->id,
                'hosting_account_id' => $actualAccount?->id,
                'amount_cents' => 5999, // R$ 59,99
                'status' => Invoice::STATUS_UNPAID,
                'due_date' => $dueDate,
                'payment_method' => Invoice::PAYMENT_PIX,
                'payment_gateway' => Invoice::GATEWAY_MERCADOPAGO,
                'pix_copy_paste' => '00020126580014br.gov.bcb.pix0136APP_USR-8785824747116398-022419-0860f3a84be40b5cd180bf6e57778c60-2351690960520400005303986540559.995802BR5915HostDevPro Cloud6009Sao Paulo62070503***6304D1A2',
                'notes' => 'Renovação Mensal - Revenda NVMe Basic Ilimitado EUA Plesk (actualagency.com.br)',
            ]
        );

        InvoiceItem::updateOrCreate(
            [
                'invoice_id' => $invoiceAle->id,
                'description' => 'Revenda NVMe Basic Ilimitado EUA Plesk - Ciclo 03/09/2026 a 03/10/2026',
            ],
            [
                'amount_cents' => 5999,
                'quantity' => 1,
            ]
        );

        // 4. Outras faturas para popular o histórico
        $carlos = Client::where('email', 'carlos.menezes@alphadev.com.br')->first();
        if ($carlos) {
            $invCarlos = Invoice::updateOrCreate(
                ['invoice_number' => 'FAT-2026-0850'],
                [
                    'client_id' => $carlos->id,
                    'amount_cents' => 3990,
                    'status' => Invoice::STATUS_PAID,
                    'due_date' => Carbon::now()->subDays(15),
                    'paid_at' => Carbon::now()->subDays(16),
                    'payment_method' => Invoice::PAYMENT_PIX,
                    'payment_gateway' => Invoice::GATEWAY_MERCADOPAGO,
                    'notes' => 'Hospedagem Cloud Pro - alphadev.com.br',
                ]
            );

            InvoiceItem::updateOrCreate(
                [
                    'invoice_id' => $invCarlos->id,
                    'description' => 'Hospedagem Cloud Pro - Mensal',
                ],
                [
                    'amount_cents' => 3990,
                    'quantity' => 1,
                ]
            );
        }
    }
}
