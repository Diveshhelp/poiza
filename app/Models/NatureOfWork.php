<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NatureOfWork extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nature_of_work';

    // Override the CREATED_AT constant to match your datetime field
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'uuid',
        'team_id',
        'user_id',
        'title',
        'status',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'created_by' => 'integer'
    ];

    protected $dates = [
        'created_at',
        'updated_at', 
        'deleted_at'
    ];

    /**
     * Get the user who created this nature of work
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all nature links for this nature of work
     */
    public function natureLinks()
    {
        return $this->hasMany(NatureLink::class, 'nature_id');
    }

    /**
     * Check if this nature of work has an active link
     */
    public function hasActiveLink()
    {
        return $this->natureLinks()->where('status', 1)->exists();
    }

    /**
     * Scope to get only active records
     */
    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    /**
     * Scope to get only inactive records
     */
    public function scopeInactive($query)
    {
        return $query->where('status', '0');
    }

    /**
     * Scope to include soft deleted records
     */
    public function scopeWithTrashed($query)
    {
        return $query->withTrashed();
    }

    /**
     * Scope to get only soft deleted records
     */
    public function scopeOnlyTrashed($query)
    {
        return $query->onlyTrashed();
    }

    /**
     * Boot method to set created_by automatically
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check() && !$model->created_by) {
                $model->created_by = auth()->id();
            }
        });
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return $this->status === '1' ? 'Active' : 'Inactive';
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->status === '1' 
            ? 'bg-green-100 text-green-800' 
            : 'bg-red-100 text-red-800';
    }

    public function createdUser()
    {
        return $this->belongsTo(User::class, 'created_by');
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