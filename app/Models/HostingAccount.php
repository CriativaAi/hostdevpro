<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HostingAccount extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_ACTIVE = 'active';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_PENDING = 'pending';
    const STATUS_TERMINATED = 'terminated';

    const PLAN_BASIC = 'basic';
    const PLAN_PRO = 'pro';
    const PLAN_ENTERPRISE = 'enterprise';

    const SSL_ACTIVE = 'active';
    const SSL_PENDING = 'pending';
    const SSL_EXPIRED = 'expired';
    const SSL_NONE = 'none';

    protected $fillable = [
        'client_id',
        'server_id',
        'domain',
        'username',
        'plan',
        'php_version',
        'disk_quota_mb',
        'disk_used_mb',
        'bandwidth_quota_mb',
        'ssl_status',
        'status',
        'suspended_reason',
        'notes',
    ];

    protected $casts = [
        'client_id' => 'integer',
        'server_id' => 'integer',
        'disk_quota_mb' => 'integer',
        'disk_used_mb' => 'integer',
        'bandwidth_quota_mb' => 'integer',
    ];

    /**
     * Cliente proprietário da conta de hospedagem.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Servidor VPS onde a conta reside.
     */
    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * Chamados de suporte associados a esta conta de hospedagem.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Accessor para label do status.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'Ativa',
            self::STATUS_SUSPENDED => 'Suspensa',
            self::STATUS_PENDING => 'Pendente',
            self::STATUS_TERMINATED => 'Cancelada',
            default => ucfirst($this->status),
        };
    }

    /**
     * Accessor para classes CSS do badge de status.
     */
    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::STATUS_SUSPENDED => 'bg-rose-50 text-rose-700 border-rose-200',
            self::STATUS_PENDING => 'bg-amber-50 text-amber-700 border-amber-200',
            self::STATUS_TERMINATED => 'bg-gray-100 text-gray-700 border-gray-200',
            default => 'bg-gray-50 text-gray-700 border-gray-200',
        };
    }

    /**
     * Accessor para label legível do plano.
     */
    public function getPlanLabelAttribute(): string
    {
        return match ($this->plan) {
            self::PLAN_BASIC => 'Start (5 GB NVMe)',
            self::PLAN_PRO => 'Pro (15 GB NVMe)',
            self::PLAN_ENTERPRISE => 'Enterprise (50 GB NVMe)',
            default => ucfirst($this->plan),
        };
    }

    /**
     * Percentual de disco consumido.
     */
    public function getDiskUsagePercentageAttribute(): float
    {
        if ($this->disk_quota_mb <= 0) {
            return 0;
        }
        return min(100, round(($this->disk_used_mb / $this->disk_quota_mb) * 100, 1));
    }

    /**
     * Cota de disco em GB.
     */
    public function getDiskQuotaGbAttribute(): float
    {
        return round($this->disk_quota_mb / 1024, 1);
    }

    /**
     * Disco consumido em GB.
     */
    public function getDiskUsedGbAttribute(): float
    {
        return round($this->disk_used_mb / 1024, 2);
    }

    /**
     * Accessor para classes do badge de SSL.
     */
    public function getSslBadgeClassesAttribute(): string
    {
        return match ($this->ssl_status) {
            self::SSL_ACTIVE => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::SSL_PENDING => 'bg-amber-50 text-amber-700 border-amber-200',
            self::SSL_EXPIRED => 'bg-rose-50 text-rose-700 border-rose-200',
            default => 'bg-gray-100 text-gray-600 border-gray-200',
        };
    }
}
