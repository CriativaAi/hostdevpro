<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    const DEPARTMENT_TECHNICAL = 'technical';
    const DEPARTMENT_FINANCIAL = 'financial';
    const DEPARTMENT_COMMERCIAL = 'commercial';
    const DEPARTMENT_DEVOPS = 'devops';

    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    const STATUS_OPEN = 'open';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_ANSWERED = 'answered';
    const STATUS_CUSTOMER_REPLY = 'customer_reply';
    const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'ticket_number',
        'client_id',
        'user_id',
        'hosting_account_id',
        'server_id',
        'project_id',
        'department',
        'priority',
        'status',
        'subject',
        'last_reply_at',
        'closed_at',
    ];

    protected $casts = [
        'client_id' => 'integer',
        'user_id' => 'integer',
        'hosting_account_id' => 'integer',
        'server_id' => 'integer',
        'project_id' => 'integer',
        'last_reply_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    /**
     * Relacionamentos
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hostingAccount(): BelongsTo
    {
        return $this->belongsTo(HostingAccount::class);
    }

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class)->oldest();
    }

    /**
     * Gerador de código legível de ticket (Ex: HDP-2026-0001)
     */
    public static function generateTicketNumber(): string
    {
        $year = date('Y');
        $latest = static::withTrashed()
            ->where('ticket_number', 'like', "HDP-{$year}-%")
            ->latest('id')
            ->first();

        if ($latest) {
            $parts = explode('-', $latest->ticket_number);
            $seq = isset($parts[2]) ? (int) $parts[2] + 1 : 1;
        } else {
            $seq = 1;
        }

        return sprintf('HDP-%s-%04d', $year, $seq);
    }

    /**
     * Accessors de Apresentação
     */
    public function getDepartmentLabelAttribute(): string
    {
        return match ($this->department) {
            self::DEPARTMENT_TECHNICAL => 'Suporte Técnico',
            self::DEPARTMENT_FINANCIAL => 'Financeiro & Faturamento',
            self::DEPARTMENT_COMMERCIAL => 'Comercial & Vendas',
            self::DEPARTMENT_DEVOPS => 'DevOps & Infraestrutura',
            default => ucfirst($this->department),
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            self::PRIORITY_LOW => 'Baixa',
            self::PRIORITY_MEDIUM => 'Média',
            self::PRIORITY_HIGH => 'Alta',
            self::PRIORITY_URGENT => 'Crítica / Urgente',
            default => ucfirst($this->priority),
        };
    }

    public function getPriorityBadgeClassesAttribute(): string
    {
        return match ($this->priority) {
            self::PRIORITY_LOW => 'bg-slate-500/10 text-slate-400 ring-slate-500/30',
            self::PRIORITY_MEDIUM => 'bg-blue-500/10 text-blue-400 ring-blue-500/30',
            self::PRIORITY_HIGH => 'bg-amber-500/10 text-amber-400 ring-amber-500/30',
            self::PRIORITY_URGENT => 'bg-rose-500/10 text-rose-400 ring-rose-500/30 animate-pulse',
            default => 'bg-slate-500/10 text-slate-400 ring-slate-500/30',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_OPEN => 'Aberto',
            self::STATUS_IN_PROGRESS => 'Em Atendimento',
            self::STATUS_ANSWERED => 'Respondido pelo Suporte',
            self::STATUS_CUSTOMER_REPLY => 'Resposta do Cliente',
            self::STATUS_CLOSED => 'Fechado',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_OPEN => 'bg-emerald-500/10 text-emerald-400 ring-emerald-500/30',
            self::STATUS_IN_PROGRESS => 'bg-amber-500/10 text-amber-400 ring-amber-500/30',
            self::STATUS_ANSWERED => 'bg-blue-500/10 text-blue-400 ring-blue-500/30',
            self::STATUS_CUSTOMER_REPLY => 'bg-purple-500/10 text-purple-400 ring-purple-500/30',
            self::STATUS_CLOSED => 'bg-slate-500/10 text-slate-400 ring-slate-500/30',
            default => 'bg-slate-500/10 text-slate-400 ring-slate-500/30',
        };
    }

    public function getIsOpenAttribute(): bool
    {
        return $this->status !== self::STATUS_CLOSED;
    }
}
