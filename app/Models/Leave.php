<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'user_id',
        'start_date',
        'end_date',
        'total_days',
        'is_full_day',
        'leave_half',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejection_reason',
        'team_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user who owns the leave request
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship to the user who approved the leave
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the user who approved the leave request
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // public function rejected()
    // {
    //     return $this->belongsTo(User::class, 'rejected_by');
    // }
    /**
     * Get the user who rejected the leave request
     */
    public function rejected()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /*
    *Relationship to the user who rejected the leave
    */
    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Get the attachments for the leave request
     */
    public function attachments()
    {
        return $this->hasMany(LeaveAttachment::class);
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