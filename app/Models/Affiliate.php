<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Affiliate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'referral_code',
        'commission_percentage',
        'cookie_duration_days',
        'balance_cents',
        'total_earned_cents',
        'total_withdrawn_cents',
        'visitors_count',
        'conversions_count',
        'status',
        'pix_key',
        'pix_key_type',
        'activated_at',
    ];

    protected $casts = [
        'commission_percentage' => 'decimal:2',
        'cookie_duration_days' => 'integer',
        'balance_cents' => 'integer',
        'total_earned_cents' => 'integer',
        'total_withdrawn_cents' => 'integer',
        'visitors_count' => 'integer',
        'conversions_count' => 'integer',
        'activated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function referredUsers(): HasMany
    {
        return $this->hasMany(User::class, 'referred_by_affiliate_id');
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(AffiliateCommission::class)->latest();
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(AffiliateWithdrawal::class)->latest();
    }

    public function getFormattedBalanceAttribute(): string
    {
        return 'R$ ' . number_format($this->balance_cents / 100, 2, ',', '.');
    }

    public function getFormattedTotalEarnedAttribute(): string
    {
        return 'R$ ' . number_format($this->total_earned_cents / 100, 2, ',', '.');
    }

    public function getFormattedTotalWithdrawnAttribute(): string
    {
        return 'R$ ' . number_format($this->total_withdrawn_cents / 100, 2, ',', '.');
    }

    public function getReferralUrlAttribute(): string
    {
        return url('/?ref=' . $this->referral_code);
    }

    public function getConversionRateAttribute(): string
    {
        if ($this->visitors_count === 0) {
            return '0.0%';
        }

        $rate = ($this->conversions_count / $this->visitors_count) * 100;
        return number_format($rate, 1, ',', '.') . '%';
    }

    public static function generateUniqueCode(string $name): string
    {
        $clean = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $name));
        $prefix = substr($clean, 0, 4);
        if (strlen($prefix) < 3) {
            $prefix = 'PRO';
        }

        do {
            $code = 'HDP-' . $prefix . rand(100, 999);
        } while (static::where('referral_code', $code)->exists());

        return $code;
    }
}
