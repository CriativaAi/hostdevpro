<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketReplyRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketStatusRequest;
use App\Models\Client;
use App\Models\HostingAccount;
use App\Models\Project;
use App\Models\Server;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
    /**
     * Listagem de chamados com filtros e métricas.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->value();
        $status = $request->string('status')->trim()->value();
        $priority = $request->string('priority')->trim()->value();
        $department = $request->string('department')->trim()->value();
        $clientId = $request->integer('client_id') ?: null;

        $query = Ticket::query()->with(['client', 'user', 'hostingAccount', 'server']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('company', 'like', "%{$search}%");
                  });
            });
        }

        if ($status !== '' && in_array($status, [Ticket::STATUS_OPEN, Ticket::STATUS_IN_PROGRESS, Ticket::STATUS_ANSWERED, Ticket::STATUS_CUSTOMER_REPLY, Ticket::STATUS_CLOSED], true)) {
            $query->where('status', $status);
        }

        if ($priority !== '' && in_array($priority, [Ticket::PRIORITY_LOW, Ticket::PRIORITY_MEDIUM, Ticket::PRIORITY_HIGH, Ticket::PRIORITY_URGENT], true)) {
            $query->where('priority', $priority);
        }

        if ($department !== '' && in_array($department, [Ticket::DEPARTMENT_TECHNICAL, Ticket::DEPARTMENT_FINANCIAL, Ticket::DEPARTMENT_COMMERCIAL, Ticket::DEPARTMENT_DEVOPS], true)) {
            $query->where('department', $department);
        }

        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        $tickets = $query->latest('last_reply_at')->latest('id')->paginate(10)->withQueryString();

        $metrics = [
            'total' => Ticket::count(),
            'open' => Ticket::where('status', Ticket::STATUS_OPEN)->count(),
            'in_progress' => Ticket::where('status', Ticket::STATUS_IN_PROGRESS)->count(),
            'answered' => Ticket::where('status', Ticket::STATUS_ANSWERED)->count(),
            'closed' => Ticket::where('status', Ticket::STATUS_CLOSED)->count(),
        ];

        $clients = Client::orderBy('name')->get(['id', 'name', 'company']);

        return view('tickets.index', compact('tickets', 'metrics', 'clients', 'search', 'status', 'priority', 'department', 'clientId'));
    }

    /**
     * Formulário para abrir novo chamado.
     */
    public function create(Request $request): View
    {
        $clientId = $request->integer('client_id') ?: null;
        $hostingAccountId = $request->integer('hosting_account_id') ?: null;
        $serverId = $request->integer('server_id') ?: null;
        $projectId = $request->integer('project_id') ?: null;

        $clients = Client::with(['hostingAccounts', 'projects'])->orderBy('name')->get();
        $servers = Server::orderBy('name')->get();

        $ticket = new Ticket([
            'client_id' => $clientId,
            'hosting_account_id' => $hostingAccountId,
            'server_id' => $serverId,
            'project_id' => $projectId,
            'department' => Ticket::DEPARTMENT_TECHNICAL,
            'priority' => Ticket::PRIORITY_MEDIUM,
            'status' => Ticket::STATUS_OPEN,
        ]);

        return view('tickets.create', compact('ticket', 'clients', 'servers'));
    }

    /**
     * Salvar novo chamado e mensagem inicial.
     */
    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $message = $validated['message'];
        unset($validated['message']);

        $validated['ticket_number'] = Ticket::generateTicketNumber();
        $validated['user_id'] = $request->user()?->id;
        $validated['status'] = Ticket::STATUS_OPEN;
        $validated['last_reply_at'] = now();

        $ticket = Ticket::create($validated);

        // Criar primeira mensagem no chamado
        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()?->id,
            'client_id' => null,
            'author_name' => $request->user()?->name ?? 'Suporte HostDevPro',
            'author_type' => TicketReply::AUTHOR_TYPE_STAFF,
            'message' => $message,
            'is_internal_note' => false,
        ]);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', "Chamado {$ticket->ticket_number} aberto com sucesso!");
    }

    /**
     * Visualização do chamado com timeline de mensagens e notas internas.
     */
    public function show(Ticket $ticket): View
    {
        $ticket->load([
            'client',
            'user',
            'hostingAccount.server',
            'server',
            'project',
            'replies.user',
            'replies.client',
        ]);

        $users = User::orderBy('name')->get();

        return view('tickets.show', compact('ticket', 'users'));
    }

    /**
     * Adicionar resposta ou nota interna da equipe.
     */
    public function reply(StoreTicketReplyRequest $request, Ticket $ticket): RedirectResponse
    {
        $validated = $request->validated();
        $isInternal = (bool) ($validated['is_internal_note'] ?? false);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()?->id,
            'client_id' => null,
            'author_name' => $request->user()?->name ?? 'Suporte HostDevPro',
            'author_type' => TicketReply::AUTHOR_TYPE_STAFF,
            'message' => $validated['message'],
            'is_internal_note' => $isInternal,
        ]);

        if (! $isInternal) {
            $ticket->update([
                'status' => Ticket::STATUS_ANSWERED,
                'last_reply_at' => now(),
            ]);
            $msg = 'Resposta enviada com sucesso.';
        } else {
            $ticket->touch();
            $msg = 'Nota interna adicionada com sucesso. O cliente não visualiza esta mensagem.';
        }

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', $msg);
    }

    /**
     * Atualização de status e prioridade do chamado.
     */
    public function updateStatus(UpdateTicketStatusRequest $request, Ticket $ticket): RedirectResponse
    {
        $data = array_filter($request->validated(), fn ($val) => ! is_null($val));

        if (isset($data['status'])) {
            if ($data['status'] === Ticket::STATUS_CLOSED && $ticket->status !== Ticket::STATUS_CLOSED) {
                $data['closed_at'] = now();
            } elseif ($data['status'] !== Ticket::STATUS_CLOSED && $ticket->status === Ticket::STATUS_CLOSED) {
                $data['closed_at'] = null;
            }
        }

        $ticket->update($data);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', "Chamado {$ticket->ticket_number} atualizado com sucesso!");
    }

    /**
     * Excluir chamado logicamente (Soft Delete).
     */
    public function destroy(Ticket $ticket): RedirectResponse
    {
        $number = $ticket->ticket_number;
        $ticket->delete();

        return redirect()
            ->route('tickets.index')
            ->with('success', "Chamado {$number} excluído com sucesso!");
    }
}
