<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Todo extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'uuid',
        'user_id',
        'title',
        'description',
        'due_date',
        'priority',
        'status',
        'assigned_by',
        'completed_at',
        'team_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Priority levels for todos
     */
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';

    /**
     * Status types for todos
     */
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the user that owns the todo.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that assigned the todo.
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Scope a query to only include todos of a specific priority.
     */
    public function scopePriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query to only include todos with a specific status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include overdue todos.
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('status', '!=', self::STATUS_COMPLETED)
                    ->where('status', '!=', self::STATUS_CANCELLED);
    }

    /**
     * Scope a query to only include upcoming todos.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('due_date', '>=', now())
                    ->where('status', '!=', self::STATUS_COMPLETED)
                    ->where('status', '!=', self::STATUS_CANCELLED);
    }

    /**
     * Check if the todo is overdue.
     */
    public function isOverdue(): bool
    {
        return !$this->isCompleted() && 
               !$this->isCancelled() && 
               $this->due_date->isPast();
    }

    /**
     * Check if the todo is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if the todo is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Mark the todo as completed.
     */
    public function markAsCompleted(): bool
    {
        return $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark the todo as cancelled.
     */
    public function markAsCancelled(): bool
    {
        return $this->update([
            'status' => self::STATUS_CANCELLED
        ]);
    }

    /**
     * Get the attachments for the todo.
     */
    public function attachments()
    {
        return $this->hasMany(TodoAttachment::class);
    }

    public function notes()
    {
        return $this->hasMany(TodoNote::class)->with('user')->latest();
    }
    public function created_user(){
        return $this->belongsTo(User::class,'created_by');
    }
        protected function serializeDate(\DateTimeInterface $date)
     {
         return $date->setTimezone(new \DateTimeZone('Asia/Kolkata'))->format('Y-m-d h:i A');
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
}