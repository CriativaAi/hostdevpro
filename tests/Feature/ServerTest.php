<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\HostingAccount;
use App\Models\Server;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServerTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_users_cannot_access_servers_index(): void
    {
        $response = $this->get(route('servers.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_view_servers_index(): void
    {
        $user = User::factory()->create();
        $server = Server::factory()->create([
            'name' => 'VPS-PROD-BRAZIL-01',
            'ip_address' => '209.50.245.45',
            'status' => Server::STATUS_ONLINE,
        ]);

        $response = $this->actingAs($user)->get(route('servers.index'));

        $response->assertOk();
        $response->assertSee('VPS-PROD-BRAZIL-01');
        $response->assertSee('209.50.245.45');
        $response->assertSee('Servidores & Infraestrutura', false);
    }

    public function test_servers_can_be_filtered_by_status(): void
    {
        $user = User::factory()->create();
        Server::factory()->online()->create(['name' => 'Servidor Operacional']);
        Server::factory()->maintenance()->create(['name' => 'Servidor Em Manutencao']);

        $response = $this->actingAs($user)->get(route('servers.index', ['status' => 'online']));

        $response->assertOk();
        $response->assertSee('Servidor Operacional');
        $response->assertDontSee('Servidor Em Manutencao');
    }

    public function test_server_create_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('servers.create'));

        $response->assertOk();
        $response->assertSee('Cadastrar Novo Servidor');
    }

    public function test_user_can_create_a_server(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('servers.store'), [
            'name' => 'VPS-DEV-OPENRESTY-02',
            'hostname' => 'vps02.hostdevpro.app.br',
            'ip_address' => '198.51.100.22',
            'provider' => 'Integrator Cloud',
            'datacenter_location' => 'São Paulo - SP',
            'os' => 'Ubuntu 24.04 LTS x86_64',
            'cpu_cores' => 4,
            'ram_mb' => 8192,
            'disk_gb' => 120,
            'ssh_port' => 2222,
            'status' => Server::STATUS_ONLINE,
            'notes' => 'Servidor secundário provisionado para desenvolvimento.',
        ]);

        $server = Server::where('name', 'VPS-DEV-OPENRESTY-02')->first();
        $this->assertNotNull($server);

        $response->assertRedirect(route('servers.show', $server));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('servers', [
            'id' => $server->id,
            'ip_address' => '198.51.100.22',
            'cpu_cores' => 4,
            'ram_mb' => 8192,
            'ssh_port' => 2222,
            'status' => 'online',
        ]);
    }

    public function test_server_creation_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('servers.store'), []);

        $response->assertSessionHasErrors(['name', 'ip_address', 'cpu_cores', 'ram_mb', 'disk_gb', 'ssh_port', 'status']);
    }

    public function test_server_ip_address_must_be_valid(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('servers.store'), [
            'name' => 'Servidor IP Invalido',
            'ip_address' => 'not-a-valid-ip',
            'cpu_cores' => 2,
            'ram_mb' => 4096,
            'disk_gb' => 80,
            'ssh_port' => 22,
            'status' => Server::STATUS_ONLINE,
        ]);

        $response->assertSessionHasErrors(['ip_address']);
    }

    public function test_user_can_view_server_details_with_assigned_accounts(): void
    {
        $user = User::factory()->create();
        $server = Server::factory()->create(['name' => 'VPS Principal']);
        $client = Client::factory()->create(['name' => 'Cliente Alpha']);
        
        $account = HostingAccount::factory()->create([
            'server_id' => $server->id,
            'client_id' => $client->id,
            'domain' => 'alpha-site.com.br',
        ]);

        $response = $this->actingAs($user)->get(route('servers.show', $server));

        $response->assertOk();
        $response->assertSee('VPS Principal');
        $response->assertSee('alpha-site.com.br');
        $response->assertSee('Cliente Alpha');
    }

    public function test_user_can_update_a_server(): void
    {
        $user = User::factory()->create();
        $server = Server::factory()->create([
            'name' => 'VPS Antigo',
            'status' => Server::STATUS_ONLINE,
        ]);

        $response = $this->actingAs($user)->put(route('servers.update', $server), [
            'name' => 'VPS Renomeado e Atualizado',
            'ip_address' => $server->ip_address,
            'status' => Server::STATUS_MAINTENANCE,
            'cpu_cores' => 8,
            'ram_mb' => 16384,
            'disk_gb' => 240,
            'ssh_port' => 22,
        ]);

        $response->assertRedirect(route('servers.show', $server));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('servers', [
            'id' => $server->id,
            'name' => 'VPS Renomeado e Atualizado',
            'status' => 'maintenance',
            'cpu_cores' => 8,
            'ram_mb' => 16384,
            'disk_gb' => 240,
        ]);
    }

    public function test_user_can_soft_delete_a_server(): void
    {
        $user = User::factory()->create();
        $server = Server::factory()->create();

        $response = $this->actingAs($user)->delete(route('servers.destroy', $server));

        $response->assertRedirect(route('servers.index'));
        $response->assertSessionHas('success');

        $this->assertSoftDeleted($server);
    }
}
