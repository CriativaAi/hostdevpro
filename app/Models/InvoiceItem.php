<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'description',
        'amount_cents',
        'quantity',
    ];

    protected $casts = [
        'amount_cents' => 'integer',
        'quantity' => 'integer',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getAmountFormattedAttribute(): string
    {
        return 'R$ ' . number_format($this->amount_cents / 100, 2, ',', '.');
    }

    public function getTotalCentsAttribute(): int
    {
        return $this->amount_cents * $this->quantity;
    }

    public function getTotalFormattedAttribute(): string
    {
        return 'R$ ' . number_format($this->total_cents / 100, 2, ',', '.');
    }
}
