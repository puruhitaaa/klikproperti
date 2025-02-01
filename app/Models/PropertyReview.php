<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyReview extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'property_id',
        'user_id',
        'rating',
        'comment',
        'is_verified',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'float',
        'is_verified' => 'boolean',
    ];

    /**
     * Get the property that was reviewed.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the user who wrote the review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::created(function ($review) {
            $property = $review->property;
            $avgRating = (float) $property->propertyReviews()->avg('rating');
            $reviewCount = $property->propertyReviews()->count();

            $property->update([
                'rating' => round($avgRating, 2),
                'review_count' => $reviewCount
            ]);
        });

        static::updated(function ($review) {
            $property = $review->property;
            $avgRating = (float) $property->propertyReviews()->avg('rating');
            $reviewCount = $property->propertyReviews()->count();

            $property->update([
                'rating' => round($avgRating, 2),
                'review_count' => $reviewCount
            ]);
        });

        static::deleted(function ($review) {
            $property = $review->property;
            $avgRating = (float) ($property->propertyReviews()->avg('rating') ?? 0);
            $reviewCount = $property->propertyReviews()->count();

            $property->update([
                'rating' => round($avgRating, 2),
                'review_count' => $reviewCount
            ]);
        });
    }
}
