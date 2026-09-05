<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\HostingAccount;
use App\Models\Server;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AppInstallerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected HostingAccount $hosting;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $client = Client::create([
            'name' => 'Cliente Marketplace Teste',
            'email' => 'marketplace@testehospedagem.com.br',
            'company' => 'Marketplace App Ltda',
        ]);

        $server = Server::create([
            'name' => 'VPS-SP-NVME-01',
            'ip_address' => '209.50.245.45',
            'hostname' => 'vps1.hostdevpro.app.br',
            'provider' => 'HostDevPro',
            'datacenter_location' => 'São Paulo, BR',
        ]);

        $this->hosting = HostingAccount::create([
            'client_id' => $client->id,
            'server_id' => $server->id,
            'domain' => 'app-test-' . uniqid() . '.com.br',
            'username' => 'usertestapp',
            'plan' => HostingAccount::PLAN_PRO,
            'php_version' => '8.4',
            'disk_quota_mb' => 20480,
            'disk_used_mb' => 100,
            'bandwidth_quota_mb' => 102400,
            'ssl_status' => HostingAccount::SSL_ACTIVE,
            'status' => HostingAccount::STATUS_ACTIVE,
        ]);
    }

    protected function tearDown(): void
    {
        $testDir = public_path("published_sites/{$this->hosting->domain}");
        if (File::exists($testDir)) {
            File::deleteDirectory($testDir);
        }

        parent::tearDown();
    }

    public function test_guest_cannot_access_apps_catalog()
    {
        $response = $this->getJson(route('hosting.control.apps.catalog', $this->hosting));
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_view_apps_catalog()
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('hosting.control.apps.catalog', $this->hosting));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'domain' => $this->hosting->domain,
            ]);

        $apps = $response->json('apps');
        $this->assertIsArray($apps);
        $this->assertCount(4, $apps);

        $appIds = array_column($apps, 'id');
        $this->assertContains('wordpress', $appIds);
        $this->assertContains('sales_lp', $appIds);
        $this->assertContains('laravel', $appIds);
        $this->assertContains('coming_soon', $appIds);
    }

    public function test_install_sales_landing_page()
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('hosting.control.apps.install', $this->hosting), [
                'app_id' => 'sales_lp',
                'product_name' => 'Produto VIP',
                'headline' => 'Oferta Exclusiva 2026',
                'whatsapp' => '5511988887777',
                'clean_root' => true,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'app_id' => 'sales_lp',
                    'domain' => $this->hosting->domain,
                ],
            ]);

        $root = public_path("published_sites/{$this->hosting->domain}");
        $this->assertFileExists($root . '/index.html');
        $this->assertFileExists($root . '/.app-installer.json');

        $html = File::get($root . '/index.html');
        $this->assertStringContainsString('Produto VIP', $html);
        $this->assertStringContainsString('Oferta Exclusiva 2026', $html);
    }

    public function test_install_coming_soon_page()
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('hosting.control.apps.install', $this->hosting), [
                'app_id' => 'coming_soon',
                'site_title' => 'Lançamento Foguete',
                'whatsapp' => '5511977776666',
                'clean_root' => true,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'app_id' => 'coming_soon',
                ],
            ]);

        $root = public_path("published_sites/{$this->hosting->domain}");
        $this->assertFileExists($root . '/index.html');
        $this->assertFileExists($root . '/.app-installer.json');

        $html = File::get($root . '/index.html');
        $this->assertStringContainsString('Lançamento Foguete', $html);
        $this->assertStringContainsString('countdown', $html);
    }

    public function test_install_wordpress_with_database_and_salts()
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('hosting.control.apps.install', $this->hosting), [
                'app_id' => 'wordpress',
                'site_title' => 'Blog Corporativo Oficial',
                'admin_user' => 'adminwp',
                'admin_email' => 'admin@teste.com',
                'clean_root' => true,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'app_id' => 'wordpress',
                    'domain' => $this->hosting->domain,
                ],
            ]);

        $root = public_path("published_sites/{$this->hosting->domain}");
        $this->assertFileExists($root . '/wp-config.php');
        $this->assertFileExists($root . '/index.php');
        $this->assertFileExists($root . '/wp-load.php');
        $this->assertFileExists($root . '/wp-settings.php');
        $this->assertFileExists($root . '/wp-includes/template-loader.php');
        $this->assertFileExists($root . '/.app-installer.json');

        $wpConfig = File::get($root . '/wp-config.php');
        $this->assertStringContainsString("define( 'DB_NAME'", $wpConfig);
        $this->assertStringContainsString("define( 'DB_USER'", $wpConfig);
        $this->assertStringContainsString("define( 'AUTH_KEY'", $wpConfig);
        $this->assertStringContainsString("define( 'SECURE_AUTH_KEY'", $wpConfig);
        $this->assertStringContainsString("define( 'WPLANG', 'pt_BR' );", $wpConfig);

        $template = File::get($root . '/wp-includes/template-loader.php');
        $this->assertStringContainsString('WordPress 6.7 Instalado!', $template);
        $this->assertStringContainsString('Banco de Dados MySQL Criado Automaticamente', $template);
    }

    public function test_install_laravel_starter()
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('hosting.control.apps.install', $this->hosting), [
                'app_id' => 'laravel',
                'clean_root' => true,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'app_id' => 'laravel',
                ],
            ]);

        $root = public_path("published_sites/{$this->hosting->domain}");
        $this->assertFileExists($root . '/.env');
        $this->assertFileExists($root . '/routes/web.php');
        $this->assertFileExists($root . '/public/index.php');
        $this->assertFileExists($root . '/index.php');

        $env = File::get($root . '/.env');
        $this->assertStringContainsString('APP_KEY=base64:', $env);
        $this->assertStringContainsString('DB_CONNECTION=mysql', $env);
    }

    public function test_invalid_app_id_is_rejected()
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('hosting.control.apps.install', $this->hosting), [
                'app_id' => 'malicious_app_unsupported',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['app_id']);
    }
}
