<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'name' => 'Alexandre Dev',
            'email' => 'alex@actualagency.com.br',
        ]);

        $this->client = Client::create([
            'name' => 'Alexandre Dev',
            'email' => 'alex@actualagency.com.br',
            'status' => Client::STATUS_ACTIVE,
        ]);
    }

    public function test_unauthenticated_users_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_users_can_render_dashboard_with_greeting(): void
    {
        $response = $this->actingAs($this->user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Olá, Alexandre !');
        $response->assertSee('Área do Cliente');
        $response->assertSee('SERVIÇOS');
        $response->assertSee('FATURAS');
        $response->assertSee('Recursos premium');
        $response->assertSee('Gemini IA Cloud');
    }

    public function test_dashboard_displays_overdue_invoice_notice(): void
    {
        Invoice::create([
            'invoice_number' => 'FAT-2026-0873',
            'client_id' => $this->client->id,
            'amount_cents' => 5999,
            'status' => Invoice::STATUS_UNPAID,
            'due_date' => Carbon::yesterday(), // Vencida
        ]);

        $response = $this->actingAs($this->user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('1 fatura vencida');
        $response->assertSee('Total de R$ 59,99');
        $response->assertSee('Pagar agora');
    }

    public function test_dashboard_displays_pending_ticket_notice(): void
    {
        Ticket::create([
            'ticket_number' => 'CFI-073726',
            'client_id' => $this->client->id,
            'subject' => 'Domínio www.evolutionlocacoes.com.br',
            'department' => Ticket::DEPARTMENT_TECHNICAL,
            'priority' => Ticket::PRIORITY_HIGH,
            'status' => Ticket::STATUS_ANSWERED, // Aguardando resposta!
        ]);

        $response = $this->actingAs($this->user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('1 ticket aguarda sua resposta');
        $response->assertSee('#CFI-073726');
        $response->assertSee('Responder');
    }
}
