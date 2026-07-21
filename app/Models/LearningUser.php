<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningUser extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'learning_user';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'learning_id',
        'user_id',
        'admin_id',
        'comment',
        'status',
        'complete_on',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'learning_id' => 'integer',
        'user_id' => 'integer',
        'admin_id' => 'integer',
        'status' => 'boolean',
        'complete_on' => 'datetime',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    /**
     * Get the learning record associated with this pivot.
     */
    public function learning(): BelongsTo
    {
        return $this->belongsTo(Learning::class);
    }

    /**
     * Get the user associated with this learning assignment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who assigned this learning.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the creator of this record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updater of this record.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope to get completed learning assignments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', true)->whereNotNull('complete_on');
    }

    /**
     * Scope to get pending learning assignments.
     */
    public function scopePending($query)
    {
        return $query->where('status', false)->whereNull('complete_on');
    }

    /**
     * Scope to get assignments for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get assignments by a specific admin.
     */
    public function scopeByAdmin($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    /**
     * Check if the learning is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status && $this->complete_on !== null;
    }

    /**
     * Mark the learning as completed.
     */
    public function markAsCompleted(): bool
    {
        return $this->update([
            'status' => true,
            'complete_on' => now(),
        ]);
    }

            
    public function getCreatedAtAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->setTimezone('Asia/Kolkata');
        }
        return $value;
    }
    
    public function getUpdatedAtAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->setTimezone('Asia/Kolkata');
        }
        return $value;
    }
    public function getCompleteOnAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->setTimezone('Asia/Kolkata');
        }
        return $value;
    }
}