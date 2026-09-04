<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_users_cannot_access_clients_index(): void
    {
        $response = $this->get(route('clients.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_view_clients_index(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['name' => 'Acme Corporation']);

        $response = $this->actingAs($user)->get(route('clients.index'));

        $response->assertOk();
        $response->assertSee('Acme Corporation');
        $response->assertSee('Gestão de Clientes');
    }

    public function test_clients_can_be_filtered_by_status(): void
    {
        $user = User::factory()->create();
        $activeClient = Client::factory()->active()->create(['name' => 'Cliente Ativo SA']);
        $inactiveClient = Client::factory()->inactive()->create(['name' => 'Cliente Inativo Ltda']);

        $response = $this->actingAs($user)->get(route('clients.index', ['status' => 'active']));

        $response->assertOk();
        $response->assertSee('Cliente Ativo SA');
        $response->assertDontSee('Cliente Inativo Ltda');
    }

    public function test_clients_can_be_searched_by_name(): void
    {
        $user = User::factory()->create();
        $clientA = Client::factory()->create(['name' => 'Beta Soluções']);
        $clientB = Client::factory()->create(['name' => 'Alpha Inovações']);

        $response = $this->actingAs($user)->get(route('clients.index', ['search' => 'Beta']));

        $response->assertOk();
        $response->assertSee('Beta Soluções');
        $response->assertDontSee('Alpha Inovações');
    }

    public function test_client_create_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('clients.create'));

        $response->assertOk();
        $response->assertSee('Novo Cliente');
    }

    public function test_user_can_create_a_client(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('clients.store'), [
            'name' => 'Tech Dev Solutions',
            'email' => 'contato@techdev.com',
            'phone' => '(11) 99999-8888',
            'company' => 'Tech Dev Ltda',
            'status' => Client::STATUS_ACTIVE,
            'notes' => 'Cliente prioritário corporativo.',
        ]);

        $response->assertRedirect(route('clients.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('clients', [
            'name' => 'Tech Dev Solutions',
            'email' => 'contato@techdev.com',
            'phone' => '(11) 99999-8888',
            'company' => 'Tech Dev Ltda',
            'status' => 'active',
        ]);
    }

    public function test_client_creation_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('clients.store'), []);

        $response->assertSessionHasErrors(['name', 'email', 'status']);
    }

    public function test_client_email_must_be_unique(): void
    {
        $user = User::factory()->create();
        Client::factory()->create(['email' => 'duplicado@empresa.com']);

        $response = $this->actingAs($user)->post(route('clients.store'), [
            'name' => 'Outro Cliente',
            'email' => 'duplicado@empresa.com',
            'status' => Client::STATUS_ACTIVE,
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_user_can_view_client_details(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create([
            'name' => 'Cliente Especial',
            'email' => 'especial@empresa.com',
        ]);

        $response = $this->actingAs($user)->get(route('clients.show', $client));

        $response->assertOk();
        $response->assertSee('Cliente Especial');
        $response->assertSee('especial@empresa.com');
    }

    public function test_user_can_update_a_client(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create([
            'name' => 'Nome Antigo',
            'email' => 'cliente@empresa.com',
            'status' => Client::STATUS_ACTIVE,
        ]);

        $response = $this->actingAs($user)->put(route('clients.update', $client), [
            'name' => 'Nome Atualizado',
            'email' => 'cliente@empresa.com', // Mesmo e-mail deve ser aceito
            'status' => Client::STATUS_PENDING,
            'phone' => '(11) 91234-5678',
            'company' => 'Empresa Renovada',
            'notes' => 'Observação atualizada.',
        ]);

        $response->assertRedirect(route('clients.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'name' => 'Nome Atualizado',
            'status' => 'pending',
            'phone' => '(11) 91234-5678',
        ]);
    }

    public function test_user_can_soft_delete_a_client(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($user)->delete(route('clients.destroy', $client));

        $response->assertRedirect(route('clients.index'));
        $response->assertSessionHas('success');

        $this->assertSoftDeleted($client);
    }
}
