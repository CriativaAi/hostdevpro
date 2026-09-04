<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->value();
        $status = $request->string('status')->trim()->value();

        $query = Client::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (in_array($status, [Client::STATUS_ACTIVE, Client::STATUS_PENDING, Client::STATUS_INACTIVE], true)) {
            $query->where('status', $status);
        }

        $clients = $query->latest()->paginate(10)->withQueryString();

        $metrics = [
            'total' => Client::count(),
            'active' => Client::where('status', Client::STATUS_ACTIVE)->count(),
            'pending' => Client::where('status', Client::STATUS_PENDING)->count(),
            'inactive' => Client::where('status', Client::STATUS_INACTIVE)->count(),
        ];

        return view('clients.index', compact('clients', 'metrics', 'search', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $client = new Client([
            'status' => Client::STATUS_ACTIVE,
        ]);

        return view('clients.create', compact('client'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request): RedirectResponse
    {
        $client = Client::create($request->validated());

        return redirect()
            ->route('clients.index')
            ->with('success', "Cliente \"{$client->name}\" cadastrado com sucesso!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client): View
    {
        $client->load([
            'projects',
            'hostingAccounts.server',
            'tickets' => fn ($q) => $q->latest('last_reply_at'),
        ]);

        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client): View
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $client->update($request->validated());

        return redirect()
            ->route('clients.index')
            ->with('success', "Cliente \"{$client->name}\" atualizado com sucesso!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client): RedirectResponse
    {
        $name = $client->name;
        $client->delete();

        return redirect()
            ->route('clients.index')
            ->with('success', "Cliente \"{$name}\" excluído com sucesso!");
    }
}
