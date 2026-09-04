<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AiGeneratedSite extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';

    const STYLE_DARK_FROSTED = 'dark_frosted';
    const STYLE_CLEAN_MINIMAL = 'clean_minimal';
    const STYLE_CORPORATE_BLUE = 'corporate_blue';
    const STYLE_LUXURY_GOLD = 'luxury_gold';
    const STYLE_VIBRANT_MODERN = 'vibrant_modern';

    protected $fillable = [
        'user_id',
        'hosting_account_id',
        'title',
        'business_name',
        'niche',
        'description',
        'whatsapp',
        'style',
        'sections',
        'generated_html',
        'prompt_history',
        'status',
        'published_at',
        'published_path',
        'revisions_count',
    ];

    protected $casts = [
        'sections' => 'array',
        'prompt_history' => 'array',
        'published_at' => 'datetime',
        'revisions_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hostingAccount(): BelongsTo
    {
        return $this->belongsTo(HostingAccount::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PUBLISHED => 'Publicado',
            default => 'Rascunho',
        };
    }

    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PUBLISHED => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
            default => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
        };
    }

    public function getStyleLabelAttribute(): string
    {
        return match ($this->style) {
            self::STYLE_DARK_FROSTED => 'Dark Frosted Glass',
            self::STYLE_CLEAN_MINIMAL => 'Clean & Minimalista',
            self::STYLE_CORPORATE_BLUE => 'Corporativo & Tech',
            self::STYLE_LUXURY_GOLD => 'Elegante & Premium',
            self::STYLE_VIBRANT_MODERN => 'Vibrante & Criativo',
            default => 'Moderno',
        };
    }
}
