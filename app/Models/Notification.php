<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use ESolution\DBEncryption\Traits\EncryptedAttribute;

class Notification extends Model
{
    use HasFactory;
    use EncryptedAttribute;

    protected $fillable = ['uuid', 'notification', 'created_by'];
    
    protected $encryptable = [  
        'notification'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reads()
    {
        return $this->hasMany(NotificationAction::class, 'notification_id');
    }
}