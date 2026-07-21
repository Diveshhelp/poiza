<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomTaskLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'custom_task_log';

    protected $fillable = [
        'custom_task_id',
        'assign_to',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    /**
     * Get the task this log belongs to
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
     * Get the user who created this log
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this log
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope for specific task
     */
    public function scopeForTask($query, $taskId)
    {
        return $query->where('custom_task_id', $taskId);
    }

    /**
     * Scope for specific assigned user
     */
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assign_to', $userId);
    }

    /**
     * Scope for specific creator
     */
    public function scopeCreatedBy($query, $userId)
    {
        return $query->where('created_by', $userId);
    }
}