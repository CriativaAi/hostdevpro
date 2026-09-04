<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\HostingAccount;
use App\Models\Invoice;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Exibe a Área do Cliente (Portal do Cliente) inspirada na ValueHost/WHMCS.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Buscar cliente associado ao e-mail ou o cliente prioritário
        $client = Client::where('email', $user->email)->first() 
            ?? Client::where('email', 'alex@actualagency.com.br')->first()
            ?? Client::first();

        // Contadores gerais
        $servicesCount = HostingAccount::count();
        $domainsCount = HostingAccount::distinct('domain')->count();
        $ticketsCount = Ticket::count();
        $invoicesCount = Invoice::count();

        // 1. Alerta Urgente: Fatura vencida ou pendente
        $overdueInvoice = Invoice::where('status', '!=', Invoice::STATUS_PAID)
            ->where('due_date', '<', Carbon::today())
            ->latest('due_date')
            ->first() 
            ?? Invoice::where('status', Invoice::STATUS_UNPAID)->first();

        // 2. Alerta Urgente: Ticket que aguarda resposta do cliente
        $pendingTicket = Ticket::where('status', Ticket::STATUS_ANSWERED)
            ->latest('updated_at')
            ->first()
            ?? Ticket::where('status', Ticket::STATUS_OPEN)->first();

        // Serviços / Contas de Hospedagem ativas
        $services = HostingAccount::with(['server', 'client'])
            ->latest()
            ->take(5)
            ->get();

        // Chamados recentes
        $recentTickets = Ticket::with(['client'])
            ->latest('updated_at')
            ->take(4)
            ->get();

        // Notificações ativas (contagem para o badge da barra superior)
        $notificationsCount = ($overdueInvoice ? 1 : 0) + ($pendingTicket ? 1 : 0);

        // Notícias e comunicados da nuvem HostDevPro
        $news = [
            [
                'title' => 'COMUNICADO - Infraestrutura NVMe e Cluster de E-mails Plesk Ativo',
                'date' => '04/09/2026',
                'category' => 'Infraestrutura',
            ],
            [
                'title' => 'INFORMATIVO - Nova Zona de Balanceamento OpenResty e Proteção Anti-DDoS',
                'date' => '02/09/2026',
                'category' => 'Segurança',
            ],
            [
                'title' => 'ATUALIZAÇÃO - Integração de Pagamento Instantâneo via PIX e Cartões',
                'date' => '28/08/2026',
                'category' => 'Faturamento',
            ],
        ];

        return view('dashboard', compact(
            'user',
            'client',
            'servicesCount',
            'domainsCount',
            'ticketsCount',
            'invoicesCount',
            'overdueInvoice',
            'pendingTicket',
            'services',
            'recentTickets',
            'notificationsCount',
            'news'
        ));
    }
}
