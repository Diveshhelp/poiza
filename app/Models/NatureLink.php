<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NatureLink extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nature_link';

    protected $fillable = [
        'nature_id',
        'team_id',
        'user_id',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'nature_id' => 'integer',
        'status' => 'integer', // tinyint(1) maps to integer
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    protected $dates = [
        'created_at',
        'updated_at', 
        'deleted_at'
    ];

    /**
     * Get the nature of work that this link belongs to
     */
    public function natureOfWork()
    {
        return $this->belongsTo(NatureOfWork::class, 'nature_id');
    }

    /**
     * Get the user who created this nature link
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this nature link
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope to get only active records (status = 1)
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope to get only inactive records (status = 0)
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }

    /**
     * Boot method to set created_by and updated_by automatically
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                if (!$model->created_by) {
                    $model->created_by = auth()->id();
                }
                if (!$model->updated_by) {
                    $model->updated_by = auth()->id();
                }
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return $this->status === 1 ? 'Active' : 'Inactive';
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->status === 1 
            ? 'bg-green-100 text-green-800' 
            : 'bg-red-100 text-red-800';
    }

    /**
     * Check if the link is active
     */
    public function isActive()
    {
        return $this->status === 1;
    }

    /**
     * Check if the link is inactive
     */
    public function isInactive()
    {
        return $this->status === 0;
    }
}