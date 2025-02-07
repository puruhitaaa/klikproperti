<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;

class Property extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'property_type_id',
        'title',
        'description',
        'price',
        'type',
        'status',
        'location_address',
        'city',
        'state',
        'postal_code',
        'latitude',
        'longitude',
        'bedrooms',
        'bathrooms',
        'area',
        'features',
        'service_fee_percentage',
        'is_recommended',
        'documents_verified',
        'rating',
        'review_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'area' => 'decimal:2',
        'features' => 'array',
        'service_fee_percentage' => 'decimal:2',
        'is_recommended' => 'boolean',
        'documents_verified' => 'boolean',
        'last_appraisal_date' => 'datetime',
        'rating' => 'decimal:2',
        'review_count' => 'integer',
    ];

    /**
     * Get the owner of the property.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the type of the property.
     */
    public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class);
    }

    /**
     * Get the documents for the property.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(PropertyDocument::class);
    }

    /**
     * Get the appraisals for the property.
     */
    public function appraisals(): HasMany
    {
        return $this->hasMany(Appraisal::class);
    }

    /**
     * Get the transactions for the property.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the reviews for the property.
     */
    public function propertyReviews(): HasMany
    {
        return $this->hasMany(PropertyReview::class);
    }

    /**
     * Scope a query to only include available properties.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope a query to only include recommended properties.
     */
    public function scopeRecommended($query)
    {
        return $query->where('is_recommended', true);
    }

    /**
     * Scope a query to only include verified properties.
     */
    public function scopeVerified($query)
    {
        return $query->where('documents_verified', true);
    }

    /**
     * Scope a query to only include popular properties.
     */
    public function scopePopular($query)
    {
        return $query->where('review_count', '>', 0)
                    ->orderBy('rating', 'desc')
                    ->orderBy('review_count', 'desc');
    }

    /**
     * Register the media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('property-images')
            ->useDisk('s3')
            ->acceptsFile(function (File $file) {
                return $file->mimeType === 'image/jpeg' || $file->mimeType === 'image/jpg' || $file->mimeType === 'image/png' || $file->mimeType === 'image/webp';
            })
            ->useFallbackUrl('/images/property-placeholder.jpg');

        $this->addMediaCollection('documents')
            ->useDisk('s3');

        $this->addMediaCollection('property-videos')
            ->useDisk('s3')
            ->acceptsFile(function (File $file) {
                return $file->mimeType === 'video/mp4' || $file->mimeType === 'video/quicktime' || $file->mimeType === 'video/x-msvideo' || $file->mimeType === 'video/x-matroska';
            })
            ->singleFile();
    }
}
