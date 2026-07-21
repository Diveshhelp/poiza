<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = [
        'default_credit',
        'notes',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

}
