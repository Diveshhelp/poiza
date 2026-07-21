<?php

namespace App\Models;

use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomTaskMaster extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'custom_task_master';

    protected $fillable = [
        'custom_task_id',
        'task_date',
        'task_done_on',
        'assign_to',
        'status',
        'created_by',
        'updated_by',
        'comment',
        'is_transfer',
        'uuid',
        'team_id'
    ];

    protected $casts = [
        'task_date' => 'date',
        'task_done_on' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    // Enums
    const STATUS_INACTIVE = '0';
    const STATUS_ACTIVE = '1';
    
    const IS_TRANSFER_OWNER = '0';
    const IS_TRANSFER_TRANSFER = '1';

    /**
     * Scope for active task masters
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope for inactive task masters
     */
    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    /**
     * Scope for transferred tasks
     */
    public function scopeTransferred($query)
    {
        return $query->where('is_transfer', self::IS_TRANSFER_TRANSFER);
    }

    /**
     * Scope for owner tasks
     */
    public function scopeOwner($query)
    {
        return $query->where('is_transfer', self::IS_TRANSFER_OWNER);
    }
    public function scopeMyTask($query)
    {
        return $query->where('assign_to', Auth::user()->id);
    }
    /**
     * Scope for completed tasks
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('task_done_on');
    }

    /**
     * Scope for pending tasks
     */
    public function scopePending($query)
    {
        return $query->whereNull('task_done_on');
    }

    /**
     * Scope for specific date
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('task_date', $date);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('task_date', [$startDate, $endDate]);
    }

    /**
     * Get the task this master belongs to
     */
    public function task()
    {
        return $this->belongsTo(CustomTask::class, 'custom_task_id');
    }

    /**
     * Get the user this task is assigned to
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assign_to');
    }

    /**
     * Get the user who created this task master
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this task master
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Check if task is active
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if task is transferred
     */
    public function isTransferred()
    {
        return $this->is_transfer === self::IS_TRANSFER_TRANSFER;
    }

    /**
     * Check if task is completed
     */
    public function isCompleted()
    {
        return !is_null($this->task_done_on);
    }

    /**
     * Mark task as completed
     */
    public function markCompleted($completedAt = null)
    {
        $this->task_done_on = $completedAt ?: now();
        return $this->save();
    }
    public function getTaskDoneOnAttribute($value)
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
}