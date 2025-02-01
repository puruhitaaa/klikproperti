<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_transaction_amount',
        'max_discount_amount',
        'usage_limit',
        'used_count',
        'is_active',
        'valid_from',
        'valid_until',
        'applicable_property_types',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'decimal:2',
        'min_transaction_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'is_active' => 'boolean',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'applicable_property_types' => 'array',
    ];

    /**
     * Get the transactions that used this voucher.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Scope a query to only include active vouchers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('valid_from', '<=', now())
                    ->where(function ($q) {
                        $q->whereNull('valid_until')
                          ->orWhere('valid_until', '>', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('usage_limit')
                          ->orWhereRaw('used_count < usage_limit');
                    });
    }

    /**
     * Check if the voucher is valid for a given property type.
     */
    public function isValidForPropertyType(string $propertyType): bool
    {
        if (empty($this->applicable_property_types)) {
            return true;
        }

        return in_array($propertyType, $this->applicable_property_types);
    }

    /**
     * Calculate discount amount for a given amount.
     */
    public function calculateDiscount(float $amount): float
    {
        if ($this->min_transaction_amount && $amount < $this->min_transaction_amount) {
            return 0;
        }

        $discount = $this->type === 'percentage'
            ? $amount * ($this->value / 100)
            : $this->value;

        if ($this->max_discount_amount) {
            $discount = min($discount, $this->max_discount_amount);
        }

        return $discount;
    }

    /**
     * Increment the used count of the voucher.
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            $this->update(['is_active' => false]);
        }
    }
}
