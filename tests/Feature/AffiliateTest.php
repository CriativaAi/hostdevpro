<?php

namespace Tests\Feature;

use App\Models\Affiliate;
use App\Models\AffiliateCommission;
use App\Models\AffiliateWithdrawal;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;
use App\Services\AffiliateCommissionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AffiliateTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_affiliate_sees_onboarding_activation_card(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('affiliates.index'));

        $response->assertStatus(200);
        $response->assertSee('Ganhe dinheiro indicando clientes para nós');
        $response->assertSee('Ativar Conta de Afiliado');
    }

    public function test_user_can_activate_affiliate_account(): void
    {
        $user = User::factory()->create(['name' => 'Carlos Dev']);

        $response = $this->actingAs($user)->post(route('affiliates.activate'));

        $response->assertRedirect(route('affiliates.index'));
        $this->assertDatabaseHas('affiliates', [
            'user_id' => $user->id,
            'status' => 'active',
            'commission_percentage' => 15.00,
            'cookie_duration_days' => 90,
        ]);

        $affiliate = $user->fresh()->affiliate;
        $this->assertNotNull($affiliate);
        $this->assertStringStartsWith('HDP-', $affiliate->referral_code);
    }

    public function test_track_affiliate_referral_middleware_increments_visitors_and_sets_cookie(): void
    {
        $referrer = User::factory()->create();
        $affiliate = Affiliate::create([
            'user_id' => $referrer->id,
            'referral_code' => 'HDP-TEST123',
            'commission_percentage' => 15.00,
            'cookie_duration_days' => 90,
            'visitors_count' => 0,
            'status' => 'active',
        ]);

        // First visit with ?ref=HDP-TEST123
        $response = $this->get('/?ref=HDP-TEST123');

        $response->assertStatus(200);
        $response->assertCookie('hdp_affiliate', 'HDP-TEST123');
        $this->assertEquals(1, $affiliate->fresh()->visitors_count);

        // Subsequent request with the cookie should not increment visitors again
        $response2 = $this->withCookie('hdp_affiliate', 'HDP-TEST123')->get('/?ref=HDP-TEST123');
        $this->assertEquals(1, $affiliate->fresh()->visitors_count);
    }

    public function test_new_user_registration_with_affiliate_cookie_links_referrer(): void
    {
        $referrer = User::factory()->create();
        $affiliate = Affiliate::create([
            'user_id' => $referrer->id,
            'referral_code' => 'HDP-REF99',
            'status' => 'active',
            'conversions_count' => 0,
        ]);

        $response = $this->withCookie('hdp_affiliate', 'HDP-REF99')->post('/register', [
            'name' => 'Novo Cliente Indicado',
            'email' => 'indicado@teste.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard', absolute: false));

        $newUser = User::where('email', 'indicado@teste.com')->first();
        $this->assertNotNull($newUser);
        $this->assertEquals($affiliate->id, $newUser->referred_by_affiliate_id);
        $this->assertEquals(1, $affiliate->fresh()->conversions_count);
    }

    public function test_affiliate_commission_credited_when_invoice_is_paid(): void
    {
        $referrer = User::factory()->create();
        $affiliate = Affiliate::create([
            'user_id' => $referrer->id,
            'referral_code' => 'HDP-COM15',
            'commission_percentage' => 15.00,
            'balance_cents' => 0,
            'total_earned_cents' => 0,
            'status' => 'active',
        ]);

        // Referred user & client
        $referredUser = User::factory()->create([
            'email' => 'cliente.indicado@empresa.com',
            'referred_by_affiliate_id' => $affiliate->id,
        ]);

        $client = Client::create([
            'name' => 'Empresa do Cliente',
            'email' => 'cliente.indicado@empresa.com',
            'status' => 'active',
        ]);

        $invoice = Invoice::create([
            'client_id' => $client->id,
            'invoice_number' => 'INV-2026-9999',
            'amount_cents' => 10000, // R$ 100,00
            'status' => Invoice::STATUS_UNPAID,
            'due_date' => now()->addDays(5),
        ]);

        // Mark as paid
        $service = app(AffiliateCommissionService::class);
        $commission = $service->creditCommissionForInvoice($invoice);

        $this->assertNotNull($commission);
        $this->assertEquals(1500, $commission->commission_cents); // 15% of 10000 = 1500 (R$ 15,00)
        $this->assertEquals('available', $commission->status);

        $affiliate->refresh();
        $this->assertEquals(1500, $affiliate->balance_cents);
        $this->assertEquals(1500, $affiliate->total_earned_cents);

        // Idempotency: calling again does not duplicate
        $duplicate = $service->creditCommissionForInvoice($invoice);
        $this->assertNull($duplicate);
        $this->assertEquals(1500, $affiliate->fresh()->balance_cents);
    }

    public function test_affiliate_can_update_pix_and_request_withdrawal(): void
    {
        $user = User::factory()->create();
        $affiliate = Affiliate::create([
            'user_id' => $user->id,
            'referral_code' => 'HDP-SAQUE1',
            'balance_cents' => 15000, // R$ 150,00
            'status' => 'active',
        ]);

        // Update PIX key
        $response = $this->actingAs($user)->put(route('affiliates.update-pix'), [
            'pix_key' => '123.456.789-00',
            'pix_key_type' => 'cpf',
        ]);
        $response->assertSessionHas('success');
        $this->assertEquals('123.456.789-00', $affiliate->fresh()->pix_key);

        // Request withdrawal of R$ 100,00 (100.00)
        $withdrawResponse = $this->actingAs($user)->post(route('affiliates.withdraw'), [
            'amount' => 100.00,
        ]);
        $withdrawResponse->assertSessionHas('success');

        $affiliate->refresh();
        $this->assertEquals(5000, $affiliate->balance_cents); // R$ 50,00 remaining
        $this->assertEquals(10000, $affiliate->total_withdrawn_cents);

        $this->assertDatabaseHas('affiliate_withdrawals', [
            'affiliate_id' => $affiliate->id,
            'amount_cents' => 10000,
            'pix_key' => '123.456.789-00',
            'status' => 'pending',
        ]);
    }
}
