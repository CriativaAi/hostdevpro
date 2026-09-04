<?php

namespace App\Models;

use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory, SoftDeletes;

    public const TYPE_SAAS = 'saas';
    public const TYPE_WEBSITE = 'website';
    public const TYPE_ECOMMERCE = 'ecommerce';
    public const TYPE_API = 'api';
    public const TYPE_LANDING_PAGE = 'landing_page';
    public const TYPE_MOBILE_APP = 'mobile_app';

    public const STATUS_PLANNING = 'planning';
    public const STATUS_DEVELOPMENT = 'development';
    public const STATUS_STAGING = 'staging';
    public const STATUS_PRODUCTION = 'production';
    public const STATUS_MAINTENANCE = 'maintenance';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'client_id',
        'name',
        'type',
        'status',
        'repository_url',
        'production_url',
        'staging_url',
        'description',
        'tech_stack',
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
     * Get the client that owns the project.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Chamados de suporte associados a este projeto.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get the human-readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PLANNING => 'Planejamento',
            self::STATUS_DEVELOPMENT => 'Desenvolvimento',
            self::STATUS_STAGING => 'Homologação',
            self::STATUS_PRODUCTION => 'Produção',
            self::STATUS_MAINTENANCE => 'Manutenção',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get the Tailwind classes for the status badge.
     */
    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PLANNING => 'bg-sky-50 text-sky-700 ring-sky-600/20',
            self::STATUS_DEVELOPMENT => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
            self::STATUS_STAGING => 'bg-amber-50 text-amber-700 ring-amber-600/20',
            self::STATUS_PRODUCTION => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
            self::STATUS_MAINTENANCE => 'bg-purple-50 text-purple-700 ring-purple-600/20',
            default => 'bg-gray-100 text-gray-700 ring-gray-600/20',
        };
    }

    /**
     * Get the human-readable type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_SAAS => 'SaaS / Web App',
            self::TYPE_WEBSITE => 'Website Institucional',
            self::TYPE_ECOMMERCE => 'E-commerce',
            self::TYPE_API => 'API / Backend',
            self::TYPE_LANDING_PAGE => 'Landing Page',
            self::TYPE_MOBILE_APP => 'Aplicativo Mobile',
            default => ucfirst($this->type),
        };
    }

    /**
     * Get tech stack as an array of tags.
     *
     * @return array<int, string>
     */
    public function getTechStackArrayAttribute(): array
    {
        if (empty($this->tech_stack)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $this->tech_stack))));
    }
}
