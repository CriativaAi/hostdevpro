<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\HostingAccount;
use App\Models\Invoice;
use App\Models\Server;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Exibe a Área do Cliente (Portal do Cliente) oficial HostDevPro Cloud.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Buscar cliente associado ao e-mail ou o cliente do usuário
        $client = Client::where('email', $user->email)->first() 
            ?? Client::where('email', 'onesitesidc@gmail.com')->first()
            ?? Client::first();

        // Contadores reais do sistema
        $servicesCount = HostingAccount::count();
        $activeServicesCount = HostingAccount::where('status', HostingAccount::STATUS_ACTIVE)->count();
        $domainsCount = HostingAccount::distinct('domain')->count();
        $ticketsCount = Ticket::count();
        $invoicesCount = Invoice::count();

        // Métricas financeiras e de infraestrutura reais
        $totalPaidCents = (int) Invoice::where('status', Invoice::STATUS_PAID)->sum('amount_cents');
        $totalUnpaidCents = (int) Invoice::where('status', Invoice::STATUS_UNPAID)->sum('amount_cents');
        $servers = Server::all();
        $serversCount = $servers->count();
        $onlineServersCount = $servers->where('status', Server::STATUS_ONLINE)->count();

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

        // Notícias e comunicados operacionais HostDevPro
        $news = [
            [
                'title' => 'Cluster Plesk NVMe Enterprise & Servidores DNS Oficiais Operacionais',
                'date' => '05/09/2026',
                'category' => 'Infraestrutura',
            ],
            [
                'title' => 'Proteção Anti-DDoS e Roteamento OpenResty HTTP/3 Ativado',
                'date' => '04/09/2026',
                'category' => 'Segurança',
            ],
            [
                'title' => 'Checkout Automatizado com PIX Instantâneo e Cartão de Crédito',
                'date' => '01/09/2026',
                'category' => 'Faturamento',
            ],
        ];

        return view('dashboard', compact(
            'user',
            'client',
            'servicesCount',
            'activeServicesCount',
            'domainsCount',
            'ticketsCount',
            'invoicesCount',
            'totalPaidCents',
            'totalUnpaidCents',
            'servers',
            'serversCount',
            'onlineServersCount',
            'overdueInvoice',
            'pendingTicket',
            'services',
            'recentTickets',
            'notificationsCount',
            'news'
        ));
    }
}
