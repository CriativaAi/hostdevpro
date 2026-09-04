<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->value();
        $status = $request->string('status')->trim()->value();
        $type = $request->string('type')->trim()->value();
        $clientId = $request->integer('client_id');

        $query = Project::with('client');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('tech_stack', 'like', "%{$search}%")
                  ->orWhere('repository_url', 'like', "%{$search}%")
                  ->orWhere('production_url', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($clientQuery) use ($search) {
                      $clientQuery->where('name', 'like', "%{$search}%")
                                  ->orWhere('company', 'like', "%{$search}%");
                  });
            });
        }

        if ($status !== '' && in_array($status, [
            Project::STATUS_PLANNING,
            Project::STATUS_DEVELOPMENT,
            Project::STATUS_STAGING,
            Project::STATUS_PRODUCTION,
            Project::STATUS_MAINTENANCE,
        ], true)) {
            $query->where('status', $status);
        }

        if ($type !== '' && in_array($type, [
            Project::TYPE_SAAS,
            Project::TYPE_WEBSITE,
            Project::TYPE_ECOMMERCE,
            Project::TYPE_API,
            Project::TYPE_LANDING_PAGE,
            Project::TYPE_MOBILE_APP,
        ], true)) {
            $query->where('type', $type);
        }

        if ($clientId > 0) {
            $query->where('client_id', $clientId);
        }

        $projects = $query->latest()->paginate(10)->withQueryString();

        $metrics = [
            'total' => Project::count(),
            'production' => Project::where('status', Project::STATUS_PRODUCTION)->count(),
            'development' => Project::where('status', Project::STATUS_DEVELOPMENT)->count(),
            'staging' => Project::where('status', Project::STATUS_STAGING)->count(),
            'planning' => Project::where('status', Project::STATUS_PLANNING)->count(),
            'maintenance' => Project::where('status', Project::STATUS_MAINTENANCE)->count(),
        ];

        $clients = Client::orderBy('name')->get(['id', 'name', 'company']);

        return view('projects.index', compact('projects', 'metrics', 'clients', 'search', 'status', 'type', 'clientId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $clientId = $request->integer('client_id');
        $clients = Client::orderBy('name')->get();

        $project = new Project([
            'client_id' => $clientId ?: null,
            'status' => Project::STATUS_DEVELOPMENT,
            'type' => Project::TYPE_SAAS,
        ]);

        return view('projects.create', compact('project', 'clients', 'clientId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = Project::create($request->validated());

        return redirect()
            ->route('projects.show', $project)
            ->with('success', "Projeto \"{$project->name}\" cadastrado com sucesso!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): View
    {
        $project->load('client');

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project): View
    {
        $clients = Client::orderBy('name')->get();

        return view('projects.edit', compact('project', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $project->update($request->validated());

        return redirect()
            ->route('projects.show', $project)
            ->with('success', "Projeto \"{$project->name}\" atualizado com sucesso!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): RedirectResponse
    {
        $name = $project->name;
        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('success', "Projeto \"{$name}\" excluído com sucesso!");
    }
}
