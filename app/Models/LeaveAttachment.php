<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'leave_id',
        'file_name',
        'original_file_name',
        'file_path',
        'file_type',
        'file_size'
    ];

    /**
     * Get the leave request that owns the attachment
     */
    public function leave()
    {
        return $this->belongsTo(Leave::class);
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