<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'department_id',
        'assigned_to',
        'created_by',
        'title',
        'work_detail',
        'deadline',
        'priority',
        'work_type',
        'status',
        'repetition',
        'repeat_until',
        'notes',
        'uuid',
        'repetition_details',
        'sub_departments',
        'create_before_days',
        'is_master_task',
        'team_id'
    ];
    public function created_user(){
        return $this->belongsTo(User::class,'created_by');
    }
    public function department_object(){
        return $this->belongsTo(Department::class,'department_id');
    }
    public function assign_to(){
        return $this->belongsTo(User::class,'assigned_to');
    }
    public function notes()
    {
        return $this->hasMany(TaskNote::class)->orderBy('created_at','DESC');
    }
    public function task_images(){
        return $this->hasMany(TaskImage::class);
    }
    public function statusHistories()
    {
        return $this->hasMany(TaskStatusHistory::class);
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


