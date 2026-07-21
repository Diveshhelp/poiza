<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeamSubscriptions extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'plan_id',
        'status',
        'starts_at',
        'ends_at',
        'trial_ends_at',
        'auto_renew',
        'razorpay_subscription_id',
        'razorpay_subscription_data',
        'cancellation_reason',
        'canceled_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'canceled_at' => 'datetime',
        'auto_renew' => 'boolean',
        'razorpay_subscription_data' => 'array',
    ];

    /**
     * Get the team that owns the subscription
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Check if the subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the subscription is canceled
     */
    public function isCanceled(): bool
    {
        return $this->status === 'canceled';
    }

    /**
     * Check if the subscription is on trial
     */
    public function onTrial(): bool
    {
        return $this->trial_ends_at !== null && now()->lt($this->trial_ends_at);
    }

    /**
     * Check if the subscription has expired
     */
    public function hasExpired(): bool
    {
        return $this->ends_at !== null && now()->gte($this->ends_at);
    }

    /**
     * Cancel the subscription
     */
    public function cancel(string $reason = null): bool
    {
        $this->status = 'canceled';
        $this->cancellation_reason = $reason;
        $this->canceled_at = now();
        
        return $this->save();
    }
}