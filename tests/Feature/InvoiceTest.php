<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->client = Client::create([
            'name' => 'Cliente Teste',
            'email' => 'cliente@teste.com',
            'status' => Client::STATUS_ACTIVE,
        ]);
    }

    public function test_unauthenticated_users_cannot_access_invoices(): void
    {
        $response = $this->get('/invoices');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_users_can_view_invoices_index(): void
    {
        Invoice::create([
            'invoice_number' => 'FAT-2026-0001',
            'client_id' => $this->client->id,
            'amount_cents' => 5999,
            'status' => Invoice::STATUS_UNPAID,
            'due_date' => Carbon::tomorrow(),
        ]);

        $response = $this->actingAs($this->user)->get('/invoices');
        $response->assertStatus(200);
        $response->assertSee('FAT-2026-0001');
        $response->assertSee('R$ 59,99');
    }

    public function test_invoices_can_be_filtered_by_status(): void
    {
        Invoice::create([
            'invoice_number' => 'FAT-2026-0001',
            'client_id' => $this->client->id,
            'amount_cents' => 5999,
            'status' => Invoice::STATUS_UNPAID,
            'due_date' => Carbon::tomorrow(),
        ]);

        Invoice::create([
            'invoice_number' => 'FAT-2026-0002',
            'client_id' => $this->client->id,
            'amount_cents' => 9900,
            'status' => Invoice::STATUS_PAID,
            'due_date' => Carbon::yesterday(),
            'paid_at' => Carbon::now(),
        ]);

        $responseUnpaid = $this->actingAs($this->user)->get('/invoices?status=unpaid');
        $responseUnpaid->assertSee('FAT-2026-0001');
        $responseUnpaid->assertDontSee('FAT-2026-0002');

        $responsePaid = $this->actingAs($this->user)->get('/invoices?status=paid');
        $responsePaid->assertSee('FAT-2026-0002');
        $responsePaid->assertDontSee('FAT-2026-0001');
    }

    public function test_user_can_view_invoice_details_and_items(): void
    {
        $invoice = Invoice::create([
            'invoice_number' => 'FAT-2026-0001',
            'client_id' => $this->client->id,
            'amount_cents' => 5999,
            'status' => Invoice::STATUS_UNPAID,
            'due_date' => Carbon::tomorrow(),
            'pix_copy_paste' => 'pix_sample_code',
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'description' => 'Hospedagem Pro Mensal',
            'amount_cents' => 5999,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($this->user)->get("/invoices/{$invoice->id}");
        $response->assertStatus(200);
        $response->assertSee('FAT-2026-0001');
        $response->assertSee('Hospedagem Pro Mensal');
        $response->assertSee('PIX Mercado Pago');
    }

    public function test_user_can_mark_invoice_as_paid(): void
    {
        $invoice = Invoice::create([
            'invoice_number' => 'FAT-2026-0001',
            'client_id' => $this->client->id,
            'amount_cents' => 5999,
            'status' => Invoice::STATUS_UNPAID,
            'due_date' => Carbon::tomorrow(),
        ]);

        $response = $this->actingAs($this->user)->post("/invoices/{$invoice->id}/mark-paid");
        $response->assertRedirect();

        $this->assertEquals(Invoice::STATUS_PAID, $invoice->fresh()->status);
        $this->assertNotNull($invoice->fresh()->paid_at);
    }
}
