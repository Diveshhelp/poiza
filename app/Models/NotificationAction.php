<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class NotificationAction extends Model
{
    use HasFactory;

    protected $fillable = ['notification_id', 'read_by', 'read_at'];
    //date casting for read_at
    protected $casts = [
        'read_at' => 'datetime'
    ];
    
    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }

    public function reader()
    {
        return $this->belongsTo(User::class, 'read_by');
    }
}