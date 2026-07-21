<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomTask extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'custom_tasks';

    protected $fillable = [
        'title',
        'task_type',
        'repeat_on_day',
        'task_trigger_date',
        'task_due_day',
        'status',
        'created_by',
        'updated_by',
        'team_id',
        'uuid'
    ];

    protected $casts = [
        'task_trigger_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    // Enums
    const TASK_TYPE_ONETIME = 'onetime';
    const TASK_TYPE_REPEAT = 'repeat';
    
    const STATUS_INACTIVE = '0';
    const STATUS_ACTIVE = '1';

    /**
     * Scope for active tasks
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope for inactive tasks
     */
    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    /**
     * Scope for onetime tasks
     */
    public function scopeOnetime($query)
    {
        return $query->where('task_type', self::TASK_TYPE_ONETIME);
    }

    /**
     * Scope for repeat tasks
     */
    public function scopeRepeat($query)
    {
        return $query->where('task_type', self::TASK_TYPE_REPEAT);
    }

    public function isAssignedToCurrentUser()
    {
        if (!Auth::check()) {
            return false;
        }

        return $this->taskUsers()
                   ->where('user_id', Auth::id())
                   ->exists();
    }


    /**
     * Get the user who created this task
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this task
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the task logs for this task
     */
    public function taskLogs()
    {
        return $this->hasMany(CustomTaskLog::class, 'custom_task_id');
    }

    /**
     * Get the task masters for this task
     */
    public function taskMasters()
    {
        return $this->hasMany(CustomTaskMaster::class, 'custom_task_id');
    }

    /**
     * Get the users assigned to this task through task_user table
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'custom_task_user', 'custom_task_id', 'user_id')
                    ->withTimestamps()
                    ->withPivot('created_by', 'deleted_at');
    }

    public function assignTo()
    {
        
    }
    /**
     * Get the task users (pivot records)
     */
    public function taskUsers()
    {
        return $this->hasMany(CustomTaskUser::class, 'custom_task_id');
    }

    /**
     * Check if task is active
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if task is repeat type
     */
    public function isRepeat()
    {
        return $this->task_type === self::TASK_TYPE_REPEAT;
    }

    /**
     * Check if task is onetime type
     */
    public function isOnetime()
    {
        return $this->task_type === self::TASK_TYPE_ONETIME;
    }
}