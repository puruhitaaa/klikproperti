<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'transaction_code',
        'property_id',
        'buyer_id',
        'seller_id',
        'type',
        'payment_type',
        'total_amount',
        'platform_fee',
        'service_fee',
        'voucher_id',
        'discount_amount',
        'final_amount',
        'status',
        'midtrans_transaction_id',
        'midtrans_response',
        'notes',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'midtrans_response' => 'array',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the property associated with the transaction.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the buyer associated with the transaction.
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Get the seller associated with the transaction.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the voucher used in the transaction.
     */
    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    /**
     * Get the installments for the transaction.
     */
    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }

    /**
     * Scope a query to only include pending transactions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include completed transactions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Check if the transaction is an installment type.
     */
    public function isInstallment(): bool
    {
        return $this->payment_type === 'installment';
    }

    /**
     * Calculate the total amount including fees and discounts.
     */
    public function calculateTotalAmount(): void
    {
        $this->platform_fee = $this->total_amount * (setting('platform_fee_percentage', 2.5) / 100);
        $this->service_fee = $this->total_amount * ($this->property->service_fee_percentage / 100);

        if ($this->voucher_id) {
            $this->discount_amount = $this->voucher->calculateDiscount($this->total_amount);
        }

        $this->final_amount = $this->total_amount + $this->platform_fee + $this->service_fee - ($this->discount_amount ?? 0);
    }

    /**
     * Generate a transaction code.
     */
    public static function generateTransactionCode(): string
    {
        $prefix = 'TRX';
        $timestamp = now()->format('YmdHis');
        $random = str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);

        return $prefix . $timestamp . $random;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            $transaction->transaction_code = self::generateTransactionCode();
        });

        static::created(function ($transaction) {
            if ($transaction->voucher_id) {
                $transaction->voucher->incrementUsage();
            }
        });
    }
}
