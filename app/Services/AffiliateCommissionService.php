<?php

namespace App\Services;

use App\Models\AffiliateCommission;
use App\Models\Invoice;
use App\Models\User;

class AffiliateCommissionService
{
    /**
     * Calcula e credita comissão ao afiliado se a fatura pertencer a um cliente indicado.
     */
    public function creditCommissionForInvoice(Invoice $invoice): ?AffiliateCommission
    {
        // Verifica se a fatura já gerou comissão anteriormente
        if (AffiliateCommission::where('invoice_id', $invoice->id)->exists()) {
            return null;
        }

        // Localiza o usuário correspondente ao cliente da fatura
        $user = User::where('email', $invoice->client?->email)->first();
        if (!$user || !$user->referred_by_affiliate_id) {
            return null;
        }

        $affiliate = $user->referrerAffiliate;
        if (!$affiliate || $affiliate->status !== 'active') {
            return null;
        }

        $orderAmountCents = $invoice->amount_cents;
        if ($orderAmountCents <= 0) {
            return null;
        }

        $rate = (float) $affiliate->commission_percentage;
        $commissionCents = (int) round(($orderAmountCents * $rate) / 100);

        $commission = AffiliateCommission::create([
            'affiliate_id' => $affiliate->id,
            'invoice_id' => $invoice->id,
            'referred_user_id' => $user->id,
            'order_amount_cents' => $orderAmountCents,
            'commission_cents' => $commissionCents,
            'rate_percentage' => $rate,
            'status' => 'available',
            'description' => "Comissão de {$rate}% sobre Fatura {$invoice->invoice_number}",
        ]);

        $affiliate->increment('balance_cents', $commissionCents);
        $affiliate->increment('total_earned_cents', $commissionCents);

        return $commission;
    }
}
