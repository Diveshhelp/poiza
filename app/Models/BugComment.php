<?php

// app/Models/BugComment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BugComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'bug_master_id',
        'comment',
        'comment_type',
        'is_internal',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_internal' => 'boolean',
        'is_active' => 'boolean'
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
    public function bugMaster(): BelongsTo
    {
        return $this->belongsTo(BugMaster::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_internal', false);
    }

    public function scopeInternal($query)
    {
        return $query->where('is_internal', true);
    }

    // Accessors
    public function getCommentTypeColorAttribute(): string
    {
        return match($this->comment_type) {
            'General' => 'primary',
            'Status Update' => 'success',
            'Internal Note' => 'warning',
            'Client Response' => 'info',
            default => 'primary'
        };
    }
}