<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'affiliate_id',
        'invoice_id',
        'referred_user_id',
        'order_amount_cents',
        'commission_cents',
        'rate_percentage',
        'status',
        'description',
    ];

    protected $casts = [
        'order_amount_cents' => 'integer',
        'commission_cents' => 'integer',
        'rate_percentage' => 'decimal:2',
    ];

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function referredUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    public function getFormattedCommissionAttribute(): string
    {
        return 'R$ ' . number_format($this->commission_cents / 100, 2, ',', '.');
    }

    public function getFormattedOrderAmountAttribute(): string
    {
        return 'R$ ' . number_format($this->order_amount_cents / 100, 2, ',', '.');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'available' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
            'paid' => 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20',
            'pending' => 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
            'cancelled' => 'bg-rose-500/10 text-rose-400 border border-rose-500/20',
            default => 'bg-slate-500/10 text-slate-400 border border-slate-500/20',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'available' => 'Disponível',
            'paid' => 'Pago',
            'pending' => 'Pendente',
            'cancelled' => 'Cancelado',
            default => ucfirst($this->status),
        };
    }
}
