<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientEmailTemplate extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'uuid',
        'name',
        'subject',
        'content',
        'user_id',
        'is_active',
        'team_id',
    ];

}
