<?php

namespace App\Models;

use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    /** @use HasFactory<ClientFactory> */
    use HasFactory, SoftDeletes;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_PENDING = 'pending';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'status',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the human-readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'Ativo',
            self::STATUS_PENDING => 'Pendente',
            self::STATUS_INACTIVE => 'Inativo',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get the Tailwind classes for the status badge.
     */
    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
            self::STATUS_PENDING => 'bg-amber-50 text-amber-700 ring-amber-600/20',
            self::STATUS_INACTIVE => 'bg-slate-100 text-slate-700 ring-slate-600/20',
            default => 'bg-gray-100 text-gray-700 ring-gray-600/20',
        };
    }

    /**
     * Get initials from client name.
     */
    public function getInitialsAttribute(): string
    {
        $words = preg_split('/\s+/', trim($this->name));
        if (empty($words) || empty($words[0])) {
            return 'CL';
        }

        if (count($words) === 1) {
            return mb_strtoupper(mb_substr($words[0], 0, 2));
        }

        return mb_strtoupper(mb_substr($words[0], 0, 1) . mb_substr(end($words), 0, 1));
    }
}
