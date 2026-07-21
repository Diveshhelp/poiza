<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientEmailLog extends Model
{
    
    use HasFactory;
    
    protected $fillable = [
        'subject',
        'content',
        'recipients_count',
        'status',
        'user_id',
        'attachment',
        'success_count',
        'failure_count',
        'failure_count',
        'completed_at',
        'team_id',
    ];
}
