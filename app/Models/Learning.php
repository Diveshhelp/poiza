<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Learning extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'learning';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'learning_title',
        'learning_description',
        'status',
        'sort_order',
        'created_by',
        'updated_by',
        'team_id',
        'uuid'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    /**
     * Get the users associated with this learning through the pivot table.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'learning_user')
            ->withPivot([
                'admin_id',
                'comment',
                'status',
                'complete_on',
                'created_by',
                'updated_by',
                'created_at',
                'updated_at'
            ])
            ->withTimestamps();
    }

    /**
     * Get all learning user records for this learning.
     */
    public function learningUsers(): HasMany
    {
        return $this->hasMany(LearningUser::class);
    }

    /**
     * Scope to get only active learning records.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Get the creator of this learning record.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updater of this learning record.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
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