<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomTaskUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'custom_task_user';

    protected $fillable = [
        'custom_task_id',
        'user_id',
        'created_by'
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
     * Get the task this relationship belongs to
     */
    public function task()
    {
        return $this->belongsTo(CustomTask::class, 'custom_task_id');
    }

    /**
     * Get the user in this relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who created this relationship
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for specific task
     */
    public function scopeForTask($query, $taskId)
    {
        return $query->where('custom_task_id', $taskId);
    }

    /**
     * Scope for specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for specific creator
     */
    public function scopeCreatedBy($query, $userId)
    {
        return $query->where('created_by', $userId);
    }
}