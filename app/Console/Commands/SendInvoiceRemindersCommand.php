<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Services\WhatsAppNotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendInvoiceRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispara lembretes automáticos de vencimento e cobrança amigável via WhatsApp';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppNotificationService $whatsapp): int
    {
        $this->info('Iniciando processamento de lembretes de faturas...');

        $today = Carbon::today();
        $inTwoDays = Carbon::today()->addDays(2);
        $yesterday = Carbon::today()->subDay();

        $sentCount = 0;

        // 1. Vencem Hoje
        $dueToday = Invoice::where('status', Invoice::STATUS_UNPAID)
            ->whereDate('due_date', $today)
            ->get();

        foreach ($dueToday as $invoice) {
            if ($whatsapp->sendInvoiceReminder($invoice, 'due_today')) {
                $this->line("✔ Lembrete 'Vence Hoje' enviado para fatura {$invoice->invoice_number}");
                $sentCount++;
            }
        }

        // 2. Vencem em 2 dias
        $dueSoon = Invoice::where('status', Invoice::STATUS_UNPAID)
            ->whereDate('due_date', $inTwoDays)
            ->get();

        foreach ($dueSoon as $invoice) {
            if ($whatsapp->sendInvoiceReminder($invoice, 'due_soon')) {
                $this->line("✔ Lembrete 'Vence em Breve' enviado para fatura {$invoice->invoice_number}");
                $sentCount++;
            }
        }

        // 3. Vencidas ontem (1 dia em atraso)
        $overdue = Invoice::where('status', Invoice::STATUS_UNPAID)
            ->whereDate('due_date', $yesterday)
            ->get();

        foreach ($overdue as $invoice) {
            if ($whatsapp->sendInvoiceReminder($invoice, 'overdue')) {
                $this->line("✔ Aviso 'Fatura em Atraso' enviado para fatura {$invoice->invoice_number}");
                $sentCount++;
            }
        }

        $this->info("Processamento concluído. Total de lembretes enviados: {$sentCount}");

        return Command::SUCCESS;
    }
}
