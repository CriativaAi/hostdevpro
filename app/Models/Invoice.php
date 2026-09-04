<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_UNPAID = 'unpaid';
    const STATUS_PAID = 'paid';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_CANCELLED = 'cancelled';

    const PAYMENT_PIX = 'pix';
    const PAYMENT_CREDIT_CARD = 'credit_card';
    const PAYMENT_BANK_SLIP = 'bank_slip';

    const GATEWAY_MERCADOPAGO = 'mercadopago';
    const GATEWAY_STRIPE = 'stripe';

    protected $fillable = [
        'invoice_number',
        'client_id',
        'hosting_account_id',
        'amount_cents',
        'status',
        'due_date',
        'paid_at',
        'payment_method',
        'payment_gateway',
        'gateway_transaction_id',
        'pix_qr_code_base64',
        'pix_copy_paste',
        'notes',
    ];

    protected $casts = [
        'amount_cents' => 'integer',
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function hostingAccount(): BelongsTo
    {
        return $this->belongsTo(HostingAccount::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public static function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $last = static::whereYear('created_at', $year)
            ->withTrashed()
            ->orderBy('id', 'desc')
            ->first();

        $seq = $last ? ((int) substr($last->invoice_number, -4)) + 1 : 1;
        return sprintf('FAT-%s-%04d', $year, $seq);
    }

    public function getAmountFormattedAttribute(): string
    {
        return 'R$ ' . number_format($this->amount_cents / 100, 2, ',', '.');
    }

    public function getIsOverdueAttribute(): bool
    {
        if ($this->status === self::STATUS_PAID || $this->status === self::STATUS_CANCELLED) {
            return false;
        }

        return $this->due_date < Carbon::today();
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->is_overdue) {
            return 'Vencida';
        }

        return match ($this->status) {
            self::STATUS_PAID => 'Paga',
            self::STATUS_UNPAID => 'Pendente',
            self::STATUS_OVERDUE => 'Vencida',
            self::STATUS_CANCELLED => 'Cancelada',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassesAttribute(): string
    {
        if ($this->is_overdue || $this->status === self::STATUS_OVERDUE) {
            return 'bg-red-100 text-red-800 border-red-200';
        }

        return match ($this->status) {
            self::STATUS_PAID => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            self::STATUS_UNPAID => 'bg-amber-100 text-amber-800 border-amber-200',
            self::STATUS_CANCELLED => 'bg-gray-100 text-gray-700 border-gray-200',
            default => 'bg-blue-100 text-blue-800 border-blue-200',
        };
    }
}
