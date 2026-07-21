<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'uuid',
        'client_name',
        'client_email',
        'mobile_number',
        'address',
        'team_id',
        'email_count',
        'sms_count', 
        'whatsapp_count', 
        'is_active', 
    ];
}
