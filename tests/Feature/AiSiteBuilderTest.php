<?php

namespace Tests\Feature;

use App\Models\AiGeneratedSite;
use App\Models\Client;
use App\Models\HostingAccount;
use App\Models\Server;
use App\Models\User;
use App\Services\GeminiSiteBuilderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class AiSiteBuilderTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_unauthenticated_users_cannot_access_ai_builder(): void
    {
        $this->get(route('ai-builder.index'))->assertRedirect(route('login'));
        $this->get(route('ai-builder.create'))->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_view_ai_builder_index(): void
    {
        $response = $this->actingAs($this->user)->get(route('ai-builder.index'));

        $response->assertOk();
        $response->assertSee('Criador de Sites com IA');
        $response->assertSee('Gemini 3.6 Flash');
    }

    public function test_authenticated_users_can_view_create_wizard(): void
    {
        $response = $this->actingAs($this->user)->get(route('ai-builder.create'));

        $response->assertOk();
        $response->assertSee('Identidade da Empresa');
        $response->assertSee('Nicho de Atuação');
        $response->assertSee('Dark Frosted');
    }

    public function test_user_can_generate_site(): void
    {
        $mockService = Mockery::mock(GeminiSiteBuilderService::class);
        $mockService->shouldReceive('generateSite')
            ->once()
            ->andReturn('<!DOCTYPE html><html><head><title>Barbearia Teste</title></head><body><h1>Barbearia Teste</h1></body></html>');

        $this->app->instance(GeminiSiteBuilderService::class, $mockService);

        $response = $this->actingAs($this->user)->post(route('ai-builder.store'), [
            'business_name' => 'Barbearia Alpha VIP',
            'niche' => 'Barbearia e Estética Masculina',
            'description' => 'Cortes modernos e barba terapia.',
            'whatsapp' => '11921381308',
            'style' => 'dark_frosted',
            'sections' => ['hero', 'services', 'contact'],
        ]);

        $this->assertDatabaseHas('ai_generated_sites', [
            'business_name' => 'Barbearia Alpha VIP',
            'niche' => 'Barbearia e Estética Masculina',
            'style' => 'dark_frosted',
            'status' => AiGeneratedSite::STATUS_DRAFT,
        ]);

        $site = AiGeneratedSite::first();
        $response->assertRedirect(route('ai-builder.studio', $site));
    }

    public function test_user_can_view_studio(): void
    {
        $site = AiGeneratedSite::create([
            'user_id' => $this->user->id,
            'title' => 'Oficina Mecânica Master',
            'business_name' => 'Oficina Mecânica Master',
            'niche' => 'Oficina Mecânica',
            'description' => 'Serviços de motor e suspensão.',
            'whatsapp' => '11921381308',
            'style' => 'dark_frosted',
            'generated_html' => '<!DOCTYPE html><html><body><h1>Oficina Master</h1></body></html>',
            'status' => AiGeneratedSite::STATUS_DRAFT,
            'revisions_count' => 1,
        ]);

        $response = $this->actingAs($this->user)->get(route('ai-builder.studio', $site));

        $response->assertOk();
        $response->assertSee('Oficina Mecânica Master');
        $response->assertSee('Ajustar com Gemini IA');
        $response->assertSee('Desktop');
        $response->assertSee('Tablet');
        $response->assertSee('Mobile');
    }

    public function test_user_can_view_isolated_preview(): void
    {
        $html = '<!DOCTYPE html><html><body><h1>Conteúdo Isolado</h1></body></html>';
        $site = AiGeneratedSite::create([
            'user_id' => $this->user->id,
            'title' => 'Site Teste',
            'business_name' => 'Site Teste',
            'niche' => 'Tecnologia',
            'style' => 'clean_minimal',
            'generated_html' => $html,
        ]);

        $response = $this->actingAs($this->user)->get(route('ai-builder.preview', $site));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $this->assertEquals($html, $response->getContent());
    }

    public function test_user_can_refine_site(): void
    {
        $site = AiGeneratedSite::create([
            'user_id' => $this->user->id,
            'title' => 'Site para Refinar',
            'business_name' => 'Site para Refinar',
            'niche' => 'Gastronomia',
            'style' => 'vibrant_modern',
            'generated_html' => '<!DOCTYPE html><html><body><h1>Antes</h1></body></html>',
            'revisions_count' => 1,
        ]);

        $updatedHtml = '<!DOCTYPE html><html><body><h1>Depois com FAQ</h1></body></html>';

        $mockService = Mockery::mock(GeminiSiteBuilderService::class);
        $mockService->shouldReceive('refineSite')
            ->once()
            ->andReturn($updatedHtml);

        $this->app->instance(GeminiSiteBuilderService::class, $mockService);

        $response = $this->actingAs($this->user)->postJson(route('ai-builder.refine', $site), [
            'instruction' => 'Adicione uma seção de FAQ no final da página.',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'revisions_count' => 2,
        ]);

        $site->refresh();
        $this->assertEquals($updatedHtml, $site->generated_html);
        $this->assertEquals(2, $site->revisions_count);
    }

    public function test_user_can_download_html(): void
    {
        $site = AiGeneratedSite::create([
            'user_id' => $this->user->id,
            'title' => 'Download Test',
            'business_name' => 'Download Test',
            'niche' => 'Marketing',
            'style' => 'dark_frosted',
            'generated_html' => '<html><body>Download</body></html>',
        ]);

        $response = $this->actingAs($this->user)->get(route('ai-builder.download.html', $site));

        $response->assertOk();
        $response->assertHeader('Content-Disposition', 'attachment; filename=index.html');
    }

    public function test_user_can_publish_site(): void
    {
        $site = AiGeneratedSite::create([
            'user_id' => $this->user->id,
            'title' => 'Publicação Teste',
            'business_name' => 'Publicação Teste',
            'niche' => 'Comércio',
            'style' => 'corporate_blue',
            'generated_html' => '<html><body>Publicado</body></html>',
            'status' => AiGeneratedSite::STATUS_DRAFT,
        ]);

        $response = $this->actingAs($this->user)->post(route('ai-builder.publish', $site));

        $response->assertRedirect(route('ai-builder.studio', $site));
        
        $site->refresh();
        $this->assertEquals(AiGeneratedSite::STATUS_PUBLISHED, $site->status);
        $this->assertNotNull($site->published_at);
    }

    public function test_user_can_soft_delete_site(): void
    {
        $site = AiGeneratedSite::create([
            'user_id' => $this->user->id,
            'title' => 'Para Deletar',
            'business_name' => 'Para Deletar',
            'niche' => 'Serviços',
            'style' => 'dark_frosted',
            'generated_html' => '<html><body>Tchau</body></html>',
        ]);

        $response = $this->actingAs($this->user)->delete(route('ai-builder.destroy', $site));

        $response->assertRedirect(route('ai-builder.index'));
        $this->assertSoftDeleted('ai_generated_sites', ['id' => $site->id]);
    }
}
