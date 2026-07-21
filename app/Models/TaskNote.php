<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskNote extends Model
{
    use HasFactory;
    protected $fillable = ['task_id', 'user_id', 'content'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
     // In your model
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
