<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServerRequest;
use App\Http\Requests\UpdateServerRequest;
use App\Models\Server;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServerController extends Controller
{
    /**
     * Display a listing of servers.
     */
    public function index(Request $request): View
    {
        $query = Server::withCount('hostingAccounts')->latest();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('hostname', 'like', "%{$search}%")
                  ->orWhere('provider', 'like', "%{$search}%")
                  ->orWhere('datacenter_location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $kpis = [
            'total' => Server::count(),
            'online' => Server::where('status', Server::STATUS_ONLINE)->count(),
            'total_cores' => Server::sum('cpu_cores'),
            'total_ram_gb' => round(Server::sum('ram_mb') / 1024, 1),
        ];

        $servers = $query->paginate(10)->withQueryString();

        return view('servers.index', compact('servers', 'kpis'));
    }

    /**
     * Show the form for creating a new server.
     */
    public function create(): View
    {
        return view('servers.create');
    }

    /**
     * Store a newly created server in storage.
     */
    public function store(StoreServerRequest $request): RedirectResponse
    {
        $server = Server::create($request->validated());

        return redirect()
            ->route('servers.show', $server)
            ->with('success', 'Servidor cadastrado com sucesso!');
    }

    /**
     * Display the specified server.
     */
    public function show(Server $server): View
    {
        $server->load(['hostingAccounts' => function ($q) {
            $q->with('client')->latest();
        }]);

        return view('servers.show', compact('server'));
    }

    /**
     * Show the form for editing the specified server.
     */
    public function edit(Server $server): View
    {
        return view('servers.edit', compact('server'));
    }

    /**
     * Update the specified server in storage.
     */
    public function update(UpdateServerRequest $request, Server $server): RedirectResponse
    {
        $server->update($request->validated());

        return redirect()
            ->route('servers.show', $server)
            ->with('success', 'Servidor atualizado com sucesso!');
    }

    /**
     * Remove the specified server from storage.
     */
    public function destroy(Server $server): RedirectResponse
    {
        $server->delete();

        return redirect()
            ->route('servers.index')
            ->with('success', 'Servidor removido com sucesso!');
    }
}
