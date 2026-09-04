<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_users_cannot_access_projects_index(): void
    {
        $response = $this->get(route('projects.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_view_projects_index(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['name' => 'Tech Corp']);
        $project = Project::factory()->create([
            'client_id' => $client->id,
            'name' => 'Sistema ERP Cloud',
        ]);

        $response = $this->actingAs($user)->get(route('projects.index'));

        $response->assertOk();
        $response->assertSee('Sistema ERP Cloud');
        $response->assertSee('Tech Corp');
        $response->assertSee('Gestão de Projetos');
    }

    public function test_projects_can_be_filtered_by_status(): void
    {
        $user = User::factory()->create();
        $prodProject = Project::factory()->inProduction()->create(['name' => 'App em Produção']);
        $devProject = Project::factory()->inDevelopment()->create(['name' => 'App em Desenvolvimento']);

        $response = $this->actingAs($user)->get(route('projects.index', ['status' => 'production']));

        $response->assertOk();
        $response->assertSee('App em Produção');
        $response->assertDontSee('App em Desenvolvimento');
    }

    public function test_projects_can_be_filtered_by_client(): void
    {
        $user = User::factory()->create();
        $clientA = Client::factory()->create(['name' => 'Cliente A']);
        $clientB = Client::factory()->create(['name' => 'Cliente B']);

        $projectA = Project::factory()->create(['client_id' => $clientA->id, 'name' => 'Projeto do Cliente A']);
        $projectB = Project::factory()->create(['client_id' => $clientB->id, 'name' => 'Projeto do Cliente B']);

        $response = $this->actingAs($user)->get(route('projects.index', ['client_id' => $clientA->id]));

        $response->assertOk();
        $response->assertSee('Projeto do Cliente A');
        $response->assertDontSee('Projeto do Cliente B');
    }

    public function test_project_create_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($user)->get(route('projects.create', ['client_id' => $client->id]));

        $response->assertOk();
        $response->assertSee('Novo Projeto');
        $response->assertSee($client->name);
    }

    public function test_user_can_create_a_project(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($user)->post(route('projects.store'), [
            'client_id' => $client->id,
            'name' => 'Plataforma E-commerce V1',
            'type' => Project::TYPE_ECOMMERCE,
            'status' => Project::STATUS_DEVELOPMENT,
            'repository_url' => 'https://github.com/empresa/ecommerce',
            'production_url' => 'https://loja.empresa.com.br',
            'staging_url' => 'https://staging.loja.empresa.com.br',
            'tech_stack' => 'Laravel, Vue.js, Tailwind CSS, Redis',
            'description' => 'Desenvolvimento de marketplace corporativo.',
        ]);

        $project = Project::where('name', 'Plataforma E-commerce V1')->first();
        $this->assertNotNull($project);

        $response->assertRedirect(route('projects.show', $project));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'client_id' => $client->id,
            'name' => 'Plataforma E-commerce V1',
            'type' => 'ecommerce',
            'status' => 'development',
        ]);
    }

    public function test_project_creation_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('projects.store'), []);

        $response->assertSessionHasErrors(['name', 'client_id', 'type', 'status']);
    }

    public function test_project_creation_validates_client_exists(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('projects.store'), [
            'name' => 'Projeto Sem Cliente',
            'client_id' => 99999, // Não existe
            'type' => Project::TYPE_SAAS,
            'status' => Project::STATUS_DEVELOPMENT,
        ]);

        $response->assertSessionHasErrors(['client_id']);
    }

    public function test_user_can_view_project_details(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['name' => 'Cliente Alfa']);
        $project = Project::factory()->create([
            'client_id' => $client->id,
            'name' => 'API de Pagamentos',
            'production_url' => 'https://api.empresa.com.br',
        ]);

        $response = $this->actingAs($user)->get(route('projects.show', $project));

        $response->assertOk();
        $response->assertSee('API de Pagamentos');
        $response->assertSee('Cliente Alfa');
        $response->assertSee('https://api.empresa.com.br');
    }

    public function test_user_can_update_a_project(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $project = Project::factory()->create([
            'client_id' => $client->id,
            'name' => 'Nome Antigo',
            'status' => Project::STATUS_DEVELOPMENT,
        ]);

        $response = $this->actingAs($user)->put(route('projects.update', $project), [
            'client_id' => $client->id,
            'name' => 'Nome Atualizado do Projeto',
            'type' => Project::TYPE_SAAS,
            'status' => Project::STATUS_PRODUCTION,
            'production_url' => 'https://app.novo.com.br',
            'tech_stack' => 'Laravel, Tailwind, MySQL',
        ]);

        $response->assertRedirect(route('projects.show', $project));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Nome Atualizado do Projeto',
            'status' => 'production',
            'production_url' => 'https://app.novo.com.br',
        ]);
    }

    public function test_user_can_soft_delete_a_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $response = $this->actingAs($user)->delete(route('projects.destroy', $project));

        $response->assertRedirect(route('projects.index'));
        $response->assertSessionHas('success');

        $this->assertSoftDeleted($project);
    }
}
