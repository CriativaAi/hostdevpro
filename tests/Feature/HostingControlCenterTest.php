<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\HostingAccount;
use App\Models\Server;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class HostingControlCenterTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected HostingAccount $hosting;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $client = Client::create([
            'name' => 'Cliente Teste Hospedagem',
            'email' => 'cliente@testehospedagem.com.br',
            'company' => 'Empresa Teste Ltda',
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
            'domain' => 'test-domain-' . uniqid() . '.com.br',
            'username' => 'usertest',
            'plan' => HostingAccount::PLAN_PRO,
            'php_version' => '8.4',
            'disk_quota_mb' => 15360,
            'disk_used_mb' => 250,
            'bandwidth_quota_mb' => 102400,
            'ssl_status' => HostingAccount::SSL_ACTIVE,
            'status' => HostingAccount::STATUS_ACTIVE,
        ]);
    }

    protected function tearDown(): void
    {
        // Limpa pasta criada para o teste
        $testDir = public_path("published_sites/{$this->hosting->domain}");
        if (File::exists($testDir)) {
            File::deleteDirectory($testDir);
        }

        parent::tearDown();
    }

    public function test_authenticated_user_can_view_hosting_control_center()
    {
        $response = $this->actingAs($this->user)
            ->get(route('hosting.show', $this->hosting));

        $response->assertStatus(200);
        $response->assertSee('Gerenciador de Arquivos');
        $response->assertSee('Zonas DNS');
        $response->assertSee('E-mails Corporativos');
        $response->assertSee('Bancos MySQL');
    }

    public function test_can_list_files_and_initial_index_html_is_seeded()
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('hosting.control.files', $this->hosting));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $items = $response->json('data.items');
        $names = array_column($items, 'name');
        $this->assertContains('index.html', $names);
    }

    public function test_can_create_and_read_and_save_file()
    {
        // 1. Cria arquivo
        $createRes = $this->actingAs($this->user)
            ->postJson(route('hosting.control.create-file', $this->hosting), [
                'filename' => 'teste.php',
            ]);
        $createRes->assertStatus(200);

        // 2. Lê conteúdo
        $readRes = $this->actingAs($this->user)
            ->getJson(route('hosting.control.file-content', ['hosting' => $this->hosting, 'filepath' => 'teste.php']));
        $readRes->assertStatus(200);

        // 3. Salva novo conteúdo
        $saveRes = $this->actingAs($this->user)
            ->postJson(route('hosting.control.save-file', $this->hosting), [
                'filepath' => 'teste.php',
                'content' => '<?php echo "HostDevPro Cloud";',
            ]);
        $saveRes->assertStatus(200);

        // 4. Valida persistência
        $readAgain = $this->actingAs($this->user)
            ->getJson(route('hosting.control.file-content', ['hosting' => $this->hosting, 'filepath' => 'teste.php']));
        $readAgain->assertJsonFragment(['content' => '<?php echo "HostDevPro Cloud";']);
    }

    public function test_can_create_folder_and_delete_item()
    {
        // Cria pasta
        $folderRes = $this->actingAs($this->user)
            ->postJson(route('hosting.control.create-folder', $this->hosting), [
                'folder_name' => 'novapasta',
            ]);
        $folderRes->assertStatus(200);

        // Exclui pasta
        $delRes = $this->actingAs($this->user)
            ->deleteJson(route('hosting.control.delete-item', $this->hosting), [
                'path' => 'novapasta',
            ]);
        $delRes->assertStatus(200);
    }

    public function test_can_query_dns_records_endpoint()
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('hosting.control.dns.list', $this->hosting));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'domain' => $this->hosting->domain,
        ]);
        $this->assertNotEmpty($response->json('records'));
    }

    public function test_can_update_php_version()
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('hosting.control.update-php', $this->hosting), [
                'php_version' => '8.3',
            ]);

        $response->assertStatus(200);
        $this->assertEquals('8.3', $this->hosting->fresh()->php_version);
    }

    public function test_can_renew_ssl()
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('hosting.control.renew-ssl', $this->hosting));

        $response->assertStatus(200);
        $this->assertEquals(HostingAccount::SSL_ACTIVE, $this->hosting->fresh()->ssl_status);
    }

    public function test_can_download_backup_zip()
    {
        $response = $this->actingAs($this->user)
            ->get(route('hosting.control.backup', $this->hosting));

        $response->assertStatus(200);
        $this->assertEquals('application/zip', $response->headers->get('content-type'));
    }
}
