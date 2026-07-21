<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamSettings extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'uuid',
        'setting_status',
        'app_name',
        'team_id'
    ];

}