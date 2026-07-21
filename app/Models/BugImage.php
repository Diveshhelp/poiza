<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/BugImage.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BugImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'bug_master_id',
        'image_path',
        'original_name',
        'mime_type',
        'file_size',
        'sort_order',
        'is_active',
        'uploaded_by'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'sort_order' => 'integer',
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

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }
}
