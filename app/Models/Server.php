<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Server extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_ONLINE = 'online';
    const STATUS_OFFLINE = 'offline';
    const STATUS_MAINTENANCE = 'maintenance';

    protected $fillable = [
        'name',
        'hostname',
        'ip_address',
        'provider',
        'datacenter_location',
        'os',
        'cpu_cores',
        'ram_mb',
        'disk_gb',
        'ssh_port',
        'status',
        'notes',
    ];

    protected $casts = [
        'cpu_cores' => 'integer',
        'ram_mb' => 'integer',
        'disk_gb' => 'integer',
        'ssh_port' => 'integer',
    ];

    /**
     * Contas de Hospedagem alocadas neste servidor.
     */
    public function hostingAccounts(): HasMany
    {
        return $this->hasMany(HostingAccount::class);
    }

    /**
     * Chamados de suporte vinculados a este servidor.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Accessor para label legível do status.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ONLINE => 'Online',
            self::STATUS_OFFLINE => 'Offline',
            self::STATUS_MAINTENANCE => 'Manutenção',
            default => ucfirst($this->status),
        };
    }

    /**
     * Accessor para classes CSS do badge de status.
     */
    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ONLINE => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::STATUS_OFFLINE => 'bg-red-50 text-red-700 border-red-200',
            self::STATUS_MAINTENANCE => 'bg-amber-50 text-amber-700 border-amber-200',
            default => 'bg-gray-50 text-gray-700 border-gray-200',
        };
    }

    /**
     * Accessor para memória formatada em GB.
     */
    public function getRamGbAttribute(): float
    {
        return round($this->ram_mb / 1024, 1);
    }
}
