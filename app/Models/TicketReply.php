<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketReply extends Model
{
    use HasFactory, SoftDeletes;

    const AUTHOR_TYPE_STAFF = 'staff';
    const AUTHOR_TYPE_CLIENT = 'client';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'client_id',
        'author_name',
        'author_type',
        'message',
        'is_internal_note',
    ];

    protected $casts = [
        'ticket_id' => 'integer',
        'user_id' => 'integer',
        'client_id' => 'integer',
        'is_internal_note' => 'boolean',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function scopePublicOnly(Builder $query): Builder
    {
        return $query->where('is_internal_note', false);
    }

    public function scopeInternalNotes(Builder $query): Builder
    {
        return $query->where('is_internal_note', true);
    }

    public function getIsStaffAttribute(): bool
    {
        return $this->author_type === self::AUTHOR_TYPE_STAFF;
    }

    public function getIsClientAttribute(): bool
    {
        return $this->author_type === self::AUTHOR_TYPE_CLIENT;
    }
}
