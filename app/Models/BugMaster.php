<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class BugMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'explanation',
        'status',
        'client_status',
        'type',
        'user_id',
        'team_id',
        'assigned_to',
        'priority',
        'due_date',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'is_active' => 'boolean',
        'priority' => 'integer'
    ];

    protected static function boot(): void
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function images(): HasMany
    {
        return $this->hasMany(BugImage::class)->where('is_active', true)->orderBy('sort_order');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BugComment::class)->where('is_active', true)->orderBy('created_at', 'desc');
    }

    public function publicComments(): HasMany
    {
        return $this->hasMany(BugComment::class)
            ->where('is_active', true)
            ->where('is_internal', false)
            ->orderBy('created_at', 'desc');
    }

    public function internalComments(): HasMany
    {
        return $this->hasMany(BugComment::class)
            ->where('is_active', true)
            ->where('is_internal', true)
            ->orderBy('created_at', 'desc');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByTeam($query, $teamId)
    {
        return $query->where('team_id', $teamId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByClientStatus($query, $clientStatus)
    {
        return $query->where('client_status', $clientStatus);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // Accessors
    public function getPriorityTextAttribute(): string
    {
        return match($this->priority) {
            1 => 'Low',
            2 => 'Medium',
            3 => 'High',
            4 => 'Critical',
            default => 'Low'
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            1 => 'success',
            2 => 'warning',
            3 => 'danger',
            4 => 'dark',
            default => 'success'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'Draft' => 'secondary',
            'Ready for work' => 'info',
            'In progress' => 'warning',
            'Attention required' => 'danger',
            'Deployed' => 'primary',
            'Done' => 'success',
            default => 'secondary'
        };
    }

    public function getClientStatusColorAttribute(): string
    {
        return match($this->client_status) {
            'Created' => 'secondary',
            'In Review' => 'info',
            'In Development' => 'warning',
            'In Testing' => 'primary',
            'Done' => 'success',
            'Ready for check' => 'dark',
            default => 'secondary'
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'Bug' => 'danger',
            'Enhancement' => 'success',
            'Justification' => 'info',
            default => 'primary'
        };
    }
}