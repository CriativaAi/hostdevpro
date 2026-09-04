<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use HasFactory, SoftDeletes;

    const CATEGORY_HOSTING = 'hosting';
    const CATEGORY_RESELLER = 'reseller';
    const CATEGORY_VPS = 'vps';
    const CATEGORY_CLOUD = 'cloud';

    const CYCLE_MONTHLY = 'monthly';
    const CYCLE_QUARTERLY = 'quarterly';
    const CYCLE_SEMIANNUAL = 'semiannual';
    const CYCLE_ANNUAL = 'annual';

    protected $fillable = [
        'name',
        'slug',
        'category',
        'price_cents',
        'billing_cycle',
        'disk_quota_mb',
        'bandwidth_quota_mb',
        'description',
        'features',
        'is_active',
    ];

    protected $casts = [
        'price_cents' => 'integer',
        'disk_quota_mb' => 'integer',
        'bandwidth_quota_mb' => 'integer',
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    public function hostingAccounts(): HasMany
    {
        return $this->hasMany(HostingAccount::class);
    }

    public function getPriceFormattedAttribute(): string
    {
        return 'R$ ' . number_format($this->price_cents / 100, 2, ',', '.');
    }

    public function getBillingCycleLabelAttribute(): string
    {
        return match ($this->billing_cycle) {
            self::CYCLE_MONTHLY => 'Mensal',
            self::CYCLE_QUARTERLY => 'Trimestral',
            self::CYCLE_SEMIANNUAL => 'Semestral',
            self::CYCLE_ANNUAL => 'Anual',
            default => ucfirst($this->billing_cycle),
        };
    }

    public function getDiskQuotaGbAttribute(): float
    {
        return round($this->disk_quota_mb / 1024, 1);
    }
}
