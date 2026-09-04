<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\HostingAccount;
use App\Models\Server;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_users_cannot_access_tickets_index(): void
    {
        $response = $this->get(route('tickets.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_view_tickets_index_and_kpis(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['name' => 'Empresa Parceira']);
        
        $ticket = Ticket::factory()->create([
            'client_id' => $client->id,
            'subject' => 'Problema com SSL no subdomínio',
            'status' => Ticket::STATUS_OPEN,
        ]);

        $response = $this->actingAs($user)->get(route('tickets.index'));

        $response->assertOk();
        $response->assertSee('Problema com SSL no subdomínio');
        $response->assertSee('Empresa Parceira');
        $response->assertSee('Central de Suporte & Chamados', false);
    }

    public function test_tickets_can_be_filtered_by_status(): void
    {
        $user = User::factory()->create();
        Ticket::factory()->open()->create(['subject' => 'Ticket Aberto Urgente']);
        Ticket::factory()->closed()->create(['subject' => 'Ticket Fechado Antigo']);

        $response = $this->actingAs($user)->get(route('tickets.index', ['status' => 'open']));

        $response->assertOk();
        $response->assertSee('Ticket Aberto Urgente');
        $response->assertDontSee('Ticket Fechado Antigo');
    }

    public function test_tickets_can_be_filtered_by_department(): void
    {
        $user = User::factory()->create();
        Ticket::factory()->create([
            'department' => Ticket::DEPARTMENT_FINANCIAL,
            'subject' => 'Dúvida sobre fatura',
        ]);
        Ticket::factory()->create([
            'department' => Ticket::DEPARTMENT_DEVOPS,
            'subject' => 'Reconfiguração do firewall VPS',
        ]);

        $response = $this->actingAs($user)->get(route('tickets.index', ['department' => 'financial']));

        $response->assertOk();
        $response->assertSee('Dúvida sobre fatura');
        $response->assertDontSee('Reconfiguração do firewall VPS');
    }

    public function test_tickets_can_be_filtered_by_priority(): void
    {
        $user = User::factory()->create();
        Ticket::factory()->urgent()->create(['subject' => 'Servidor Fora do Ar']);
        Ticket::factory()->create([
            'priority' => Ticket::PRIORITY_LOW,
            'subject' => 'Alteração de cor de botão',
        ]);

        $response = $this->actingAs($user)->get(route('tickets.index', ['priority' => 'urgent']));

        $response->assertOk();
        $response->assertSee('Servidor Fora do Ar');
        $response->assertDontSee('Alteração de cor de botão');
    }

    public function test_tickets_can_be_filtered_by_client(): void
    {
        $user = User::factory()->create();
        $clientA = Client::factory()->create(['name' => 'Cliente A']);
        $clientB = Client::factory()->create(['name' => 'Cliente B']);

        Ticket::factory()->create(['client_id' => $clientA->id, 'subject' => 'Chamado do Cliente A']);
        Ticket::factory()->create(['client_id' => $clientB->id, 'subject' => 'Chamado do Cliente B']);

        $response = $this->actingAs($user)->get(route('tickets.index', ['client_id' => $clientA->id]));

        $response->assertOk();
        $response->assertSee('Chamado do Cliente A');
        $response->assertDontSee('Chamado do Cliente B');
    }

    public function test_ticket_create_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($user)->get(route('tickets.create', ['client_id' => $client->id]));

        $response->assertOk();
        $response->assertSee('Abrir Novo Chamado de Suporte');
        $response->assertSee($client->name);
    }

    public function test_user_can_create_a_ticket_with_initial_reply(): void
    {
        $user = User::factory()->create(['name' => 'Suporte Técnico']);
        $client = Client::factory()->create();
        $server = Server::factory()->create();

        $response = $this->actingAs($user)->post(route('tickets.store'), [
            'client_id' => $client->id,
            'server_id' => $server->id,
            'department' => Ticket::DEPARTMENT_DEVOPS,
            'priority' => Ticket::PRIORITY_HIGH,
            'subject' => 'Reinicialização do container MySQL',
            'message' => 'Detectamos lentidão no container MySQL e precisamos agendar um restart.',
        ]);

        $ticket = Ticket::where('subject', 'Reinicialização do container MySQL')->first();
        $this->assertNotNull($ticket);

        $response->assertRedirect(route('tickets.show', $ticket));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'client_id' => $client->id,
            'server_id' => $server->id,
            'department' => 'devops',
            'priority' => 'high',
            'status' => 'open',
        ]);

        // Verifica a primeira mensagem criada
        $this->assertDatabaseHas('ticket_replies', [
            'ticket_id' => $ticket->id,
            'author_type' => 'staff',
            'message' => 'Detectamos lentidão no container MySQL e precisamos agendar um restart.',
            'is_internal_note' => false,
        ]);
    }

    public function test_ticket_creation_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('tickets.store'), []);

        $response->assertSessionHasErrors(['client_id', 'department', 'priority', 'subject', 'message']);
    }

    public function test_user_can_view_ticket_details_and_replies(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['name' => 'Tech Master']);
        $ticket = Ticket::factory()->create([
            'client_id' => $client->id,
            'subject' => 'Consulta de SLA',
        ]);

        TicketReply::factory()->create([
            'ticket_id' => $ticket->id,
            'author_name' => 'Cliente Tech',
            'message' => 'Qual é o SLA para incidentes críticos?',
        ]);

        $response = $this->actingAs($user)->get(route('tickets.show', $ticket));

        $response->assertOk();
        $response->assertSee($ticket->ticket_number);
        $response->assertSee('Consulta de SLA');
        $response->assertSee('Qual é o SLA para incidentes críticos?');
        $response->assertSee('Tech Master');
    }

    public function test_user_can_reply_to_a_ticket(): void
    {
        $user = User::factory()->create(['name' => 'Atendente Carlos']);
        $ticket = Ticket::factory()->open()->create();

        $response = $this->actingAs($user)->post(route('tickets.reply', $ticket), [
            'message' => 'Prezado cliente, sua solicitação foi encaminhada para a engenharia.',
            'is_internal_note' => 0,
        ]);

        $response->assertRedirect(route('tickets.show', $ticket));
        $response->assertSessionHas('success');

        $ticket->refresh();
        $this->assertEquals(Ticket::STATUS_ANSWERED, $ticket->status);

        $this->assertDatabaseHas('ticket_replies', [
            'ticket_id' => $ticket->id,
            'author_name' => 'Atendente Carlos',
            'message' => 'Prezado cliente, sua solicitação foi encaminhada para a engenharia.',
            'is_internal_note' => false,
        ]);
    }

    public function test_user_can_add_internal_note_to_a_ticket(): void
    {
        $user = User::factory()->create(['name' => 'Admin DevOps']);
        $ticket = Ticket::factory()->open()->create();

        $response = $this->actingAs($user)->post(route('tickets.reply', $ticket), [
            'message' => 'Nota interna: verificado no container que o uso de memória está normal.',
            'is_internal_note' => 1,
        ]);

        $response->assertRedirect(route('tickets.show', $ticket));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('ticket_replies', [
            'ticket_id' => $ticket->id,
            'message' => 'Nota interna: verificado no container que o uso de memória está normal.',
            'is_internal_note' => true,
        ]);
    }

    public function test_user_can_close_and_reopen_ticket(): void
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->open()->create();

        // Fechar chamado
        $response = $this->actingAs($user)->patch(route('tickets.update-status', $ticket), [
            'status' => Ticket::STATUS_CLOSED,
        ]);

        $response->assertRedirect(route('tickets.show', $ticket));
        $ticket->refresh();
        $this->assertEquals(Ticket::STATUS_CLOSED, $ticket->status);
        $this->assertNotNull($ticket->closed_at);

        // Reabrir chamado
        $response2 = $this->actingAs($user)->patch(route('tickets.update-status', $ticket), [
            'status' => Ticket::STATUS_OPEN,
        ]);

        $response2->assertRedirect(route('tickets.show', $ticket));
        $ticket->refresh();
        $this->assertEquals(Ticket::STATUS_OPEN, $ticket->status);
        $this->assertNull($ticket->closed_at);
    }

    public function test_user_can_soft_delete_a_ticket(): void
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create();

        $response = $this->actingAs($user)->delete(route('tickets.destroy', $ticket));

        $response->assertRedirect(route('tickets.index'));
        $response->assertSessionHas('success');

        $this->assertSoftDeleted($ticket);
    }
}
