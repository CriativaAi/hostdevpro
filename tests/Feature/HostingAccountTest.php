<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\HostingAccount;
use App\Models\Server;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HostingAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_users_cannot_access_hosting_index(): void
    {
        $response = $this->get(route('hosting.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_view_hosting_index(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['name' => 'Empresa Exemplo']);
        $server = Server::factory()->create(['name' => 'VPS-01']);
        
        $account = HostingAccount::factory()->create([
            'client_id' => $client->id,
            'server_id' => $server->id,
            'domain' => 'empresaexemplo.com.br',
            'status' => HostingAccount::STATUS_ACTIVE,
        ]);

        $response = $this->actingAs($user)->get(route('hosting.index'));

        $response->assertOk();
        $response->assertSee('empresaexemplo.com.br');
        $response->assertSee('Empresa Exemplo');
        $response->assertSee('VPS-01');
        $response->assertSee('Contas de Hospedagem');
    }

    public function test_hosting_accounts_can_be_filtered_by_status(): void
    {
        $user = User::factory()->create();
        HostingAccount::factory()->active()->create(['domain' => 'ativo-site.com.br']);
        HostingAccount::factory()->suspended()->create(['domain' => 'suspenso-site.com.br']);

        $response = $this->actingAs($user)->get(route('hosting.index', ['status' => 'active']));

        $response->assertOk();
        $response->assertSee('ativo-site.com.br');
        $response->assertDontSee('suspenso-site.com.br');
    }

    public function test_hosting_accounts_can_be_filtered_by_client(): void
    {
        $user = User::factory()->create();
        $clientA = Client::factory()->create(['name' => 'Cliente A']);
        $clientB = Client::factory()->create(['name' => 'Cliente B']);

        HostingAccount::factory()->create(['client_id' => $clientA->id, 'domain' => 'site-cliente-a.com']);
        HostingAccount::factory()->create(['client_id' => $clientB->id, 'domain' => 'site-cliente-b.com']);

        $response = $this->actingAs($user)->get(route('hosting.index', ['client_id' => $clientA->id]));

        $response->assertOk();
        $response->assertSee('site-cliente-a.com');
        $response->assertDontSee('site-cliente-b.com');
    }

    public function test_hosting_accounts_can_be_filtered_by_server(): void
    {
        $user = User::factory()->create();
        $serverA = Server::factory()->create(['name' => 'Servidor A']);
        $serverB = Server::factory()->create(['name' => 'Servidor B']);

        HostingAccount::factory()->create(['server_id' => $serverA->id, 'domain' => 'hosted-on-a.com']);
        HostingAccount::factory()->create(['server_id' => $serverB->id, 'domain' => 'hosted-on-b.com']);

        $response = $this->actingAs($user)->get(route('hosting.index', ['server_id' => $serverA->id]));

        $response->assertOk();
        $response->assertSee('hosted-on-a.com');
        $response->assertDontSee('hosted-on-b.com');
    }

    public function test_hosting_create_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $server = Server::factory()->create();

        $response = $this->actingAs($user)->get(route('hosting.create', ['client_id' => $client->id]));

        $response->assertOk();
        $response->assertSee('Provisionar Nova Hospedagem');
        $response->assertSee($client->name);
        $response->assertSee($server->name);
    }

    public function test_user_can_create_a_hosting_account(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $server = Server::factory()->create();

        $response = $this->actingAs($user)->post(route('hosting.store'), [
            'client_id' => $client->id,
            'server_id' => $server->id,
            'domain' => 'portalcorporativo.app.br',
            'username' => 'portalcorp',
            'plan' => HostingAccount::PLAN_PRO,
            'disk_quota_mb' => 20480,
            'bandwidth_quota_mb' => 100000,
            'php_version' => '8.4',
            'status' => HostingAccount::STATUS_ACTIVE,
            'ssl_status' => HostingAccount::SSL_ACTIVE,
            'notes' => 'Conta provisionada com Let\'s Encrypt e PHP 8.4.',
        ]);

        $account = HostingAccount::where('domain', 'portalcorporativo.app.br')->first();
        $this->assertNotNull($account);

        $response->assertRedirect(route('hosting.show', $account));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('hosting_accounts', [
            'id' => $account->id,
            'client_id' => $client->id,
            'server_id' => $server->id,
            'domain' => 'portalcorporativo.app.br',
            'username' => 'portalcorp',
            'plan' => 'pro',
            'status' => 'active',
        ]);
    }

    public function test_hosting_account_creation_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('hosting.store'), []);

        $response->assertSessionHasErrors(['client_id', 'server_id', 'domain', 'plan', 'status', 'disk_quota_mb', 'bandwidth_quota_mb']);
    }

    public function test_hosting_account_creation_validates_domain_format(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $server = Server::factory()->create();

        $response = $this->actingAs($user)->post(route('hosting.store'), [
            'client_id' => $client->id,
            'server_id' => $server->id,
            'domain' => 'not a valid domain!@#$',
            'username' => 'invaliduser',
            'plan' => HostingAccount::PLAN_BASIC,
            'status' => HostingAccount::STATUS_ACTIVE,
            'php_version' => '8.4',
            'disk_quota_mb' => 5120,
            'bandwidth_quota_mb' => 50000,
            'ssl_status' => HostingAccount::SSL_ACTIVE,
        ]);

        $response->assertSessionHasErrors(['domain']);
    }

    public function test_user_can_view_hosting_account_details_and_dns_instructions(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['name' => 'Cliente Corporativo']);
        $server = Server::factory()->create(['name' => 'VPS-Integrator-01', 'ip_address' => '209.50.245.45']);
        
        $account = HostingAccount::factory()->create([
            'client_id' => $client->id,
            'server_id' => $server->id,
            'domain' => 'meucliente.com.br',
            'status' => HostingAccount::STATUS_ACTIVE,
        ]);

        $response = $this->actingAs($user)->get(route('hosting.show', $account));

        $response->assertOk();
        $response->assertSee('meucliente.com.br');
        $response->assertSee('Cliente Corporativo');
        $response->assertSee('VPS-Integrator-01');
        $response->assertSee('209.50.245.45');
        // DNS & MX ValueHost references
        $response->assertSee('ValueHost');
        $response->assertSee('Registro.br');
    }

    public function test_user_can_update_a_hosting_account(): void
    {
        $user = User::factory()->create();
        $account = HostingAccount::factory()->create([
            'domain' => 'siteantigo.com.br',
            'plan' => HostingAccount::PLAN_BASIC,
        ]);

        $response = $this->actingAs($user)->put(route('hosting.update', $account), [
            'client_id' => $account->client_id,
            'server_id' => $account->server_id,
            'domain' => 'siteantigo.com.br',
            'username' => $account->username,
            'plan' => HostingAccount::PLAN_ENTERPRISE,
            'disk_quota_mb' => 51200,
            'bandwidth_quota_mb' => 500000,
            'php_version' => '8.5',
            'status' => HostingAccount::STATUS_ACTIVE,
            'ssl_status' => HostingAccount::SSL_ACTIVE,
        ]);

        $response->assertRedirect(route('hosting.show', $account));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('hosting_accounts', [
            'id' => $account->id,
            'plan' => 'enterprise',
            'disk_quota_mb' => 51200,
            'php_version' => '8.5',
        ]);
    }

    public function test_user_can_toggle_hosting_account_status(): void
    {
        $user = User::factory()->create();
        $account = HostingAccount::factory()->active()->create();

        // Toggle from active to suspended
        $response = $this->actingAs($user)->patch(route('hosting.toggle-status', $account));

        $response->assertRedirect(route('hosting.show', $account));
        $response->assertSessionHas('success');

        $account->refresh();
        $this->assertEquals(HostingAccount::STATUS_SUSPENDED, $account->status);

        // Toggle from suspended back to active
        $response2 = $this->actingAs($user)->patch(route('hosting.toggle-status', $account));

        $account->refresh();
        $this->assertEquals(HostingAccount::STATUS_ACTIVE, $account->status);
    }

    public function test_user_can_soft_delete_a_hosting_account(): void
    {
        $user = User::factory()->create();
        $account = HostingAccount::factory()->create();

        $response = $this->actingAs($user)->delete(route('hosting.destroy', $account));

        $response->assertRedirect(route('hosting.index'));
        $response->assertSessionHas('success');

        $this->assertSoftDeleted($account);
    }
}
