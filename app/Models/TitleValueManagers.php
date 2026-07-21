<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitleValueManagers extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_value',
        'title_status',
        'uuid',
        'title_manager_id',
        'created_by'
    ];

}
