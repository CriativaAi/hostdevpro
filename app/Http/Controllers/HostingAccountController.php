<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHostingAccountRequest;
use App\Http\Requests\UpdateHostingAccountRequest;
use App\Models\Client;
use App\Models\HostingAccount;
use App\Models\Server;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HostingAccountController extends Controller
{
    /**
     * Display a listing of hosting accounts.
     */
    public function index(Request $request): View
    {
        $query = HostingAccount::with(['client', 'server'])->latest();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('domain', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                         ->orWhere('company', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->input('client_id'));
        }

        if ($request->filled('server_id')) {
            $query->where('server_id', $request->input('server_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('plan')) {
            $query->where('plan', $request->input('plan'));
        }

        $kpis = [
            'total' => HostingAccount::count(),
            'active' => HostingAccount::where('status', HostingAccount::STATUS_ACTIVE)->count(),
            'suspended' => HostingAccount::where('status', HostingAccount::STATUS_SUSPENDED)->count(),
            'ssl_active' => HostingAccount::where('ssl_status', HostingAccount::SSL_ACTIVE)->count(),
        ];

        $hostingAccounts = $query->paginate(10)->withQueryString();
        $clients = Client::orderBy('name')->get();
        $servers = Server::orderBy('name')->get();

        return view('hosting.index', compact('hostingAccounts', 'kpis', 'clients', 'servers'));
    }

    /**
     * Show the form for creating a new hosting account.
     */
    public function create(Request $request): View
    {
        $clients = Client::orderBy('name')->get();
        $servers = Server::orderBy('name')->get();
        $selectedClientId = $request->query('client_id');
        $selectedServerId = $request->query('server_id');

        return view('hosting.create', compact('clients', 'servers', 'selectedClientId', 'selectedServerId'));
    }

    /**
     * Store a newly created hosting account in storage.
     */
    public function store(StoreHostingAccountRequest $request): RedirectResponse
    {
        $account = HostingAccount::create($request->validated());

        return redirect()
            ->route('hosting.show', $account)
            ->with('success', 'Conta de hospedagem provisionada com sucesso!');
    }

    /**
     * Display the specified hosting account.
     */
    public function show(HostingAccount $hosting): View
    {
        $hosting->load(['client', 'server']);

        return view('hosting.show', compact('hosting'));
    }

    /**
     * Show the form for editing the specified hosting account.
     */
    public function edit(HostingAccount $hosting): View
    {
        $clients = Client::orderBy('name')->get();
        $servers = Server::orderBy('name')->get();

        return view('hosting.edit', compact('hosting', 'clients', 'servers'));
    }

    /**
     * Update the specified hosting account in storage.
     */
    public function update(UpdateHostingAccountRequest $request, HostingAccount $hosting): RedirectResponse
    {
        $hosting->update($request->validated());

        return redirect()
            ->route('hosting.show', $hosting)
            ->with('success', 'Conta de hospedagem atualizada com sucesso!');
    }

    /**
     * Remove the specified hosting account from storage.
     */
    public function destroy(HostingAccount $hosting): RedirectResponse
    {
        $hosting->delete();

        return redirect()
            ->route('hosting.index')
            ->with('success', 'Conta de hospedagem removida com sucesso!');
    }

    /**
     * Alternar status de suspensão da conta.
     */
    public function toggleStatus(Request $request, HostingAccount $hosting): RedirectResponse
    {
        if ($hosting->status === HostingAccount::STATUS_ACTIVE) {
            $hosting->update([
                'status' => HostingAccount::STATUS_SUSPENDED,
                'suspended_reason' => $request->input('reason', 'Suspensão manual administrativa'),
            ]);
            $msg = "Conta {$hosting->domain} suspensa com sucesso.";
        } else {
            $hosting->update([
                'status' => HostingAccount::STATUS_ACTIVE,
                'suspended_reason' => null,
            ]);
            $msg = "Conta {$hosting->domain} reativada com sucesso.";
        }

        return redirect()->route('hosting.show', $hosting)->with('success', $msg);
    }
}
