<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appraisal extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'property_id',
        'agent_id',
        'appraised_value',
        'comments',
        'assessment_details',
        'status',
        'is_recommended',
        'completed_at',
        'scheduled_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'appraised_value' => 'decimal:2',
        'assessment_details' => 'array',
        'is_recommended' => 'boolean',
        'completed_at' => 'datetime',
        'scheduled_date' => 'datetime',
    ];

    /**
     * Get the property being appraised.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the agent performing the appraisal.
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Scope a query to only include pending appraisals.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include completed appraisals.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include recommended appraisals.
     */
    public function scopeRecommended($query)
    {
        return $query->where('is_recommended', true);
    }

    /**
     * Scope a query to only include scheduled appraisals.
     */
    public function scopeScheduled($query)
    {
        return $query->whereNotNull('scheduled_date')
                    ->where('scheduled_date', '>', now())
                    ->where('status', 'pending');
    }
}
