<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubDepartments extends Model
{
    protected $fillable = [
        'name',
        'department_id',
        'description',
        'is_active',
        'created_by',
        'team_id'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function createdUser(): BelongsTo
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
