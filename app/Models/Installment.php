<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Installment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'transaction_id',
        'installment_number',
        'amount',
        'due_date',
        'status',
        'midtrans_transaction_id',
        'midtrans_response',
        'paid_at',
        'late_fee',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'midtrans_response' => 'array',
    ];

    /**
     * Get the transaction that owns the installment.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Scope a query to only include pending installments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include overdue installments.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
                    ->where('due_date', '<', now());
    }

    /**
     * Scope a query to only include paid installments.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Check if the installment is overdue.
     */
    public function isOverdue(): bool
    {
        return !$this->paid_at && $this->due_date < now();
    }

    /**
     * Calculate late fee if applicable.
     */
    public function calculateLateFee(): float
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        $lateFeePercentage = Setting::get('late_fee_percentage', 1); // 1% per day default
        $daysLate = now()->diffInDays($this->due_date);

        return $this->amount * ($lateFeePercentage / 100) * $daysLate;
    }

    /**
     * Get total amount including late fee.
     */
    public function getTotalAmount(): float
    {
        return $this->amount + $this->late_fee;
    }
}
