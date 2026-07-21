<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PunchRecord extends Model
{
    use HasFactory;
    
    protected $fillable = ['employee_id', 'date', 'punch_time'];
    
    protected $casts = [
        'date' => 'date',
        'punch_time' => 'datetime',
    ];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeGetDailyPunches($query, $employeeId, $date)
    {
        return $query->where('employee_id', $employeeId)
                    ->where('date', $date)
                    ->orderBy('punch_time', 'asc')
                    ->get(['id','punch_time']);
                    // ->get(['punch_time']);
    }
}